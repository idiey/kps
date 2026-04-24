# Documentation

> Entry point for KPS documentation.

## Overview

KPS is a multi-site cooperative system for managing FELDA peneroka debt and monthly potongan
allocation. Use PRD-KPS and ARCH-KPS as the canonical sources of truth.

## Prerequisites

None.

## Main Content

### Documentation Sections

1. **[Getting Started](01-getting-started/README.md)** - Quick start guides and overview
   - [KPS Overview](01-getting-started/01-overview.md)
   - [Architecture Overview](01-getting-started/02-architecture-overview.md)

2. **[Architecture](02-architecture/README.md)** - System design and technical specifications
   - [Product Requirements (PRD)](02-architecture/01-prd.md)
   - [System Design](02-architecture/02-system-design.md)
   - [Glossary](02-architecture/03-glossary.md)
   - [Decisions](02-architecture/04-decisions.md)

3. **[Development](03-development/README.md)** - Development workflows and coding standards

4. **[Deployment](04-deployment/README.md)** - Deployment procedures and operations

5. **[User Guide](05-user-guide/README.md)** - Role-specific guides and tutorials

### Control Center

- **[Control Center](00-control-center/README.md)** - Project memory hub and AI agent context

### AI Command Sync

- Source workflows are authored in `.agent/workflows/*.md`.
- Codex slash-command mirror lives in `.codex/commands/*.md`.
- After workflow changes, sync with:

```powershell
New-Item -ItemType Directory -Force .codex\commands | Out-Null
Copy-Item .agent\workflows\*.md .codex\commands\ -Force
```

### Additional References

- [Documentation Index](master-index.md)
- [Documentation Changelog](CHANGELOG_DOCS.md)

## Examples

Not applicable.

## Related Documents

- [Getting Started](01-getting-started/README.md)
- [Architecture](02-architecture/README.md)
- [PRD](02-architecture/01-prd.md)
- [System Design](02-architecture/02-system-design.md)

## Troubleshooting

Not applicable.

---

**Last Updated**: 2026-04-07
