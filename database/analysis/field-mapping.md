# Field Mapping: Dynamic → Static Columns
**Project**: Architecture Simplification  
**Purpose**: Map KEW.PA-10 dynamic JSON fields to static database columns  
**Date**: 2026-02-03

---

## KEW.PA-10 Field Mapping

### Complete Column Mapping

| # | Dynamic JSON Key | Static Column Name | Data Type | Max Length | Required | Default | Notes |
|---|------------------|-------------------|-----------|------------|----------|---------|-------|
| 1 | `vehicle_registration` | `kew_vehicle_registration` | VARCHAR | 255 | ✅ Yes | NULL | Malaysian plate format (e.g., "WA1234A") |
| 2 | `asset_tag` | `kew_asset_tag` | VARCHAR | 255 | ✅ Yes | NULL | Government asset number |
| 3 | `department_name` | `kew_department_name` | VARCHAR | 255 | ✅ Yes | NULL | Full department name |
| 4 | `inspection_date` | `kew_inspection_date` | DATE | - | ✅ Yes | NULL | Date job inspection performed |
| 5 | `inspector_name` | `kew_inspector_name` | VARCHAR | 255 | ✅ Yes | NULL | Full name of inspector |
| 6 | `inspector_ic` | `kew_inspector_ic` | VARCHAR | 14 | ✅ Yes | NULL | Malaysian NRIC (12 digits + 2 dash) |
| 7 | `findings` | `kew_findings` | TEXT | 65535 | ✅ Yes | NULL | Inspection findings (long text) |
| 8 | `recommendations` | `kew_recommendations` | TEXT | 65535 | ✅ Yes | NULL | Recommendations (long text) |
| - | N/A (new field) | `kew_approval_status` | ENUM | - | ❌ No | 'pending' | Values: pending, approved, rejected |
| - | N/A (new field) | `kew_approved_by_id` | UUID | - | ❌ No | NULL | FK → users.id |
| - | N/A (new field) | `kew_approved_at` | TIMESTAMP | - | ❌ No | NULL | Approval timestamp |
| - | N/A (new field) | `kew_rejection_reason` | TEXT | 65535 | ❌ No | NULL | Rejection reason if status='rejected' |

**Total New Columns**: 12 (8 migrated + 4 new approval fields)

---

## Migration SQL Template

### Add Columns Migration

```sql
-- Migration: Add static KEW.PA-10 columns
ALTER TABLE workshop_jobs 
  ADD COLUMN kew_vehicle_registration VARCHAR(255) NULL AFTER status,
  ADD COLUMN kew_asset_tag VARCHAR(255) NULL,
  ADD COLUMN kew_department_name VARCHAR(255) NULL,
  ADD COLUMN kew_inspection_date DATE NULL,
  ADD COLUMN kew_inspector_name VARCHAR(255) NULL,
  ADD COLUMN kew_inspector_ic VARCHAR(14) NULL,
  ADD COLUMN kew_findings TEXT NULL,
  ADD COLUMN kew_recommendations TEXT NULL,
  ADD COLUMN kew_approval_status ENUM('pending', 'approved', 'rejected') NULL DEFAULT 'pending',
  ADD COLUMN kew_approved_by_id CHAR(36) NULL,
  ADD COLUMN kew_approved_at TIMESTAMP NULL,
  ADD COLUMN kew_rejection_reason TEXT NULL;

-- Add foreign key
ALTER TABLE workshop_jobs
  ADD CONSTRAINT fk_kew_approved_by 
  FOREIGN KEY (kew_approved_by_id) REFERENCES users(id) ON DELETE SET NULL;

-- Add indexes for performance
CREATE INDEX idx_kew_approval_status ON workshop_jobs(kew_approval_status, kew_approved_at);
CREATE INDEX idx_kew_inspection_date ON workshop_jobs(kew_inspection_date);
```

### Data Migration Script

