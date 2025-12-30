# KEW.PA-10 Quick Start Verification Guide

**Status**: ✅ Implementation Complete (100%)
**Date**: 2025-12-30
**Time to Complete**: 10-15 minutes

## Prerequisites Checklist

Before testing, ensure you have:

- [x] All models created (KewPA10, Asset, GovernmentDepartment, etc.)
- [x] All migrations run successfully
- [x] Test data seeder created (KewPA10TestDataSeeder)
- [x] Controllers implemented (KewPA10Controller, InspectionController, PhotoController, RepairCompletionController)
- [x] Frontend pages created (6 Vue.js pages)
- [x] Routes registered (verified with `artisan route:list`)
- [x] Sidebar navigation added (KEW.PA-10 Forms, Inspections)
- [x] Foreign key relationship fixed (KewPA10::workshopJob)

## Step 1: Build Frontend Assets (Required)

The sidebar navigation changes won't be visible until you rebuild the frontend:

```bash
# Option A: Development mode with hot reload (recommended)
npm run dev

# Option B: Production build (one-time)
npm run build
```

**Expected Output**:
- Vite server starts on http://localhost:5173
- No compilation errors
- Assets compiled successfully

## Step 2: Seed Test Data

Run the test data seeder to create:
- 4 test users (admin, inspector, supervisor, technician)
- 1 government department (Ministry of Health)
- 1 test asset (Toyota Hilux 4x4)
- 1 KEW.PA-10 form (ready for verification)

```bash
/c/Users/zuraidiismail/.config/herd/bin/php.bat artisan db:seed --class=KewPA10TestDataSeeder
```

**Expected Output**:
```
Creating test users for KEW.PA-10 workflow...
✓ Admin Officer created: admin@workshop.gov.my / password
✓ Inspector created: inspector@workshop.gov.my / password
✓ Supervisor created: supervisor@workshop.gov.my / password
✓ Technician created: technician@workshop.gov.my / password

Creating government department...
✓ Department created: Ministry of Health (MOH)

Creating test asset...
✓ Asset created: Toyota Hilux 4x4 (MOH-VEH-2025-001)

Creating KEW.PA-10 form (ready for testing)...
✓ KEW.PA-10 created: KEW.PA-10/MOH/2025/001
  → NOT verified yet (test the verification workflow)

═══════════════════════════════════════════════════════════════
✓ Test data seeded successfully!
═══════════════════════════════════════════════════════════════
```

## Step 3: Login and Verify Navigation

1. **Access the application**:
   ```
   http://workshop.test (or your configured local domain)
   ```

2. **Login credentials**:
   - **Email**: admin@workshop.gov.my
   - **Password**: password
   - **Role**: Pentadbiran (Admin Officer)

3. **Check sidebar navigation**:
   - ✅ "Dashboard" - should be visible
   - ✅ "KEW.PA-10 Forms" - **NEW** (should appear with FileText icon)
   - ✅ "Inspections" - **NEW** (should appear with ClipboardCheck icon)
   - ✅ "Jobs" - should be visible
   - ✅ "Customers" - should be visible

**Troubleshooting**:
- If sidebar items don't appear, verify `npm run dev` is running
- Check browser console for JavaScript errors
- Hard refresh with Ctrl+Shift+R (Chrome) or Ctrl+F5 (Firefox)

## Step 4: Test KEW.PA-10 Form List Page

1. **Click "KEW.PA-10 Forms" in sidebar**

2. **Verify page loads**: `http://workshop.test/kew-pa-10`

3. **Expected UI Elements**:
   - ✅ Page title: "KEW.PA-10 Forms"
   - ✅ "Register New Form" button (top right)
   - ✅ Search box
   - ✅ 6 filter dropdowns:
     - Government Department
     - Priority
     - Verified Status
     - Date From
     - Date To
     - "Apply Filters" button
   - ✅ Data table with columns:
     - Form Number
     - Department
     - Asset
     - Priority (badge)
     - Verified Status (badge)
     - Received Date
     - Actions (View/Edit buttons)

4. **Verify test data appears**:
   - ✅ One row: "KEW.PA-10/MOH/2025/001"
   - ✅ Department: "Ministry of Health"
   - ✅ Asset: "Toyota Hilux 4x4"
   - ✅ Priority: "HIGH" (red badge)
   - ✅ Verified: "Not Verified" (yellow/orange badge)

