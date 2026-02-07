-- ==================================================
-- Data Audit Queries - Architecture Redesign
-- Date: 2026-02-03
-- Purpose: Analyze current workflow/template usage
-- ==================================================

-- Query 1: Count jobs per workflow type
-- Expected to identify KEW.PA-10 vs other workflows
SELECT 
    w.name AS workflow_name, 
    COUNT(j.id) AS job_count,
    ROUND(COUNT(j.id) * 100.0 / (SELECT COUNT(*) FROM workshop_jobs), 2) AS percentage
FROM workshop_jobs j
LEFT JOIN workflows w ON j.workflow_id = w.id
GROUP BY w.name
ORDER BY job_count DESC;

-- Query 2: Identify KEW.PA-10 jobs with dynamic forms
-- This helps us understand which jobs need data migration
SELECT 
    j.id AS job_id,
    j.reference_no,
    ft.name AS form_template_name,
    JSON_KEYS(jfd.form_data_json) AS dynamic_fields,
    jfd.created_at AS form_created_at
FROM workshop_jobs j
INNER JOIN job_form_data jfd ON j.id = jfd.job_id
LEFT JOIN form_templates ft ON jfd.form_template_id = ft.id
WHERE ft.name LIKE '%KEW%' OR ft.name LIKE '%PA-10%'
ORDER BY j.id DESC
LIMIT 100;

-- Query 3: Find active workflow rules
-- Identifies complexity we need to hardcode into services
SELECT 
    wr.id AS rule_id,
    w.name AS workflow_name,
    wr.from_status,
    wr.to_status,
    wr.condition_json,
    wr.action_json,
    w.is_active
FROM workflow_rules wr
INNER JOIN workflows w ON wr.workflow_id = w.id
WHERE w.is_active = 1
ORDER BY w.name, wr.from_status;

-- Query 4: Count jobs by status and workflow
-- Helps identify active jobs in each workflow state
SELECT 
    w.name AS workflow_name,
    j.status,
    COUNT(j.id) AS job_count
FROM workshop_jobs j
LEFT JOIN workflows w ON j.workflow_id = w.id
GROUP BY w.name, j.status
ORDER BY w.name, j.status;

-- Query 5: Sample KEW.PA-10 form data export
-- Get detailed view of 10 KEW jobs for field mapping
SELECT 
    j.id,
    j.reference_no,
    j.title,
    j.status,
    ft.name AS form_template_name,
    jfd.form_data_json,
    j.created_at,
    j.updated_at
FROM workshop_jobs j
INNER JOIN job_form_data jfd ON j.id = jfd.job_id
LEFT JOIN form_templates ft ON jfd.form_template_id = ft.id
WHERE ft.name LIKE '%KEW%' OR ft.name LIKE '%PA-10%'
ORDER BY j.created_at DESC
LIMIT 10;

-- Query 6: Check for custom validations in templates
-- Identifies validation logic we need to hardcode
SELECT 
    ft.id,
    ft.name,
    ft.validation_rules,
    ft.is_active,
    COUNT(jfd.id) AS usage_count
FROM form_templates ft
LEFT JOIN job_form_data jfd ON ft.id = jfd.form_template_id
GROUP BY ft.id, ft.name, ft.validation_rules, ft.is_active
ORDER BY usage_count DESC;

-- Query 7: Identify all template variations
-- Lists all templates currently in use
SELECT 
    ft.id,
    ft.name,
    ft.description,
    ft.version,
    COUNT(jfd.id) AS jobs_using_template,
    ft.created_at,
    ft.updated_at
FROM form_templates ft
LEFT JOIN job_form_data jfd ON ft.id = jfd.form_template_id
GROUP BY ft.id
ORDER BY jobs_using_template DESC;

-- Query 8: Jobs with workflow_id but no form_data
-- Potential data integrity issues
SELECT 
    j.id,
    j.reference_no,
    w.name AS workflow_name,
    j.status,
    j.created_at
FROM workshop_jobs j
LEFT JOIN workflows w ON j.workflow_id = w.id
LEFT JOIN job_form_data jfd ON j.id = jfd.job_id
WHERE j.workflow_id IS NOT NULL 
  AND jfd.id IS NULL
ORDER BY j.created_at DESC;

-- Query 9: Total counts summary
-- Overall statistics
SELECT 
    (SELECT COUNT(*) FROM workshop_jobs) AS total_jobs,
    (SELECT COUNT(*) FROM workshop_jobs WHERE workflow_id IS NOT NULL) AS jobs_with_workflow,
    (SELECT COUNT(*) FROM workflows WHERE is_active = 1) AS active_workflows,
    (SELECT COUNT(*) FROM form_templates WHERE is_active = 1) AS active_templates,
    (SELECT COUNT(*) FROM job_form_data) AS total_form_submissions,
    (SELECT COUNT(*) FROM workflow_rules) AS total_workflow_rules;

-- Query 10: Identify jobs created in last 30 days
-- Recent activity to validate current usage
SELECT 
    DATE(j.created_at) AS creation_date,
    w.name AS workflow_name,
    COUNT(j.id) AS jobs_created
FROM workshop_jobs j
LEFT JOIN workflows w ON j.workflow_id = w.id
WHERE j.created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
GROUP BY DATE(j.created_at), w.name
ORDER BY creation_date DESC, jobs_created DESC;
