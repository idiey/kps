<?php

use App\Enums\JobPriority;
use App\Enums\JobStatus;
use App\Enums\PhotoStage;
use App\Models\Asset;
use App\Models\Customer;
use App\Models\GovernmentDepartment;
use App\Models\InspectionReport;
use App\Models\JobPhoto;
use App\Models\KewPA10;
use App\Models\RepairCompletionReport;
use App\Models\User;
use App\Models\WorkshopJob;
use App\Services\InspectionService;
use App\Services\KewPA10Service;
use App\Services\PhotoStorageService;
use App\Services\RepairCompletionService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * KEW.PA-10 Workflow Option 1 - End-to-End Integration Test
 *
 * This test validates the complete 11-step workflow from form registration
 * to final invoicing with actual data flowing through all stages.
 *
 * Workflow Steps:
 * 1. KEW.PA-10 Form Registration
 * 2. Form Verification
 * 3. Workshop Job Creation
 * 4. Inspector Assignment & Site Inspection
 * 5. Inspection Report Creation
 * 6. Inspection Approval
 * 7. Repair Work Assignment
 * 8. Photo Documentation During Repair
 * 9. Repair Completion Report
 * 10. Asset Return to Department
 * 11. Final Invoicing
 */
beforeEach(function () {
    // Create test users for each role
    $this->admin = User::factory()->create(['role' => 'pentadbiran']);
    $this->inspector = User::factory()->create(['role' => 'pemeriksa']);
    $this->supervisor = User::factory()->create(['role' => 'penyelia']);
    $this->technician = User::factory()->create(['role' => 'juruteknik']);

    // Create government department
    $this->department = GovernmentDepartment::create([
        'name' => 'Ministry of Health Malaysia',
        'department_code' => 'MOH',
        'ministry' => 'Health',
        'contact_person' => 'Dr. Ahmad Faizal',
        'email' => 'workshop@moh.gov.my',
        'phone' => '+60388883000',
        'address' => 'Level 1, Block E1, Parcel E, Federal Government Administrative Centre',
        'city' => 'Putrajaya',
        'state' => 'Wilayah Persekutuan',
        'postcode' => '62590',
        'is_active' => true,
    ]);

    // Create government asset
    $this->asset = Asset::create([
        'government_department_id' => $this->department->id,
        'asset_tag' => 'MOH-VEH-2025-001',
        'asset_type' => 'Vehicle',
        'asset_name' => 'Toyota Hilux 4x4 Double Cab',
        'description' => 'Government-issued emergency response vehicle',
        'location' => 'MOH Central Workshop, Putrajaya',
        'current_condition' => 'Engine overheating, radiator leak detected',
        'last_maintenance_date' => now()->subMonths(3),
    ]);

    // Initialize services
    $this->kewService = app(KewPA10Service::class);
    $this->inspectionService = app(InspectionService::class);
    $this->photoService = app(PhotoStorageService::class);
    $this->completionService = app(RepairCompletionService::class);

    // Setup fake storage for photo uploads
    Storage::fake('public');
});

