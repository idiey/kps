# Review and Recalculate Allocations

> Use `Allocation Review` to inspect the debt waterfall for each deduction and rerun it while the
> month is still open.

## Overview

Every saved potongan flows into allocation automatically. `Allocation Review` lets you inspect that
result at two levels:

- the month ledger
- the individual deduction detail page

The current codebase supports recalculation of the waterfall. It does not expose manual line-by-line
editing of allocation amounts in this screen.

## Review the Month

1. Open the site workspace.
2. Click `Allocation Review`.
3. Choose a month in the month filter.
4. Click `Apply`.

The ledger shows:

- the month
- peneroka name
- deduction amount
- number of allocation rows
- unallocated balance
- open or closed status

## What to Watch For

Review these indicators before you close a month:

- `Unallocated`
  A remaining amount that did not get absorbed by the current debt waterfall
- `Open`
  The deduction can still be recalculated
- `Closed`
  The deduction is locked
- `Allocations`
  The count of debt rows that received part of the deduction

## Open a Deduction Detail Screen

1. In the ledger, click `View` for the row you want to inspect.
2. Review the summary cards:
   `Amount`, `Allocated`, `Unallocated`, and `Status`.
3. Review the `Debt waterfall result` table.

The detail page shows exactly how the saved deduction amount was distributed across debts.

## Recalculate an Open Deduction

Use recalculation when:

- debt priority changed
- debt balances changed
- you want to rerun the current waterfall on an open month

### Recalculate steps

1. Open the deduction detail page.
2. Confirm the status is `Open`.
3. Click `Recalculate`.

After recalculation, KPS reruns the allocation waterfall using the current debt order. Feature tests
confirm that updated debt priority changes the recalculated result.

## When Recalculation Is Blocked

KPS rejects recalculation when the deduction is already closed. In that case:

- the button is disabled in the UI
- backend requests for recalculation return a month-closed error

## How to Read the Result

- `Allocated`
  Total amount absorbed by debts
- `Unallocated`
  Balance left after the waterfall finishes
- `Priority`
  The debt order used by the waterfall
- `Remaining Balance`
  Current balance after allocation has been applied

If the detail screen shows `No allocations found`, the deduction has no recorded debt allocations in
the current state. Check the related hutang records and confirm the month is still open before
trying to recalculate.

## Operational Limits

The current implementation does not show:

- manual editing of individual allocation lines
- a partial close workflow
- a reopen control after the month is closed

Use the existing recalculation flow for open deductions and treat closed months as locked.

## Related Chapters

- [Enter Monthly Potongan](02-enter-monthly-potongan.md)
- [Close the Month](04-close-the-month.md)
- [Rules and Troubleshooting](06-rules-and-troubleshooting.md)

---

**Last Updated**: 2026-04-04
