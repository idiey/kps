# KPS Role-Based User Manual

This manual is for KPS users who manage FELDA site records, peneroka profiles, hutang,
monthly potongan, allocation review, month closing, reporting, and system access.

The guide is bilingual in use: instructions are written in English, while actual KPS labels and
common Malay operational terms are preserved, such as `Peneroka`, `Hutang`, `Potongan Bulanan`,
`Allocation Review`, and `Reports`.

## Intended Audience

- **Admin**: `pentadbiran`, `company_admin`, and `site_admin` users.
- **Staff**: `staff` users assigned to one or more KPS sites.

Admin capabilities differ by role:

| Role | Typical Scope |
| --- | --- |
| `pentadbiran` | Full system administration, users, roles, permissions, and all KPS workflows. |
| `company_admin` | HQ-level KPS oversight, sites, reports, analytics, and month approval. |
| `site_admin` | Site-level operations for assigned sites, including peneroka, hutang, potongan, reports, and month closing. |
| `staff` | View assigned-site KPS information. In the current seeded permissions, staff do not create or edit operational records. |

## Scope

Included:

- Access, navigation, and site context.
- Admin tasks for sites, users, roles, and permissions.
- Staff access and current limits.
- Site operations for `Peneroka`, `Hutang`, and `Potongan Bulanan`.
- Allocation review, month closing, audit trail, and reports.
- Common rules and troubleshooting.

Not included:

- Payment gateway processing.
- Bank reconciliation.
- Offline mobile operation.
- Reopening a closed month, because the current application does not expose that workflow.
- Developer setup or code architecture.

## Contents

- [Getting Started](01-getting-started.md) - sign in, navigate KPS, understand site context, and update profile settings.
- [Admin Guide](02-admin-guide.md) - manage HQ oversight, sites, users, roles, permissions, and admin-only actions.
- [Staff Guide](03-staff-guide.md) - understand staff access, assigned-site navigation, and view-only limits.
- [Site Operations](04-site-operations.md) - maintain peneroka, hutang, and monthly potongan records.
- [Month Closing and Reports](05-month-closing-and-reports.md) - review allocations, close months, use audit trail, and export reports.
- [Troubleshooting and Rules](06-troubleshooting-and-rules.md) - resolve missing pages, permission errors, closed months, and upload issues.

## Quick Links

- New admin setup: [Admin Guide](02-admin-guide.md#manage-users)
- New site month: [Site Operations](04-site-operations.md#monthly-potongan-potongan-bulanan)
- Before closing month: [Month Closing and Reports](05-month-closing-and-reports.md#review-allocations)
- Statement export: [Month Closing and Reports](05-month-closing-and-reports.md#peneroka-statements)
- Missing menu or forbidden page: [Troubleshooting and Rules](06-troubleshooting-and-rules.md#access-and-navigation)

## Common Rules

- KPS access depends on both role permissions and site assignment.
- HQ users can work across sites when they have `kps.manage_sites`.
- Site users can only work inside assigned sites.
- Closed months cannot be edited or recalculated through the current site workflow.
- Saving a potongan automatically recalculates allocations.
- Allocation follows priority order, due date order, then creation order.
- If a page or button is missing, your role probably does not have the required permission.