test('complete kew pa 10 workflow option 1 from registration to invoicing', function () {
    // ============================================================
    // STEP 1: KEW.PA-10 Form Registration by Admin
    // ============================================================

    $this->actingAs($this->admin);

    $formData = [
        'kew_pa_10_number' => 'KEW.PA-10/MOH/2025/001',
        'government_department_id' => $this->department->id,
        'asset_id' => $this->asset->id,
        'description' => 'Emergency vehicle requires immediate attention. Engine overheating during emergency runs. Radiator showing visible coolant leaks. Critical safety issue affecting emergency medical transport capabilities.',
        'priority' => JobPriority::HIGH->value,
        'budget_allocation_reference' => 'BA-MOH-2025-12345',
        'received_date' => now()->format('Y-m-d'),
        'received_by' => $this->admin->id,
        'form_completeness_verified' => false,
        'signatures_verified' => false,
    ];

    $kew = KewPA10::create($formData);

    expect($kew)->toBeInstanceOf(KewPA10::class);
    expect($kew->kew_pa_10_number)->toBe('KEW.PA-10/MOH/2025/001');
    expect($kew->priority)->toBe(JobPriority::HIGH);
    expect($kew->form_completeness_verified)->toBeFalse();
    expect($kew->signatures_verified)->toBeFalse();

    $this->assertDatabaseHas('kew_pa_10s', [
        'kew_pa_10_number' => 'KEW.PA-10/MOH/2025/001',
        'government_department_id' => $this->department->id,
        'asset_id' => $this->asset->id,
    ]);

    // ============================================================
    // STEP 2: Form Verification by Admin
    // ============================================================

    $verificationData = [
        'form_completeness_verified' => true,
        'signatures_verified' => true,
        'verification_notes' => 'Form verified on ' . now()->format('Y-m-d') . '. All signatures valid. Department head authorization confirmed. Budget allocation verified with Finance Department.',
    ];

    $kew->update($verificationData);
    $kew->refresh();

    expect($kew->form_completeness_verified)->toBeTrue();
    expect($kew->signatures_verified)->toBeTrue();
    expect($kew->isVerified())->toBeTrue();
    expect($kew->isComplete())->toBeTrue();

    // ============================================================
    // STEP 3: Workshop Job Creation
    // ============================================================

    // Create a customer representing the government department
    $customer = Customer::factory()->create([
        'name' => $this->department->name,
        'email' => $this->department->email ?? 'moh@example.gov.my',
        'phone' => '03-12345678',
    ]);

    $job = WorkshopJob::create([
        'customer_id' => $customer->id,
        'kew_pa_10_id' => $kew->id,
        'government_department_id' => $this->department->id,
        'asset_id' => $this->asset->id,
        'title' => 'Emergency Vehicle Cooling System Repair',
        'description' => $kew->description,
        'status' => JobStatus::NEW,
        'priority' => $kew->priority,
    ]);

    expect($job)->toBeInstanceOf(WorkshopJob::class);
    expect($job->kew_pa_10_id)->toBe($kew->id);
    expect($job->status)->toBe(JobStatus::NEW);
    expect($job->priority)->toBe(JobPriority::HIGH);
    expect($job->job_number)->not->toBeEmpty();

    $kew->refresh();
    expect($kew->workshopJob->id)->toBe($job->id);

    // ============================================================
    // STEP 4: Inspector Assignment & Site Inspection
    // ============================================================

    $this->actingAs($this->admin);

    // Transition to PENDING_INSPECTION status
    $job->status = JobStatus::PENDING_INSPECTION;
    $job->save();

    // Assign inspector
    $job->assignments()->create([
        'assigned_to' => $this->inspector->id,
        'assigned_by' => $this->admin->id,
        'assigned_at' => now(),
        'notes' => 'Assigned to Senior Inspector for emergency vehicle assessment',
    ]);

    // Inspector starts inspection
    $job->status = JobStatus::INSPECTION_IN_PROGRESS;
    $job->save();

    expect($job->status)->toBe(JobStatus::INSPECTION_IN_PROGRESS);
    expect($job->assignments)->toHaveCount(1);

    // ============================================================
    // STEP 5: Inspection Report Creation by Inspector
    // ============================================================

    $this->actingAs($this->inspector);

    $inspectionData = [
        'workshop_job_id' => $job->id,
        'inspector_id' => $this->inspector->id,
        'inspection_date' => now()->format('Y-m-d'),
        'approval_status' => 'pending',
        'asset_condition_current' => 'Vehicle operational but experiencing critical cooling system failure. Engine temperature reaches unsafe levels within 15 minutes of operation.',
        'visual_damage_assessment' => 'Radiator core shows visible corrosion and coolant seepage at multiple points. Coolant reservoir level critically low. No visible body damage or collision marks.',
        'functional_testing_results' => 'Engine test: Temperature gauge reading 105°C at idle after 10 minutes (normal: 85-90°C). Coolant pressure test failed - system unable to maintain pressure. Fan operation: Normal.',
        'safety_hazards_identified' => 'CRITICAL: Risk of engine seizure if operated beyond 15 minutes. Potential coolant spray hazard if hose fails under pressure. Vehicle currently UNSAFE for emergency operations.',
        'additional_issues_discovered' => 'Water pump bearings showing early wear. Thermostat replacement recommended as preventive measure. Coolant hoses show age-related hardening.',
        'recommended_repairs' => 'IMMEDIATE: 1) Replace radiator assembly (estimated 2-3 days), 2) Replace all coolant hoses, 3) Replace thermostat, 4) Install new water pump, 5) Full cooling system flush and refill',
        'estimated_repair_time_hours' => 24,
        'estimated_cost' => 4500.00,
        'spare_parts_availability' => 'Radiator: 3-day lead time from supplier. Other parts: In stock at workshop.',
    ];

    $inspection = InspectionReport::create($inspectionData);

    expect($inspection)->toBeInstanceOf(InspectionReport::class);
    expect($inspection->inspector_id)->toBe($this->inspector->id);
    expect($inspection->workshop_job_id)->toBe($job->id);
    expect($inspection->approval_status)->toBe('pending');

    // Upload initial inspection photos
    $initialPhoto = UploadedFile::fake()->image('radiator_damage.jpg', 1920, 1080);

    $photo1 = $this->photoService->uploadPhoto($job, $initialPhoto, PhotoStage::INITIAL, [
        'description' => 'Radiator showing severe corrosion and coolant leakage at lower tank seam',
        'location_context' => 'Engine bay - driver side radiator core',
        'is_public' => true,
        'inspection_report_id' => $inspection->id,
    ]);

    expect($photo1->photo_stage)->toBe(PhotoStage::INITIAL);
    expect($photo1->workshop_job_id)->toBe($job->id);

    // ============================================================
    // STEP 6: Inspection Approval by Supervisor
    // ============================================================

    $this->actingAs($this->supervisor);

    $approvalData = [
        'approval_status' => 'approved',
        'approval_notes' => 'Inspection report reviewed and approved. Repair estimate within budget allocation. Priority HIGH confirmed - vehicle critical for emergency medical services. Authorize immediate repair work commencement.',
        'digital_signature' => 'SUPERVISOR_SIG_' . now()->timestamp,
    ];

    $inspection->update($approvalData);
    $inspection->refresh();

    // Update job status to reflect approval
    $job->update(['status' => JobStatus::INSPECTION_APPROVED]);
    $job->refresh();

    expect($inspection->approval_status)->toBe('approved');
    expect($job->status)->toBe(JobStatus::INSPECTION_APPROVED);

    // ============================================================
    // STEP 7: Repair Work Assignment to Technician
    // ============================================================

    $this->actingAs($this->admin);

    // Assign technician for repair work
    $job->assignments()->create([
        'assigned_to' => $this->technician->id,
        'assigned_by' => $this->admin->id,
        'assigned_at' => now(),
        'notes' => 'Assigned to Master Technician Muthu for cooling system overhaul. HIGH PRIORITY - Emergency vehicle.',
    ]);

    $job->status = JobStatus::REPAIR_IN_PROGRESS;
    $job->save();

    expect($job->status)->toBe(JobStatus::REPAIR_IN_PROGRESS);

    // ============================================================
    // STEP 8: Photo Documentation During Repair by Technician
    // ============================================================

    $this->actingAs($this->technician);

    // During repair photos
    $duringRepairPhoto = UploadedFile::fake()->image('radiator_removal.jpg', 1920, 1080);

    $photo2 = $this->photoService->uploadPhoto($job, $duringRepairPhoto, PhotoStage::DURING_REPAIR, [
        'description' => 'Old radiator removed. Visible scale buildup and corrosion damage confirmed.',
        'location_context' => 'Workshop Bay 3 - removed component on workbench',
        'is_public' => true,
    ]);

    // After repair photos
    $afterRepairPhoto = UploadedFile::fake()->image('new_radiator_installed.jpg', 1920, 1080);

    $photo3 = $this->photoService->uploadPhoto($job, $afterRepairPhoto, PhotoStage::AFTER_REPAIR, [
        'description' => 'New OEM radiator installed. All hoses replaced. System pressure tested - holding 15 PSI.',
        'location_context' => 'Engine bay - new components installed',
        'is_public' => true,
    ]);

    // Verify minimum photo requirements met
    expect($job->photos()->where('photo_stage', PhotoStage::INITIAL)->exists())->toBeTrue();
    expect($job->photos()->where('photo_stage', PhotoStage::DURING_REPAIR)->exists())->toBeTrue();
    expect($job->photos()->where('photo_stage', PhotoStage::AFTER_REPAIR)->exists())->toBeTrue();

    // ============================================================
    // STEP 9: Repair Completion Report by Technician
    // ============================================================

    $completionData = [
        'workshop_job_id' => $job->id,
        'technician_id' => $this->technician->id,
        'completion_date' => now()->format('Y-m-d'),
        'work_completed' => true,
        'time_spent_hours' => 22.5,
        'work_description' => 'Complete cooling system overhaul performed:\n1. Removed and replaced radiator assembly (OEM part)\n2. Replaced all coolant hoses (upper, lower, heater hoses)\n3. Installed new thermostat (82°C rating)\n4. Replaced water pump assembly\n5. Flushed entire cooling system\n6. Refilled with premium coolant (50/50 mix)\n7. Pressure tested system - holding 15 PSI for 30 minutes\n8. Test drive 45km - temperature stable at 88°C under load',
        'issues_encountered' => 'Minor delay sourcing OEM radiator (3 days). Discovered water pump shaft bearing wear during disassembly - replaced as preventive measure (additional 2 hours labor).',
        'recommendations' => 'Coolant system operating normally. Recommend coolant change at 50,000km intervals. Monitor for leaks during first 1,000km. Vehicle safe for immediate emergency service deployment.',
        'quality_rating' => 5,
        'parts_used' => [
            [
                'name' => 'Radiator Assembly - OEM Toyota',
                'part_number' => '16400-75580',
                'quantity' => 1,
                'unit_cost' => 1850.00,
                'supplier' => 'Toyota Genuine Parts Malaysia',
            ],
            [
                'name' => 'Water Pump Assembly',
                'part_number' => '16100-69445',
                'quantity' => 1,
                'unit_cost' => 650.00,
                'supplier' => 'Toyota Genuine Parts Malaysia',
            ],
            [
                'name' => 'Thermostat 82°C',
                'part_number' => '90916-03129',
                'quantity' => 1,
                'unit_cost' => 85.00,
                'supplier' => 'Toyota Genuine Parts Malaysia',
            ],
            [
                'name' => 'Radiator Hose Kit (Upper & Lower)',
                'part_number' => 'HOSE-KIT-HIL',
                'quantity' => 1,
                'unit_cost' => 280.00,
                'supplier' => 'Gates Malaysia',
            ],
            [
                'name' => 'Premium Coolant 5L',
                'part_number' => 'COOL-PREM-5L',
                'quantity' => 3,
                'unit_cost' => 45.00,
                'supplier' => 'Workshop Stock',
            ],
        ],
        'labor_cost' => 1125.00, // 22.5 hours @ RM50/hour
    ];

    $completion = RepairCompletionReport::create($completionData);

    expect($completion)->toBeInstanceOf(RepairCompletionReport::class);
    expect($completion->work_completed)->toBeTrue();
    expect($completion->technician_id)->toBe($this->technician->id);
    expect($completion->quality_rating)->toBe(5);

    // Calculate total parts cost
    $totalPartsCost = collect($completion->parts_used)->sum(fn($part) => $part['quantity'] * $part['unit_cost']);
    expect($totalPartsCost)->toEqual(3000); // 1850 + 650 + 85 + 280 + (3×45)

    // Sign the completion report
    $completion->update([
        'technician_signature' => 'TECH_SIG_MUTHU_' . now()->timestamp,
        'technician_signed_at' => now(),
    ]);
    $completion->refresh();

    expect($completion->technician_signature)->not->toBeNull();
    expect($completion->technician_signed_at)->not->toBeNull();

    // Update job status to pending review
    $job->update(['status' => JobStatus::PENDING_REVIEW]);
    $job->refresh();
    expect($job->status)->toBe(JobStatus::PENDING_REVIEW);

    // ============================================================
    // STEP 10: Review & Mark as Completed
    // ============================================================

    $this->actingAs($this->supervisor);

    // Supervisor reviews and approves
    $job->status = JobStatus::COMPLETED;
    $job->completed_at = now();
    $job->save();

    expect($job->status)->toBe(JobStatus::COMPLETED);
    expect($job->completed_at)->not->toBeNull();

    // ============================================================
    // STEP 11: Asset Return to Department
    // ============================================================

    $this->actingAs($this->admin);

    // Prepare for return
    $job->status = JobStatus::PENDING_KEW_PA_10_RETURN;
    $job->save();

    // Mark as returned
    $job->status = JobStatus::KEW_PA_10_RETURNED;
    $job->kew_pa_10_returned_at = now();
    $job->save();

    expect($job->status)->toBe(JobStatus::KEW_PA_10_RETURNED);
    expect($job->kew_pa_10_returned_at)->not->toBeNull();

    // ============================================================
    // STEP 12: Final Invoicing
    // ============================================================

    $job->status = JobStatus::INVOICED;
    $job->save();

    expect($job->status)->toBe(JobStatus::INVOICED);

    // ============================================================
    // FINAL VALIDATIONS: Complete Workflow Integrity
    // ============================================================

    // Verify all relationships are intact
    $kew->refresh();
    $job->refresh();

    expect($kew->workshopJob->id)->toBe($job->id);
    expect($job->kew_pa_10_id)->toBe($kew->id); // Verify foreign key is set
    expect($job->inspectionReport)->not->toBeNull();
    expect($job->completionReport)->not->toBeNull();
    expect($job->photos()->count())->toBe(3);
    expect($job->assignments()->count())->toBe(2); // Inspector + Technician

    // Verify all required data is present
    expect($kew->isVerified())->toBeTrue();
    expect($kew->isComplete())->toBeTrue();
    expect($job->status)->toBe(JobStatus::INVOICED);
    expect($inspection->approval_status)->toBe('approved');
    expect($completion->work_completed)->toBeTrue();
    expect($completion->technician_signed_at)->not->toBeNull();

    // Verify photo documentation
    expect($job->photos()->where('photo_stage', PhotoStage::INITIAL)->count())->toBe(1);
    expect($job->photos()->where('photo_stage', PhotoStage::DURING_REPAIR)->count())->toBe(1);
    expect($job->photos()->where('photo_stage', PhotoStage::AFTER_REPAIR)->count())->toBe(1);

    // Verify status progression logged in database
    $this->assertDatabaseHas('workshop_jobs', [
        'id' => $job->id,
        'status' => JobStatus::INVOICED->value,
        'kew_pa_10_id' => $kew->id,
    ]);

    $this->assertDatabaseHas('kew_pa_10s', [
        'id' => $kew->id,
        'form_completeness_verified' => true,
        'signatures_verified' => true,
    ]);

    $this->assertDatabaseHas('inspection_reports', [
        'workshop_job_id' => $job->id,
        'approval_status' => 'approved',
    ]);

    $this->assertDatabaseHas('repair_completion_reports', [
        'workshop_job_id' => $job->id,
        'work_completed' => true,
    ]);

    // Calculate final costs
    $finalPartsCost = $totalPartsCost;
    expect($finalPartsCost)->toEqual(3000); // Parts cost verified

    dump([
        '✅ WORKFLOW COMPLETE - All 11 Steps Validated',
        'KEW.PA-10 Number' => $kew->kew_pa_10_number,
        'Workshop Job Number' => $job->job_number,
        'Final Status' => $job->status->value,
        'Total Duration' => $job->created_at->diffForHumans($job->kew_pa_10_returned_at, true),
        'Parts Cost' => 'RM ' . number_format($finalPartsCost, 2),
        'Parts Used' => count($completion->parts_used),
        'Photos Documented' => $job->photos()->count(),
        'Personnel Involved' => $job->assignments()->count() + 2, // +2 for admin and supervisor
    ]);
})->group('integration', 'workflow', 'kew-pa-10');

