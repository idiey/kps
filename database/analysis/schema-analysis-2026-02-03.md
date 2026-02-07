# Database Schema Analysis & Cleanup Plan
**Date**: 2026-02-03  
**Purpose**: Analyze current schema against architecture simplification requirements

---

## Current State Analysis

### Migration Status
- ✅ Up to migration: `2026_02_02_100000_add_kew_pa_10_static_fields_to_workshop_jobs`
- ❌ Failed: `2026_02_02_110000_drop_dynamic_workflow_tables` (Foreign key constraint error)

### Error Details
```
SQLSTATE[HY000]: General error: 3730 Cannot drop table 'job_templates' 
referenced by a foreign key constraint 'workflow_statuses_required_template_id_foreign' 
on table 'workflow_statuses'.
```

---

## Schema Cleanup Requirements

### Tables to Keep (18 tables)

#### Core Tables
1. ✅ `users` - User accounts
2. ✅ `password_reset_tokens` - Password resets
3. ✅ `sessions` - User sessions
4. ✅ `cache` - Application cache
5. ✅ `cache_locks` - Cache locking
6. ✅ `jobs` - Queue jobs
7. ✅ `job_batches` - Queue batch tracking
8. ✅ `failed_jobs` - Failed queue jobs

#### Business Tables
9. ✅ `customers` - Customers (government departments)
10. ✅ `workshop_jobs` - Main jobs table (with new `job_mode` column)
11. ✅ `job_notes` - Job notes/comments
12. ✅ `job_status_histories` - Status change history
13. ✅ `job_assignments` - Job assignments to technicians
14. ✅ `job_photos` - Job photos
15. ✅ `government_departments` - Government department directory
16. ✅ `assets` - Asset tracking
17. ✅ `permissions` & `roles` - Role-based access control (Spatie)
18. ✅ `model_has_permissions`, `model_has_roles`, `role_has_permissions` - RBAC pivots

### Tables to Remove (9 tables) - Dynamic Workflow System

#### Template System (5 tables)
1. ❌ `job_templates` - Job template definitions
2. ❌ `template_fields` - Dynamic form fields
3. ❌ `template_field_types` - Field type definitions
4. ❌ `template_workflows` - Template-workflow associations
5. ❌ `job_field_values` - EAV field values

#### Workflow Engine (4 tables)
6. ❌ `workflows` - Workflow definitions
7. ❌ `workflow_statuses` - Workflow status nodes
8. ❌ `workflow_transitions` - Status transitions
9. ❌ `workflow_rules` - Validation rules

### Foreign Key Dependencies (The Problem)

```
workflow_statuses.required_template_id → job_templates.id
job_templates.default_workflow_id → workflows.id
template_fields.template_id → job_templates.id
template_fields.field_type_id → template_field_types.id
template_workflows.template_id → job_templates.id
template_workflows.workflow_id → workflows.id
job_field_values.field_id → template_fields.id
job_field_values.job_id → workshop_jobs.id
workflow_statuses.workflow_id → workflows.id
workflow_transitions.workflow_id → workflows.id
workflow_transitions.from_status_id → workflow_statuses.id
workflow_transitions.to_status_id → workflow_statuses.id
workflow_rules.workflow_id → workflows.id
workflow_rules.status_id → workflow_statuses.id
```

---

## Correct Drop Order (Fixed)

### Phase 1: Drop Foreign Keys from Remaining Tables
```sql
-- From workshop_jobs
ALTER TABLE workshop_jobs DROP FOREIGN KEY IF EXISTS workshop_jobs_template_id_foreign;
ALTER TABLE workshop_jobs DROP FOREIGN KEY IF EXISTS workshop_jobs_workflow_id_foreign;
ALTER TABLE workshop_jobs DROP FOREIGN KEY IF EXISTS workshop_jobs_current_workflow_status_id_foreign;

-- From job_status_histories
ALTER TABLE job_status_histories DROP FOREIGN KEY IF EXISTS job_status_histories_transition_id_foreign;
ALTER TABLE job_status_histories DROP FOREIGN KEY IF EXISTS job_status_histories_workflow_status_id_foreign;
```

### Phase 2: Drop Columns with Foreign Keys
```sql
-- From workshop_jobs
ALTER TABLE workshop_jobs DROP COLUMN IF EXISTS template_id;
ALTER TABLE workshop_jobs DROP COLUMN IF EXISTS workflow_id;
ALTER TABLE workshop_jobs DROP COLUMN IF EXISTS current_workflow_status_id;

-- From job_status_histories
ALTER TABLE job_status_histories DROP COLUMN IF EXISTS transition_id;
ALTER TABLE job_status_histories DROP COLUMN IF EXISTS workflow_status_id;
ALTER TABLE job_status_histories DROP COLUMN IF EXISTS metadata;
```

### Phase 3: Drop Tables (Correct Order)
```sql
-- 1. Drop EAV table first (references template_fields and workshop_jobs)
DROP TABLE IF EXISTS job_field_values;

-- 2. Drop template-workflow pivot
DROP TABLE IF EXISTS template_workflows;

-- 3. Drop workflow rules (references workflows and workflow_statuses)
DROP TABLE IF EXISTS workflow_rules;

-- 4. Drop workflow transitions (references workflows and workflow_statuses)
DROP TABLE IF EXISTS workflow_transitions;

-- 5. Drop workflow_statuses (IMPORTANT: Now has FK to job_templates via required_template_id)
--    Must be dropped BEFORE job_templates
DROP TABLE IF EXISTS workflow_statuses;

-- 6. Drop template_fields (references job_templates)
DROP TABLE IF EXISTS template_fields;

-- 7. Drop template_field_types (referenced by template_fields - already dropped)
DROP TABLE IF EXISTS template_field_types;

-- 8. Drop job_templates (references workflows via default_workflow_id)
DROP TABLE IF EXISTS job_templates;

-- 9. Finally drop workflows (no more references)
DROP TABLE IF EXISTS workflows;
```

