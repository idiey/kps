# Field Mapping: Dynamic → Static Architecture

> **Sprint**: Architecture Simplification  
> **Week**: 1 - Assessment Phase  
> **Date**: 2026-02-02  
> **Status**: ✅ Ready for Review  

---

## Overview

This document maps **KEW.PA-10** dynamic template fields (stored in `job_field_values`) to **static columns** in `workshop_jobs` table.

### Migration Strategy

**FROM**: Dynamic fields → EAV model (job_field_values)  
**TO**: Static columns → Direct table columns (workshop_jobs)

---

## Current Structure (Dynamic)

### Template: KEW.PA-10 Form

| Field Code | Field Name | Type | Required | Section | Grid Span |
|-----------|-----------|------|----------|---------|-----------|
| `kew_pa_10_number` | Nombor KEW.PA-10 | text | ✅ Yes | Maklumat Borang | 6 |
| `received_date` | Tarikh Terima | date | ✅ Yes | Maklumat Borang | 6 |
| `government_department_id` | Jabatan Kerajaan | select (DB) | ✅ Yes | Maklumat Borang | 6 |
| `asset_id` | Aset | select (DB) | ✅ Yes | Maklumat Borang | 6 |
| `description` | Perihal Pembaikan | textarea | ✅ Yes | Butiran Permohonan | 12 |
| `priority` | Keutamaan | select | ✅ Yes | Butiran Permohonan | 6 |
| `budget_allocation_reference` | Rujukan Peruntukan Bajet | text | ❌ No | Butiran Permohonan | 6 |
| `kew_pa_10_document` | Dokumen KEW.PA-10 | file | ❌ No | Dokumen | 12 |
| `form_completeness_verified` | Borang Lengkap Disahkan | checkbox | ❌ No | Pengesahan | 6 |
| `signatures_verified` | Tandatangan Disahkan | checkbox | ❌ No | Pengesahan | 6 |

**Total**: 10 dynamic fields

---

## New Structure (Static)

### Proposed `workshop_jobs` Table Additions

```sql
ALTER TABLE workshop_jobs ADD COLUMN (
    -- Job Mode
    job_mode ENUM('KEW_PA_10', 'NORMAL') NOT NULL DEFAULT 'NORMAL',
    
    -- KEW.PA-10 Static Fields (nullable for NORMAL jobs)
    kew_pa_10_number VARCHAR(50) NULL,
    kew_pa_10_received_date DATE NULL,
    kew_pa_10_government_department_id BIGINT UNSIGNED NULL,
    kew_pa_10_asset_id BIGINT UNSIGNED NULL,
    kew_pa_10_description TEXT NULL,
    kew_pa_10_priority ENUM('low', 'medium', 'high', 'urgent') NULL DEFAULT 'medium',
    kew_pa_10_budget_reference VARCHAR(100) NULL,
    kew_pa_10_document_path VARCHAR(255) NULL,
    kew_pa_10_form_verified BOOLEAN NULL DEFAULT FALSE,
    kew_pa_10_signatures_verified BOOLEAN NULL DEFAULT FALSE,
    
    -- Foreign Keys
    FOREIGN KEY (kew_pa_10_government_department_id) 
        REFERENCES government_departments(id) ON DELETE SET NULL,
    FOREIGN KEY (kew_pa_10_asset_id) 
        REFERENCES assets(id) ON DELETE SET NULL,
    
    -- Indexes
    INDEX idx_job_mode (job_mode),
    INDEX idx_kew_pa_10_number (kew_pa_10_number),
    INDEX idx_kew_received_date (kew_pa_10_received_date)
);
```

---

## Field-by-Field Mapping

### 1. KEW.PA-10 Number

**Dynamic Field**:
- Code: `kew_pa_10_number`
- Type: `text` (stored in `job_field_values.value_text`)
- Required: ✅ Yes

**Static Column**:
```sql
kew_pa_10_number VARCHAR(50) NULL
```

**Migration Query**:
```sql
UPDATE workshop_jobs wj
JOIN job_field_values jfv ON wj.id = jfv.job_id
JOIN template_fields tf ON jfv.field_id = tf.id
SET wj.kew_pa_10_number = jfv.value_text
WHERE tf.code = 'kew_pa_10_number'
AND wj.template_id = (SELECT id FROM job_templates WHERE code = 'kew-pa-10');
```

