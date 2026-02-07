# Job Mode System

> **Purpose**: Explain the dual-mode job system (KEW.PA-10 vs Normal)  
> **Audience**: Developers, Business Analysts, Trainers

---

## Overview

The Workshop Management System supports **two job modes** selected by the user when creating a job:

| Mode | Use Case | Customer Type |
|------|----------|---------------|
| **KEW.PA-10** | Government compliance jobs | Government departments |
| **Normal** | Standard workshop operations | Walk-in / Booked customers |

---

## Mode Selection

### UI Flow

When creating a new job, user sees:

```
┌────────────────────────────────────────┐
│  Create New Job                        │
│                                        │
│  Select Job Mode:                      │
│  ┌────────────┐  ┌────────────┐       │
│  │ KEW.PA-10  │  │   Normal   │       │
│  │ Government │  │   Walk-in  │       │
│  │            │  │   Booking  │       │
│  └────────────┘  └────────────┘       │
└────────────────────────────────────────┘
```

### Form Adaptation

Based on selection:

**KEW.PA-10 Mode Selected** → Show:
- Government department picker
- KEW.PA-10 form number
- Compliance fields (received date, return deadline)
- Full audit trail
- 5-role workflow

**Normal Mode Selected** → Show:
- Customer picker (walk-in/existing)
- Service type dropdown
- Appointment date (if booked)
- Simplified fields
- 3-step workflow

---

## JobMode Enum

### Implementation

```php
// app/Enums/JobMode.php
enum JobMode: string
{
    case KEWPA10 = 'kewpa10';
    case NORMAL = 'normal';
    
    public function label(): string
    {
        return match($this) {
            self::KEWPA10 => 'KEW.PA-10 (Government)',
            self::NORMAL => 'Normal (Walk-in/Booked)',
        };
    }
}
```

### Database

```php
// workshop_jobs table
$table->string('job_mode'); // 'kewpa10' or 'normal'
$table->enum('source', ['department', 'walkin', 'booked', 'online']);
```

---

## Workflow Differences

### KEW.PA-10 Mode (Government)

**Static 5-Role Workflow**:

```
Pending → Received → Inspected → In Progress → Completed → Returned
   │         │           │             │            │          │
   └─────────┴───────────┴─────────────┴────────────┴──────────┘
        Pentadbiran → Pemeriksa → Juruteknik → Penyelia → Pelulus
```

**Characteristics**:
- Full compliance tracking
- Digital signatures required
- KEW.PA-10 form template
- Photo evidence (before/during/after)
- Approval workflow
- Audit trail for government

### Normal Mode (Walk-in/Booked)

**Simplified 3-Step Workflow**:

```
Received → In Progress → Completed
    │           │            │
    └───────────┴────────────┘
   Receptionist → Technician → Manager
```

**Characteristics**:
- Quick job creation
- Quotation generation
- Invoice/payment tracking
- SMS notifications
- Simplified forms

---

## Service Layer

### JobModeService

```php
// app/Services/JobModeService.php
class JobModeService
{
    public function getStatusesFor(JobMode $mode): array
    {
        return match($mode) {
            JobMode::KEWPA10 => [
                'pending',
                'received',
                'inspected',
                'in_progress',
                'completed',
                'returned'
            ],
            JobMode::NORMAL => [
                'received',
                'in_progress',
                'completed',
            ],
        };
    }
    
    public function getNextStatus(JobMode $mode, string $current): ?string
    {
        $statuses = $this->getStatusesFor($mode);
        $currentIndex = array_search($current, $statuses);
        
        if ($currentIndex === false || $currentIndex >= count($statuses) - 1) {
            return null;
        }
        
        return $statuses[$currentIndex + 1];
    }
    
    public function canTransition(JobMode $mode, string $from, string $to): bool
    {
        $next = $this->getNextStatus($mode, $from);
        return $next === $to;
    }
}
```

---

## Form Fields

### KEW.PA-10 Mode Fields

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| government_department_id | FK | Yes | Which government agency |
| kew_pa_10_number | String | Yes | Form reference number |
| received_date | Date | Yes | When form was received |
| return_deadline | Date | Yes | When to return asset |
| asset_description | Text | Yes | Official asset details |
| defect_description | Text | Yes | Reported issues |
| inspector_notes | Text | No | Inspection findings |
| approval_notes | Text | No | Approver comments |

### Normal Mode Fields

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| customer_id | FK | Yes | Walk-in or existing customer |
| source | Enum | Yes | walkin/booked/online |
| appointment_at | DateTime | No | For booked jobs |
| vehicle_plate | String | Yes | Registration number |
| make_model | String | Yes | Vehicle details |
| issue_description | Text | Yes | Customer complaint |
| estimated_cost | Decimal | No | Initial estimate |

---

## Backend Logic

### Job Creation

```php
// app/Http/Controllers/JobController.php
public function store(Request $request)
{
    $mode = JobMode::from($request->job_mode);
    
    $validated = $this->validateByMode($request, $mode);
    
    $job = WorkshopJob::create([
        'job_mode' => $mode,
        'workshop_id' => auth()->user()->current_workshop_id,
        'status' => $this->getInitialStatus($mode),
        ...$validated
    ]);
    
    return redirect()->route('jobs.show', $job);
}

private function getInitialStatus(JobMode $mode): string
{
    return match($mode) {
        JobMode::KEWPA10 => 'pending',
        JobMode::NORMAL => 'received',
    };
}
```

---

## Frontend Components

### Mode Selector Component

```vue
<!-- resources/js/components/JobModeSelector.vue -->
<template>
  <div class="grid grid-cols-2 gap-4">
    <button
      @click="selectMode('kewpa10')"
      :class="{ 'ring-2 ring-blue-500': selectedMode === 'kewpa10' }"
      class="p-6 border rounded-lg hover:bg-gray-50"
    >
      <div class="text-2xl mb-2">🏛️</div>
      <div class="font-semibold">KEW.PA-10</div>
      <div class="text-sm text-gray-500">Government Jobs</div>
    </button>
    
    <button
      @click="selectMode('normal')"
      :class="{ 'ring-2 ring-blue-500': selectedMode === 'normal' }"
      class="p-6 border rounded-lg hover:bg-gray-50"
    >
      <div class="text-2xl mb-2">🔧</div>
      <div class="font-semibold">Normal</div>
      <div class="text-sm text-gray-500">Walk-in / Booking</div>
    </button>
  </div>
</template>
```

---

## Reporting

### Mode-Specific Metrics

**KEW.PA-10 Dashboard**:
- Pending government jobs
- Overdue returns
- Compliance rate
- Average turnaround (by department)

**Normal Dashboard**:
- Walk-in vs booked ratio
- Revenue by day
- Outstanding quotations
- Unpaid invoices

---

## Related Documentation

- [Multi-Tenant Architecture](11-multi-tenant-architecture.md)
- [User Roles](../../06-user-guide/01-user-roles.md)

---

**Last Updated**: 2026-02-02
