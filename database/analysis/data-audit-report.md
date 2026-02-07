# Data Audit Report - Architecture Redesign
**Date**: 2026-02-03  
**Sprint**: Week 1 - Assessment Phase  
**Prepared By**: Technical Team  
**Status**: ⚠️ Database Connection Required

---

## Executive Summary

This report outlines the findings from the Day 1-2 Data Audit for the Architecture Simplification project. The goal is to assess the current state of dynamic workflow/template usage and plan the migration to static job modes.

### Key Findings

**⚠️ Database Status**: Database connection required to run complete analysis queries. Manual queries need to be executed when database is available.

**Audit Scripts Prepared**:
- ✅ 10 SQL analysis queries created
- ✅ PHP audit runner script created  
- ⏳ Pending database connection to execute

---

## 1. Database Analysis

### 1.1 Query Status

| Query # | Purpose | Status | Priority |
|---------|---------|--------|----------|
| 1 | Count jobs per workflow type | ⏳ Pending | 🔴 Critical |
| 2 | Identify KEW.PA-10 jobs with dynamic forms | ⏳ Pending | 🔴 Critical |
| 3 | Find active workflow rules | ⏳ Pending | 🔴 Critical |
| 4 | Count jobs by status and workflow | ⏳ Pending | 🟡 Important |
| 5 | Sample KEW.PA-10 form data export | ⏳ Pending | 🔴 Critical |
| 6 | Check custom validations | ⏳ Pending | 🟡 Important |
| 7 | Identify template variations | ⏳ Pending | 🟡 Important |
| 8 | Jobs without form_data | ⏳ Pending | 🟢 Nice to have |
| 9 | Total counts summary | ⏳ Pending | 🔴 Critical |
| 10 | Recent activity (30 days) | ⏳ Pending | 🟡 Important |

### 1.2 Scripts Created

**Location**: `database/analysis/`
- ✅ `data-audit-queries.sql` - All 10 analysis queries
- ✅ `run-audit.php` - PHP script to execute and format results
- ⏳ `data-audit-results.txt` - Pending execution

### 1.3 Next Steps for Database Analysis

```bash
# When database is available, run:
php database/analysis/run-audit.php

# Or manually execute queries from:
database/analysis/data-audit-queries.sql
```

---

## 2. Documentation Review

### 2.1 Reviewed Documents

| Document | Location | Status | Findings |
|----------|----------|--------|----------|
| **ERD Simplified** | `docs/02-architecture/erd-simplified.md` | ✅ Reviewed | Well-defined, 18 tables (down from 26) |
| **Simplified Job Modes** | `docs/02-architecture/16-simplified-job-modes.md` | ✅ Reviewed | Clear KEW.PA-10 vs NORMAL distinction |
| **Sprint Plan** | `docs/04-sprints/07-sprint-architecture-simplification.md` | ✅ Reviewed | Comprehensive 6-7 week plan |
| **TODO Checklist** | `docs/04-sprints/architecture-redesign-todo.md` | ✅ Reviewed | Detailed task breakdown |

### 2.2 Architecture Documentation Findings

#### ✅ **erd-simplified.md** (528 lines)
**Strengths**:
- Clear ERD with 18 tables (vs 26+ previously)
- Well-documented KEW.PA-10 static fields (11 columns)
- Comprehensive migration strategy outlined
- Index strategy defined for performance
- Mermaid diagrams for visual clarity

**Key Takeaways**:
- Remove 8 tables: 4 workflow + 4 template tables
- Add `job_mode` enum: KEW_PA_10 | NORMAL
- Migration involves: Archive → Add columns → Migrate data → Drop old tables

#### ✅ **16-simplified-job-modes.md** (507 lines)
**Strengths**:
- Clear business rules for KEW.PA-10 and NORMAL modes
- Status transition logic well-defined
- Hardcoded validation rules
- Vue component examples provided
- Mobile impact clearly explained

**Key Takeaways**:
- KEW.PA-10: 8-step approval workflow
- NORMAL: 4-step simple workflow
- All logic hardcoded in services (no runtime config)
- Static forms = faster + simpler

#### ✅ **07-sprint-architecture-simplification.md** (695 lines)
**Strengths**:
- 6-7 week timeline with clear phases
- Gantt chart visualization
- Comprehensive task breakdown
- Migration scripts included
- Risk mitigation strategies

