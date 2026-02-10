# KPS Decisions

> Key KPS decisions derived from PRD-KPS and ARCH-KPS.

## Overview

These decisions are derived directly from PRD-KPS and ARCH-KPS.

## Prerequisites

None.

## Main Content

| Decision | Description | Source |
| --- | --- | --- |
| Two-sidebar UX | HQ users always see the main sidebar; site sidebar appears on site selection; site users see only the site sidebar by default. | PRD-KPS |
| Multi-site scoping | All site data is scoped by the active site context; HQ can switch context from the Sites list. | PRD-KPS |
| Allocation ordering | Allocation waterfall uses priority ASC, due_date ASC (null last), then created_at ASC. | PRD-KPS, ARCH-KPS |
| Unallocated handling | Overpayments are stored in `unallocated_amount`. | PRD-KPS, ARCH-KPS |
| Monthly closing | Closing locks a site month from edits and is audit logged. | PRD-KPS, ARCH-KPS |
| Route scoping | Use `/kps/sites/{site}/...` for site routes. | ARCH-KPS |
| Module isolation | KPS uses its own controllers, models, policies, routes, and Vue pages under `/kps`. | ARCH-KPS |
| Data isolation | KPS tables are owned by the module and do not depend on non-KPS domain tables. | ARCH-KPS |
| Access control | HQ access is permission-based (`kps.manage_sites`); site access uses `kps_site_user`. | ARCH-KPS |
| Layout split | Use KpsShellLayout for HQ and SiteShellLayout for site users. | ARCH-KPS |
| Workshop removal | All Workshop-related code removed (2026-02-10). Application now focuses solely on KPS domain. | Refactoring |

## Examples

Not applicable.

## Related Documents

- [PRD](01-prd.md)
- [System Design](02-system-design.md)
- [Glossary](03-glossary.md)

## Troubleshooting

Not applicable.

---

**Last Updated**: 2026-02-10