---

## New Schema Structure

### workshop_jobs (Enhanced)

**New Columns Added**:
- `job_mode` ENUM('KEW_PA_10', 'NORMAL') NOT NULL
- `kew_vehicle_registration` VARCHAR(255) NULL
- `kew_asset_tag` VARCHAR(255) NULL
- `kew_department_name` VARCHAR(255) NULL
- `kew_inspection_date` DATE NULL
- `kew_inspector_name` VARCHAR(255) NULL
- `kew_inspector_ic` VARCHAR(14) NULL
- `kew_findings` TEXT NULL
- `kew_recommendations` TEXT NULL
- `kew_approval_status` ENUM('pending', 'approved', 'rejected') NULL
- `kew_approved_by_id` UUID NULL
- `kew_approved_at` TIMESTAMP NULL
- `kew_rejection_reason` TEXT NULL

**Columns Removed**:
- `template_id` (replaced by `job_mode`)
- `workflow_id` (replaced by hardcoded logic in services)
- `current_workflow_status_id` (replaced by `status` column)

### job_status_histories (Simplified)

**Columns Kept**:
- `id`, `job_id`, `changed_by_id`
- `old_status`, `new_status`
- `notes`, `created_at`

**Columns Removed**:
- `transition_id` (no longer using workflow transitions)
- `workflow_status_id` (using simple string status)
- `metadata` (no longer needed)

---

## Migration Fix Required

**File**: `database/migrations/2026_02_02_110000_drop_dynamic_workflow_tables.php`

**Issue**: Line 77 tries to drop `job_templates` before `workflow_statuses`, but `workflow_statuses` has a foreign key pointing to `job_templates.id`.

**Fix**: Reorder the drop sequence:

```php
// STEP 3: Drop tables in CORRECT dependency order
Schema::dropIfExists('job_field_values');        // FK to template_fields, workshop_jobs
Schema::dropIfExists('template_workflows');      // FK to job_templates, workflows
Schema::dropIfExists('workflow_rules');          // FK to workflows, workflow_statuses
Schema::dropIfExists('workflow_transitions');    // FK to workflows, workflow_statuses
Schema::dropIfExists('workflow_statuses');       // 👈 MOVE BEFORE job_templates (has FK to it)
Schema::dropIfExists('template_fields');         // FK to job_templates
Schema::dropIfExists('template_field_types');    // No FK dependencies
Schema::dropIfExists('job_templates');           // 👈 MOVE AFTER workflow_statuses
Schema::dropIfExists('workflows');               // Last (referenced by job_templates)
```

---

## Verification Queries

After successful migration, run these to verify:

```sql
-- 1. Count jobs by mode
SELECT job_mode, COUNT(*) as count 
FROM workshop_jobs 
GROUP BY job_mode;

-- 2. Verify no orphaned KEW jobs
SELECT COUNT(*) 
FROM workshop_jobs 
WHERE job_mode = 'KEW_PA_10' 
  AND (kew_vehicle_registration IS NULL 
    OR kew_inspection_date IS NULL);

-- 3. Confirm all 9 tables are gone
SHOW TABLES LIKE '%template%';
SHOW TABLES LIKE '%workflow%';
-- Should return 0 results

-- 4. Confirm columns removed from workshop_jobs
SHOW COLUMNS FROM workshop_jobs LIKE '%template%';
SHOW COLUMNS FROM workshop_jobs LIKE '%workflow%';
-- Should return 0 results

-- 5. List remaining tables (should be 18-20 tables)
SELECT TABLE_NAME 
FROM information_schema.TABLES 
WHERE TABLE_SCHEMA = DATABASE()
ORDER BY TABLE_NAME;
```

---

## Backup Tables Created

The migration automatically creates timestamped backups before dropping:

- `job_field_values_backup_YYYYMMDD_HHMMSS`
- `template_workflows_backup_YYYYMMDD_HHMMSS`
- `workflow_rules_backup_YYYYMMDD_HHMMSS`
- `workflow_transitions_backup_YYYYMMDD_HHMMSS`
- `workflow_statuses_backup_YYYYMMDD_HHMMSS`
- `workflows_backup_YYYYMMDD_HHMMSS`
- `template_fields_backup_YYYYMMDD_HHMMSS`
- `template_field_types_backup_YYYYMMDD_HHMMSS`
- `job_templates_backup_YYYYMMDD_HHMMSS`

**Retention**: Keep for 6 months, then archive to cold storage.

---

## Rollback Plan

If migration fails or issues discovered:

### Option 1: Rollback Migration
```bash
php artisan migrate:rollback --step=1
```

### Option 2: Restore from Backup Tables
```sql
-- Rename backup tables back to original
RENAME TABLE job_templates_backup_20260203_160000 TO job_templates;
-- Repeat for all 9 tables
```

### Option 3: Full Database Restore
```bash
# Restore from full database backup taken before migration
mysql -u root -p workshop < workshop_backup_2026-02-03.sql
```

---

## Next Steps

1. ✅ Fix migration file (reorder drop sequence)
2. ⏳ Run migration: `php artisan migrate`
3. ⏳ Run data migration seeder
4. ⏳ Run verification queries
5. ⏳ Run unit tests
6. ⏳ Update controllers to use new services
7. ⏳ Update frontend components

---

**Status**: Migration fix identified, ready to apply  
**Risk**: Low (backup tables created automatically)  
**Estimated Time**: 5 minutes to fix + 2 minutes to run