**Validation**: Format `KEW.PA-10/YYYY/###`

---

### 2. Received Date

**Dynamic Field**:
- Code: `received_date`
- Type: `date` (stored in `job_field_values.value_date`)
- Required: ✅ Yes

**Static Column**:
```sql
kew_pa_10_received_date DATE NULL
```

**Migration Query**:
```sql
UPDATE workshop_jobs wj
JOIN job_field_values jfv ON wj.id = jfv.job_id
JOIN template_fields tf ON jfv.field_id = tf.id
SET wj.kew_pa_10_received_date = jfv.value_date
WHERE tf.code = 'received_date'
AND wj.template_id = (SELECT id FROM job_templates WHERE code = 'kew-pa-10');
```

---

### 3. Government Department

**Dynamic Field**:
- Code: `government_department_id`
- Type: `select` (database source)
- Stored in: `job_field_values.value_number`
- Required: ✅ Yes

**Static Column**:
```sql
kew_pa_10_government_department_id BIGINT UNSIGNED NULL,
FOREIGN KEY (kew_pa_10_government_department_id) 
    REFERENCES government_departments(id) ON DELETE SET NULL
```

**Migration Query**:
```sql
UPDATE workshop_jobs wj
JOIN job_field_values jfv ON wj.id = jfv.job_id
JOIN template_fields tf ON jfv.field_id = tf.id
SET wj.kew_pa_10_government_department_id = CAST(jfv.value_number AS UNSIGNED)
WHERE tf.code = 'government_department_id'
AND wj.template_id = (SELECT id FROM job_templates WHERE code = 'kew-pa-10');
```

---

### 4. Asset

**Dynamic Field**:
- Code: `asset_id`
- Type: `select` (database source)
- Stored in: `job_field_values.value_number`
- Required: ✅ Yes

**Static Column**:
```sql
kew_pa_10_asset_id BIGINT UNSIGNED NULL,
FOREIGN KEY (kew_pa_10_asset_id) 
    REFERENCES assets(id) ON DELETE SET NULL
```

**Migration Query**:
```sql
UPDATE workshop_jobs wj
JOIN job_field_values jfv ON wj.id = jfv.job_id
JOIN template_fields tf ON jfv.field_id = tf.id
SET wj.kew_pa_10_asset_id = CAST(jfv.value_number AS UNSIGNED)
WHERE tf.code = 'asset_id'
AND wj.template_id = (SELECT id FROM job_templates WHERE code = 'kew-pa-10');
```

---

### 5. Description (Perihal Pembaikan)

**Dynamic Field**:
- Code: `description`
- Type: `textarea`
- Stored in: `job_field_values.value_text`
- Required: ✅ Yes

**Static Column**:
```sql
kew_pa_10_description TEXT NULL
```

**Migration Query**:
```sql
UPDATE workshop_jobs wj
JOIN job_field_values jfv ON wj.id = jfv.job_id
JOIN template_fields tf ON jfv.field_id = tf.id
SET wj.kew_pa_10_description = jfv.value_text
WHERE tf.code = 'description'
AND wj.template_id = (SELECT id FROM job_templates WHERE code = 'kew-pa-10');
```

**Note**: Different from `workshop_jobs.description` (NORMAL jobs)

---

### 6. Priority

**Dynamic Field**:
- Code: `priority`
- Type: `select` with options: `low`, `medium`, `high`, `urgent`
- Stored in: `job_field_values.value_text`
- Required: ✅ Yes

**Static Column**:
```sql
kew_pa_10_priority ENUM('low', 'medium', 'high', 'urgent') NULL DEFAULT 'medium'
```

**Migration Query**:
```sql
UPDATE workshop_jobs wj
JOIN job_field_values jfv ON wj.id = jfv.job_id
JOIN template_fields tf ON jfv.field_id = tf.id
SET wj.kew_pa_10_priority = jfv.value_text
WHERE tf.code = 'priority'
AND wj.template_id = (SELECT id FROM job_templates WHERE code = 'kew-pa-10')
AND jfv.value_text IN ('low', 'medium', 'high', 'urgent');
```

**Validation**: Must be one of 4 enum values

---

### 7. Budget Allocation Reference

**Dynamic Field**:
- Code: `budget_allocation_reference`
- Type: `text`
- Stored in: `job_field_values.value_text`
- Required: ❌ No
- Example: "VOT 13000"

