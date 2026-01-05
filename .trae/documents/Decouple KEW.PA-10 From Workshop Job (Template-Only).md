## Outcome and Capabilities
- Admin defines KEW.PA-10, KEW.PA-12, KEW.PA-13 as templates in a generic Templates module.
- Templates plug into existing workflow (statuses and transitions) and drive dynamic job creation.
- Jobs process normally with workflow states; no hard coupling to any specific template class.

## What Changes
- Decouple KEW.PA-10 from the job class and UI; treat KEW.PA-10 pages as independent template records.
- Replace KEW.PA-10 → Job linking (current POST `kew-pa-10/{id}/create-job`) with navigation to Dynamic Job creation:
  - Template selection: `DynamicJobController` route `/jobs/templates/select`.
  - Direct create by template id: `/jobs/create/{template}` when the KEW.PA-10 record already knows its `template_id`.

## UI Updates
- `resources/js/pages/KewPA10/Show.vue`
  - Remove the "Workshop Job" linkage block; keep verification and standalone details.
  - Change "Create Workshop Job" to navigate to `/jobs/templates/select` (or `/jobs/create/{template}` if `template_id` is available).
- `resources/js/pages/Inspections/Show.vue`, `Photos/Gallery.vue`, `Completion/Create.vue`
  - Remove/avoid any `workshop_job.kew_pa_10` references so jobs have no KEW.PA-* object attached.

## Types and Data
- Remove `kew_pa_10` from `WorkshopJob` typings used in pages; optionally keep a loose string like `kew_pa_10_number` if needed purely for display.
- If prefilling from KEW.PA-10 is desired, pass data as query or props into the dynamic job form, mapping template fields to job form defaults without persisting an association.

## Workflow Integration
- Continue using existing Admin Workflows (statuses, transitions). Templates reference workflows; jobs created from any template use the selected workflow.
- Dynamic transitions remain available via `DynamicJobController::executeTransition`.

## Verification
- Navigate: KEW.PA-10 Show → Select Template → Create Job. Confirm jobs process through workflow with no KEW.PA-* coupling.
- Grep UI for `kew_pa_10` to ensure decoupling from job-facing components.

If confirmed, I will implement these decouplings and route changes, then verify in the running app.