test('workflow can handle inspection rejection and resubmission', function () {
    $this->actingAs($this->admin);

    // Create KEW.PA-10 and job
    $kew = KewPA10::factory()->create([
        'form_completeness_verified' => true,
        'signatures_verified' => true,
    ]);

    $job = WorkshopJob::factory()->create([
        'kew_pa_10_id' => $kew->id,
        'status' => JobStatus::INSPECTION_IN_PROGRESS,
    ]);

    // Inspector creates report
    $this->actingAs($this->inspector);
    $inspection = InspectionReport::factory()->create([
        'workshop_job_id' => $job->id,
        'inspector_id' => $this->inspector->id,
        'approval_status' => 'pending',
    ]);

    // Supervisor REJECTS inspection
    $this->actingAs($this->supervisor);

    $inspection->update([
        'approval_status' => 'rejected',
        'approval_notes' => 'Inspection incomplete - missing water pump assessment and brake system check',
    ]);
    $inspection->refresh();

    // Update job status to reflect rejection
    $job->update(['status' => JobStatus::INSPECTION_REJECTED]);
    $job->refresh();

    expect($inspection->approval_status)->toBe('rejected');
    expect($job->status)->toBe(JobStatus::INSPECTION_REJECTED);

    // Inspector resubmits with corrections
    $this->actingAs($this->inspector);

    $newInspection = InspectionReport::factory()->create([
        'workshop_job_id' => $job->id,
        'inspector_id' => $this->inspector->id,
        'approval_status' => 'pending',
        'additional_issues_discovered' => 'Updated: Water pump bearings worn. Brake pads at 30% - within acceptable range.',
    ]);

    // Supervisor approves corrected inspection
    $this->actingAs($this->supervisor);

    $newInspection->update([
        'approval_status' => 'approved',
        'approval_notes' => 'Corrected inspection approved - all systems assessed',
    ]);
    $newInspection->refresh();

    // Update job status to reflect approval
    $job->update(['status' => JobStatus::INSPECTION_APPROVED]);
    $job->refresh();

    expect($newInspection->approval_status)->toBe('approved');
    expect($job->status)->toBe(JobStatus::INSPECTION_APPROVED);

})->group('integration', 'workflow', 'edge-cases');