**Static Column**:
```sql
kew_pa_10_budget_reference VARCHAR(100) NULL
```

**Migration Query**:
```sql
UPDATE workshop_jobs wj
JOIN job_field_values jfv ON wj.id = jfv.job_id
JOIN template_fields tf ON jfv.field_id = tf.id
SET wj.kew_pa_10_budget_reference = jfv.value_text
WHERE tf.code = 'budget_allocation_reference'
AND wj.template_id = (SELECT id FROM job_templates WHERE code = 'kew-pa-10');
```

---

### 8. KEW.PA-10 Document (File Upload)

**Dynamic Field**:
- Code: `kew_pa_10_document`
- Type: `file` (PDF)
- Stored in: `job_field_values.value_text` (file path)
- Required: ❌ No

**Static Column**:
```sql
kew_pa_10_document_path VARCHAR(255) NULL
```

**Migration Query**:
```sql
UPDATE workshop_jobs wj
JOIN job_field_values jfv ON wj.id = jfv.job_id
JOIN template_fields tf ON jfv.field_id = tf.id
SET wj.kew_pa_10_document_path = jfv.value_text
WHERE tf.code = 'kew_pa_10_document'
AND wj.template_id = (SELECT id FROM job_templates WHERE code = 'kew-pa-10');
```

**Note**: File uploads handled separately via `job_photos` or `media` table

---

### 9. Form Completeness Verified

**Dynamic Field**:
- Code: `form_completeness_verified`
- Type: `checkbox`
- Stored in: `job_field_values.value_boolean`
- Required: ❌ No

**Static Column**:
```sql
kew_pa_10_form_verified BOOLEAN NULL DEFAULT FALSE
```

**Migration Query**:
```sql
UPDATE workshop_jobs wj
JOIN job_field_values jfv ON wj.id = jfv.job_id
JOIN template_fields tf ON jfv.field_id = tf.id
SET wj.kew_pa_10_form_verified = COALESCE(jfv.value_boolean, FALSE)
WHERE tf.code = 'form_completeness_verified'
AND wj.template_id = (SELECT id FROM job_templates WHERE code = 'kew-pa-10');
```

---

### 10. Signatures Verified

**Dynamic Field**:
- Code: `signatures_verified`
- Type: `checkbox`
- Stored in: `job_field_values.value_boolean`
- Required: ❌ No

**Static Column**:
```sql
kew_pa_10_signatures_verified BOOLEAN NULL DEFAULT FALSE
```

**Migration Query**:
```sql
UPDATE workshop_jobs wj
JOIN job_field_values jfv ON wj.id = jfv.job_id
JOIN template_fields tf ON jfv.field_id = tf.id
SET wj.kew_pa_10_signatures_verified = COALESCE(jfv.value_boolean, FALSE)
WHERE tf.code = 'signatures_verified'
AND wj.template_id = (SELECT id FROM job_templates WHERE code = 'kew-pa-10');
```

---

## Job Mode Assignment

### Set Job Mode Based on Template

```sql
-- Set KEW.PA-10 jobs
UPDATE workshop_jobs
SET job_mode = 'KEW_PA_10'
WHERE template_id = (SELECT id FROM job_templates WHERE code = 'kew-pa-10');

-- Set all other jobs to NORMAL
UPDATE workshop_jobs
SET job_mode = 'NORMAL'
WHERE template_id IS NULL 
   OR template_id != (SELECT id FROM job_templates WHERE code = 'kew-pa-10');
```

---

## Data Migration Procedure

### Phase 1: Backup (Week 2, Day 1)

```sql
-- Create backup tables
CREATE TABLE workshop_jobs_backup AS SELECT * FROM workshop_jobs;
CREATE TABLE job_field_values_backup AS SELECT * FROM job_field_values;
CREATE TABLE job_templates_backup AS SELECT * FROM job_templates;
```

### Phase 2: Add Columns (Week 2, Day 1)

```sql
-- Run migration: add_kew_pa_10_static_fields_to_workshop_jobs
php artisan migrate --path=database/migrations/2026_02_XX_add_kew_pa_10_static_fields.php
```

### Phase 3: Migrate Data (Week 2, Day 2)

```sql
-- Run all 10 UPDATE queries above in transaction
START TRANSACTION;

-- Run each field migration query (1-10)
-- ...

COMMIT;
```

