# KPS Demo Flow Seed Data - Design Spec

**Date:** 2026-04-05
**Status:** Approved

---

## Overview

KPS already supports the site-level payment-related workflow for:

1. entering monthly potongan
2. reviewing allocations
3. closing a month
4. opening peneroka statements and exports

The current seed data is not suitable for demos of that flow because it is random and spread across
multiple sites. The goal of this change is to add deterministic mock data that a user can browse
from the site workspace and immediately understand which peneroka demonstrate which scenario.

This is a seed-data project only. It does not introduce a new payment or payout feature. The seeded
data should support the existing UI and routes already present in the repo.

---

## Goals

1. Create one dedicated demo site for the site-admin payment workflow.
2. Seed one known `site_admin` user who can access the full end-to-end site flow.
3. Seed a small number of named peneroka with deterministic, easy-to-recognize scenarios.
4. Make the data useful across `Potongan Bulanan`, `Allocation Review`, month closing, and
   `Reports`.
5. Remove randomness from the demo data so manual testing and documentation remain stable.

---

## Non-Goals

- Add a new disbursement or payout-to-peneroka feature.
- Replace every existing seeded site with deterministic demo cases.
- Rework the allocation algorithm.
- Add screenshot generation or UI automation as part of the seeding change.

---

## Recommended Approach

Add a dedicated deterministic seeder for a demo flow site and wire it into the normal seed path.

This is preferable to editing the current random seeder in place because:

- the demo data needs stable names and values
- the random seed remains useful for broader non-demo coverage if still needed
- the dedicated site gives one obvious place for manual verification

The seeder should be idempotent. Re-running it should update or recreate the same records without
creating duplicate visible demo cases.

---

## Seeded Site and User

### Demo Site

Create one dedicated demo site with a code and name that are clearly visible in the UI:

- code: `FELDA-DEMO`
- name: `FELDA Demo Pembayaran`

The site should be active and have realistic contact fields so it looks complete in the site
workspace.

### Demo User

Create one deterministic `site_admin` user assigned to the demo site. This account must be able to:

- enter potongan
- review allocations
- close a month
- view reports
- open audit data related to month closing

The seeded user should reuse the existing permission model rather than bypassing it.

---

## Peneroka Cases

Seed a small set of named peneroka cases that are obvious from the UI.

### Case 1: Full Allocation

One peneroka should have:

- multiple hutang with different priorities
- an open-month deduction that is fully absorbed by the waterfall
- no remaining `unallocated_amount`

Purpose:

- prove that the normal allocation path works cleanly
- make the allocation detail view easy to inspect

### Case 2: Partial Allocation With Unallocated Balance

One peneroka should have:

- limited hutang balance
- an open-month deduction larger than the available debt balance
- a non-zero `unallocated_amount`

Purpose:

- make the unallocated behavior visible in both `Potongan Bulanan` and `Allocation Review`

### Case 3: Closed-Month History

One peneroka should have:

- at least one deduction in a past month
- that month marked closed
- statement history showing the closed deduction

Purpose:

- demonstrate the locked-month workflow
- provide an obvious row for month-close-related checks

### Case 4: Statement-Rich History

One peneroka should have:

- multiple months of deductions
- multiple hutang allocations
- enough history to make the statement page and exports useful

Purpose:

- make `Reports` and statement export worth opening in a demo

---

## Month Strategy

Use fixed month values rather than `now()`-relative random behavior for the demo flow records.

Recommended shape:

- one past closed month
- one current open month
- one additional past month for the statement-rich case

The month values should be chosen so:

- the open month appears in current ledgers
- the closed month remains visible in history
- the statement page shows more than one deduction where appropriate

This design intentionally prioritizes deterministic demo behavior over “always current calendar”
seeding.

---

## Data Creation Rules

### Sites and Users

- use `firstOrCreate` or `updateOrCreate` for deterministic site and user records
- assign the seeded `site_admin` to the demo site through the existing site-user pivot
- normalize roles using the existing role seeding approach

### Peneroka

- create deterministic names, IC numbers, phone numbers, and addresses
- keep the names descriptive enough to distinguish demo cases in the UI

### Hutang

- assign fixed priorities, balances, descriptions, and due dates
- choose values that produce the exact allocation scenarios needed

### Monthly Deductions and Allocations

- create deterministic monthly deductions with fixed amounts
- use the real allocation service when possible so the seeded data matches actual runtime behavior
- use the real monthly closing service for the closed month so audit logs and close-state behavior are
  created consistently

The seeder should avoid hand-building inconsistent allocation states unless a specific edge case
cannot be expressed through the normal services.

---

## Seeder Structure

### New Seeder

Add a dedicated seeder:

- `database/seeders/KpsDemoFlowSeeder.php`

Responsibilities:

- create or update the dedicated demo site
- create or update the dedicated demo `site_admin`
- seed the fixed peneroka cases
- create debts and monthly deductions for each case
- run allocation and month-closing steps using existing services

### DatabaseSeeder Integration

Wire the demo-flow seeder into the normal seed path after roles and base site data exist.

The recommended order is:

1. roles and permissions
2. base sites
3. existing production/general mock data if retained
4. dedicated demo flow site data

This keeps the dedicated demo site easy to find while preserving existing broader seed coverage.

---

## Verification Strategy

Verification should prove that the seeded data is visible through the actual site workflow.

### Seeder-Level Verification

- the demo site exists with the expected code
- the demo `site_admin` is assigned to that site
- the expected peneroka cases exist under the demo site

### Flow-Level Verification

After seeding, the following should be true from the UI and route perspective:

- `Potongan Bulanan` shows open-month deductions for the demo site
- `Allocation Review` shows both fully allocated and partially allocated cases
- at least one past month is closed
- `Reports` shows statement-ready peneroka rows
- a statement page contains debts, deductions, and allocations

### Test Coverage

Add focused tests for the new deterministic seeding behavior rather than relying on manual seed
inspection alone.

Recommended tests:

- demo site and admin user are created
- seeded peneroka cases exist with the expected deduction states
- the closed month is actually closed
- statement-rich peneroka has visible deductions and allocations

---

## Risks and Mitigations

| Risk | Mitigation |
| --- | --- |
| Random legacy seeding hides the demo site in noise | Use a clearly named dedicated site with deterministic names |
| Seed re-runs create duplicates | Use idempotent create-or-update behavior |
| Hand-built allocations drift from runtime logic | Prefer the real allocation and closing services |
| Demo data goes stale when the UI changes | Keep cases small and tied to current routes and tests |
| User expects a payout feature that does not exist | Explicitly scope this to the current payment-related site flow only |

---

## Out of Scope

- adding a “mark as paid” workflow
- changing permission semantics
- replacing all existing seeded sites with only demo data
- producing non-site demo flows for HQ users
