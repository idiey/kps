# Risk Mitigation Plan - Architecture Redesign
**Project**: Workshop Management System - Architecture Simplification  
**Sprint**: Week 1 Assessment  
**Date**: 2026-02-03

---

## Executive Summary

This document outlines major risks associated with removing the dynamic workflow/template system and migrating to static job modes. Each risk includes severity rating, mitigation strategies, and rollback procedures.

---

## Risk Matrix

| ID | Risk | Severity | Likelihood | Impact | Priority |
|----|------|----------|------------|--------|----------|
| R1 | Data loss during JSON → static migration | 🔴 Critical | 🟡 Medium | Complete loss of KEW job data | P1 |
| R2 | NULL values in required KEW fields | 🔴 High | 🟡 Medium | Jobs fail validation post-migration | P1 |
| R3 | Workflow rules not captured in code | 🟡 High | 🟡 Medium | Business logic gaps, incorrect transitions | P2 |
| R4 | Breaking changes for mobile app | 🟡 High | 🟡 Medium | Mobile sync failures, offline jobs lost | P2 |
| R5 | Template variations beyond KEW.PA-10 | 🟢 Medium | 🟢 Low | Other forms affected unexpectedly | P3 |
| R6 | Performance degradation | 🟢 Medium | 🟢 Low | Slower queries post-migration | P3 |
| R7 | User training gaps | 🟢 Low | 🟡 Medium | User confusion, productivity loss | P4 |
| R8 | Hidden workflow dependencies | 🟡 High | 🟢 Low | External integrations break | P2 |

---

## R1: Data Loss During Migration 🔴 CRITICAL

### Description
JSON data from `job_form_data` table might be lost or corrupted when migrating to static columns.

### Potential Impact
- Complete loss of KEW.PA-10 inspection data
- Jobs become unusable
- Regulatory compliance issues (government inspections must be traceable)
- Cannot rollback without full database restore

### Likelihood: 🟡 Medium
- Complex JSON extraction logic
- Multiple migration steps
- Potential charset/encoding issues

### Mitigation Strategies

#### Pre-Migration
1. **Full Database Backup**
   ```bash
   # Create timestamped backup
   DATE=$(date +%Y%m%d_%H%M%S)
   mysqldump -u root -p workshop_db > backup_pre_migration_$DATE.sql
   
   # Verify backup integrity
   mysql -u root -p test_restore_db < backup_pre_migration_$DATE.sql
   ```

2. **Archive Tables (Keep 6 Months)**
   ```sql
   CREATE TABLE _archive_workflows AS SELECT * FROM workflows;
   CREATE TABLE _archive_workflow_statuses AS SELECT * FROM workflow_statuses;
   CREATE TABLE _archive_workflow_transitions AS SELECT * FROM workflow_transitions;
   CREATE TABLE _archive_workflow_rules AS SELECT * FROM workflow_rules;
   CREATE TABLE _archive_form_templates AS SELECT * FROM form_templates;
   CREATE TABLE _archive_form_template_fields AS SELECT * FROM form_template_fields;
   CREATE TABLE _archive_form_template_sections AS SELECT * FROM form_template_sections;
   CREATE TABLE _archive_job_form_data AS SELECT * FROM job_form_data;
   
   -- Add retention policy metadata
   ALTER TABLE _archive_job_form_data 
     ADD COLUMN archived_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
     ADD COLUMN delete_after DATE AS (DATE_ADD(archived_at, INTERVAL 6 MONTH));
   ```

3. **Dry Run on Staging**
   ```bash
   # Clone production to staging
   php artisan db:clone --from=production --to=staging
   
   # Run migrations on staging
   php artisan migrate --env=staging
   
   # Verify data integrity
   php artisan data:verify --compare-counts
   ```

