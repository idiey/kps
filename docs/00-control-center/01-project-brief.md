# Project Brief: KPS

> High-level overview of the KPS project purpose, scope, and objectives.

**Purpose**: Multi-site cooperative system for managing FELDA peneroka debt and monthly potongan allocation  
**Intended audience**: All stakeholders (HQ Admin, Site Staff, Developers, Product Owners)  
**Last updated**: 2026-02-10  
**Links**: [PRD](../02-architecture/01-prd.md) | [System Design](../02-architecture/02-system-design.md)

## Problem Statement

Debt and potongan tracking are currently fragmented, manual, and inconsistent across sites. This creates:
- Allocation errors
- Delayed collections
- Weak auditability
- Limited HQ visibility

## Solution

KPS delivers a structured, auditable multi-site system with:
- Deterministic allocation waterfall
- Clear HQ vs site scopes
- Two-sidebar UX separating HQ and site navigation
- MVP CRUD for sites, peneroka, hutang, potongan, allocation review, and reporting

## Goals

1. Deliver structured and auditable monthly potongan workflow
2. Enforce strict, deterministic allocation waterfall
3. Support multi-site operations with clear HQ vs site scopes
4. Provide two-sidebar UX separating HQ and site navigation
5. Deliver MVP CRUD for core entities

## Non-goals

- Payment gateway integrations
- Automated bank reconciliation
- Mobile offline-first operations
- Advanced predictive analytics

## Stakeholders

| Stakeholder | Responsibility |
|------------|----------------|
| FELDA (Principal) | Oversight of cooperative performance and compliance |
| KPS (Agent) | Operates the system and enforces allocation rules |
| HQ Admin | Global admin with cross-site access |
| Site Staff | Site-scoped users managing daily operations |

## Core Workflows

1. **Manage Sites** (HQ): Create, update, activate/deactivate sites
2. **Manage Peneroka** (Site): CRUD peneroka with IC/ID, name, contact, address
3. **Manage Hutang** (Site): CRUD debts with priority, balance, due_date
4. **Monthly Potongan Entry** (Site): Single/bulk entry per peneroka per month
5. **Auto Allocation Engine** (Site): Priority waterfall ordering
6. **Review and Adjust Allocation** (Site): Recalculate with audit logging
7. **Reports**: Peneroka statements, site summaries, HQ consolidated view

## Success Criteria

- HQ can view all sites and access HQ dashboard/analytics
- Site users can only access site-scoped pages
- Allocation strictly follows priority and ordering rules
- Unallocated amounts are stored and visible
- Closed months are locked from edits

## Related Documents

- [PRD](../02-architecture/01-prd.md)
- [System Design](../02-architecture/02-system-design.md)
- [Glossary](../02-architecture/03-glossary.md)

---

**Last Updated**: 2026-02-10
