# KEW.PA-10 Workflow Testing Guide

## Overview

This guide provides step-by-step instructions for testing the complete KEW.PA-10 (Malaysian Government Procurement Form) workflow from initial form registration to final return.

**Workflow**: External KEW.PA-10 Reception (Option 1)

**Roles Required**: Pentadbiran, Pemeriksa, Penyelia, Juruteknik

**Estimated Test Time**: 30-45 minutes for complete workflow

---

## Prerequisites

### 1. Database Setup

Run migrations to create all required tables:

```bash
php artisan migrate
```

### 2. Seed Initial Data

Create roles and permissions:

```bash
php artisan db:seed --class=RolePermissionSeeder
```

### 3. Create Test Users

Create one user for each role:

```bash
php artisan tinker
```

```php
// Admin Officer (Pentadbiran)
$admin = User::create([
    'name' => 'Ahmad Ibrahim',
    'email' => 'admin@workshop.gov.my',
    'password' => bcrypt('password'),
    'role' => 'pentadbiran',
]);

// Inspector (Pemeriksa)
$inspector = User::create([
    'name' => 'Siti Nurhaliza',
    'email' => 'inspector@workshop.gov.my',
    'password' => bcrypt('password'),
    'role' => 'pemeriksa',
]);

// Supervisor (Penyelia)
$supervisor = User::create([
    'name' => 'Razak Abdullah',
    'email' => 'supervisor@workshop.gov.my',
    'password' => bcrypt('password'),
    'role' => 'penyelia',
]);

// Technician (Juruteknik)
$technician = User::create([
    'name' => 'Muthu Kumar',
    'email' => 'technician@workshop.gov.my',
    'password' => bcrypt('password'),
    'role' => 'juruteknik',
]);
```

### 4. Create Test Government Department

```php
$dept = GovernmentDepartment::create([
    'name' => 'Ministry of Health',
    'department_code' => 'MOH',
    'ministry' => 'Health',
    'contact_person' => 'Dr. Faizal Hassan',
    'contact_phone' => '+60123456789',
    'contact_email' => 'workshop@moh.gov.my',
    'address' => 'Putrajaya Federal Territory',
    'is_active' => true,
]);
```

### 5. Create Test Asset

```php
$asset = Asset::create([
    'government_department_id' => $dept->id,
    'asset_tag' => 'MOH-VEH-2025-001',
    'asset_name' => 'Toyota Hilux 4x4',
    'asset_type' => 'Vehicle',
    'current_condition' => 'Engine overheating, radiator leaking',
    'acquisition_date' => '2020-01-15',
]);
```

---

## Complete Workflow Test Scenario

### Scenario: Vehicle Repair Request

**Asset**: Toyota Hilux 4x4 from Ministry of Health
**Issue**: Engine overheating and radiator leak
**Priority**: High (affects emergency medical transport)
**Expected Duration**: 2-3 days

---

## Phase 1: KEW.PA-10 Form Registration

**Role**: Admin Officer (Pentadbiran)
**Login**: admin@workshop.gov.my / password

### Step 1.1: Navigate to KEW.PA-10 List

1. Login to the system
2. Navigate to `/kew-pa-10` or click "KEW.PA-10 Forms" in sidebar
3. Verify you see the KEW.PA-10 index page with filters

**Expected Result**:
- ✅ KEW.PA-10 list page loads
- ✅ Search and filter options visible
- ✅ "Register New Form" button visible
- ✅ Empty state if no forms exist

### Step 1.2: Register New KEW.PA-10 Form

1. Click "Register New Form" button
2. Fill in the form:
   - **KEW.PA-10 Number**: `KEW.PA-10/MOH/2025/001`
   - **Government Department**: Select "Ministry of Health"
   - **Asset**: Select "Toyota Hilux 4x4 - MOH-VEH-2025-001"
   - **Description**:
     ```
     Vehicle engine overheating during operation. Radiator showing visible leaks.
     Requires immediate repair as vehicle is used for emergency medical transport.
     ```
   - **Priority**: Select "High"
   - **Budget Allocation Reference**: `BA-MOH-2025-12345` (optional)
   - **Received Date**: Select today's date
3. Click "Register Form"