4. **Data Integrity Checksums**
   ```sql
   -- Before migration: Count KEW jobs and fields
   SELECT 
     'KEW_JOBS_COUNT' as metric,
     COUNT(*) as value
   FROM workshop_jobs j
   INNER JOIN job_form_data jfd ON j.id = jfd.job_id
   WHERE jfd.form_template_id IN (
     SELECT id FROM form_templates WHERE name LIKE '%KEW%'
   );
   
   -- After migration: Verify count matches
   SELECT 
     'KEW_JOBS_MIGRATED' as metric,
     COUNT(*) as value
   FROM workshop_jobs
   WHERE job_mode = 'KEW_PA_10';
   ```

#### During Migration
1. **Transaction Wrapping**
   ```php
   DB::transaction(function () {
       // All migration steps in single transaction
       // Auto-rollback on exception
   });
   ```

2. **Row-Level Verification**
   ```php
   foreach ($kewJobs as $job) {
       $originalData = json_decode($job->form_data_json, true);
       
       // Migrate
       DB::table('workshop_jobs')->where('id', $job->id)->update([...]);
       
       // Verify immediately
       $migrated = DB::table('workshop_jobs')->find($job->id);
       if ($migrated->kew_vehicle_registration !== $originalData ['vehicle_registration']) {
           throw new \Exception("Migration failed for job {$job->id}");
       }
   }
   ```

3. **Progress Logging**
   ```php
   Log::info("Starting KEW migration for {$total} jobs");
   foreach ($kewJobs as $index => $job) {
       // Migrate job
       Log::info("Migrated job {$index}/{$total}: {$job->id}");
   }
   ```

#### Post-Migration
1. **Data Validation Report**
   ```sql
   -- Compare row counts
   SELECT 
     'Original KEW jobs' as source,
     COUNT(*) as count
   FROM _archive_job_form_data jfd
   INNER JOIN _archive_form_templates ft ON jfd.form_template_id = ft.id
   WHERE ft.name LIKE '%KEW%'
   
   UNION ALL
   
   SELECT 
     'Migrated KEW jobs' as source,
     COUNT(*) as count
   FROM workshop_jobs
   WHERE job_mode = 'KEW_PA_10';
   ```

2. **Spot Check Sample Jobs**
   ```bash
   php artisan data:verify --sample=20 --job-mode=KEW_PA_10
   ```

### Rollback Procedure
```bash
# 1. Stop application
php artisan down --message="Emergency rollback in progress"

# 2. Restore from backup
mysql -u root -p workshop_db < backup_pre_migration_YYYYMMDD_HHMMSS.sql

# 3. Verify restore
php artisan db:verify --compare-schema --compare-counts

# 4. Restart application
php artisan up

# 5. Notify stakeholders
php artisan notify:stakeholders "Rollback completed at $(date)"
```

**Rollback Time**: ~30 minutes (depends on database size)

---

## R2: NULL Values in Required KEW Fields 🔴 HIGH

### Description
Some KEW.PA-10 jobs might have incomplete data in JSON, leading to NULL values post-migration.

### Potential Impact
- Jobs fail validation checks
- Cannot submit for approval
- Users cannot complete existing jobs
- Data quality issues

### Mitigation Strategies

#### Pre-Migration
1. **Data Quality Audit**
   ```sql
   -- Find KEW jobs with missing required fields
   SELECT 
       j.id,
       j.job_number,
       j.created_at,
       CONCAT_WS(', ',
           IF(JSON_EXTRACT(jfd.form_data_json, '$.vehicle_registration') IS NULL, 'vehicle_registration', NULL),
           IF(JSON_EXTRACT(jfd.form_data_json, '$.asset_tag') IS NULL, 'asset_tag', NULL),
           IF(JSON_EXTRACT(jfd.form_data_json, '$.findings') IS NULL, 'findings', NULL),
           IF(JSON_EXTRACT(jfd.form_data_json, '$.recommendations') IS NULL, 'recommendations', NULL)
       ) AS missing_fields
   FROM workshop_jobs j
   INNER JOIN job_form_data jfd ON j.id = jfd.job_id
   INNER JOIN form_templates ft ON jfd.form_template_id = ft.id
   WHERE (ft.name LIKE '%KEW%')
     AND (
       JSON_EXTRACT(jfd.form_data_json, '$.vehicle_registration') IS NULL OR
       JSON_EXTRACT(jfd.form_data_json, '$.asset_tag') IS NULL OR
       JSON_EXTRACT(jfd.form_data_json, '$.findings') IS NULL OR
       JSON_EXTRACT(jfd.form_data_json, '$.recommendations') IS NULL
     );
   ```

