# Overview and Access

> Understand what the `site_admin` payment workflow covers before you start entering monthly
> deductions.

## Overview

KPS groups the payment-related site workflow into three main sidebar areas:

- `Potongan Bulanan`
- `Allocation Review`
- `Reports`

For a `site_admin`, these areas work together as one monthly process:

1. save the deduction
2. inspect the allocation result
3. close the month when ready
4. review or export statements

## What This Role Normally Handles

In the seeded role setup, `site_admin` is the site-level operations role. In practice, that means
this workflow expects you to be able to:

- enter single monthly deductions
- enter bulk monthly deductions
- review allocation results
- recalculate open deductions
- close a month
- open site reports and peneroka statements

If one of these pages is missing, your account or site assignment may not be complete.

## Main Navigation

Use the site sidebar to move through the monthly workflow:

- `Potongan Bulanan`
  The deduction ledger. Use this to add new potongan, switch to bulk entry, and filter by month.
- `Allocation Review`
  The allocation ledger. Use this to inspect open and closed rows, open a deduction detail screen,
  and close a month.
- `Reports`
  The reporting workspace. Use this to find a peneroka, open the statement view, and export data.

## Readiness Checklist

Before you process a month, confirm:

- you are inside the correct site workspace
- the month you want to work on is not already closed
- the peneroka record already exists
- the hutang records are already present and current
- you have the final deduction amounts ready for entry

## Important Workflow Notes

- The payment workflow is site-scoped. Data from another site is not part of this workspace.
- A saved potongan immediately enters the allocation workflow.
- Allocation results depend on the current debt priority waterfall.
- Closing a month locks deductions for that site and month.
- The current repo does not expose a reopen action from the site UI.

## When to Use Which Screen

Use `Potongan Bulanan` when:

- you need to add a new deduction
- you need to update an open deduction amount
- you want to review the deduction ledger by month

Use `Allocation Review` when:

- you want to inspect how deductions were allocated
- you need to rerun allocation after debt order or balances changed
- you are ready to close the month

Use `Reports` when:

- you need a site-level report export
- you want to open a peneroka statement
- you need CSV or PDF output for a specific peneroka

## Related Chapters

- [Enter Monthly Potongan](02-enter-monthly-potongan.md)
- [Review and Recalculate Allocations](03-review-and-recalculate-allocations.md)
- [Close the Month](04-close-the-month.md)
- [View and Export Peneroka Statements](05-view-and-export-peneroka-statements.md)

---

**Last Updated**: 2026-04-04