**Expected Result**:
- ✅ Form validates successfully
- ✅ Redirects to form detail page
- ✅ Success message displayed
- ✅ Form shows "Verification Required" alert (yellow)

### Step 1.3: Verify KEW.PA-10 Form

1. On the form detail page, click "Verify Form" button
2. In the verification dialog:
   - ✅ Check "Form completeness verified"
   - ✅ Check "Signatures verified"
   - Add verification notes:
     ```
     Form received from MOH on [date]. All required signatures present.
     Budget allocation confirmed. Approved for workshop processing.
     ```
3. Click "Save Verification"

**Expected Result**:
- ✅ Dialog closes
- ✅ Success message: "KEW.PA-10 form verified successfully"
- ✅ Alert changes to green "Form Verified"
- ✅ Checkmarks appear next to verification items in sidebar
- ✅ "Create Workshop Job" button appears

### Step 1.4: Create Workshop Job

1. Click "Create Workshop Job" button
2. In the confirmation dialog, verify details
3. Click "Create Job"

**Expected Result**:
- ✅ Redirects to job detail page
- ✅ Job reference generated (e.g., `WS-2025-0001`)
- ✅ Job status is "NEW"
- ✅ KEW.PA-10 reference displayed
- ✅ Asset information visible

**Checkpoint**: Take note of the job reference number (e.g., `WS-2025-0001`)

---

## Phase 2: Inspection Assignment

**Role**: Admin Officer (Pentadbiran)
**Login**: admin@workshop.gov.my / password

### Step 2.1: Assign Inspector

1. Navigate to the job detail page (WS-2025-0001)
2. Change job status to "PENDING_INSPECTION"
3. Navigate to `/jobs/{job}/inspections/create`
4. Fill in inspection assignment:
   - **Inspector**: Select "Siti Nurhaliza (Pemeriksa)"
   - **Notes**:
     ```
     Please inspect vehicle engine and radiator system.
     Verify extent of damage and provide repair recommendations.
     ```
5. Click "Assign Inspection"

**Expected Result**:
- ✅ Inspection report created
- ✅ Redirects to inspection detail page
- ✅ Status shows "Pending"
- ✅ Inspector name displayed

**Checkpoint**: Logout from admin account

---

## Phase 3: Inspection Process

**Role**: Inspector (Pemeriksa)
**Login**: inspector@workshop.gov.my / password

### Step 3.1: Access Assigned Inspection

1. Login as inspector
2. Navigate to "My Inspections" or `/inspections`
3. Find the inspection for job WS-2025-0001
4. Click to view inspection details

**Expected Result**:
- ✅ Inspection loads
- ✅ Job details visible
- ✅ Asset information displayed
- ✅ "Edit Report" button visible

### Step 3.2: Upload Initial Assessment Photos

1. Navigate to `/jobs/{job}/photos`
2. Click "Upload Photos"
3. In the upload dialog:
   - **Photo Stage**: Select "Initial Assessment"
   - **Photos**: Upload 3-5 photos of damaged radiator and engine
   - **Description**: "Initial condition - radiator leak visible"
   - **Location**: "Engine bay, radiator mounting"
   - **Public**: Check (make visible to department)
4. Click "Upload Photos"

**Expected Result**:
- ✅ Photos upload successfully
- ✅ Photos appear in "Initial Assessment" section
- ✅ Green checkmark shows minimum photos met
- ✅ Photo count updates

### Step 3.3: Upload Diagnostic Photos

1. Click "Upload Photos" again
2. Select **Photo Stage**: "Diagnostic"
3. Upload 3-5 photos showing detailed damage assessment
4. Add description: "Radiator core damage, coolant hose deterioration"
5. Click "Upload Photos"

**Expected Result**:
- ✅ Photos uploaded to Diagnostic stage
- ✅ Both stages now have green checkmarks

### Step 3.4: Complete Inspection Report

