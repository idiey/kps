# 🧪 Week 6: Integration Testing & UAT - Plan

> **Date**: February 4, 2026 11:32 MYT  
> **Duration**: 5 days  
> **Status**: 🟡 **IN PROGRESS**  
> **Goal**: Comprehensive testing and user acceptance validation

---

## 📋 Testing Objectives

1. **End-to-End Testing** - Complete user journeys
2. **Integration Testing** - Component interactions
3. **Performance Testing** - Query optimization validation
4. **Security Testing** - Role-based access control
5. **UAT Preparation** - Stakeholder demo readiness

---

## 🎯 Test Scenarios

### 1. KEW.PA-10 Workflow (Critical Path)

#### Scenario 1.1: Create KEW Job as Inspector
**Steps:**
1. Login as inspector@workshop.gov.my
2. Navigate to Jobs → Create Job
3. Select "KEW.PA-10 Inspection" mode
4. Fill all 8 required fields:
   - Title, Description
   - Vehicle Registration, Asset Tag, Department
   - Inspection Date, Inspector Name, Inspector IC
   - Findings, Recommendations
5. Submit job
6. Verify job created with status "Inspection In Progress"

**Expected Results:**
- ✅ Form validation works
- ✅ IC auto-formats (XXXXXX-XX-XXXX)
- ✅ Job saved with all KEW fields
- ✅ Status = "Inspection In Progress"
- ✅ Redirect to job details page

#### Scenario 1.2: Supervisor Approves KEW Job
**Steps:**
1. Login as supervisor@workshop.gov.my
2. Navigate to KEW Approvals → Pending
3. View pending KEW job
4. Review inspection details
5. Click "Approve" button
6. Add approval notes
7. Confirm approval

**Expected Results:**
- ✅ Only supervisors see approval panel
- ✅ Approval updates status to "Inspection Approved"
- ✅ Approval history recorded
- ✅ Timestamp and user logged
- ✅ Job can proceed to repair

#### Scenario 1.3: Supervisor Rejects KEW Job
**Steps:**
1. Login as supervisor@workshop.gov.my
2. View pending KEW job
3. Click "Reject" button
4. Enter rejection reason
5. Confirm rejection

**Expected Results:**
- ✅ Status updates to "Inspection Rejected"
- ✅ Rejection reason saved
- ✅ History recorded
- ✅ Job cannot proceed

---

### 2. Normal Job Workflow

#### Scenario 2.1: Create Normal Job
**Steps:**
1. Login as any user
2. Navigate to Jobs → Create Job
3. Select "Normal Job" mode
4. Select customer
5. Enter title and description
6. Select priority (Low/Medium/High/Urgent)
7. Enter estimated cost
8. Submit

**Expected Results:**
- ✅ Customer dropdown populated
- ✅ Priority selector works
- ✅ Cost formats as currency
- ✅ Job created with status "New"
- ✅ No KEW fields required

#### Scenario 2.2: Assign Normal Job
**Steps:**
1. Login as supervisor
2. View normal job
3. Assign to inspector
4. Verify assignment

**Expected Results:**
- ✅ Assignment dropdown shows users
- ✅ Status updates to "Assigned"
- ✅ Assigned user receives notification (if implemented)

---

### 3. Admin Module Testing

#### Scenario 3.1: User Management
**Steps:**
1. Login as admin@workshop.gov.my
2. Navigate to Admin → User Management
3. Create new user
4. Assign role (pemeriksa)
5. Edit user details
6. Toggle user activation
7. Search and filter users

**Expected Results:**
- ✅ CRUD operations work
- ✅ Role assignment persists
- ✅ Activation toggle works
- ✅ Search filters correctly
- ✅ Pagination works

#### Scenario 3.2: Reports Generation
**Steps:**
1. Navigate to Admin → Reports
2. Select "Job Reports" tab
3. Set date range filter
4. Select job mode filter (KEW/Normal/All)
5. Generate PDF report
6. Generate Excel report
7. Generate CSV report

**Expected Results:**
- ✅ Filters apply correctly
- ✅ PDF generates with data
- ✅ Excel downloads
- ✅ CSV downloads
- ✅ Data matches filters

#### Scenario 3.3: Parts Inventory
**Steps:**
1. Navigate to Admin → Inventory
2. Create new part
3. Adjust stock (add/remove)
4. View movement history
5. Filter by low stock
6. Search parts

**Expected Results:**
- ✅ Part created successfully
- ✅ Stock adjustments recorded
- ✅ Movement history shows all transactions
- ✅ Low stock filter works
- ✅ Search returns correct results

#### Scenario 3.4: Settings Management
**Steps:**
1. Navigate to Admin → Settings
2. Click "Initialize Defaults"
3. Update string setting (app name)
4. Toggle boolean setting (maintenance mode)
5. Update numeric setting (low stock threshold)
6. Save changes
7. Refresh page and verify persistence

**Expected Results:**
- ✅ Defaults initialize correctly
- ✅ Settings persist after save
- ✅ Settings load from cache
- ✅ Type casting works (bool, int, string)

---

### 4. Security Testing

#### Scenario 4.1: Role-Based Access Control
**Test Matrix:**

| Feature | Pentadbiran | Penyelia | Pemeriksa | Expected |
|---------|-------------|----------|-----------|----------|
| Create KEW Job | ✅ | ✅ | ✅ | All can create |
| Approve KEW Job | ✅ | ✅ | ❌ | Supervisor+ only |
| User Management | ✅ | ❌ | ❌ | Admin only |
| Reports | ✅ | ❌ | ❌ | Admin only |
| Inventory | ✅ | ❌ | ❌ | Admin only |
| Settings | ✅ | ❌ | ❌ | Admin only |

