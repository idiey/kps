# Week 1: Data Audit Report

> **Sprint**: Architecture Simplification  
> **Task**: Analyze existing workflow/template usage  
> **Date**: 2026-02-02  
> **Status**: 🟡 In Progress  

---

## Audit Objectives

1. Count jobs using workflows and templates
2. Identify KEW.PA-10-specific jobs
3. Map dynamic field values → static columns
4. Assess data migration risk
5. Identify any custom workflow rules

---

## SQL Audit Queries

### 1. Job Distribution by Template

```sql
-- Count jobs per template
SELECT 
    jt.name AS template_name,
    jt.code AS template_code,
    COUNT(wj.id) AS job_count,
    COUNT(DISTINCT wj.customer_id) AS unique_customers
FROM workshop_jobs wj
LEFT JOIN job_templates jt ON wj.template_id = jt.id
GROUP BY jt.id, jt.name, jt.code
ORDER BY job_count DESC;
```

**Expected Output**: Identify which templates are in use

---

### 2. Job Distribution by Workflow

```sql
-- Count jobs per workflow
SELECT 
    w.name AS workflow_name,
    w.code AS workflow_code,
    COUNT(wj.id) AS job_count,
    ws.name AS current_status,
    COUNT(*) AS count_at_status
FROM workshop_jobs wj
LEFT JOIN workflows w ON wj.workflow_id = w.id
LEFT JOIN workflow_statuses ws ON wj.current_workflow_status_id = ws.id
GROUP BY w.id, w.name, w.code, ws.name
ORDER BY w.name, count_at_status DESC;
```

**Expected Output**: Show job distribution across workflows and their current statuses

---

### 3. Identify KEW.PA-10 Jobs

```sql
-- Find KEW.PA-10 template jobs
SELECT 
    wj.id,
    wj.job_number,
    jt.name AS template_name,
    w.name AS workflow_name,
    ws.name AS current_status,
    wj.created_at
FROM workshop_jobs wj
LEFT JOIN job_templates jt ON wj.template_id = jt.id
LEFT JOIN workflows w ON wj.workflow_id = wj.id
LEFT JOIN workflow_statuses ws ON wj.current_workflow_status_id = ws.id
WHERE jt.code LIKE '%KEW%' OR jt.code LIKE '%PA%10%'
   OR jt.name LIKE '%KEW%' OR jt.name LIKE '%government%'
ORDER BY wj.created_at DESC
LIMIT 20;
```

**Expected Output**: List of government inspection jobs

---

### 4. Dynamic Field Values Analysis

```sql
-- Count field values per job (KEW jobs)
SELECT 
    wj.id,
    wj.job_number,
    jt.name AS template_name,
    COUNT(jfv.id) AS field_count,
    GROUP_CONCAT(
        CONCAT(tf.code, ':', 
            COALESCE(jfv.value_text, jfv.value_number, jfv.value_date, 'null')
        ) SEPARATOR '; '
    ) AS field_values
FROM workshop_jobs wj
INNER JOIN job_templates jt ON wj.template_id = jt.id
LEFT JOIN job_field_values jfv ON wj.id = jfv.job_id
LEFT JOIN template_fields tf ON jfv.field_id = tf.id
WHERE jt.code LIKE '%KEW%'
GROUP BY wj.id, wj.job_number, jt.name
LIMIT 10;
```

**Expected Output**: Show which fields are filled for KEW jobs

---

### 5. Template Fields Inventory

```sql
-- List all fields in KEW.PA-10 template
SELECT 
    tf.code AS field_code,
    tf.label AS field_label,
    tft.name AS field_type,
    tf.is_required,
    tf.validation_rules,
    COUNT(jfv.id) AS usage_count
FROM template_fields tf
INNER JOIN job_templates jt ON tf.template_id = jt.id
INNER JOIN template_field_types tft ON tf.field_type_id = tft.id
LEFT JOIN job_field_values jfv ON tf.id = jfv.field_id
WHERE jt.code LIKE '%KEW%'
GROUP BY tf.id, tf.code, tf.label, tft.name, tf.is_required, tf.validation_rules
ORDER BY tf.display_order;
```

**Expected Output**: Complete list of KEW.PA-10 template fields

---

### 6. Workflow Transitions Analysis

```sql
-- Show workflow transition rules
SELECT 
    w.name AS workflow_name,
    ws_from.name AS from_status,
    ws_to.name AS to_status,
    wt.is_automatic,
    wt.conditions,
    wt.actions
FROM workflow_transitions wt
INNER JOIN workflows w ON wt.workflow_id = w.id
LEFT JOIN workflow_statuses ws_from ON wt.from_status_id = ws_from.id
LEFT JOIN workflow_statuses ws_to ON wt.to_status_id = ws_to.id
WHERE w.code LIKE '%KEW%'
ORDER BY w.name, ws_from.display_order;
```