1. Navigate back to inspection detail page
2. Click "Edit Report"
3. Fill in all assessment fields:

   **Current Asset Condition**:
   ```
   Vehicle mileage: 125,000 km
   Overall condition: Fair, regular maintenance evident
   Engine bay: Clean but shows coolant staining from leak
   ```

   **Visual Damage Assessment**:
   ```
   - Radiator core: Multiple fin damage, visible corrosion
   - Coolant hoses: Upper hose shows cracking, requires replacement
   - Radiator cap: Seal deteriorated
   - No damage to engine block or water pump observed
   ```

   **Functional Testing Results**:
   ```
   - Engine starts normally
   - Temperature rises rapidly to 110°C within 5 minutes
   - Coolant leaking at rate of approximately 500ml per hour
   - Pressure test confirms radiator core perforation
   ```

   **Safety Hazards Identified**:
   ```
   - Risk of engine seizure if operated in current condition
   - Coolant spill creates slip hazard in workshop
   - Steam risk if operated hot
   ```

   **Additional Issues Discovered**:
   ```
   - Thermostat housing shows minor seepage
   - Fan belt showing wear (not urgent)
   - Coolant reservoir cracked (minor)
   ```

   **Recommended Repairs**:
   ```
   Priority 1 (Urgent):
   - Replace radiator assembly
   - Replace upper and lower coolant hoses
   - Replace radiator cap

   Priority 2 (Recommended):
   - Replace thermostat housing gasket
   - Replace coolant reservoir
   - Replace fan belt

   Estimated time: 2 days
   Estimated cost: RM 2,500 - 3,000
   ```

4. Click "Update Report"

**Expected Result**:
- ✅ Report saves successfully
- ✅ All fields populate on inspection detail page
- ✅ "Approve" and "Reject" buttons visible

### Step 3.5: Approve Inspection

1. Click "Approve" button
2. In the approval dialog:
   - **Approval Notes**:
     ```
     Inspection completed. Repairs approved as recommended.
     All safety concerns noted. Proceed with radiator replacement.
     ```
   - **Digital Signature**: Type full name: "Siti Nurhaliza"
3. Click "Approve Inspection"

**Expected Result**:
- ✅ Alert changes to green "Inspection Approved"
- ✅ Approval timestamp recorded
- ✅ Digital signature displayed
- ✅ Status badge shows "Approved"
- ✅ Edit button disappears (cannot edit after approval)

**Alternative Test - Rejection Flow**:

If you want to test rejection (create a second inspection):
1. Click "Reject" instead
2. Provide reason:
   ```
   Insufficient photos of engine block. Please provide additional
   photos showing water pump and engine block condition before approval.
   ```
3. Enter digital signature
4. Verify status changes to "Rejected"
5. Verify technician must create new inspection

**Checkpoint**: Logout from inspector account

---

## Phase 4: Repair Assignment

**Role**: Admin Officer or Supervisor
**Login**: supervisor@workshop.gov.my / password

### Step 4.1: Assign Technician

1. Navigate to job WS-2025-0001
2. Click "Assign Technician"
3. Select "Muthu Kumar (Juruteknik)"
4. Update job status to "REPAIR_IN_PROGRESS"
5. Save assignment

**Expected Result**:
- ✅ Technician assigned to job
- ✅ Job status updates
- ✅ Assignment recorded in history

**Checkpoint**: Logout, login as technician

---

## Phase 5: Repair Execution

**Role**: Technician (Juruteknik)
**Login**: technician@workshop.gov.my / password

### Step 5.1: Access Assigned Job

1. Login as technician
2. Navigate to "My Jobs" or dashboard
3. Find job WS-2025-0001
4. Click to view job details

**Expected Result**:
- ✅ Job details load
- ✅ Shows assigned to current user
- ✅ Inspection report accessible
- ✅ Photos viewable

### Step 5.2: Upload During-Repair Photos

1. Navigate to `/jobs/{job}/photos`
2. Upload 3+ photos:
   - **Stage**: "During Repair"
   - **Photos**: Radiator removal, old parts, new parts
   - **Description**: "Radiator replacement in progress"
   - **Location**: "Engine bay"
3. Click "Upload Photos"

**Expected Result**:
- ✅ Photos uploaded successfully
- ✅ "During Repair" stage has green checkmark

### Step 5.3: Upload After-Repair Photos

1. Upload 3+ photos:
   - **Stage**: "After Repair"
   - **Photos**: New radiator installed, system tested, clean engine bay
   - **Description**: "Repair completed, system tested and verified"
2. Click "Upload Photos"

**Expected Result**:
- ✅ All critical stages now have photos
- ✅ Photo requirements met

### Step 5.4: Create Completion Report

