# Rules and Troubleshooting

> Use this chapter when the monthly payment workflow does not behave as expected.

## Overview

This chapter collects the operating rules and failure cases confirmed by the current codebase,
routes, policies, and tests.

## Operating Rules

- work is site-scoped, not cross-site
- `Potongan Bulanan` is for entry
- `Allocation Review` is for inspection, recalculation, and month closing
- `Reports` is for viewing and exporting statement data
- closed months block new deduction entry for that month
- closed months block recalculation for deductions in that month
- allocation uses the current debt priority waterfall
- the current repo does not expose manual allocation line editing
- the current repo does not expose a reopen-month action

## Common Problems

### I cannot see `Potongan Bulanan`, `Allocation Review`, or `Reports`

Possible causes:

- you are not assigned to the site
- your account permissions are incomplete
- you are in the wrong site workspace

What to do:

1. confirm the site code shown in the sidebar
2. sign out and back in if your access was recently changed
3. ask the system administrator to verify your site assignment and role access

### I get `Month is closed for this site`

This happens when you try to save a deduction into a month that already contains closed deductions
for that site.

What to do:

1. confirm the selected month
2. switch to another month if appropriate
3. escalate internally if the month was closed too early

Do not assume the site UI includes a reopen flow.

### Recalculate is unavailable or fails

Possible causes:

- the deduction is already closed
- you opened the wrong site or row
- the month was closed after the row was first loaded

What to do:

1. check the `Status` card on the deduction detail page
2. return to the allocation ledger and confirm the row is still open
3. if the row is closed, stop and follow your internal escalation path

### `No allocations found` appears on the detail or statement screen

This means no debt allocations are currently recorded for that deduction row.

What to check:

- the peneroka has active hutang records
- the deduction amount was saved successfully
- the month is still open if you need to rerun allocation

### Unallocated balance is still visible

This means part of the deduction was not absorbed by the current waterfall.

What to do:

1. open the deduction detail page
2. review the debt waterfall result
3. confirm that debt priorities and balances are current
4. use `Recalculate` if the month is still open and debt data changed
5. close the month only when the remaining unallocated amount is understood

### Reports page opens, but statement export is what I really need

Use:

- `Statement` from the report list for one peneroka
- `Export CSV` from the statement page for spreadsheet output
- `Download PDF` from the statement page for document output

### I need to edit allocation amounts manually

The current repo does not show a manual allocation editing screen in `Allocation Review`. The
confirmed site workflow supports:

- automatic allocation after save
- recalculation of open deductions
- month closing

Do not assume a hidden manual override exists in this flow.

## Escalate Instead of Guessing

Escalate when:

- the wrong month was closed
- a page is missing for a user who should have access
- reports are forbidden even though the site assignment is correct
- the workflow you expect is not visible in the current UI

## Related Chapters

- [Overview and Access](01-overview-and-access.md)
- [Review and Recalculate Allocations](03-review-and-recalculate-allocations.md)
- [Close the Month](04-close-the-month.md)

---

**Last Updated**: 2026-04-04
