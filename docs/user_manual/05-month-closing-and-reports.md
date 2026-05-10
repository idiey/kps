# Month Closing And Reports

Use this chapter after monthly potongan entries have been entered and allocation results are ready
for review.

## Review Allocations

Required access: KPS site access. Recalculation requires `kps.manage_potongan`.

1. Open the selected site.
2. Select `Allocation Review`.
3. Choose the target month.
4. Select `Apply`.
5. Review total deductions, unallocated amount, closed count, and the deduction list.
6. Open `View` for any row that needs detailed review.

The detail page shows the peneroka, deduction amount, unallocated amount, and allocation lines
against hutang.

## Recalculate Allocations

Use recalculation when open-month hutang details changed after potongan was saved.

1. Open `Allocation Review`.
2. Open the deduction detail with `View`.
3. Select the recalculation action if available.
4. Confirm that the success message appears.
5. Recheck the allocation lines and unallocated amount.

Recalculation is blocked for closed months. The system reverses the previous allocation lines,
restores the related debt balances, then applies the current waterfall again.

## Close The Month

Required permission: `kps.approve_month`.

Closing a month is an irreversible operational lock in the current application. There is no exposed
reopen-month workflow.

1. Open the selected site.
2. Select `Allocation Review`.
3. Choose the month to close.
4. Review the deduction list and investigate unexpected unallocated amounts.
5. Select `Close Month`.
6. Confirm the success message.

Closing marks all deductions for the selected site and month as closed and writes an audit log entry
with the month and number of deductions closed.

## Audit Trail

Required permission: `kps.approve_month`.

1. Open the selected site.
2. Select `Audit Trail` or `Open audit trail`.
3. Review action, user, time, and metadata.

Audit Trail is useful after month closing or when checking who performed an important site action.

## Site Reports

Required permission: `kps.view_reports`.

1. Open the selected site.
2. Select `Reports`.
3. Review site summary values:
   - peneroka count
   - total outstanding
   - current month deductions
4. Search or sort the peneroka list.
5. Use site CSV export when available.

The site CSV export includes peneroka details, debt count, outstanding total, current month
deduction, latest deduction month, and allocation columns by debt description.

## Peneroka Statements

Required permission: `kps.view_reports`.

1. Open `Peneroka` and select `Statement`, or open a peneroka from `Reports`.
2. Review the statement summary:
   - debt count
   - outstanding balance
   - total deductions
   - allocated total
   - unallocated total
3. Review debts ordered by allocation priority.
4. Review monthly deductions and allocation lines.
5. Export CSV or PDF when available.

CSV exports include statement summary, debt rows, monthly deductions, allocation details, and open
or closed status. PDF exports download an A4 statement.

## Closing Checklist

Before closing a month:

- Confirm all expected peneroka have potongan entries.
- Check unusually high or low potongan amounts.
- Check unallocated amounts and confirm they are expected.
- Confirm hutang priorities and due dates are correct.
- Export reports if your organization requires a pre-close copy.
- Close only the correct site and month.
