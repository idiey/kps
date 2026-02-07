# Simplified Job Modes Architecture

> **Last Updated**: 2026-02-07
> **Version**: 3.0.1
> **Status**: Implemented (static job modes)

---

## Overview

The system uses a simplified, static job-mode architecture with two modes:

1. **KEW.PA-10** - Government jobs with KEW.PA-10 form fields
2. **NORMAL** - Standard workshop jobs

Dynamic workflow and template tables were removed as part of the architecture simplification (see migration `2026_02_02_110000_drop_dynamic_workflow_tables.php`).

---

## Job Mode Comparison

| Aspect | KEW.PA-10 (Government) | NORMAL (Workshop) |
|--------|-------------------------|-------------------|
| **Purpose** | Government asset maintenance | Standard workshop service/repair |
| **Form** | Static KEW.PA-10 fields | Standard job fields |
| **Approval** | KEW approval fields recorded | Optional workflow approvals |
| **Customer** | Government or workshop customer | Any customer |

---

## KEW.PA-10 Mode (Static Fields)

### Core KEW.PA-10 Fields

- `kew_pa_10_number`
- `kew_pa_10_received_date`
- `kew_pa_10_government_department_id`
- `kew_pa_10_asset_id`
- `kew_pa_10_description`
- `kew_pa_10_priority`
- `kew_pa_10_budget_reference`
- `kew_pa_10_document_path`
- `kew_pa_10_form_verified`
- `kew_pa_10_signatures_verified`

### Approval Tracking Fields

- `kew_approval_status`
- `kew_approved_by_id`
- `kew_approved_at`
- `kew_rejection_reason`

### Extended KEW Inspection Fields

- `kew_vehicle_registration`
- `kew_asset_tag`
- `kew_department_name`
- `kew_inspection_date`
- `kew_inspector_name`
- `kew_inspector_ic`
- `kew_findings`
- `kew_recommendations`

---

## NORMAL Mode (Standard Job Fields)

Key fields in `workshop_jobs` for normal jobs include:

- `title`, `description`, `priority`
- `customer_id`, `assigned_to`
- `vehicle_registration`, `asset_tag`
- `estimated_cost`, `actual_cost`
- `estimated_hours`, `actual_hours`
- `due_date`, `estimated_completion_date`

---

## Status Flow

Job status transitions use the `App\Enums\JobStatus` enum. The system applies a shared status set for both modes:

- `new`
- `pending_inspection`
- `inspection_in_progress`
- `inspection_approved`
- `inspection_rejected`
- `awaiting_parts`
- `repair_in_progress`
- `pending_review`
- `in_progress`
- `completed`
- `pending_kew_pa_10_return`
- `kew_pa_10_returned`
- `invoiced`
- `cancelled`

Actual allowed transitions are enforced by `JobStatus::allowedTransitions()` and role-based policies.

---

## Related Files

- `app/Enums/JobMode.php`
- `app/Enums/JobStatus.php`
- `app/Models/WorkshopJob.php`
- `database/migrations/2026_02_02_100000_add_kew_pa_10_static_fields_to_workshop_jobs.php`
- `database/migrations/2026_02_03_120000_add_missing_kew_fields.php`
- `database/migrations/2026_02_03_130000_add_kew_approval_fields.php`
