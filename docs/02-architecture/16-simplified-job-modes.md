# Simplified Job Modes Architecture

> **Last Updated**: 2026-02-02  
> **Version**: 3.0.0-simplified  
> **Status**: Proposed - Replaces Dynamic Workflow System  

---

## Overview

This document defines the **simplified job mode system** that replaces the dynamic workflow engine and template system.

**Two Static Job Modes**:
1. **KEW.PA-10** - Government inspection jobs (predefined form)
2. **NORMAL** - Standard workshop jobs (standard form)

---

## Job Mode Comparison

| Aspect | KEW.PA-10 (Government) | NORMAL (Workshop) |
|--------|----------------------|-------------------|
| **Purpose** | Government vehicle inspections | Standard repair/service jobs |
| **Form** | Static 10-field form | Standard workshop form |
| **Workflow** | 8-step approval process | 4-step simple flow |
| **Approvals** | Mandatory supervisor approval | Optional |
| **Customers** | Government departments only | Any customer |
| **Complexity** | High (inspection + approval) | Low (standard job) |

---

## KEW.PA-10 Mode

### Static Form Fields

```typescript
interface KewPa10Job {
  // Vehicle/Asset Information
  kew_vehicle_registration: string;      // e.g., "WA1234A"
  kew_asset_tag: string;                 // e.g., "ASSET-001"
  kew_department_name: string;           // e.g., "Jabatan Kerja Raya"
  
  // Inspection Details
  kew_inspection_date: Date;
  kew_inspector_name: string;
  kew_inspector_ic: string;              // NRIC
  kew_findings: string;                  // Inspection findings (text)
  kew_recommendations: string;           // Recommendations (text)
  
  // Approval
  kew_approval_status: 'pending' | 'approved' | 'rejected';
  kew_approved_by_id: UUID;              // Supervisor who approved
  kew_approved_at: DateTime;
  kew_rejection_reason?: string;         // If rejected
}
```

### Status Flow

```
┌─────────┐
│  draft  │ Initial creation by technician
└────┬────┘
     │
     ▼
┌──────────────┐
│kew_inspection│ Performing inspection, filling form
└──────┬───────┘
       │
       ▼
┌────────────────────┐
│kew_approval_pending│ Submitted for supervisor review
└─────┬──────────────┘
      │
      ├─────────► [APPROVED] ──┐
      │                        │
      └─────────► [REJECTED] ──┤
                               │
              ┌────────────────┘
              │
              ▼
       ┌─────────────┐
       │kew_approved │
       └──────┬──────┘
              │
              ▼
       ┌────────────┐
       │in_progress │ Actual repair work
       └──────┬─────┘
              │
              ▼
       ┌───────────┐
       │ completed │
       └───────────┘
```

### Business Rules (Hardcoded)

```php
class KewPa10BusinessRules
{
    // Rule 1: Frontdesk, Supervisor, and Admin can create KEW.PA-10 jobs
    public function canCreateKewJob(User $user): bool
    {
        return $user->hasAnyRole(['pentadbiran', 'penyelia', 'kaunter']);
    }
    
    // Rule 2: All KEW fields are mandatory before submission
    public function validateKewFields(Job $job): array
    {
        $required = [
            'kew_vehicle_registration',
            'kew_asset_tag',
            'kew_department_name',
            'kew_inspection_date',
            'kew_inspector_name',
            'kew_inspector_ic',
            'kew_findings',
            'kew_recommendations',
        ];
        
        $missing = [];
        foreach ($required as $field) {
            if (empty($job->$field)) {
                $missing[] = $field;
            }
        }
        
        return $missing;
    }
    
    // Rule 3: Only supervisors can approve KEW.PA-10 jobs
    public function canApprove(User $user, Job $job): bool
    {
        return $user->hasRole('supervisor') 
            && $job->job_mode === JobMode::KEW_PA_10
            && $job->status === 'kew_approval_pending';
    }
    
    // Rule 4: Rejected jobs can be re-inspected
    public function canReturnToInspection(Job $job): bool
    {
        return $job->kew_approval_status === 'rejected';
    }
}
```

---

## NORMAL Mode

### Standard Form Fields

```typescript
interface NormalJob {
  // Customer & Vehicle
  customer_id: UUID;
  vehicle_registration?: string;
  
  // Job Details
  title: string;
  description: string;
  priority: 'low' | 'medium' | 'high';
  
  // Financial (optional)
  estimated_cost?: number;
  actual_cost?: number;
  
  // Assignment
  assigned_to?: UUID;
  
  // Timeline
  due_date?: Date;
  started_at?: DateTime;
  completed_at?: DateTime;
}
```

