## Understanding Your Requirement
- A job is essentially a workflow instance (job = name + workflow process).
- Workflow has statuses and transitions.
- **Job access** should be controlled by the workflow’s allowed roles.
- **Transition actions** should be controlled by each transition’s allowed roles (only assigned roles can execute).

## Current State
- Transition visibility/execution is already filtered by `workflow_transitions.allowed_roles` (via `userCanExecute`).
- Job visibility is workflow-role filtered but still treats `workflow.allowed_roles = null/empty` as public.

## Plan (No Hardcoded Role Names)
### 1) Define Workflow-Level Allowed Roles as Mandatory
- Enforce: every workflow used by jobs must have `allowed_roles` set (non-empty).
- Backfill existing workflows that have null/empty `allowed_roles` to **all active role IDs** (so behavior stays open but becomes explicit).

### 2) Implement Workflow-Centric Job Access
- `Workflow::userCanAccess(User $user)`:
  - Get user role IDs (from Spatie roles; and also map `users.role` string to a Role record by name, without hardcoding any specific role).
  - If workflow.allowed_roles is empty/null = deny (after backfill, this should not occur).
  - Allow only if intersection(userRoleIds, workflow.allowed_roles) is non-empty.
- Apply this consistently:
  - `WorkshopJobPolicy::view()` denies if user cannot access the job’s workflow.
  - Job listing queries only return jobs whose workflow allows the user.
  - Job creation workflow dropdown only shows workflows allowed for the user.

### 3) Transition Action Access (Per-Transition)
- Keep `WorkflowTransition::userCanExecute()` as the source of truth:
  - Transition is shown only if user roles intersect transition.allowed_roles (and permission checks if used).
  - Execution path re-checks before updating job status.
- Add/keep defense-in-depth: when computing available transitions, also require workflow access (so if job payload leaks, transitions still empty).

### 4) Data Setup: Seed/Backfill
- Add a dedicated “backfill workflow allowed_roles” seeder (or command) that:
  - Loads active roles table IDs.
  - Updates workflows with null/empty allowed_roles to that full active-role list.
- Update your demo seeders so every seeded workflow explicitly sets allowed_roles.

### 5) Tests
- Add/update feature tests verifying:
  - User cannot see/access jobs for workflows not containing their role.
  - User can see/access jobs when workflow contains their role.
  - Transitions shown only when transition.allowed_roles contains user role.
  - Transition execution fails/403 when transition.allowed_roles does not contain user role.

## Deliverables
- Strict, workflow-centric job access control.
- Transition-level action control per transition allowed_roles.
- Backfill + seed data so existing DB doesn’t lock everyone out.
- Automated tests to ensure the behavior stays correct.