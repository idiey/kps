# Architecture Capability Overview (KPS)

> Summary of KPS capabilities and architectural scope derived from PRD-KPS and ARCH-KPS.

## Overview

This document provides a consolidated capability view of KPS. It reflects the product scope,
workflows, and architecture defined in PRD-KPS and ARCH-KPS.

## Prerequisites

None.

## Main Content

### Capability Map

| Area | Capability | Notes |
| --- | --- | --- |
| Sites | Create, update, activate, and deactivate sites. | HQ scope. |
| Peneroka | Manage peneroka records with identity and contact data. | Site scope. |
| Hutang | Manage debt records with priority, balance, and due date. | Site scope. |
| Monthly potongan | Capture monthly deductions per peneroka, with bulk entry support. | Site scope. |
| Allocation | Deterministic allocation waterfall and line items. | Site scope. |
| Allocation review | Recalculate and adjust allocations with audit logging. | Site scope. |
| Reporting | Peneroka statements, site summaries, HQ consolidated view. | HQ and site. |
| Closing | Monthly closing locks edits and records audit data. | Site scope. |

### UX and Access Model

- Two-sidebar UX separates HQ navigation from site navigation.
- HQ users always see the main sidebar and can select a site context.
- Site users see only the site sidebar by default.

### Data Model Summary

| Table | Purpose |
| --- | --- |
| `sites` | Site records and status. |
| `penerokas` | Peneroka records linked to a site. |
| `debts` | Debt records linked to a peneroka. |
| `monthly_deductions` | Monthly potongan entries and unallocated amounts. |
| `deduction_allocations` | Allocation line items per monthly deduction. |
| `kps_audit_logs` | Audit trail entries with site and user context. |
| `kps_site_user` | Site-user role assignments. |

### Domain Services

- AllocationService enforces the waterfall ordering and updates balances.
- SiteContextResolver resolves the active site and site role.
- MonthlyClosingService locks months and writes audit logs.

### Routing Model

- HQ routes live under `/kps`.
- Site routes are scoped under `/kps/sites/{site}/...`.

### Constraints

- Allocation ordering uses priority ASC, due_date ASC (null last), then created_at ASC.
- Overpayments are stored in `monthly_deductions.unallocated_amount`.
- Closed months are locked from edits and audit logged.

### Out of Scope

- Payment gateway integrations.
- Automated bank reconciliation.
- Mobile offline-first operations.
- Advanced predictive analytics beyond basic reporting.

## Examples

Not applicable.

## Related Documents

- [PRD](../02-architecture/01-prd.md)
- [System Design](../02-architecture/02-system-design.md)
- [KPS Overview](01-overview.md)

## Troubleshooting

Not applicable.

---

**Last Updated**: 2026-02-10
