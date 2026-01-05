## Goal
- Use both workflows (Option 1: External KEW.PA-10 Reception, Option 2: Internal Inspection & KEW.PA-10 Generation) end-to-end.
- Seed all required data (workflows, statuses, transitions, sample templates) so the UI and API work immediately.

## Data Seeding (Backend)
- Create `WorkflowSeeder` to insert:
  - Workflows: `external_kew_pa_10` and `internal_inspection_kew_pa_10`.
  - Statuses for each workflow with ordering and slugs.
  - Transitions mapping valid status changes (assign, approve, approve_with_conditions, reject, start_repair, complete, etc.).
- Optional seed:
  - Sample templates: KEW.PA-10, KEW.PA-12, KEW.PA-13, each linked to a default workflow via `workflow_id`.
  - Minimal users/roles (Admin Officer, Supervisor, Inspector, Technician) to demo transitions.
- Run `php artisan db:seed --class=WorkflowSeeder`.

## Backend Wiring
- Ensure templates have `workflow_id` (add migration if missing) so dynamic job creation selects the right workflow automatically.
- Confirm `DynamicJobController` uses the template’s `workflow_id` to set the job’s initial status and allowed transitions.

## Frontend Integration
- Template selection flow (`/jobs/templates/select`):
  - Show templates (including KEW.PA-10/12/13) and reflect their workflow association.
  - On continue, navigate to `/jobs/create/{template}` with workflow context.
- KEW.PA-10 Show:
  - “Create Job” navigates to `/jobs/templates/select`, prefiltering to KEW.PA-* templates.
- Jobs pages:
  - Status badges reflect seeded statuses.
  - Transition actions call dynamic transitions API (already present) constrained by the seeded workflow.

## Verification
- Run feature tests (e.g., `tests/Feature/KewPA10WorkflowOption1Test.php`) to validate Option 1.
- Manual UI checks:
  - Option 1 flow: KEW.PA-10 → Template Select → Job Create → Assign/Inspect/Approve → Repair → Complete.
  - Option 2 flow: Internal Inspection → Generate KEW.PA-10 → Template Select → Job Create → Approval → Repair → Complete.
- Grep to ensure no hard-coded coupling to KEW.PA-10 in job class.

## Deliverables
- Seeder(s) and optional migration.
- Minimal template records seeded and linked to workflows.
- Verified UI and transitions for both workflow options.

If approved, I will implement the seeder, link templates to workflows, wire the template selection to set the job workflow, and verify with tests and the running app.