test('workflow validates status transitions', function () {
    $this->actingAs($this->admin);

    $kew = KewPA10::factory()->create([
        'form_completeness_verified' => true,
        'signatures_verified' => true,
    ]);

    $job = WorkshopJob::factory()->create([
        'kew_pa_10_id' => $kew->id,
        'status' => JobStatus::NEW,
    ]);

    // Valid transition sequence
    $validTransitions = [
        [JobStatus::NEW, JobStatus::PENDING_INSPECTION],
        [JobStatus::PENDING_INSPECTION, JobStatus::INSPECTION_IN_PROGRESS],
        [JobStatus::INSPECTION_IN_PROGRESS, JobStatus::INSPECTION_APPROVED],
        [JobStatus::INSPECTION_APPROVED, JobStatus::REPAIR_IN_PROGRESS],
        [JobStatus::REPAIR_IN_PROGRESS, JobStatus::PENDING_REVIEW],
        [JobStatus::PENDING_REVIEW, JobStatus::COMPLETED],
        [JobStatus::COMPLETED, JobStatus::PENDING_KEW_PA_10_RETURN],
        [JobStatus::PENDING_KEW_PA_10_RETURN, JobStatus::KEW_PA_10_RETURNED],
        [JobStatus::KEW_PA_10_RETURNED, JobStatus::INVOICED],
    ];

    foreach ($validTransitions as [$from, $to]) {
        $job->status = $from;
        $job->save();

        // Verify transition is allowed
        expect($from->canTransitionTo($to))->toBeTrue(
            "Expected transition from {$from->value} to {$to->value} to be allowed"
        );

        $job->status = $to;
        $job->save();

        expect($job->status)->toBe($to);
    }

    expect($job->status)->toBe(JobStatus::INVOICED);

})->group('integration', 'workflow', 'validation');