**Expected Output**: Workflow transition logic to hardcode

---

### 7. Workflow Rules Inventory

```sql
-- List all workflow rules
SELECT 
    w.name AS workflow_name,
    ws.name AS status_name,
    wr.rule_type,
    wr.conditions,
    wr.actions,
    wr.priority
FROM workflow_rules wr
INNER JOIN workflows w ON wr.workflow_id = w.id
LEFT JOIN workflow_statuses ws ON wr.status_id = ws.id
WHERE w.is_active = 1
ORDER BY w.name, wr.priority DESC;
```

**Expected Output**: Business rules to convert to hardcoded logic

---

### 8. Data Quality Check

```sql
-- Check for orphaned field values or incomplete data
SELECT 
    'Orphaned field values' AS issue_type,
    COUNT(*) AS count
FROM job_field_values jfv
LEFT JOIN workshop_jobs wj ON jfv.job_id = wj.id
WHERE wj.id IS NULL

UNION ALL

SELECT 
    'Jobs with template but no field values' AS issue_type,
    COUNT(*) AS count
FROM workshop_jobs wj
WHERE wj.template_id IS NOT NULL
AND wj.id NOT IN (SELECT DISTINCT job_id FROM job_field_values)

UNION ALL

SELECT 
    'Jobs with workflow but no status' AS issue_type,
    COUNT(*) AS count
FROM workshop_jobs wj
WHERE wj.workflow_id IS NOT NULL AND wj.current_workflow_status_id IS NULL;
```

**Expected Output**: Data integrity issues to fix before migration

---

### 9. Sample KEW.PA-10 Data Export

```sql
-- Export sample KEW jobs for field mapping
SELECT 
    wj.id,
    wj.job_number,
    tf.code AS field_code,
    tf.label AS field_label,
    COALESCE(
        jfv.value_text,
        CAST(jfv.value_number AS CHAR),
        CAST(jfv.value_date AS CHAR),
        CAST(jfv.value_datetime AS CHAR),
        IF(jfv.value_boolean, 'true', 'false'),
        jfv.value_json
    ) AS field_value
FROM workshop_jobs wj
INNER JOIN job_templates jt ON wj.template_id = jt.id
INNER JOIN job_field_values jfv ON wj.id = jfv.job_id
INNER JOIN template_fields tf ON jfv.field_id = tf.id
WHERE jt.code LIKE '%KEW%'
ORDER BY wj.id, tf.display_order
LIMIT 100;
```

**Expected Output**: Sample data for manual review

---

### 10. Job Status Distribution

```sql
-- Current status distribution
SELECT 
    COALESCE(ws.name, wj.status) AS status,
    COUNT(*) AS count,
    ROUND(COUNT(*) * 100.0 / (SELECT COUNT(*) FROM workshop_jobs), 2) AS percentage
FROM workshop_jobs wj
LEFT JOIN workflow_statuses ws ON wj.current_workflow_status_id = ws.id
GROUP BY COALESCE(ws.name, wj.status)
ORDER BY count DESC;
```

**Expected Output**: Status distribution to map to simplified enum

---

## Execution Instructions

### Prerequisites

```bash
# 1. Connect to database
mysql -u workshop_user -p workshop_db

# Or using Laravel Tinker
php artisan tinker
```

### Running Queries

```php
// Execute in Tinker for formatted output
DB::table('workshop_jobs')
    ->leftJoin('job_templates', 'workshop_jobs.template_id', '=', 'job_templates.id')
    ->select('job_templates.name', DB::raw('COUNT(*) as count'))
    ->groupBy('job_templates.name')
    ->get();
```

### Export Results

```bash
# Export to CSV for analysis
mysql -u workshop_user -p workshop_db < audit_query.sql > results.csv
```

---

## Expected Findings

### High Priority
- [ ] Total number of KEW.PA-10 jobs
- [ ] List of dynamic fields used in KEW template
- [ ] Field value types (text, number, date, etc.)
- [ ] Current workflow statuses in use

### Medium Priority
- [ ] Workflow transition rules to hardcode
- [ ] Business rules to convert
- [ ] Data quality issues to fix

### Low Priority
- [ ] Template metadata
- [ ] Non-KEW templates (will become NORMAL mode)

---

## Next Steps After Audit

1. **Create Field Mapping Document**
   - Map each template field → static column
   - Document data types and constraints
   - Identify nullable vs required fields

2. **Risk Assessment**
   - Identify potential data loss scenarios
   - Flag complex workflow rules
   - Note any custom validations

3. **Stakeholder Review**
   - Present findings
   - Get approval for migration approach
   - Confirm static field structure

---

**Status**: Ready to execute queries  
**Deliverable**: Completed audit report with findings
