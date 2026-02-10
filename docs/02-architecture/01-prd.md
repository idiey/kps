# PRD: KPS (Koperasi FELDA Debt & Deduction Management)

> Product requirements for the KPS domain.

## Overview

KPS is a multi-site cooperative system for managing FELDA peneroka debt and monthly potongan
allocation. HQ oversees the organization, while site teams manage peneroka records, hutang,
monthly potongan, allocation review, and site reports.

## Prerequisites

None specified in PRD-KPS or ARCH-KPS.

## Main Content

### Problem Statement

Debt and potongan tracking are currently fragmented, manual, and inconsistent across sites. This
creates allocation errors, delayed collections, weak auditability, and limited HQ visibility.

### Goals

- Deliver a structured and auditable monthly potongan workflow.
- Enforce a strict, deterministic allocation waterfall.
- Support multi-site operations with clear HQ vs site scopes.
- Provide a two-sidebar UX that separates HQ and site navigation.
- Deliver MVP CRUD for sites, peneroka, hutang, potongan, allocation review, and reporting.

### Non-goals

- Payment gateway integrations.
- Automated bank reconciliation.
- Mobile offline-first operations.
- Advanced predictive analytics beyond basic reporting.

### Stakeholders

| Stakeholder | Responsibility |
| --- | --- |
| FELDA (Principal) | Oversight of cooperative performance and compliance. |
| KPS (Agent) | Operates the system and enforces allocation rules. |
| HQ Admin | Global admin with cross-site access. |
| Site Staff | Site-scoped users managing daily operations. |

### Roles and Permissions

Roles:

| Role | Description |
| --- | --- |
| HQ Admin / Superadmin | Global access to sites, analytics, and system settings. |
| Site Admin | Manages site data and users (if permitted). |
| Site Staff | Executes day-to-day site workflows. |

Core permissions:

| Permission | Scope |
| --- | --- |
| `kps.view` | KPS access |
| `kps.manage_sites` | HQ |
| `kps.manage_peneroka` | Site |
| `kps.manage_hutang` | Site |
| `kps.manage_potongan` | Site |
| `kps.view_reports` | HQ and site |
| `kps.approve_month` | Site closing |

### Two-Sidebar UX Specification

Main sidebar (HQ scope):

- KPS (brand / landing)
- Dashboard (HQ overview across all sites)
- Analytics (HQ analytics)
- Sites (list of sites)
- Global Admin Settings (users, roles, permissions, system settings)

Site sidebar (site scope):

- Site Dashboard
- Peneroka
- Hutang
- Potongan Bulanan
- Allocation Review
- Reports
- Site Settings (if permitted)

Sidebar behavior rules:

- HQ users always see the main sidebar.
- The site sidebar appears when a site is selected.
- Site users see only the site sidebar by default.
- Site sidebar displays site name/code and a "Back to HQ" action for HQ users only.

### Multi-site Requirements

- Each site has a unique code and active status.
- All site data is scoped by the active site context.
- HQ can switch site context from the Sites list.
- Site users are automatically scoped to their assigned site on login.

### Core Workflows

1. Manage Sites (HQ): Create, update, activate/deactivate sites; view site list and key metadata.
2. Manage Peneroka (Site): CRUD peneroka with IC/ID, name, contact, and address.
3. Manage Hutang (Site): CRUD debts linked to peneroka; each debt includes priority, balance,
   due_date, and description.
4. Monthly Potongan Entry (Site): Single entry per peneroka per month; supports bulk entry for a
   month across multiple peneroka.
5. Auto Allocation Engine (Site): Priority waterfall ordering; creates allocation line items and
   updates balances.
6. Review and Adjust Allocation (Audit controlled): Recalculate allocations when needed;
   adjustments must be audit logged.
7. Reports: Peneroka statements (debts, allocations, remaining); site summaries; HQ consolidated
   view.

### Allocation Constraints

- Priority waterfall is mandatory.
- Sorting order within priority: due_date ASC (null last), then created_at ASC.
- Partial payments supported.
- Overpayment stored as `unallocated_amount`.

### Closing and Audit Constraints

- Monthly closing locks a site month from edits.
- Audit trail captures site context, user, action, and metadata.

### Acceptance Criteria

- HQ can view all sites and access HQ dashboard and analytics.
- Site users can only access site-scoped pages and see only site sidebar.
- Allocation strictly follows priority and ordering rules.
- Unallocated amounts are stored and visible in statements.
- Closed months are locked from edits.

### Edge Cases

- Potongan amount is zero or negative (reject).
- Multiple debts with same priority and no due_date (order by created_at).
- Overpayment after all debts cleared (unallocated stored).
- User without site assignment attempts site routes (forbidden).
- HQ user attempts site page without selecting a site (redirect to Sites list).

### Assumptions

- KPS is a dedicated application area with shared auth and role-based access.
- Site role assignments are stored on a site-user pivot.
- Allocation adjustments require audit logging.

## Examples

Not applicable.

## Related Documents

- [System Design](02-system-design.md)
- [Architecture Overview](../01-getting-started/02-architecture-overview.md)
- [Glossary](03-glossary.md)
- [Decisions](04-decisions.md)

## Troubleshooting

Not applicable.

---

**Last Updated**: 2026-02-10