**Steps:**
1. Test each feature with each role
2. Verify 403 errors for unauthorized access
3. Check middleware enforcement

**Expected Results:**
- ✅ Correct access control per role
- ✅ 403 errors for unauthorized
- ✅ No privilege escalation possible

---

### 5. Performance Testing

#### Scenario 5.1: Query Performance
**Tests:**
1. Load jobs index with 1000+ jobs
2. Filter by status (should use index)
3. Filter by job_mode (should use index)
4. Filter by date range (should use index)
5. Search by title (full-text)

**Expected Results:**
- ✅ Page load < 500ms
- ✅ Filters apply < 200ms
- ✅ Pagination works smoothly
- ✅ No N+1 query issues

#### Scenario 5.2: Database Indexes
**Verification:**
```sql
SHOW INDEX FROM workshop_jobs;
SHOW INDEX FROM parts;
SHOW INDEX FROM assets;
```

**Expected Results:**
- ✅ All 16 indexes present
- ✅ Composite indexes correct
- ✅ Foreign key indexes exist

---

### 6. UI/UX Testing

#### Scenario 6.1: Responsive Design
**Devices to Test:**
- Desktop (1920x1080)
- Laptop (1366x768)
- Tablet (768x1024)
- Mobile (375x667)

**Pages to Test:**
- Job creation forms
- Job details page
- Admin pages
- Dashboard

**Expected Results:**
- ✅ All layouts responsive
- ✅ No horizontal scroll
- ✅ Touch targets adequate (mobile)
- ✅ Forms usable on mobile

#### Scenario 6.2: Dark Mode
**Steps:**
1. Toggle dark mode
2. Navigate through all pages
3. Check contrast ratios
4. Verify readability

**Expected Results:**
- ✅ Dark mode applies globally
- ✅ All text readable
- ✅ Contrast meets WCAG AA
- ✅ No white flashes

---

## 🧪 Automated Testing

### Unit Tests to Run
```bash
php artisan test --testsuite=Unit
```

**Expected:**
- ✅ Model tests pass
- ✅ Enum tests pass
- ✅ Helper tests pass

### Feature Tests to Run
```bash
php artisan test --testsuite=Feature
```

**Expected:**
- ✅ Controller tests pass
- ✅ Route tests pass
- ✅ Middleware tests pass

---

## 📊 Test Tracking

### Test Execution Checklist

#### Critical Path (Must Pass)
- [ ] KEW job creation
- [ ] KEW approval workflow
- [ ] Normal job creation
- [ ] User management CRUD
- [ ] Role-based access control

#### High Priority
- [ ] Reports generation
- [ ] Inventory management
- [ ] Settings persistence
- [ ] Performance benchmarks
- [ ] Responsive design

#### Medium Priority
- [ ] Dark mode
- [ ] Search functionality
- [ ] Pagination
- [ ] Sorting
- [ ] Filtering

#### Low Priority (Nice to Have)
- [ ] Keyboard shortcuts
- [ ] Print styles
- [ ] Export formats
- [ ] Tooltips
- [ ] Animations

---

## 🐛 Bug Tracking Template

```markdown
### Bug #[ID]
**Severity**: Critical | High | Medium | Low
**Component**: [Controller/Page/Feature]
**Steps to Reproduce**:
1. 
2. 
3. 

**Expected**: 
**Actual**: 
**Screenshots**: 
**Fix Priority**: P0 | P1 | P2 | P3
```

---

## 📈 Success Criteria

### Must Have (Go/No-Go)
- ✅ All critical path tests pass
- ✅ No P0/P1 bugs
- ✅ Security tests pass
- ✅ Performance benchmarks met

### Should Have
- ✅ 90%+ test coverage
- ✅ All high priority tests pass
- ✅ No P2 bugs
- ✅ Responsive on all devices

### Nice to Have
- ✅ 100% test coverage
- ✅ All tests pass
- ✅ No bugs
- ✅ Perfect performance scores

---

## 🚀 UAT Preparation

### Demo Scenarios
1. **KEW Workflow Demo** (5 min)
   - Create inspection
   - Supervisor approval
   - Show history

2. **Admin Features Demo** (5 min)
   - User management
   - Generate report
   - Inventory tracking

3. **Performance Demo** (2 min)
   - Fast filtering
   - Smooth pagination
   - Quick search

### Stakeholder Questions to Prepare For
1. Can we customize KEW fields?
2. Can we add more job modes?
3. How do we export data?
4. What about mobile access?
5. How do we manage users?

---

## 📅 Week 6 Schedule

### Day 1 (Today)
- [ ] Execute critical path tests
- [ ] Run automated test suite
- [ ] Document bugs

### Day 2
- [ ] Admin module testing
- [ ] Security testing
- [ ] Performance testing

### Day 3
- [ ] UI/UX testing
- [ ] Responsive testing
- [ ] Bug fixes

### Day 4
- [ ] UAT preparation
- [ ] Demo rehearsal
- [ ] Documentation review

### Day 5
- [ ] Stakeholder demo
- [ ] Collect feedback
- [ ] Plan Week 7 deployment

---

**Status**: 🟡 **Ready to Start**  
**Next Step**: Execute critical path tests  
**Estimated Duration**: 5 days  
**Success Rate Target**: 95%+
