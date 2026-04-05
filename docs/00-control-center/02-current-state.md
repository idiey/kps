# Current State: KPS

> Snapshot of the live KPS application and what still needs hardening.

**Purpose**: Track the real implementation status of the KPS codebase  
**Intended audience**: Developers, product owners, implementers  
**Last updated**: 2026-04-04  
**Links**: [PRD](../02-architecture/01-prd.md) | [System Design](../02-architecture/02-system-design.md)

## Current Assessment

The source migration is complete. The active application is now KPS, with a Laravel backend, Vue 3 + Inertia frontend, and KPS-specific routes, permissions, and seed data.

The core product is usable as a late-MVP / stabilization build. The main business flows are present, but analytics, exports, and documentation still need depth before the system should be treated as fully hardened production software.

## Implementation Status

### Completed

- KPS-only navigation and route surface
- HQ and site-scoped views
- Site management
- Peneroka management
- Hutang management
- Monthly potongan entry
- Allocation waterfall processing
- Month closing and audit logging
- Admin user management
- Admin role management
- KPS-only seeding and role normalization

### Current Roles

- `pentadbiran`
- `company_admin`
- `site_admin`
- `staff`

### Active Modules

- Dashboard
- Analytics
- Sites
- Peneroka
- Hutang
- Potongan Bulanan
- Allocation Review
- Reports
- User Management
- Role Management

### In Progress

- Broader feature tests for the KPS workflows
- Reporting and export depth
- Developer and deployment documentation
- End-user guidance
- Analytics expansion beyond summary metrics

### Not Yet Fully Covered

- CSV/PDF export flows for reports
- Audit log review UI
- Deeper operational analytics
- Comprehensive browser and feature coverage

## Recent Delivery Notes

- The codebase now routes through KPS-specific controllers and pages.
- The frontend uses Vue 3 with Inertia.
- Site access is controlled through permissions and site assignment.
- The seeders now provision only the KPS roles and sample data needed by the active app.

## Known Gaps

- The report surface is functional but still basic.
- The documentation set had placeholder pages and is being filled in.
- Internal status docs may still lag the implementation until this refresh is fully merged.

## Related Documents

- [System Design](../02-architecture/02-system-design.md)
- [Development](../03-development/README.md)
- [Deployment](../04-deployment/README.md)
- [User Guide](../05-user-guide/README.md)
