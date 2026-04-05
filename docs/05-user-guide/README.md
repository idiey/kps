# User Guide

## Overview

KPS supports a small set of operational roles. The interface and available actions depend on your role and the site assignments attached to your account.

## Roles

- `pentadbiran`: Full administrative access across the system.
- `company_admin`: HQ access for oversight and cross-site management.
- `site_admin`: Site-level management for assigned sites.
- `staff`: Day-to-day operational access for assigned sites.

## Main Areas

- Dashboard: system overview.
- Analytics: summary figures for the KPS operation.
- Sites: manage site records.
- Peneroka: manage peneroka profiles for a site.
- Hutang: manage debt records for a site.
- Potongan Bulanan: enter monthly deductions.
- Allocation Review: inspect allocation results and close the month.
- Reports: view site reports and peneroka statements.
- Administration: manage users and roles.

## Typical Workflows

### HQ Users

1. Review the dashboard and analytics.
2. Maintain the site list.
3. Manage user access and roles from Administration.

### Site Users

1. Open the assigned site context.
2. Maintain peneroka and hutang records.
3. Enter monthly deductions.
4. Review allocations.
5. Close the month when the site is ready.
6. Use reports to review peneroka statements.

## Common Rules

- You can only work inside sites assigned to your account.
- Closed months should not be edited.
- Allocation results follow the debt priority order defined in the application logic.
- If a page is missing from the sidebar, your role may not have permission for it.

## Related Links

- [Development](../03-development/README.md)
- [Deployment](../04-deployment/README.md)
- [System Map](../00-control-center/03-system-map.md)
