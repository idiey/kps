# ARCH: KPS Module Design

> Module boundaries, data model, services, and routing decisions for KPS.

## Overview

This document defines the KPS module design derived from the product requirements.

## Prerequisites

None specified in PRD-KPS or ARCH-KPS.

## Main Content

### Module Boundaries

- KPS domain: Sites, Peneroka, Hutang, Monthly Potongan, Allocation Review, Reporting.
- Platform services: Auth, users, permissions, Inertia, shared UI components.
- Isolation rule: KPS uses its own controllers, models, policies, routes, and Vue pages under
  `/kps`.

### Database Schema (KPS)

All KPS tables are owned by this module and do not depend on non-KPS domain tables.

#### `sites`

- `id` (uuid, pk)
- `name` (string)
- `code` (string, unique)
- `address` (text, nullable)
- `phone` (string, nullable)
- `email` (string, nullable)
- `is_active` (boolean)
- `created_at`, `updated_at`, `deleted_at`

#### `penerokas`

- `id` (uuid, pk)
- `site_id` (uuid, fk -> sites.id)
- `name` (string)
- `ic_number` (string, nullable)
- `phone` (string, nullable)
- `address` (text, nullable)
- `created_at`, `updated_at`, `deleted_at`
- Unique constraint: `site_id + ic_number`

#### `debts`

- `id` (uuid, pk)
- `peneroka_id` (uuid, fk -> penerokas.id)
- `priority` (int)
- `balance` (decimal(12,2))
- `original_amount` (decimal(12,2))
- `due_date` (date, nullable)
- `description` (string, nullable)
- `created_at`, `updated_at`, `deleted_at`

#### `monthly_deductions`

- `id` (uuid, pk)
- `peneroka_id` (uuid, fk -> penerokas.id)
- `site_id` (uuid, fk -> sites.id)
- `month` (date, first day of month)
- `amount` (decimal(12,2))
- `unallocated_amount` (decimal(12,2), default 0)
- `is_closed` (boolean, default false)
- `closed_at` (timestamp, nullable)
- `created_at`, `updated_at`
- Unique constraint: `peneroka_id + month`

#### `deduction_allocations`

- `id` (uuid, pk)
- `monthly_deduction_id` (uuid, fk -> monthly_deductions.id)
- `debt_id` (uuid, fk -> debts.id)
- `amount` (decimal(12,2))
- `created_at`, `updated_at`

#### `kps_audit_logs`

- `id` (uuid, pk)
- `site_id` (uuid, nullable)
- `user_id` (uuid/int, nullable)
- `action` (string)
- `auditable_type` (string)
- `auditable_id` (uuid)
- `metadata` (json, nullable)
- `created_at`

#### `kps_site_user` (pivot)

- `site_id` (uuid)
- `user_id` (uuid/int)
- `role` (enum: site_admin, staff)
- `created_at`, `updated_at`

### Domain Services

- AllocationService: Uses priority ASC, due_date ASC (null last), then created_at ASC; creates
  allocation lines; updates debt balances; stores remaining in
  `monthly_deductions.unallocated_amount`.
- SiteContextResolver: Resolves current site from route param or assigned site and produces `site`
  and `siteRole` for layout and authorization.
- MonthlyClosingService: Locks a site month (`is_closed`, `closed_at`), prevents edits, and writes
  audit logs for closures.

### Routes

Chosen option: `/kps/sites/{site}/...`

- `/kps` (HQ landing)
- `/kps/dashboard` (HQ overview)
- `/kps/analytics` (HQ analytics)
- `/kps/sites` (HQ list)
- `/kps/sites/{site}` (site dashboard)
- `/kps/sites/{site}/peneroka`
- `/kps/sites/{site}/hutang`
- `/kps/sites/{site}/potongan`
- `/kps/sites/{site}/allocations`
- `/kps/sites/{site}/reports`

### UI Architecture

- KpsShellLayout: Renders `KpsMainSidebar` for HQ users, renders `KpsSiteSidebar` when site context
  is active, and provides a shared header + content area.
- SiteShellLayout: Renders `KpsSiteSidebar` only and is used for site users without HQ navigation.

### Security and Access

- Site access is enforced by middleware and policies.
- HQ access is permission-based (`kps.manage_sites`).
- Site access is pivot-based (`kps_site_user`).
- All CRUD is scoped by `site_id`.

### Testing

- Unit: Allocation waterfall ordering and unallocated handling.
- Feature: Multi-site scoping and sidebar visibility rules.

### Assumptions

- KPS is a dedicated module under the `/kps` route prefix.
- Shared auth and permission system are available.
- KPS tables and logic remain isolated from other domains.

## Examples

Not applicable.

## Related Documents

- [PRD](01-prd.md)
- [Architecture Overview](../01-getting-started/02-architecture-overview.md)
- [Glossary](03-glossary.md)
- [Decisions](04-decisions.md)

## Troubleshooting

Not applicable.

---

**Last Updated**: 2026-02-10
