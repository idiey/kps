# Admin Guide

This chapter covers admin workflows for `pentadbiran`, `company_admin`, and `site_admin` users.
Actions appear only when your role has the required permission.

## Admin Role Summary

| Role | Main Responsibilities |
| --- | --- |
| `pentadbiran` | Full administration: KPS, users, roles, and permissions. |
| `company_admin` | HQ oversight: sites, analytics, reports, and month approval. |
| `site_admin` | Assigned-site operations: peneroka, hutang, potongan, reports, and month closing. |

## HQ Dashboard And Analytics

Use HQ pages to monitor KPS across sites.

1. Open `Dashboard` to review high-level KPS totals.
2. Open `Analytics` to review the current month snapshot and site performance.
3. Use the figures as review indicators before visiting a specific site.

If `Analytics` is missing, your role may not have HQ-level access.

## Manage Sites

Site management is available to roles with `kps.manage_sites`.

### View Sites

1. Open `Sites`.
2. Review the site list by name, code, and active status.
3. Select `View` to open a site workspace.
4. Select `Edit` if you need to update site details.

### Add A Site

1. Open `Sites`.
2. Select `Add Site`.
3. Enter the required fields:
   - `Name`
   - `Code`
4. Optionally enter address, phone, email, active status, and `hutang_weightage_pct`.
5. Save the site.

`Code` must be unique. `hutang_weightage_pct` controls how much of a monthly potongan is available
for debt allocation and must be between 0 and 100.

### Edit A Site

1. Open `Sites`.
2. Select `Edit` for the site.
3. Update the site details.
4. Save the changes.

Site admins assigned to a site can update that site where the system permits it. Creating or
deleting sites is HQ-only.

## Manage Users

User Management is available to `pentadbiran` users with user permissions.

### View And Filter Users

1. Open `User Management`.
2. Search by name or email.
3. Filter by role or active status.
4. Use pagination to move through the user list.

### Create A User

1. Open `User Management`.
2. Select `Create User`.
3. Enter name, email, password, password confirmation, role, department if needed, and active status.
4. Save the user.

Passwords must meet the system password rules. A user can only sign in when active.

### Edit Or Deactivate A User

1. Open `User Management`.
2. Select the edit action for the user.
3. Update profile fields, role, or active status.
4. Leave the password blank if it should not change.
5. Save the user.

The system prevents users from deleting or deactivating their own account.

## Manage Roles And Permissions

Role Management is available to `pentadbiran` users with role permissions.

### View Roles

1. Open `Role Management`.
2. Review each role, user count, permission count, and active status.
3. Open a role to see assigned permissions and users.

System roles include `pentadbiran`, `company_admin`, `site_admin`, and `staff`.

### Add A Custom Role

1. Open `Role Management`.
2. Select `Add Role`.
3. Enter role name, description, color if available, and active status.
4. Select the permissions for the role.
5. Save the role.

System roles cannot be created through this form; they are seeded by the application.

### Edit A Role

1. Open `Role Management`.
2. Select edit for a non-system role.
3. Update details or permissions.
4. Save changes.

System roles cannot be renamed or deleted from the role edit form. Their permissions are managed
through the permission matrix.

### Update The Permission Matrix

1. Open `Role Management`.
2. Select `Permission Matrix`.
3. Tick or untick permissions for each role.
4. Save changes.

Permission changes affect what users can see and do. Apply changes carefully and test with a
representative user account before relying on them for operations.

## Admin-Only Warnings

- User, role, and permission changes affect live access immediately.
- Deleting a role is blocked when users are assigned to it.
- Closing a month locks that month from editing and recalculation.
- Site-level records should be updated in the correct site context only.