2. **Manual Data Cleanup**
   - Export jobs with missing data to CSV
   - Contact job creators to fill in missing information
   - Update JSON before migration

3. **Draft Job Exception**
   - Allow NULL for draft status jobs
   - Validation only enforced when submitting for approval

#### During Migration
1. **Graceful NULL Handling**
   ```php
   UPDATE workshop_jobs j
   INNER JOIN job_form_data jfd ON j.id = jfd.job_id
   SET 
     j.kew_vehicle_registration = COALESCE(
       JSON_UNQUOTE(JSON_EXTRACT(jfd.form_data_json, '$.vehicle_registration')),
       '[MISSING - DRAFT]'
     ),
     j.kew_findings = COALESCE(
       JSON_UNQUOTE(JSON_EXTRACT(jfd.form_data_json, '$.findings')),
       'Findings pending completion'
     )
   WHERE j.job_mode = 'KEW_PA_10';
   ```

2. **Flag Incomplete Jobs**
   ```sql
   ALTER TABLE workshop_jobs ADD COLUMN needs_data_review BOOLEAN DEFAULT FALSE;
   
   UPDATE workshop_jobs
   SET needs_data_review = TRUE
   WHERE job_mode = 'KEW_PA_10'
     AND (
       kew_vehicle_registration IS NULL OR
       kew_findings IS NULL
     );
   ```

#### Post-Migration
1. **Validation Report**
   ```sql
   SELECT 
     status,
     COUNT(*) as jobs_with_null_fields
   FROM workshop_jobs
   WHERE job_mode = 'KEW_PA_10'
     AND needs_data_review = TRUE
   GROUP BY status;
   ```

2. **User Notification**
   ```php
   // Notify users to complete incomplete jobs
   $incompleteJobs = Job::where('needs_data_review', true)->get();
   foreach ($incompleteJobs as $job) {
       Notification::send($job->assignedUser, new CompleteJobDataNotification($job));
   }
   ```

---

## R3: Workflow Rules Not Captured in Code 🟡 HIGH

### Description
Complex workflow rules (conditions, actions) might not be fully replicated in hardcoded `JobStatusService`.

### Mitigation Strategies

1. **Document All Workflow Rules**
   ```sql
   -- Export all active workflow rules
   SELECT 
       w.name as workflow_name,
       wr.id,
       wr.from_status,
       wr.to_status,
       wr.condition_json,
       wr.action_json,
       wr.created_at
   FROM workflow_rules wr
   INNER JOIN workflows w ON wr.workflow_id = w.id
   WHERE w.is_active = 1
   ORDER BY w.name, wr.id;
   
   -- Export to CSV for review
   INTO OUTFILE '/tmp/workflow_rules_export.csv'
   FIELDS TERMINATED BY ','
   ENCLOSED BY '"'
   LINES TERMINATED BY '\n';
   ```

2. **Map Rules to Code**
   Create a mapping document:
   
   | Workflow Rule ID | From Status | To Status | Condition | Action | Implemented In Code |
   |------------------|-------------|-----------|-----------|--------|---------------------|
   | 1 | draft | kew_inspection | Always allowed | Set assigned_to | ✅ `JobStatusService::transitionStatus()` line 283 |
   | 2 | kew_inspection | kew_approval_pending | All 8 fields filled | Send notification | ✅ `KewPa10ValidationService::validate()` + Event listener |

