# Close the Month

> Close a month only after deduction entry and allocation review are complete for that site.

## Overview

Month closing is the final control in the site payment workflow. In the current implementation,
closing a month:

- marks all deductions for the selected site and month as closed
- records a close timestamp
- prevents new deduction entry for that month
- prevents recalculation for that month
- writes an audit log entry

The current repo does not expose a reopen action from the site workflow, so treat closing as final.

## Before You Close

Complete these checks first:

- all required potongan for the month are already saved
- you reviewed open deductions in `Allocation Review`
- any needed recalculation has already been done
- unallocated balances are understood and accepted
- statement output is ready for reporting or handoff

## Close the Selected Month

1. Open `Allocation Review`.
2. Set the month filter to the target month.
3. Click `Apply`.
4. Review the ledger one last time.
5. Click `Close Month`.

The close button only works when a month is selected.

## What Happens After Closing

After KPS closes the month:

- deductions for that site and month are updated to `Closed`
- `closed_at` is recorded
- the close action is written to the audit log with the month and count of closed deductions
- later deduction entry for the same month is blocked
- recalculation for deductions in that month is blocked

## What You Will See

After the close succeeds:

- rows in the deduction and allocation ledgers show `Closed`
- the allocation detail page shows `Closed`
- open-month actions are no longer available for that month

## If You Close Too Early

This manual cannot document a reopen step, because the current repo does not expose one. If a month
was closed too early, use your internal escalation path instead of assuming a self-service reopen
option exists.

## Permission Note

`site_admin` normally includes the ability to close a month. If KPS rejects the action, check:

- that you are assigned to the site
- that the month is selected
- that your account still has month-closing access

## Related Chapters

- [Review and Recalculate Allocations](03-review-and-recalculate-allocations.md)
- [View and Export Peneroka Statements](05-view-and-export-peneroka-statements.md)
- [Rules and Troubleshooting](06-rules-and-troubleshooting.md)

---

**Last Updated**: 2026-04-04
