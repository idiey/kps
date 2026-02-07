# User Roles and Permissions

## Overview

The system uses Spatie Laravel-Permission for role-based access and a workshop assignment pivot (`workshop_user`) for site-level roles. Role permissions are seeded in `database/seeders/RolePermissionSeeder.php`.

## Application Roles (Spatie)

| Role (code) | Display | Primary Capabilities |
|-------------|---------|----------------------|
| `pentadbiran` | Admin Officer | Full access to all modules and settings |
| `company_admin` | Company Admin | Manage sites/users, view analytics and reports |
| `penyelia` | Supervisor | Create/manage jobs, assign technicians, approve inspections |
| `pemeriksa` | Inspector | Create inspection reports, view jobs and analytics |
| `pelulus` | Approver | Approve/reject jobs and view reports |
| `juruteknik` | Technician | Work on assigned jobs, update status, add notes |
| `kaunter` | Front Desk | Intake jobs and manage customers |

## Site-Level Assignment Roles (Workshop Pivot)

Users can be assigned to a workshop with a site role stored on the `workshop_user` pivot:

- `site_admin`
- `supervisor`
- `technician`
- `staff`

These roles are used for workshop-level access checks and dashboards.

## Capability Summary

- **Job creation**: `pentadbiran`, `penyelia`, `kaunter`
- **Job assignment**: `pentadbiran`, `penyelia`
- **Inspection**: `pemeriksa`
- **Approval (KEW.PA-10)**: `pentadbiran`, `penyelia`, `pelulus`
- **Admin modules** (workshops/users/roles/assets/inventory/settings): `pentadbiran` (full), `company_admin` (limited)
- **Reports/analytics**: `pentadbiran`, `company_admin`, `penyelia`, `pemeriksa`, `pelulus`

## Notes

- Policies in `app/Policies` enforce access at the model level.
- Routes also apply role middleware for KEW approval actions.
- Site admins may be redirected to their assigned workshop dashboards on login.

---

**Last Updated**: 2026-02-07
