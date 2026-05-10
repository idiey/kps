# Staff Guide

This chapter explains the current `staff` experience.

## Staff Scope

In the current seeded permissions, `staff` has `kps.view` only. This means staff can access KPS
and assigned-site information, but do not create or edit operational records unless an admin changes
the role's permissions.

Staff users are site-scoped:

- They only access sites assigned to their account.
- They are redirected to their first assigned site when opening KPS without a selected site.
- They do not see HQ-only pages such as user management, role management, or global site creation.

## Open Your Assigned Site

1. Sign in to KPS.
2. Let the system redirect you to your assigned site, or open the site from the available navigation.
3. Confirm the site name or code in the header.
4. Use the visible site menu to view available information.

If the system shows a forbidden page, your account may not be assigned to the selected site.

## View Site Information

Depending on the available menu items, staff may view:

- Site dashboard information.
- `Peneroka` records.
- `Hutang` records.
- `Potongan Bulanan` ledger entries.
- `Allocation Review` results.

The current role does not include create, edit, delete, potongan entry, report export, or month
closing permissions by default.

## What Staff Should Escalate

Ask an admin or site admin to handle these tasks:

- Add or edit a peneroka.
- Add or edit hutang.
- Enter or import monthly potongan.
- Recalculate allocations.
- Close a month.
- Export reports or statements.
- Fix a missing site assignment.
- Update your role or permissions.

## Common Staff Issues

| Issue | What To Do |
| --- | --- |
| A menu is missing | Ask an admin to confirm your role permissions. |
| A site is missing | Ask an admin to confirm your site assignment. |
| You can view but not save | This is expected for default `staff` permissions. |
| You need a report | Ask a user with `kps.view_reports` access. |