1. Navigate to `/jobs/{job}/completion/create`
2. Verify photo requirements alert shows green (all photos uploaded)
3. Fill in work details:

   **Work Completed**: ✅ Checked

   **Time Spent (Hours)**: `16` (2 days × 8 hours)

   **Work Description**:
   ```
   Radiator Replacement - Complete System Overhaul

   Work Performed:
   1. Drained cooling system completely
   2. Removed old radiator assembly
   3. Installed new OEM radiator (Toyota Part #16400-0L140)
   4. Replaced upper coolant hose (Gates #22036)
   5. Replaced lower coolant hose (Gates #22037)
   6. Installed new radiator cap (Stant #10230)
   7. Replaced thermostat housing gasket
   8. Installed new coolant reservoir
   9. Replaced fan belt (Gates #K040378)
   10. Filled system with Toyota Long Life Coolant (50/50 mix)
   11. Bled air from cooling system
   12. Pressure tested system (16 PSI, no leaks)
   13. Road tested vehicle (30 minutes, normal operating temp maintained)

   All repairs completed as per inspection recommendations.
   System operating within normal parameters.
   ```

   **Issues Encountered**:
   ```
   - Lower radiator mount bracket was corroded, required replacement
   - One radiator mount bolt was seized, required drilling out
   - Coolant sensor wire connector brittle, handled carefully

   All issues resolved during repair process.
   ```

   **Recommendations**:
   ```
   1. Schedule coolant system inspection in 6 months
   2. Replace coolant sensor within next 12 months (connector showing wear)
   3. Consider preventive replacement of water pump at next service
   4. Recommend driver training on temperature gauge monitoring
   ```

   **Quality Rating**: Select `4 - Very Good`

4. Click "Add Part" to add parts used

**Expected Result**:
- ✅ Form validates
- ✅ Parts dialog opens

### Step 5.5: Add Parts Used

Add each part individually:

**Part 1**:
- Name: `Toyota OEM Radiator (16400-0L140)`
- Quantity: `1`
- Unit Cost: `1250.00`
- Click "Add Part"

**Part 2**:
- Name: `Upper Coolant Hose (Gates #22036)`
- Quantity: `1`
- Unit Cost: `85.00`
- Click "Add Part"

**Part 3**:
- Name: `Lower Coolant Hose (Gates #22037)`
- Quantity: `1`
- Unit Cost: `95.00`
- Click "Add Part"

**Part 4**:
- Name: `Radiator Cap (Stant #10230)`
- Quantity: `1`
- Unit Cost: `45.00`
- Click "Add Part"

**Part 5**:
- Name: `Thermostat Housing Gasket`
- Quantity: `1`
- Unit Cost: `25.00`
- Click "Add Part"

**Part 6**:
- Name: `Coolant Reservoir`
- Quantity: `1`
- Unit Cost: `120.00`
- Click "Add Part"

**Part 7**:
- Name: `Fan Belt (Gates #K040378)`
- Quantity: `1`
- Unit Cost: `65.00`
- Click "Add Part"

**Part 8**:
- Name: `Toyota Long Life Coolant (5L)`
- Quantity: `2`
- Unit Cost: `75.00`
- Click "Add Part"

**Part 9**:
- Name: `Radiator Mount Bracket`
- Quantity: `1`
- Unit Cost: `35.00`
- Click "Add Part"

**Part 10**:
- Name: `Coolant System Flush Chemical`
- Quantity: `1`
- Unit Cost: `45.00`
- Click "Add Part"

**Expected Result**:
- ✅ Parts table populates with all items
- ✅ Quantities and costs display correctly
- ✅ Total cost calculates automatically: **RM 1,990.00**
- ✅ Each part has "Remove" button

### Step 5.6: Review and Submit

1. Verify all fields are complete
2. Verify photo summary shows all requirements met
3. Click "Save Completion Report"

**Expected Result**:
- ✅ Report saves successfully
- ✅ Redirects to completion report detail page
- ✅ All work details displayed
- ✅ Parts table shows with total cost
- ✅ "Sign Report" button visible

### Step 5.7: Sign Completion Report

1. Click "Sign Report" button
2. In the signature dialog:
   - **Signature**: Type full name: "Muthu Kumar"
3. Click "Sign Report"

