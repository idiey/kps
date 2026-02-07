# Architecture Redesign - Quick Reference

> **Checkpoint:** architecture-redesign-workflow-removal.md  
> **Created:** 2026-02-02  
> **Status:** Planning Complete, Ready to Begin Week 1  

---

## 🎯 Mission

Remove dynamic workflow engine and template system. Replace with static KEW.PA-10 and NORMAL job modes.

---

## 📊 Impact Summary

| Aspect | Change |
|--------|--------|
| **Database** | 26 tables → 18 tables (-8) |
| **Performance** | Job creation: 800ms → <400ms (50% faster) |
| **Performance** | Status transition: 300ms → <150ms (50% faster) |
| **Complexity** | High → Medium |
| **Developer Onboarding** | 2 weeks → 3 days (78% reduction) |

---

## 📋 6-Week Sprint Timeline

```
Week 1: Assessment & Data Audit (3 days)
Week 2-3: Backend Migration (11 days)  
Week 4: Frontend Rebuild (5 days)
Week 5: Mobile App Updates (4 days)
Week 6-7: Testing & Deployment (10 days)
```

---

## 📚 Key Documents

1. **[architecture-redesign.md](file:///C:/Users/zuraidiismail/.gemini/antigravity/brain/c2bfc08a-4fde-4d78-84d0-6de5c361a30c/architecture-redesign.md)** - Master redesign plan
2. **[erd-simplified.md](file:///c:/Users/zuraidiismail/RnD/workshop/docs/02-architecture/erd-simplified.md)** - New database schema with mermaid
3. **[16-simplified-job-modes.md](file:///c:/Users/zuraidiismail/RnD/workshop/docs/02-architecture/16-simplified-job-modes.md)** - KEW.PA-10 vs NORMAL comparison
4. **[07-sprint-architecture-simplification.md](file:///c:/Users/zuraidiismail/RnD/workshop/docs/04-sprints/07-sprint-architecture-simplification.md)** - Sprint plan with Gantt
5. **[architecture-redesign-todo.md](file:///c:/Users/zuraidiismail/RnD/workshop/docs/04-sprints/architecture-redesign-todo.md)** - 200+ task checklist

---

## ⚡ Quick Start (Week 1)

### SQL Data Audit

```sql
-- Count jobs per workflow
SELECT w.name, COUNT(j.id) as job_count
FROM workshop_jobs j
LEFT JOIN workflows w ON j.workflow_id = w.id
GROUP BY w.name;

-- Find KEW.PA-10 jobs
SELECT j.id, j.job_number, jfd.form_data_json
FROM workshop_jobs j
JOIN job_form_data jfd ON j.id = jfd.job_id
JOIN form_templates ft ON jfd.template_id = ft.id
WHERE ft.name LIKE '%KEW%'
LIMIT 10;
```

### Deliverables
- [ ] Data audit report
- [ ] Field mapping (JSON → static columns)
- [ ] Stakeholder approval
- [ ] Risk assessment

---

## 🔧 Technical Changes

### Database Tables Removed (8)
- `workflows`
- `workflow_statuses`
- `workflow_transitions`
- `workflow_rules`
- `form_templates`
- `form_template_fields`
- `form_template_sections`
- `job_form_data`

### Static Fields Added to `workshop_jobs` (11)
- `job_mode` (enum: KEW_PA_10 | NORMAL)
- `kew_vehicle_registration`
- `kew_asset_tag`
- `kew_department_name`
- `kew_inspection_date`
- `kew_inspector_name`
- `kew_inspector_ic`
- `kew_findings`
- `kew_recommendations`
- `kew_approval_status`
- `kew_approved_by_id`
- `kew_approved_at`

### Services Created (3)
- `JobStatusService` - Hardcoded status transitions
- `KewPa10ValidationService` - Form validation
- `KewPa10ApprovalService` - Approval workflow

### Vue Components (3)
- `CreateKewPa10.vue` - Government inspection form
- `CreateNormal.vue` - Standard workshop form
- `SelectMode.vue` - Job type selector

---

## 🎖️ Success Criteria

- ✅ Zero data loss during migration
- ✅ All tests passing (80%+ coverage)
- ✅ Performance targets met
- ✅ Stakeholder approval obtained
- ✅ Documentation updated

---

**Full Details:** See [architecture-redesign-workflow-removal.md](file:///c:/Users/zuraidiismail/RnD/workshop/docs/09-plan/03-checkpoints/architecture-redesign-workflow-removal.md)