### Status Flow

```
┌───────┐
│ draft │ Job creation
└───┬───┘
    │
    ▼
┌─────────┐
│ pending │ Awaiting assignment/approval
└────┬────┘
     │
     ├──────► [assigned] ──┐
     │                     │
     └──────► [cancelled]  │
                           │
            ┌──────────────┘
            │
            ▼
     ┌────────────┐
     │in_progress │ Active work
     └─────┬──────┘
           │
           ▼
     ┌───────────┐
     │ completed │
     └───────────┘
```

### Business Rules (Hardcoded)

```php
class NormalJobBusinessRules
{
    // Rule 1: Frontdesk, Supervisor, and Admin can create normal jobs
    public function canCreateJob(User $user): bool
    {
        return $user->hasAnyRole(['pentadbiran', 'penyelia', 'kaunter']);
    }
    
    // Rule 2: Jobs must have customer
    public function validateJob(Job $job): array
    {
        $errors = [];
        
        if (empty($job->customer_id)) {
            $errors[] = 'Customer is required';
        }
        
        if (empty($job->title)) {
            $errors[] = 'Title is required';
        }
        
        return $errors;
    }
    
    // Rule 3: Can cancel if not completed
    public function canCancel(Job $job): bool
    {
        return $job->status !== 'completed';
    }
}
```

---

## Status Transition Logic

### Hardcoded Service Implementation

```php
// app/Services/JobStatusService.php
namespace App\Services;

use App\Models\Job;
use App\Enums\JobMode;
use App\Enums\JobStatus;
use App\Exceptions\InvalidStatusTransitionException;

class JobStatusService
{
    public function transitionStatus(Job $job, JobStatus $newStatus): void
    {
        // Validate transition based on job mode
        if ($job->job_mode === JobMode::KEW_PA_10) {
            $this->validateKewTransition($job, $newStatus);
        } else {
            $this->validateNormalTransition($job, $newStatus);
        }
        
        // Update status
        $oldStatus = $job->status;
        $job->update(['status' => $newStatus->value]);
        
        // Execute side effects
        $this->executeTransitionSideEffects($job, $oldStatus, $newStatus);
        
        // Trigger event
        event(new JobStatusChanged($job, $oldStatus, $newStatus));
    }
    
    private function validateKewTransition(Job $job, JobStatus $newStatus): void
    {
        $allowedTransitions = [
            JobStatus::DRAFT->value => [JobStatus::KEW_INSPECTION],
            JobStatus::KEW_INSPECTION->value => [JobStatus::KEW_APPROVAL_PENDING],
            JobStatus::KEW_APPROVAL_PENDING->value => [JobStatus::KEW_APPROVED, JobStatus::KEW_REJECTED],
            JobStatus::KEW_REJECTED->value => [JobStatus::KEW_INSPECTION], // Re-inspect
            JobStatus::KEW_APPROVED->value => [JobStatus::IN_PROGRESS],
            JobStatus::IN_PROGRESS->value => [JobStatus::COMPLETED],
        ];
        
        $allowed = $allowedTransitions[$job->status->value] ?? [];
        
        if (!in_array($newStatus, $allowed)) {
            throw new InvalidStatusTransitionException(
                "Cannot transition from {$job->status->value} to {$newStatus->value}"
            );
        }
    }
    
    private function validateNormalTransition(Job $job, JobStatus $newStatus): void
    {
        $allowedTransitions = [
            JobStatus::DRAFT->value => [JobStatus::PENDING],
            JobStatus::PENDING->value => [JobStatus::IN_PROGRESS, JobStatus::CANCELLED],
            JobStatus::IN_PROGRESS->value => [JobStatus::COMPLETED, JobStatus::CANCELLED],
        ];
        
        $allowed = $allowedTransitions[$job->status->value] ?? [];
        
        if (!in_array($newStatus, $allowed)) {
            throw new InvalidStatusTransitionException(
                "Cannot transition from {$job->status->value} to {$newStatus->value}"
            );
        }
    }
    
    private function executeTransitionSideEffects(Job $job, JobStatus $oldStatus, JobStatus $newStatus): void
    {
        // Example: Auto-assign technician when moving to in_progress
        if ($newStatus === JobStatus::IN_PROGRESS && !$job->assigned_to) {
            $job->update(['assigned_to' => auth()->id()]);
        }
        
        // Example: Set timestamps
        if ($newStatus === JobStatus::IN_PROGRESS && !$job->started_at) {
            $job->update(['started_at' => now()]);
        }
        
        if ($newStatus === JobStatus::COMPLETED && !$job->completed_at) {
            $job->update(['completed_at' => now()]);
        }
    }
}
```