**Expected Result**:
- ✅ Digital signature captured
- ✅ Signature timestamp recorded
- ✅ "Submit for Review" button appears
- ✅ Edit functionality disabled

### Step 5.8: Submit for Review

1. Click "Submit for Review"
2. Confirm submission

**Expected Result**:
- ✅ Job status changes to "PENDING_REVIEW"
- ✅ Redirects to job detail page
- ✅ Status badge updates

**Checkpoint**: Logout from technician account

---

## Phase 6: Supervisor Review

**Role**: Supervisor (Penyelia)
**Login**: supervisor@workshop.gov.my / password

### Step 6.1: Review Completion Report

1. Navigate to pending reviews or job WS-2025-0001
2. Click "View Completion Report"
3. Review:
   - Work description
   - Parts used and costs
   - Time spent
   - Photos (all stages)
   - Quality rating

**Expected Result**:
- ✅ All information displayed correctly
- ✅ Total cost: RM 1,990.00
- ✅ Time: 16 hours
- ✅ All photos accessible

### Step 6.2: Approve and Complete Job

1. Navigate back to job detail
2. Update job status to "COMPLETED"
3. Add completion notes:
   ```
   Work reviewed and approved. Quality meets workshop standards.
   All documentation complete. Ready for KEW.PA-10 return process.
   ```

**Expected Result**:
- ✅ Job status updates to "COMPLETED"
- ✅ Completion timestamp recorded
- ✅ Status in timeline updates

---

## Phase 7: KEW.PA-10 Return Process

**Role**: Admin Officer (Pentadbiran)
**Login**: admin@workshop.gov.my / password

### Step 7.1: Prepare Return Package

1. Navigate to job WS-2025-0001
2. Update status to "PENDING_KEW_PA_10_RETURN"
3. Navigate to `/jobs/{job}/prepare-return`
4. Review return package contents:
   - Original KEW.PA-10 form details
   - Inspection report summary
   - Completion report summary
   - Parts list and costs
   - Photos (selected for return)
   - Total cost breakdown

**Expected Result**:
- ✅ Return package page loads
- ✅ All documents summarized
- ✅ Costs itemized
- ✅ Photos organized by stage

### Step 7.2: Mark as Returned

1. Click "Mark as Returned to Department"
2. In the dialog, add notes:
   ```
   KEW.PA-10 form and supporting documents returned to Ministry of Health
   on [date]. Received by Dr. Faizal Hassan. Vehicle released in good
   working condition. All repairs completed as specified.

   Documents provided:
   - Original KEW.PA-10 form
   - Inspection report
   - Completion report
   - Parts invoice
   - Photo documentation (CD)
   ```
3. Click "Confirm Return"

**Expected Result**:
- ✅ Job status updates to "KEW_PA_10_RETURNED"
- ✅ Return timestamp recorded
- ✅ Notes saved
- ✅ Status visible in timeline

### Step 7.3: Final Invoicing

1. Update job status to "INVOICED"
2. Add invoice reference: `INV-2025-0001`

**Expected Result**:
- ✅ Job marked as fully complete
- ✅ Invoice reference recorded
- ✅ Workflow complete

---

## Verification Checklist

After completing the workflow, verify the following:

### Database Records

```bash
php artisan tinker
```

```php
// Verify KEW.PA-10 form
$kew = KewPA10::with('workshopJob')->first();
echo "KEW.PA-10: {$kew->kew_pa_10_number}\n";
echo "Verified: " . ($kew->isComplete() ? 'Yes' : 'No') . "\n";
echo "Job: {$kew->workshopJob->job_reference}\n";

// Verify inspection
$inspection = InspectionReport::first();
echo "Inspection Status: {$inspection->approval_status}\n";
echo "Inspector: {$inspection->inspector->name}\n";

// Verify photos
$photoCount = JobPhoto::count();
echo "Total Photos: {$photoCount}\n";
foreach (PhotoStage::cases() as $stage) {
    $count = JobPhoto::where('photo_stage', $stage)->count();
    echo "  {$stage->label()}: {$count} photos\n";
}

// Verify completion report
$completion = RepairCompletionReport::first();
echo "Time Spent: {$completion->time_spent_hours} hours\n";
echo "Parts Used: " . count($completion->parts_used) . " items\n";
echo "Total Cost: RM " . array_sum(array_map(fn($p) => $p['quantity'] * $p['cost'], $completion->parts_used)) . "\n";
echo "Signed: " . ($completion->isSigned() ? 'Yes' : 'No') . "\n";

// Verify job status history
$job = WorkshopJob::with('statusHistories')->first();
echo "Job: {$job->job_reference}\n";
echo "Current Status: {$job->status->label()}\n";
echo "Status History: {$job->statusHistories->count()} transitions\n";
```