3. **Unit Tests for Each Rule**
   ```php
   // tests/Unit/Services/JobStatusServiceTest.php
   public function test_kew_inspection_to_approval_pending_requires_all_fields()
   {
       $job = Job::factory()->kewPa10()->draft()->create([
           'kew_vehicle_registration' => null, // Missing field
       ]);
       
       $this->expectException(ValidationException::class);
       
       app(JobStatusService::class)->transitionStatus(
           $job, 
           JobStatus::KEW_APPROVAL_PENDING
       );
   }
   ```

### Validation
- [ ] All workflow rules documented
- [ ] All rules mapped to code
- [ ] Unit tests cover all transitions
- [ ] Integration tests verify e2e workflows

---

## R4: Breaking Changes for Mobile App 🟡 HIGH

### Description
Mobile app expects dynamic templates, will break when templates removed.

### Mitigation Strategies

1. **Parallel Mobile Release**
   - Release mobile app update before backend migration
   - Hardcode static forms in mobile app
   - Remove template parsing logic

2. **API Versioning**
   ```php
   // API v2: Static forms
   Route::prefix('v2')->group(function () {
       Route::get('/jobs/kew/schema', [JobController::class, 'getKewSchema']);
       Route::post('/jobs/kew', [JobController::class, 'createKew']);
   });
   
   // API v1: Legacy (keep for 30 days)
   Route::prefix('v1')->group(function () {
       Route::get('/templates/{id}', [TemplateController::class, 'show']);
   });
   ```

3. **Mobile Offline Detection**
   ```typescript
   // mobile/src/services/JobService.ts
   const apiVersion = await AsyncStorage.getItem('api_version') || 'v1';
   
   if (apiVersion === 'v2') {
       // Use static forms
       return KEW_PA_10_SCHEMA;
   } else {
       // Fall back to template fetch
       return await api.getTemplate(templateId);
   }
   ```

4. **Force Update Notification**
   ```php
   // Notify mobile users to update app
   if (request()->header('App-Version') < '3.0.0') {
       return response()->json([
           'error' => 'App update required',
           'message' => 'Please update to version 3.0.0 or later',
           'update_url' => 'https://play.google.com/store/apps/details?id=com.workshop.app'
       ], 426);
   }
   ```

---

## Monitoring & Early Warning

### Metrics to Track (First 48 Hours)

```php
// config/monitoring.php
return [
    'critical_metrics' => [
        'job_creation_success_rate' => 95, // Alert if below 95%
        'job_validation_failures' => 10,   // Alert if > 10/hour
        'mobile_sync_failures' => 5,       // Alert if > 5/hour  
        'api_error_rate' => 1,             // Alert if > 1%
    ],
];
```

### Alert Triggers

1. **High Error Rate**
   ```bash
   if [ $(grep "ERROR" storage/logs/laravel.log | wc -l) -gt 100 ]; then
       send_alert "High error rate detected post-migration"
   fi
   ```

2. **Data Integrity**
   ```sql
   -- Run every hour
   SELECT COUNT(*) 
   FROM workshop_jobs 
   WHERE job_mode = 'KEW_PA_10'
     AND kew_vehicle_registration IS NULL
     AND status != 'draft';
     
   -- Alert if > 0
   ```

---

## Risk Acceptance

Some risks are accepted with business approval:

| Risk | Acceptance Rationale |
|------|---------------------|
| 30-day template API deprecation | Low usage, mobile update compliance high |
| Draft jobs with incomplete data | Users can complete later, validation only on submit |

---

## Checklist

- [ ] All risks documented
- [ ] Mitigation strategies defined
- [ ] Rollback procedures tested
- [ ] Monitoring configured
- [ ] Stakeholders informed
- [ ] Emergency contacts listed

---

**Status**: ✅ Complete  
**Last Updated**: 2026-02-03  
**Emergency Contact**: [Insert team lead contact]
