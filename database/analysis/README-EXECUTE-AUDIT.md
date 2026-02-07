# Quick Start: Database Audit Execution
**When Database Is Available - Run This!**

---

## Option 1: Automated (Recommended) ✅

```bash
# Navigate to project root
cd c:\Users\zuraidiismail\RnD\workshop

# Run the automated audit script
php database/analysis/run-audit.php

# Output will be displayed and saved to:
# database/analysis/data-audit-results.txt
```

**Expected Output**:
```
=================================================
DATA AUDIT REPORT - 2026-02-03 HH:MM:SS
=================================================

QUERY 1: Jobs per Workflow Type
--------------------------------------------------
  KEW.PA-10: 45 jobs (23.45%)
  NORMAL: 147 jobs (76.55%)
  NULL: 0 jobs (0.00%)

QUERY 2: Overall Statistics
--------------------------------------------------
  Total Jobs: 192
  Jobs with Workflow: 45
  Active Workflows: 2
  Active Templates: 3
  Total Form Submissions: 45
  Total Workflow Rules: 8

... (continues for all 10 queries)
```

---

## Option 2: Manual SQL Execution

If the PHP script fails, run queries manually:

```bash
# Option A: MySQL Command Line
mysql -u root -p workshop_db < database/analysis/data-audit-queries.sql

# Option B: Via Laravel Tinker
php artisan tinker
>>> DB::statement(file_get_contents('database/analysis/data-audit-queries.sql'));
```

---

## Critical Queries to Check First

### 1. Total Counts (Priority: 🔴 Critical)
```sql
SELECT 
    (SELECT COUNT(*) FROM workshop_jobs) AS total_jobs,
    (SELECT COUNT(*) FROM workshop_jobs WHERE workflow_id IS NOT NULL) AS jobs_with_workflow,
    (SELECT COUNT(*) FROM workflows WHERE is_active = 1) AS active_workflows,
    (SELECT COUNT(*) FROM form_templates WHERE is_active = 1) AS active_templates,
    (SELECT COUNT(*) FROM job_form_data) AS total_form_submissions,
    (SELECT COUNT(*) FROM workflow_rules) AS total_workflow_rules;
```

**What to Look For**:
- Total jobs count (need this for migration planning)
- Number of KEW jobs (jobs_with_workflow)
- Active templates (should be small - ideally just KEW.PA-10)

### 2. KEW.PA-10 Job Count (Priority: 🔴 Critical)
```sql
SELECT COUNT(*) as kew_job_count
FROM workshop_jobs j
INNER JOIN job_form_data jfd ON j.id = jfd.job_id
LEFT JOIN form_templates ft ON jfd.form_template_id = ft.id
WHERE ft.name LIKE '%KEW%' OR ft.name LIKE '%PA-10%';
```

**What to Look For**:
- If count = 0: Good! No KEW jobs exist yet (simpler migration)
- If count > 0: Need to verify data quality before migration

### 3. Data Quality Check (Priority: 🔴 Critical)
```sql
-- Find KEW jobs with missing required fields
SELECT 
    j.id,
    j.job_number,
    jfd.form_data_json,
    CASE 
        WHEN JSON_EXTRACT(jfd.form_data_json, '$.vehicle_registration') IS NULL 
            THEN 'MISSING: vehicle_registration'
        WHEN JSON_EXTRACT(jfd.form_data_json, '$.asset_tag') IS NULL 
            THEN 'MISSING: asset_tag'
        WHEN JSON_EXTRACT(jfd.form_data_json, '$.findings') IS NULL 
            THEN 'MISSING: findings'
        ELSE 'OK'
    END AS data_quality
FROM workshop_jobs j
INNER JOIN job_form_data jfd ON j.id = jfd.job_id
INNER JOIN form_templates ft ON jfd.form_template_id = ft.id
WHERE (ft.name LIKE '%KEW%' OR ft.name LIKE '%PA-10%')
  AND (
    JSON_EXTRACT(jfd.form_data_json, '$.vehicle_registration') IS NULL OR
    JSON_EXTRACT(jfd.form_data_json, '$.asset_tag') IS NULL OR
    JSON_EXTRACT(jfd.form_data_json, '$.findings') IS NULL
  );
```