---

## Vue Components

### KEW.PA-10 Form (Static)

```vue
<!-- resources/js/Pages/Jobs/CreateKewPa10.vue -->
<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';

const form = useForm({
  job_mode: 'KEW_PA_10',
  title: '',
  description: '',
  
  // KEW.PA-10 specific fields
  kew_vehicle_registration: '',
  kew_asset_tag: '',
  kew_department_name: '',
  kew_inspection_date: '',
  kew_inspector_name: '',
  kew_inspector_ic: '',
  kew_findings: '',
  kew_recommendations: '',
});

function submit() {
  form.post(route('jobs.store'));
}
</script>

<template>
  <form @submit.prevent="submit">
    <h2>KEW.PA-10 Government Inspection Job</h2>
    
    <!-- Vehicle Information -->
    <section>
      <h3>Vehicle/Asset Information</h3>
      
      <div class="form-group">
        <label>Vehicle Registration *</label>
        <input v-model="form.kew_vehicle_registration" required />
        <span v-if="form.errors.kew_vehicle_registration" class="error">
          {{ form.errors.kew_vehicle_registration }}
        </span>
      </div>
      
      <div class="form-group">
        <label>Asset Tag *</label>
        <input v-model="form.kew_asset_tag" required />
      </div>
      
      <div class="form-group">
        <label>Department *</label>
        <input v-model="form.kew_department_name" required />
      </div>
    </section>
    
    <!-- Inspection Details -->
    <section>
      <h3>Inspection Details</h3>
      
      <div class="form-group">
        <label>Inspection Date *</label>
        <input type="date" v-model="form.kew_inspection_date" required />
      </div>
      
      <div class="form-group">
        <label>Inspector Name *</label>
        <input v-model="form.kew_inspector_name" required />
      </div>
      
      <div class="form-group">
        <label>Inspector IC *</label>
        <input v-model="form.kew_inspector_ic" required />
      </div>
      
      <div class="form-group">
        <label>Findings *</label>
        <textarea v-model="form.kew_findings" rows="5" required></textarea>
      </div>
      
      <div class="form-group">
        <label>Recommendations *</label>
        <textarea v-model="form.kew_recommendations" rows="5" required></textarea>
      </div>
    </section>
    
    <button type="submit" :disabled="form.processing">
      Create KEW.PA-10 Job
    </button>
  </form>
</template>
```

---

## Mobile App Impact

### Simplified Offline Forms

**Before (Dynamic)**:
```typescript
// Download template definition
const template = await api.getTemplate(templateId);

// Render fields dynamically
template.fields.forEach(field => renderField(field));

// Parse validation rules at runtime
validateForm(template.validationRules);
```

**After (Static)**:
```typescript
// Hardcoded form schema
const KEW_PA_10_SCHEMA = {
  fields: [
    { name: 'kew_vehicle_registration', type: 'text', required: true },
    { name: 'kew_asset_tag', type: 'text', required: true },
    { name: 'kew_inspection_date', type: 'date', required: true },
    { name: 'kew_findings', type: 'textarea', required: true },
    // ... all fields predefined
  ]
};

// Simple validation
const validate = (data) => {
  return KEW_PA_10_SCHEMA.fields.every(field => 
    !field.required || Boolean(data[field.name])
  );
};
```

---

## Migration Checklist

- [ ] Archive workflow/template tables
- [ ] Add `job_mode` column to `workshop_jobs`
- [ ] Add KEW.PA-10 static fields (11 columns)
- [ ] Migrate dynamic form data to static columns
- [ ] Update status enum in database
- [ ] Create `JobStatusService` with hardcoded logic
- [ ] Build static Vue forms (KEW.PA-10 + Normal)
- [ ] Update mobile app with static schemas
- [ ] Remove workflow engine code
- [ ] Update API endpoints
- [ ] Test status transitions
- [ ] Update documentation

---

## Summary

| Feature | Old (Dynamic) | New (Static) |
|---------|---------------|--------------|
| **Job Types** | Configurable via workflows | Fixed: KEW.PA-10 / NORMAL |
| **Forms** | JSON templates | Vue components |
| **Statuses** | Dynamic per workflow | Enum (9 statuses) |
| **Transitions** | Rules engine | Hardcoded service |
| **Approvals** | Configurable | KEW.PA-10 only |
| **Complexity** | High | Low |
| **Flexibility** | High (runtime config) | Low (code deployments) |
| **Performance** | Slower (parsing) | Faster (direct logic) |
| **Mobile** | Complex sync | Simple sync |

---

**Recommendation**: ✅ **Proceed with simplified architecture** for better maintainability and performance.