**Key Takeaways**:
- Week 1: Assessment (current phase)
- Week 2-3: Backend migration
- Week 4: Frontend rebuild
- Week 5: Mobile updates
- Week 6-7: Testing & deployment

---

## 3. KEW.PA-10 Field Mapping

### 3.1 Dynamic → Static Field Mapping

Based on documentation review, the following mapping will be used:

| Dynamic Field (JSON) | Static Column | Type | Required | Notes |
|---------------------|---------------|------|----------|-------|
| `vehicle_registration` | `kew_vehicle_registration` | VARCHAR(255) | ✅ Yes | Vehicle plate number |
| `asset_tag` | `kew_asset_tag` | VARCHAR(255) | ✅ Yes | Government asset number |
| `department_name` | `kew_department_name` | VARCHAR(255) | ✅ Yes | E.g., "Jabatan Kerja Raya" |
| `inspection_date` | `kew_inspection_date` | DATE | ✅ Yes | Date of inspection |
| `inspector_name` | `kew_inspector_name` | VARCHAR(255) | ✅ Yes | Name of inspector |
| `inspector_ic` | `kew_inspector_ic` | VARCHAR(14) | ✅ Yes | Malaysian NRIC |
| `findings` | `kew_findings` | TEXT | ✅ Yes | Inspection findings |
| `recommendations` | `kew_recommendations` | TEXT | ✅ Yes | Recommendations |
| N/A | `kew_approval_status` | ENUM | ✅ Yes | 'pending', 'approved', 'rejected' |
| N/A | `kew_approved_by_id` | UUID | ❌ No | FK to users table |
| N/A | `kew_approved_at` | TIMESTAMP | ❌ No | Approval timestamp |
| N/A | `kew_rejection_reason` | TEXT | ❌ No | Reason if rejected |

**Total**: 8 required fields + 4 approval fields = **12 new columns**

### 3.2 Data Integrity Checks

**Pre-Migration Validation** (to be executed):
```sql
-- Check for missing critical fields in KEW jobs
SELECT 
    j.id,
    j.job_number,
    JSON_EXTRACT(jfd.form_data_json, '$.vehicle_registration') as vehicle_reg,
    JSON_EXTRACT(jfd.form_data_json, '$.asset_tag') as asset_tag,
    JSON_EXTRACT(jfd.form_data_json, '$.findings') as findings
FROM workshop_jobs j
INNER JOIN job_form_data jfd ON j.id = jfd.job_id
WHERE jfd.template_id IN (SELECT id FROM form_templates WHERE name LIKE '%KEW%')
  AND (
    JSON_EXTRACT(jfd.form_data_json, '$.vehicle_registration') IS NULL OR
    JSON_EXTRACT(jfd.form_data_json, '$.asset_tag') IS NULL OR
    JSON_EXTRACT(jfd.form_data_json, '$.findings') IS NULL
  );
```

**Expected Result**: Should identify jobs with incomplete KEW.PA-10 data that need manual review.

---

## 4. Risk Assessment

### 4.1 Data Migration Risks

| Risk | Severity | Likelihood | Impact | Mitigation |
|------|----------|------------|--------|------------|
| **Data loss during JSON → static migration** | 🔴 Critical | 🟡 Medium | Jobs lose critical KEW data | Archive all tables, dry run, checksums |
| **NULL values in required KEW fields** | 🟡 High | 🟡 Medium | Validation failures post-migration | Pre-migration data audit, manual cleanup |
| **Workflow rules not captured in hardcoded logic** | 🟡 High | 🟡 Medium | Business logic gaps | Document all workflow rules, match to code |
| **Template variations beyond KEW.PA-10** | 🟢 Medium | 🟢 Low | Other forms affected | Audit all templates, confirm only KEW affected |

### 4.2 Complex Workflow Rules (To Be Identified)

**Critical Queries to Run**:
```sql
-- Find complex workflow rules with conditional logic
SELECT 
    wr.id,
    w.name,
    wr.rule_type,
    wr.condition_json,
    wr.action_json
FROM workflow_rules wr
INNER JOIN workflows w ON wr.workflow_id = w.id
WHERE 
    w.is_active = 1
    AND (
        JSON_LENGTH(wr.condition_json) > 2 OR
        JSON_LENGTH(wr.action_json) > 1
    )
ORDER BY w.name, wr.id;
```