**What to Look For**:
- If returns 0 rows: Great! All KEW jobs have complete data
- If returns rows: Need manual cleanup before migration

---

## After Running Queries

### 1. Review Results
```bash
# Open the results file
notepad database/analysis/data-audit-results.txt

# Or view in terminal
cat database/analysis/data-audit-results.txt
```

### 2. Update Documentation
Add actual numbers to `database/analysis/data-audit-report.md`:

```markdown
## Database Statistics (Actual Results)

- Total Jobs: [INSERT COUNT]
- KEW.PA-10 Jobs: [INSERT COUNT]
- Active Workflows: [INSERT COUNT]
- Active Templates: [INSERT COUNT]
```

### 3. Identify Issues
Look for:
- ❌ Jobs with NULL in required KEW fields
- ❌ Unknown template variations
- ❌ Complex workflow rules not yet documented
- ❌ Recent job activity (last 30 days)

### 4. Update TODO Checklist
Mark database queries as fully complete in:
`docs/04-sprints/architecture-redesign-todo.md`

---

## Red Flags to Watch For 🚩

| Red Flag | Severity | Action Required |
|----------|----------|-----------------|
| **>10% KEW jobs with NULL data** | 🔴 Critical | Manual data cleanup before migration |
| **More than 2-3 template variations** | 🟡 High | Review all templates, map to static columns |
| **>10 complex workflow rules** | 🟡 High | Ensure all rules captured in code |
| **Recent KEW jobs created (last 7 days)** | 🟢 Medium | Inform users of upcoming changes |

---

## Troubleshooting

### Error: Database Connection Refused
```bash
# Check if database is running
# For MySQL/MariaDB:
# - Windows: Check Services (services.msc)
# - Or start via: net start MySQL80

# Verify .env database settings
cat .env | grep DB_
```

### Error: Table doesn't exist
```
SQLSTATE[42S02]: Base table or view not found
```
**Solution**: Check if tables exist:
```sql
SHOW TABLES LIKE '%workflow%';
SHOW TABLES LIKE '%template%';
SHOW TABLES LIKE '%job%';
```

If tables don't exist, this might be a fresh database (good! simpler migration).

### Error: JSON functions not available
```
SQLSTATE[42000]: Syntax error: JSON_EXTRACT
```
**Solution**: Using older MySQL version. Update queries to use:
```sql
-- Instead of JSON_EXTRACT
SELECT form_data_json->'$.field_name'
```

---

## Expected Timeline

| Step | Duration | Notes |
|------|----------|-------|
| Start database | 1-2 min | Via Services or command line |
| Run automated script | 5-10 min | All 10 queries execute |
| Review results | 10-15 min | Check for red flags |
| Update documentation | 5 min | Add actual numbers |
| **Total** | **~30 min** | Ready for Day 3 presentation |

---

## Success Checklist

After running queries, confirm:

- [ ] All 10 queries executed successfully
- [ ] Results saved to `data-audit-results.txt`
- [ ] Total job count identified
- [ ] KEW.PA-10 job count identified
- [ ] No critical data quality issues found
- [ ] No unknown template variations
- [ ] Workflow rules documented
- [ ] Updated `data-audit-report.md` with actual counts
- [ ] Ready for stakeholder presentation

---

## Next Steps

Once database audit is complete:

1. ✅ **Day 2 Complete** - All data gathered
2. 🎯 **Day 3 Preparation**:
   - Create KEW.PA-10 form mockup (Vue component)
   - Prepare presentation slides
   - Include actual query results in presentation
   - Schedule stakeholder approval meeting
3. ⏳ **Day 3 Meeting** - Get approval to proceed with Week 2-3 backend migration

---

**Quick Command Reference**:
```bash
# Run audit
php database/analysis/run-audit.php

# View results
cat database/analysis/data-audit-results.txt

# Check database connection
php artisan tinker
>>> DB::connection()->getPdo();
```

---

**Status**: Ready to execute when database available  
**Estimated Time**: 30 minutes total  
**Priority**: Complete before end of Day 2
