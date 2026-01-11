# Sprint 2: Workflow Restructuring

**Goal**: Create Job → Choose Workflow → Run Job (with form step activities)
**Start Date**: 2026-01-07
**Status**: Active (Testing Phase)

## 🎯 Sprint Goals

1. Restructure application to be job-centric
2. Enable dynamic form rendering from template schema
3. Verify all changes with comprehensive testing (Current Focus)

## 📋 Backlog

### Stream A: Backend & Database (Completed) ✅
- [x] A1. Database Migration
- [x] A2. Model Updates
- [x] A3. Workflow Executor Enhancement
- [x] A4. Seeder

### Stream B: Frontend (Completed) ✅
- [x] B1. Workflow Step Form UI
- [x] B2. Job Creation Flow
- [x] B3. Transition UI

### Stream C: Cleanup (Completed) ✅
- [x] C1. Code Cleanup

### Stream D: Testing (Partially Complete) ✅
- [x] D1. Created comprehensive test suite for DynamicJobController ✅
- [x] D2. Analyzed routing architecture (hybrid JobController/DynamicJobController) ✅
- [ ] D3. Update policy tests
- [x] **D4. Test workflow step with required template** ✅
- [x] **D5. Test form submission on transition** ✅
- [x] D6. Fixed test compatibility with hybrid architecture ✅
- [ ] D7. Fix remaining 10 failing tests (workflow status setup issues)

## 📝 Notes

- Migrated from `docs/04-sprints/05-sprint-2-workflow-restructuring.md`
- Previous streams A, B, and C are complete.
- **Stream D Progress**:
  - Created `tests/Feature/Controllers/DynamicJobControllerTest.php` with 17 test cases
  - **D4 & D5 Complete**: Tests for template requirements and form submission written
  - Test results: 7 passing, 10 failing (failures due to workflow status setup, not test logic)
  - Discovered hybrid JobController/DynamicJobController architecture
  - Documented architecture in walkthrough
  - Routing analysis complete: `jobs.store` uses JobController (intentional design)

---

**Last Updated**: 2026-01-10