**Action Required**: Review each complex rule and ensure it's replicated in `JobStatusService`.

### 4.3 Template Variations

**Expected Templates** (based on docs):
1. ✅ KEW.PA-10 template (to be migrated)
2. ❓ Other templates (to be identified)

**Query to Execute**:
```sql
SELECT 
    ft.name,
    ft.version,
    COUNT(DISTINCT jfd.job_id) as jobs_using,
    ft.validation_rules,
    ft.is_active
FROM form_templates ft
LEFT JOIN job_form_data jfd ON ft.id = jfd.template_id
GROUP BY ft.id
ORDER BY jobs_using DESC;
```

### 4.4 Custom Form Validations

**Potential Data Loss Scenarios**:
1. ❌ **Scenario**: JSON field exists but no matching static column
   - **Mitigation**: Comprehensive field mapping review, add columns if needed
   
2. ❌ **Scenario**: Complex validation rules in templates
   - **Mitigation**: Migrate to Laravel FormRequest validation
   
3. ❌ **Scenario**: Conditional fields (shown based on other field values)
   - **Mitigation**: Document conditional logic, implement in Vue components

---

## 5. Deliverables Status

### 5.1 Data Audit Report
- ✅ **THIS DOCUMENT** - Comprehensive analysis and findings
- ⏳ Pending: Database query results

### 5.2 Field Mapping Spreadsheet
- ✅ `field-mapping.md` - Dynamic → Static column mapping
- ✅ 12 KEW.PA-10 columns documented
- ✅ Validation rules defined

### 5.3 Risk Mitigation Plan
- ✅ `risk-mitigation-plan.md` - Detailed risk analysis
- ✅ 4 critical risks identified
- ✅ Mitigation strategies documented

---

## 6. Recommendations

### 6.1 Immediate Actions (Days 1-2)

1. **🔴 HIGH PRIORITY**: Start database and run audit queries
   ```bash
   # Start database (adjust as needed)
   # Then run:
   php database/analysis/run-audit.php
   ```

2. **🔴 HIGH PRIORITY**: Export 10-20 sample KEW.PA-10 jobs for manual review
   - Verify all 8 required fields are populated
   - Identify any edge cases or missing data

3. **🟡 MEDIUM PRIORITY**: Create field mapping spreadsheet (CSV/Excel)
   - Include sample data from real jobs
   - Mark any fields with NULL values

### 6.2 Before Proceeding to Day 3 (Stakeholder Approval)

- [ ] Complete all database queries
- [ ] Document any anomalies or data quality issues
- [ ] Create visual mockup of static KEW.PA-10 form
- [ ] Prepare migration rollback plan
- [ ] Estimate total migration time based on job counts

### 6.3 Migration Preparation Checklist

- [ ] All current KEW.PA-10 jobs identified
- [ ] Sample data verified for completeness
- [ ] Workflow rules documented and mapped to code
- [ ] No unknown template variations found
- [ ] Backup strategy confirmed
- [ ] Rollback procedure tested

---

## 7. Queries to Execute When Database Available

**Priority Order**:
1. Query #9 (Total counts) - Get overall statistics
2. Query #1 (Jobs per workflow) - Identify distribution
3. Query #2 (KEW.PA-10 jobs) - Find target jobs
4. Query #5 (Sample data) - Verify field structure
5. Query #3 (Active rules) - Document business logic
6. Queries #4, #6, #7, #8, #10 - Additional insights

---

## Appendices

### Appendix A: SQL Scripts Location
```
database/analysis/
├── data-audit-queries.sql (10 queries)
├── run-audit.php (execution script)
└── data-audit-results.txt (pending)
```

### Appendix B: Documentation References
- ERD Simplified: `docs/02-architecture/erd-simplified.md`
- Job Modes: `docs/02-architecture/16-simplified-job-modes.md`
- Sprint Plan: `docs/04-sprints/07-sprint-architecture-simplification.md`
- TODO: `docs/04-sprints/architecture-redesign-todo.md`

### Appendix C: Related Documents Created
- `database/analysis/field-mapping.md` (this sprint)
- `database/analysis/risk-mitigation-plan.md` (this sprint)

---

**Report Status**: 🟡 Partial - Awaiting database query results  
**Next Update**: After database queries executed  
**Approval Required**: Day 3 stakeholder meeting