### Expected Output

```
KEW.PA-10: KEW.PA-10/MOH/2025/001
Verified: Yes
Job: WS-2025-0001
Inspection Status: approved
Inspector: Siti Nurhaliza
Total Photos: 12
  Initial Assessment: 3 photos
  Diagnostic: 3 photos
  During Repair: 3 photos
  After Repair: 3 photos
  Documentation: 0 photos
Time Spent: 16 hours
Parts Used: 10 items
Total Cost: RM 1990
Signed: Yes
Job: WS-2025-0001
Current Status: Invoiced
Status History: 6 transitions
```

---

## Status Transition Timeline

Verify the complete status progression:

1. ✅ NEW → PENDING_INSPECTION (Job created from KEW.PA-10)
2. ✅ PENDING_INSPECTION → INSPECTION_IN_PROGRESS (Inspector assigned)
3. ✅ INSPECTION_IN_PROGRESS → INSPECTION_APPROVED (Inspection approved)
4. ✅ INSPECTION_APPROVED → REPAIR_IN_PROGRESS (Technician started work)
5. ✅ REPAIR_IN_PROGRESS → PENDING_REVIEW (Completion submitted)
6. ✅ PENDING_REVIEW → COMPLETED (Supervisor approved)
7. ✅ COMPLETED → PENDING_KEW_PA_10_RETURN (Ready for return)
8. ✅ PENDING_KEW_PA_10_RETURN → KEW_PA_10_RETURNED (Returned to department)
9. ✅ KEW_PA_10_RETURNED → INVOICED (Final billing)

---

## Common Issues and Troubleshooting

### Issue 1: Cannot Upload Photos

**Symptom**: Photos fail to upload or error appears

**Check**:
1. Verify storage link exists: `php artisan storage:link`
2. Check file permissions on `storage/app/public`
3. Verify file size < 10MB
4. Verify file type is JPG or PNG

**Fix**:
```bash
php artisan storage:link
chmod -R 775 storage/app/public
```

### Issue 2: Authorization Error

**Symptom**: "This action is unauthorized" when accessing features

**Check**:
1. Verify user has correct role assigned
2. Check policies are registered in `AppServiceProvider`
3. Verify Gate::authorize() calls in controllers

**Fix**:
```php
// In tinker, verify user role
$user = User::find(1);
echo $user->role; // Should be: pentadbiran, pemeriksa, penyelia, or juruteknik
```

### Issue 3: Status Transition Fails

**Symptom**: Cannot update job status

**Check**:
1. Verify transition is allowed in JobStatus::allowedTransitions()
2. Check workflow prerequisites are met (e.g., inspection approved before repair)

**Fix**: Follow correct sequence as documented in state machine

### Issue 4: Photos Don't Show in Gallery

**Symptom**: Uploaded photos not visible

**Check**:
1. Verify photos are saved in database: `JobPhoto::count()`
2. Check file exists in `storage/app/public/job-photos/`
3. Verify storage link: `ls -la public/storage`

**Fix**:
```bash
php artisan storage:link
```

---

## Advanced Testing Scenarios

### Scenario A: Inspection Rejection Flow

1. Complete steps 1-3.4 (up to inspection report)
2. Instead of approving, click "Reject"
3. Provide detailed rejection reason
4. Verify job status reverts
5. Create new inspection
6. Complete and approve
7. Continue workflow

### Scenario B: Incomplete Photos

1. Skip photo upload in Phase 3.2-3.3
2. Attempt to create completion report in Phase 5.4
3. Verify yellow alert shows missing photos
4. Upload required photos
5. Verify alert turns green
6. Continue with completion report

### Scenario C: Parts Cost Tracking

1. In completion report, add parts with different quantities
2. Verify total cost calculates correctly
3. Remove a part
4. Verify total updates
5. Add part back
6. Verify final total matches expected

