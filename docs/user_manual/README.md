# Site Admin Payment Workflow Manual

> Step-by-step guide for `site_admin` users who manage monthly potongan, allocation review,
> month closing, and peneroka statements in KPS.

## Overview

This manual covers the payment-related site workflow in KPS for a `site_admin`. It follows the
actual screens and behaviors present in the current codebase:

1. Enter monthly potongan
2. Review and recalculate allocations
3. Close the month
4. View and export peneroka statements

This manual is site-scoped. It assumes you are already signed in and working inside a site assigned
to your account.

## Audience

- `site_admin` users responsible for monthly payment operations in a single site

## Scope

Included:

- `Potongan Bulanan`
- `Allocation Review`
- month closing
- `Reports` and peneroka statements

Not included:

- HQ-only workflows
- user administration
- debt setup outside the payment workflow
- any reopen-month flow, because the current repo does not expose one

## Before You Start

Make sure:

- you can open the correct site workspace
- the sidebar shows `Potongan Bulanan`, `Allocation Review`, and `Reports`
- the peneroka and hutang records for the site are already maintained
- the target month is still open if you need to enter or recalculate deductions

## Contents

- [Overview and Access](01-overview-and-access.md) - role scope, navigation, and readiness checks
- [Enter Monthly Potongan](02-enter-monthly-potongan.md) - single and bulk entry workflow
- [Review and Recalculate Allocations](03-review-and-recalculate-allocations.md) - how to inspect
  waterfall results and rerun them
- [Close the Month](04-close-the-month.md) - locking the month after review
- [View and Export Peneroka Statements](05-view-and-export-peneroka-statements.md) - statements,
  CSV, and PDF output
- [Rules and Troubleshooting](06-rules-and-troubleshooting.md) - operational rules, limits, and
  common issues

## Quick Links

- Start here if this is a new month:
  [Enter Monthly Potongan](02-enter-monthly-potongan.md#single-entry)
- Start here if deductions are already entered:
  [Review and Recalculate Allocations](03-review-and-recalculate-allocations.md#review-the-month)
- Start here when the month is ready to lock:
  [Close the Month](04-close-the-month.md#close-the-selected-month)
- Start here for statement handoff:
  [View and Export Peneroka Statements](05-view-and-export-peneroka-statements.md#open-a-peneroka-statement)

## Common Rules

- You can only work inside sites assigned to your account.
- Closed months cannot be edited or recalculated through the current site workflow.
- Potongan entry triggers allocation automatically after save.
- Allocation Review supports recalculation of the current deduction waterfall.
- The current repo does not show manual line-by-line editing of allocation amounts.
- Reports and month closing are separate actions and may fail if access is missing.

## Related Documents

- [Project User Guide](../05-user-guide/README.md)
- [Architecture Overview](../01-getting-started/02-architecture-overview.md)
- [Rules and Troubleshooting](06-rules-and-troubleshooting.md)

---

**Last Updated**: 2026-04-04