## Step 5: Test KEW.PA-10 Form Detail Page

1. **Click "View" button** on the test form

2. **Verify detail page loads**: `http://workshop.test/kew-pa-10/1`

3. **Expected UI Elements**:

   **Header Section**:
   - ✅ Form number: "KEW.PA-10/MOH/2025/001"
   - ✅ Priority badge: "HIGH" (red)
   - ✅ "Back to Forms" button
   - ✅ "Edit" button

   **Form Information Card**:
   - ✅ Government Department: "Ministry of Health (MOH)"
   - ✅ Contact Person: "Dr. Faizal Hassan"
   - ✅ Email: workshop@moh.gov.my
   - ✅ Phone: +60123456789

   **Asset Information Card**:
   - ✅ Asset Name: "Toyota Hilux 4x4"
   - ✅ Asset Tag: "MOH-VEH-2025-001"
   - ✅ Asset Type: "Vehicle"
   - ✅ Location: "MOH Main Workshop"
   - ✅ Current Condition: "Engine overheating, radiator leaking"

   **Request Details Card**:
   - ✅ Description: "Vehicle engine overheating during operation..."
   - ✅ Budget Reference: "BA-MOH-2025-12345"
   - ✅ Received Date: (today's date)

   **Verification Status Card**:
   - ✅ Alert: "This form has not been verified yet" (warning/yellow)
   - ✅ Form Completeness: ❌ Not Verified
   - ✅ Signatures: ❌ Not Verified
   - ✅ "Verify Form" button (blue)

## Step 6: Test Verification Workflow

1. **Click "Verify Form" button**

2. **Verify dialog opens** with:
   - ✅ Title: "Verify KEW.PA-10 Form"
   - ✅ Checkbox: "Form is complete and all required information is present"
   - ✅ Checkbox: "Signatures are valid and authorized"
   - ✅ Textarea: "Verification Notes (Optional)"
   - ✅ "Cancel" button
   - ✅ "Submit Verification" button (disabled until both checkboxes checked)

3. **Complete verification**:
   - Check both checkboxes
   - Add note: "Form verified. All signatures valid. Engine repair authorized."
   - Click "Submit Verification"

4. **Verify success**:
   - ✅ Dialog closes
   - ✅ Success message: "KEW.PA-10 form verified successfully"
   - ✅ Verification Status Card updates:
     - Alert changes to green: "This form has been verified and is ready for job creation"
     - Form Completeness: ✅ Verified
     - Signatures: ✅ Verified
     - Verification Notes appear
     - "Verify Form" button disappears
     - "Create Workshop Job" button appears (green)

## Step 7: Test Job Creation

1. **Click "Create Workshop Job" button**

2. **Verify redirect** to new Workshop Job detail page

3. **Expected data on job page**:
   - ✅ Job created with status "Received" (JobStatus enum)
   - ✅ Job linked to KEW.PA-10 form
   - ✅ Job shows asset information from KEW.PA-10
   - ✅ Job shows government department information

4. **Verify KEW.PA-10 form updates**:
   - Navigate back to KEW.PA-10 form detail page
   - ✅ "Workshop Job" card appears showing linked job
   - ✅ "Create Workshop Job" button is disabled/hidden

## Step 8: Verify Database Records

Run these commands to verify data integrity:

```bash
# Check KEW.PA-10 form
/c/Users/zuraidiismail/.config/herd/bin/php.bat artisan tinker
>>> App\Models\KewPA10::with('governmentDepartment', 'asset', 'workshopJob')->first()
>>> exit

# Should show:
# - form_completeness_verified: true
# - signatures_verified: true
# - verification_notes: "Form verified..."
# - workshopJob relationship loaded (not null after Step 7)
```

## Success Criteria

### Frontend
- ✅ Sidebar shows "KEW.PA-10 Forms" and "Inspections" menu items
- ✅ KEW.PA-10 list page loads without errors
- ✅ Test form appears in list with correct data
- ✅ Detail page shows all form information correctly
- ✅ Verification dialog works (checkboxes, validation, submission)
- ✅ Verification status updates in real-time
- ✅ "Create Workshop Job" button appears after verification
- ✅ Job creation redirects correctly

### Backend
- ✅ Routes registered (verified with `artisan route:list`)
- ✅ Controllers handle requests without errors
- ✅ Form validation works (try submitting verification without checkboxes)
- ✅ Database updates correctly (verification fields, job creation)
- ✅ Relationships work (KewPA10 → WorkshopJob, KewPA10 → Asset, KewPA10 → GovernmentDepartment)

### Database Integrity
- ✅ No SQL errors
- ✅ Foreign keys work correctly (kew_pa_10_id, government_department_id, asset_id)
- ✅ Timestamps update correctly
- ✅ Enum values stored correctly (JobPriority, JobStatus)

## Common Issues and Solutions

### Issue 1: Sidebar items not visible
**Symptoms**: "KEW.PA-10 Forms" doesn't appear in sidebar

**Solutions**:
1. Ensure `npm run dev` is running
2. Hard refresh browser (Ctrl+Shift+R)
3. Check logged-in user role (must be pentadbiran, penyelia, or pemeriksa)
4. Check browser console for errors

### Issue 2: 404 Not Found on /kew-pa-10
**Symptoms**: Page not found error

**Solutions**:
1. Verify routes registered: `/c/Users/zuraidiismail/.config/herd/bin/php.bat artisan route:list --name=kew-pa-10`
2. Clear route cache: `/c/Users/zuraidiismail/.config/herd/bin/php.bat artisan route:clear`
3. Restart Laravel server

### Issue 3: Test data not appearing
**Symptoms**: List page is empty

**Solutions**:
1. Re-run seeder: `/c/Users/zuraidiismail/.config/herd/bin/php.bat artisan db:seed --class=KewPA10TestDataSeeder`
2. Check database: `select * from kew_pa_10s;`
3. Verify filters aren't hiding data (click "Clear Filters")

### Issue 4: Foreign key errors
**Symptoms**: SQL errors mentioning kew_p_a10_id or other column names

**Solutions**:
1. Verify KewPA10 model has explicit foreign key:
   ```php
   public function workshopJob(): HasOne
   {
       return $this->hasOne(WorkshopJob::class, 'kew_pa_10_id');
   }
   ```
2. Clear model cache: `/c/Users/zuraidiismail/.config/herd/bin/php.bat artisan model:clear-cache`

## Next Steps

After completing this verification:

1. **Complete Workflow Testing**: Follow the comprehensive testing guide at:
   - `docs/07-testing/01-kew-pa-10-workflow-testing.md`

2. **Test Other User Roles**: Login as:
   - inspector@workshop.gov.my (Pemeriksa) - Test inspection workflow
   - supervisor@workshop.gov.my (Penyelia) - Test approval workflow
   - technician@workshop.gov.my (Juruteknik) - Test job completion

3. **Test Remaining Pages**: Navigate to:
   - `/inspections` - Inspection list
   - `/jobs/{job}/photos` - Photo gallery
   - `/jobs/{job}/completion/create` - Completion report

4. **Performance Testing**: Test with multiple forms (create 10-20 test forms)

5. **Error Handling**: Test validation errors, authorization failures, etc.

## Reference Documentation

- **Full Implementation Summary**: `SPRINT_KEW_PA_10_COMPLETE.md`
- **Comprehensive Testing Guide**: `docs/07-testing/01-kew-pa-10-workflow-testing.md`
- **Sprint Documentation**: `docs/04-sprints/04-sprint-kew-pa-10-foundation.md`

## Test User Credentials

| Role | Email | Password | Name | Access |
|------|-------|----------|------|--------|
| Admin Officer (Pentadbiran) | admin@workshop.gov.my | password | Ahmad Ibrahim | All features |
| Inspector (Pemeriksa) | inspector@workshop.gov.my | password | Siti Nurhaliza | Inspections, Forms |
| Supervisor (Penyelia) | supervisor@workshop.gov.my | password | Razak Abdullah | All except verification |
| Technician (Juruteknik) | technician@workshop.gov.my | password | Muthu Kumar | Assigned jobs only |

---

**Status**: Ready for testing! Follow steps 1-8 above to verify the complete KEW.PA-10 workflow implementation.