```sql
-- Migration: Copy JSON data to static columns
UPDATE workshop_jobs AS j
INNER JOIN job_form_data AS jfd ON j.id = jfd.job_id
INNER JOIN form_templates AS ft ON jfd.form_template_id = ft.id
SET 
  j.kew_vehicle_registration = JSON_UNQUOTE(JSON_EXTRACT(jfd.form_data_json, '$.vehicle_registration')),
  j.kew_asset_tag = JSON_UNQUOTE(JSON_EXTRACT(jfd.form_data_json, '$.asset_tag')),
  j.kew_department_name = JSON_UNQUOTE(JSON_EXTRACT(jfd.form_data_json, '$.department_name')),
  j.kew_inspection_date = JSON_UNQUOTE(JSON_EXTRACT(jfd.form_data_json, '$.inspection_date')),
  j.kew_inspector_name = JSON_UNQUOTE(JSON_EXTRACT(jfd.form_data_json, '$.inspector_name')),
  j.kew_inspector_ic = JSON_UNQUOTE(JSON_EXTRACT(jfd.form_data_json, '$.inspector_ic')),
  j.kew_findings = JSON_UNQUOTE(JSON_EXTRACT(jfd.form_data_json, '$.findings')),
  j.kew_recommendations = JSON_UNQUOTE(JSON_EXTRACT(jfd.form_data_json, '$.recommendations')),
  j.job_mode = 'KEW_PA_10'
WHERE ft.name LIKE '%KEW%' OR ft.name LIKE '%PA-10%';

-- Set non-KEW jobs to NORMAL mode
UPDATE workshop_jobs
SET job_mode = 'NORMAL'
WHERE job_mode IS NULL;
```

---

## Data Validation Rules

### Required Field Validation (KEW.PA-10 Only)

```php
// app/Services/KewPa10ValidationService.php

public function validate(Job $job): array
{
    if ($job->job_mode !== JobMode::KEW_PA_10) {
        return [];
    }
    
    $errors = [];
    $rules = [
        'kew_vehicle_registration' => 'Vehicle Registration',
        'kew_asset_tag' => 'Asset Tag',
        'kew_department_name' => 'Department Name',
        'kew_inspection_date' => 'Inspection Date',
        'kew_inspector_name' => 'Inspector Name',
        'kew_inspector_ic' => 'Inspector IC',
        'kew_findings' => 'Findings',
        'kew_recommendations' => 'Recommendations',
    ];
    
    foreach ($rules as $field => $label) {
        if (empty($job->$field)) {
            $errors[$field] = "$label is required for KEW.PA-10 jobs";
        }
    }
    
    // IC format validation
    if (!empty($job->kew_inspector_ic)) {
        if (!preg_match('/^\d{6}-\d{2}-\d{4}$/', $job->kew_inspector_ic)) {
            $errors['kew_inspector_ic'] = 'Inspector IC must be in format: YYMMDD-XX-XXXX';
        }
    }
    
    return $errors;
}
```

---

## Sample Data Examples

### Example 1: Complete KEW.PA-10 Job

```json
// Before (Dynamic JSON):
{
  "vehicle_registration": "WA1234A",
  "asset_tag": "ASSET-JKR-2024-001",
  "department_name": "Jabatan Kerja Raya Malaysia",
  "inspection_date": "2024-01-15",
  "inspector_name": "Ahmad bin Abdullah", 
  "inspector_ic": "850615-08-1234",
  "findings": "Brake pads worn, tire pressure low, engine oil leak detected",
  "recommendations": "Replace brake pads immediately, inflate tires to manufacturer spec, repair engine seal"
}
```

```sql
-- After (Static Columns):
INSERT INTO workshop_jobs (
  job_mode,
  kew_vehicle_registration,
  kew_asset_tag,
  kew_department_name,
  kew_inspection_date,
  kew_inspector_name,
  kew_inspector_ic,
  kew_findings,
  kew_recommendations,
  kew_approval_status
) VALUES (
  'KEW_PA_10',
  'WA1234A',
  'ASSET-JKR-2024-001',
  'Jabatan Kerja Raya Malaysia',
  '2024-01-15',
  'Ahmad bin Abdullah',
  '850615-08-1234',
  'Brake pads worn, tire pressure low, engine oil leak detected',
  'Replace brake pads immediately, inflate tires to manufacturer spec, repair engine seal',
  'pending'
);
```

### Example 2: Approved KEW.PA-10 Job

```sql
UPDATE workshop_jobs
SET 
  kew_approval_status = 'approved',
  kew_approved_by_id = 'supervisor-uuid-here',
  kew_approved_at = '2024-01-16 10:30:00'
WHERE id = 'job-uuid-here';
```

### Example 3: Rejected KEW.PA-10 Job

```sql
UPDATE workshop_jobs
SET 
  kew_approval_status = 'rejected',
  kew_approved_by_id = 'supervisor-uuid-here',
  kew_approved_at = '2024-01-16 14:20:00',
  kew_rejection_reason = 'Insufficient detail in findings section. Please provide specific measurements and safety ratings.'
WHERE id = 'job-uuid-here';
```

