# Enter Monthly Potongan

> Use `Potongan Bulanan` to save monthly deductions for one peneroka or many peneroka in the same
> month.

## Overview

KPS provides two entry paths:

- single entry through `Add Potongan`
- batch entry through `Bulk Entry`

Both paths save a monthly deduction and immediately run the allocation workflow after saving.

## Before You Enter Potongan

Confirm all of the following first:

- you are inside the correct site
- the target month is still open
- the peneroka already exists in the site
- the amount is final enough to save

If the month is already closed, KPS rejects the save with a month-closed error.

## Single Entry

### Open the single-entry form

1. Open the site workspace.
2. Click `Potongan Bulanan` in the sidebar.
3. Click `Add Potongan`.

### Fill in the form

The single-entry form requires:

- `Peneroka`
- `Month`
- `Amount`

KPS validates all three fields. The amount must be greater than `0.01`.

### Save the deduction

1. Select the peneroka.
2. Choose the month.
3. Enter the amount.
4. Click `Save Potongan`.

### What happens after save

After a successful save:

- the deduction is stored for the selected site and month
- the allocation service runs immediately
- any remaining balance stays visible as `Unallocated`
- you return to the deduction ledger

If an open deduction already exists for the same peneroka and month, KPS updates that record
instead of creating a second one.

## Bulk Entry

### Open the batch form

1. Open `Potongan Bulanan`.
2. Click `Bulk Entry`.

### Fill in the batch

The batch form uses:

- one shared `Month` field for the whole batch
- one row per peneroka
- one `Amount` field per row

Use `Add Row` to extend the batch and `Remove` to delete a row before saving.

### Save the batch

1. Select the target month once.
2. Fill in each row with a peneroka and amount.
3. Review the `Rows` and `Planned Total` summary.
4. Click `Save Bulk`.

### Batch behavior to know

- each saved row goes through the same allocation engine as single entry
- if the same peneroka appears more than once in the batch, the backend uses update-or-create
  behavior and later saves can overwrite the earlier state for that month
- KPS skips rows whose peneroka no longer exists by the time the backend processes the batch

For cleaner month-end processing, enter each peneroka once per batch.

## Use the Deduction Ledger After Save

The `Potongan Bulanan` ledger helps you verify what you just entered:

- use the month filter to focus on one month
- review `Amount` and `Unallocated`
- check whether the row is `Open` or `Closed`
- click `View` to open the allocation detail screen for that deduction

## What the Ledger Tells You

- `Amount`
  The saved gross deduction amount for the month
- `Unallocated`
  The portion not absorbed by the current debt waterfall
- `Open`
  The deduction can still be recalculated
- `Closed`
  The month is locked for that deduction

## Common Entry Errors

- `Month is closed for this site`
  The selected month was already closed. Use another month or follow your internal recovery path.
- missing `Peneroka`, `Month`, or `Amount`
  KPS blocks save until all required fields are filled.
- amount is `0` or blank
  KPS requires a positive amount.

## Related Chapters

- [Review and Recalculate Allocations](03-review-and-recalculate-allocations.md)
- [Close the Month](04-close-the-month.md)
- [Rules and Troubleshooting](06-rules-and-troubleshooting.md)

---

**Last Updated**: 2026-04-04
