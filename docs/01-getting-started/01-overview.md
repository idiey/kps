# KPS Capability Summary

> Quick reference for KPS scope and behavior.

## Overview

This summary consolidates KPS capabilities and constraints from PRD-KPS and the Architecture
Capability Overview.

## Prerequisites

None.

## Main Content

### Objectives

- Deliver a structured and auditable monthly potongan workflow.
- Enforce a strict, deterministic allocation waterfall.
- Support multi-site operations with clear HQ vs site scopes.
- Provide a two-sidebar UX that separates HQ and site navigation.
- Deliver MVP CRUD for sites, peneroka, hutang, potongan, allocation review, and reporting.

### Core Capabilities

| Capability | Scope | Notes |
| --- | --- | --- |
| Site management | HQ | Create, update, activate, and deactivate sites. |
| Peneroka management | Site | CRUD peneroka records. |
| Hutang management | Site | CRUD debts with priority and balance. |
| Monthly potongan | Site | Single entry per month, with bulk entry support. |
| Allocation waterfall | Site | Deterministic ordering with auditability. |
| Allocation review | Site | Recalculate and adjust with audit logging. |
| Reporting | HQ and site | Statements, site summaries, consolidated view. |

### UX and Access Rules

- HQ users always see the main sidebar.
- The site sidebar appears when a site is selected.
- Site users see only the site sidebar by default.
- Site sidebar shows site name/code and a "Back to HQ" action for HQ users.

### Data and Services

Data tables: `sites`, `penerokas`, `debts`, `monthly_deductions`, `deduction_allocations`,
`kps_audit_logs`, `kps_site_user`.

Services: AllocationService, SiteContextResolver, MonthlyClosingService.

### Operational Constraints

- Allocation ordering uses priority ASC, due_date ASC (null last), then created_at ASC.
- Overpayments are stored in `monthly_deductions.unallocated_amount`.
- Monthly closing locks a site month from edits and writes audit logs.

### Assumptions

- KPS is a dedicated application area with shared auth and role-based access.
- Site role assignments are stored on a site-user pivot.
- Allocation adjustments require audit logging.

## Examples

Not applicable.

## Related Documents

- [PRD](../02-architecture/01-prd.md)
- [System Design](../02-architecture/02-system-design.md)
- [Architecture Overview](02-architecture-overview.md)

## Troubleshooting

Not applicable.

---

**Last Updated**: 2026-02-10