---

## NULL Value Handling

### Pre-Migration NULL Check

```sql
-- Identify KEW jobs with missing critical fields
SELECT 
    j.id,
    j.job_number,
    jfd.form_data_json,
    CASE 
        WHEN JSON_EXTRACT(jfd.form_data_json, '$.vehicle_registration') IS NULL THEN 'MISSING: vehicle_registration'
        WHEN JSON_EXTRACT(jfd.form_data_json, '$.asset_tag') IS NULL THEN 'MISSING: asset_tag'
        WHEN JSON_EXTRACT(jfd.form_data_json, '$.findings') IS NULL THEN 'MISSING: findings'
        ELSE 'OK'
    END AS data_quality_status
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

**Action**: Any jobs returned by this query need manual data cleanup before migration.

### Post-Migration Validation

```sql
-- Verify all KEW jobs have required fields populated
SELECT 
    id,
    job_number,
    CASE 
        WHEN kew_vehicle_registration IS NULL THEN 'FAIL: vehicle_registration NULL'
        WHEN kew_asset_tag IS NULL THEN 'FAIL: asset_tag NULL'
        WHEN kew_findings IS NULL THEN 'FAIL: findings NULL'
        WHEN kew_recommendations IS NULL THEN 'FAIL: recommendations NULL'
        ELSE 'PASS'
    END AS validation_status
FROM workshop_jobs
WHERE job_mode = 'KEW_PA_10'
  AND (
    kew_vehicle_registration IS NULL OR
    kew_asset_tag IS NULL OR
    kew_findings IS NULL OR
    kew_recommendations IS NULL
  );
```

**Expected Result**: Zero rows (all KEW.PA-10 jobs should have complete data).

---

## Edge Cases & Special Handling

### Edge Case 1: Multiple Template Versions

**Issue**: KEW template might have evolved over time (v1.0, v2.0, etc.)

**Query**:
```sql
SELECT 
    ft.name,
    ft.version,
    COUNT(jfd.job_id) as usage_count,
    JSON_KEYS(jfd.form_data_json) as field_names
FROM form_templates ft
INNER JOIN job_form_data jfd ON ft.id = jfd.form_template_id
WHERE ft.name LIKE '%KEW%'
GROUP BY ft.id, ft.name, ft.version;
```

**Solution**: Map all version field names to standard static columns.

### Edge Case 2: Extra Custom Fields

**Issue**: Some KEW jobs might have additional custom fields not in the 8 core fields.

**Query**:
```sql
-- Find all unique field names in KEW jobs
SELECT DISTINCT
    JSON_KEYS(jfd.form_data_json) as field_structure
FROM job_form_data jfd
INNER JOIN form_templates ft ON jfd.form_template_id = ft.id
WHERE ft.name LIKE '%KEW%';
```

**Solution**: 
- If extra fields are common (>10% of jobs), add additional columns
- If rare, document in `kew_findings` or `kew_recommendations`

### Edge Case 3: Partially Completed Jobs

**Issue**: Draft KEW jobs with incomplete data.

**Solution**: 
- Allow NULL values in static columns
- Validation only enforced when transitioning to 'kew_approval_pending'
- Draft jobs can be saved with partial data

---

## Rollback Mapping

In case of rollback, reverse the migration:

```sql
-- Reconstruct JSON from static columns (for rollback)
UPDATE job_form_data jfd
INNER JOIN workshop_jobs j ON jfd.job_id = j.id
SET jfd.form_data_json = JSON_OBJECT(
    'vehicle_registration', j.kew_vehicle_registration,
    'asset_tag', j.kew_asset_tag,
    'department_name', j.kew_department_name,
    'inspection_date', j.kew_inspection_date,
    'inspector_name', j.kew_inspector_name,
    'inspector_ic', j.kew_inspector_ic,
    'findings', j.kew_findings,
    'recommendations', j.kew_recommendations
)
WHERE j.job_mode = 'KEW_PA_10';
```

---

## Checklist

- [ ] All 8 core KEW fields mapped
- [ ] 4 approval fields defined
- [ ] Migration SQL tested on dev database
- [ ] NULL value checks executed
- [ ] Edge cases documented
- [ ] Rollback procedure tested
- [ ] Sample data validated
- [ ] Form validation rules implemented

---

**Status**: ✅ Complete  
**Last Updated**: 2026-02-03  
**Next Step**: Execute pre-migration NULL checks when database available
