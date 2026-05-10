# Troubleshooting And Rules

Use this chapter when a page is missing, a save fails, or allocation results do not match
expectations.

## Access And Navigation

| Problem | Likely Cause | Action |
| --- | --- | --- |
| A menu item is missing | Your role lacks the permission. | Ask an admin to check your role and permission matrix. |
| A site is missing | You are not assigned to the site. | Ask an admin or HQ user to check site assignment. |
| You are redirected to one site | Site users are automatically sent to their first assigned site. | Confirm whether your account needs more site assignments. |
| Forbidden page | The route is outside your site or permission scope. | Return to your assigned site or ask an admin to update access. |

## Staff Cannot Save

Default `staff` users have `kps.view` only. They can view assigned KPS data but cannot create or
edit peneroka, hutang, potongan, reports, users, roles, or month closing records unless permissions
are changed.

## Closed Month Errors

| Symptom | Explanation |
| --- | --- |
| `Month is closed for this site.` | The selected site and month already has closed deductions. |
| Recalculation is blocked | Closed deductions cannot be recalculated. |
| Potongan save fails | Closed months reject new or updated monthly potongan. |

There is no current reopen-month screen. If a month was closed incorrectly, escalate to the system
owner or technical support process.

## Potongan Entry Rules

- Month must use `YYYY-MM` format.
- Amount must be greater than 0.
- A single peneroka can have one potongan per month; saving again updates the existing entry.
- Saving potongan recalculates allocations immediately.
- Bulk entry requires at least one row.

## Excel Upload Issues

| Problem | Action |
| --- | --- |
| Upload button is missing | Excel upload requires `kps.manage_sites`. |
| File type rejected | Upload `.xlsx` or `.xls` only. |
| File too large | Keep the file at or below 10 MB. |
| Empty file error | Ensure the sheet contains rows with peneroka name or IC. |
| Missing columns error | Use supported column names from the template. |
| Unexpected peneroka created | Check IC numbers and names; upload matches by IC when available, otherwise by name. |

## Allocation Rules

Allocation uses a deterministic waterfall:

1. Apply site `hutang_weightage_pct`.
2. Pay lower priority numbers first.
3. Pay earlier due dates first.
4. Put debts without due dates after dated debts.
5. Pay older records first when priority and due date are otherwise equal.
6. Store any remaining amount as unallocated.

If results look wrong, check:

- The selected site and month.
- Each hutang priority.
- Due dates.
- Current balances.
- Monthly potongan limit.
- Site `hutang_weightage_pct`.

## User And Role Rules

- Only `pentadbiran` users manage users and roles through `/admin`.
- System roles cannot be deleted.
- Roles with assigned users cannot be deleted.
- Users cannot delete or deactivate their own account.
- Permission changes can change visible menus immediately.

## Reports And Statements

| Problem | Likely Cause | Action |
| --- | --- | --- |
| Reports menu is missing | Missing `kps.view_reports`. | Ask an admin to adjust permissions. |
| Statement link is unavailable | Missing report access or site access. | Confirm role and site assignment. |
| Export fails | Permission or browser download issue. | Retry from the correct site and ask support if it repeats. |

## When To Escalate

Escalate to an admin or system owner when:

- A closed month needs correction.
- A user needs a different role or site assignment.
- Allocation results remain unexpected after checking hutang details.
- Excel import repeatedly fails with a valid template.
- A report export is required but your role cannot access reports.