### Scenario D: Multiple Inspectors

1. Create second inspector user
2. Assign inspection to first inspector
3. First inspector creates report but doesn't approve
4. Admin reassigns to second inspector
5. Second inspector completes and approves
6. Verify only latest inspector can approve

---

## Performance Testing

### Load Test: Multiple Jobs

Create 50 jobs and verify system performance:

```bash
php artisan tinker
```

```php
// Create 50 test jobs
for ($i = 1; $i <= 50; $i++) {
    $kew = KewPA10::create([
        'kew_pa_10_number' => "KEW.PA-10/TEST/2025/" . str_pad($i, 4, '0', STR_PAD_LEFT),
        'government_department_id' => 1,
        'asset_id' => 1,
        'description' => "Test job $i",
        'priority' => 'normal',
        'received_date' => now(),
        'form_completeness_verified' => true,
        'signatures_verified' => true,
    ]);

    $job = WorkshopJob::create([
        'job_reference' => "WS-2025-" . str_pad($i, 4, '0', STR_PAD_LEFT),
        'kew_pa_10_id' => $kew->id,
        'government_department_id' => 1,
        'asset_id' => 1,
        'status' => JobStatus::NEW,
    ]);
}
```

Verify:
- KEW.PA-10 index page loads quickly (<2s)
- Pagination works correctly
- Filters don't slow down queries
- Search returns results quickly

---

## Automated Testing (Future)

### Pest Test Examples

Create feature tests in `tests/Feature/`:

```php
// tests/Feature/KewPA10WorkflowTest.php

test('admin can register kew pa 10 form', function () {
    $admin = User::factory()->create(['role' => 'pentadbiran']);

    $this->actingAs($admin)
        ->post('/kew-pa-10', [
            'kew_pa_10_number' => 'KEW.PA-10/TEST/2025/001',
            'government_department_id' => 1,
            'asset_id' => 1,
            'description' => 'Test description',
            'priority' => 'normal',
        ])
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('kew_pa_10s', [
        'kew_pa_10_number' => 'KEW.PA-10/TEST/2025/001',
    ]);
});

test('inspector can approve inspection', function () {
    $inspector = User::factory()->create(['role' => 'pemeriksa']);
    $inspection = InspectionReport::factory()->create([
        'inspector_id' => $inspector->id,
    ]);

    $this->actingAs($inspector)
        ->post("/inspections/{$inspection->id}/approve", [
            'notes' => 'Approved',
            'digital_signature' => 'Inspector Name',
        ])
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('inspection_reports', [
        'id' => $inspection->id,
        'approval_status' => 'approved',
    ]);
});
```

Run tests:
```bash
php artisan test --filter KewPA10Workflow
```

---

## Success Criteria

### Workflow Completion Checklist

- [ ] KEW.PA-10 form registered successfully
- [ ] Form verified with both checkmarks
- [ ] Workshop job created with correct reference
- [ ] Inspector assigned and notified
- [ ] Initial assessment photos uploaded (3+)
- [ ] Diagnostic photos uploaded (3+)
- [ ] Inspection report completed with all fields
- [ ] Inspection approved with digital signature
- [ ] Technician assigned to job
- [ ] During-repair photos uploaded (3+)
- [ ] After-repair photos uploaded (3+)
- [ ] Completion report created
- [ ] All parts added to report (10 parts)
- [ ] Total cost calculated correctly (RM 1,990.00)
- [ ] Completion report signed by technician
- [ ] Report submitted for review
- [ ] Supervisor approved completion
- [ ] KEW.PA-10 return package prepared
- [ ] Form marked as returned with notes
- [ ] Final invoice created
- [ ] Job status reaches INVOICED

**Test Result**: ✅ PASS / ❌ FAIL

**Time Taken**: _______ minutes

**Notes**: ___________________________

---

## Next Steps

After successful manual testing:

1. **Document Bugs**: Create issues for any problems found
2. **Create Automated Tests**: Convert manual tests to Pest tests
3. **Performance Optimization**: Address any slow queries
4. **User Training**: Use this guide to train actual users
5. **Production Deployment**: Deploy to staging environment
6. **UAT**: Conduct user acceptance testing with real users

---

**Test Guide Version**: 1.0
**Last Updated**: 2025-12-30
**Status**: Ready for Testing