### Phase 4: Validate (Week 2, Day 3)

```sql
-- Check data integrity
SELECT 
    COUNT(*) AS total_kew_jobs,
    SUM(CASE WHEN kew_pa_10_number IS NOT NULL THEN 1 ELSE 0 END) AS with_number,
    SUM(CASE WHEN kew_pa_10_received_date IS NOT NULL THEN 1 ELSE 0 END) AS with_date,
    SUM(CASE WHEN kew_pa_10_description IS NOT NULL THEN 1 ELSE 0 END) AS with_description
FROM workshop_jobs
WHERE job_mode = 'KEW_PA_10';
```

**Expected**: All KEW.PA-10 jobs should have required fields populated

### Phase 5: Drop Dynamic Tables (Week 2, Day 4)

```sql
-- After validation passes
DROP TABLE job_field_values;
DROP TABLE template_fields;
DROP TABLE template_field_types;
DROP TABLE job_templates;
DROP TABLE template_workflows;
```

---

## Rollback Plan

### If Migration Fails

```sql
-- Option 1: Restore from backup
DROP TABLE workshop_jobs;
RENAME TABLE workshop_jobs_backup TO workshop_jobs;

DROP TABLE job_field_values;
RENAME TABLE job_field_values_backup TO job_field_values;

-- Option 2: Keep old structure, add columns later
-- Do nothing, continue using dynamic fields until issue resolved
```

---

## Testing Criteria

### ✅ Success Criteria

1. **Data Accuracy**: All KEW.PA-10 field values preserved
2. **Referential Integrity**: Foreign keys valid (government_department, asset)
3. **Null Safety**: Required fields not null for KEW.PA-10 jobs
4. **Type Safety**: Enums only contain valid values
5. **Performance**: Query time <100ms for job retrieval

### ❌ Failure Scenarios

- Missing required field values
- Invalid foreign key references
- Data type mismatches
- File paths broken/invalid

---

## Model Changes Required

### Before (Dynamic)

```php
class WorkshopJob extends Model
{
    public function template() {
        return $this->belongsTo(JobTemplate::class);
    }
    
    public function fieldValues() {
        return $this->hasMany(JobFieldValue::class, 'job_id');
    }
    
    // Get KEW.PA-10 number dynamically
    public function getKewPa10Number() {
        return $this->fieldValues()
            ->whereHas('field', fn($q) => $q->where('code', 'kew_pa_10_number'))
            ->first()?->value_text;
    }
}
```

### After (Static)

```php
class WorkshopJob extends Model
{
    protected $fillable = [
        'job_mode',
        'kew_pa_10_number',
        'kew_pa_10_received_date',
        'kew_pa_10_government_department_id',
        'kew_pa_10_asset_id',
        'kew_pa_10_description',
        'kew_pa_10_priority',
        'kew_pa_10_budget_reference',
        'kew_pa_10_document_path',
        'kew_pa_10_form_verified',
        'kew_pa_10_signatures_verified',
    ];
    
    protected $casts = [
        'job_mode' => 'string',
        'kew_pa_10_received_date' => 'date',
        'kew_pa_10_priority' => 'string',
        'kew_pa_10_form_verified' => 'boolean',
        'kew_pa_10_signatures_verified' => 'boolean',
    ];
    
    public function governmentDepartment() {
        return $this->belongsTo(GovernmentDepartment::class, 'kew_pa_10_government_department_id');
    }
    
    public function asset() {
        return $this->belongsTo(Asset::class, 'kew_pa_10_asset_id');
    }
    
    // Scopes
    public function scopeKewPa10($query) {
        return $query->where('job_mode', 'KEW_PA_10');
    }
    
    public function scopeNormal($query) {
        return $query->where('job_mode', 'NORMAL');
    }
}
```

---

## Summary

- **Dynamic Fields Removed**: 10 fields from EAV model
- **Static Columns Added**: 11 columns (10 fields + job_mode)
- **Tables to Drop**: 5 tables (job_field_values, template_fields, template_field_types, job_templates, template_workflows)
- **Foreign Keys**: 2 (government_department, asset)
- **Migration Time**: Estimated 2-3 hours for 1000 jobs

**Status**: ✅ Ready for stakeholder review  
**Next**: Create SQL migration files
