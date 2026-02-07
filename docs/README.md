# Documentation

## Overview

Welcome to the Workshop Management System documentation.
This repository documents a multi-tenant Laravel 12 + Vue 3 + Inertia web app for workshop operations,
including KEW.PA-10 (government) and normal job modes.

Core capabilities reflected in the codebase:
- Dual job modes: KEW.PA-10 and Normal
- Job lifecycle: intake, inspection, approvals, repair, completion
- Photos, notes, assignments, and status history (audit trail)
- Multi-tenant companies and workshop sites with role-based access
- Admin modules: workshops, users, roles, assets, inventory, reports, settings
- Analytics dashboards and report exports (PDF/Excel/CSV)

## Documentation Structure

### [01. Getting Started](01-getting-started/README.md)

Quick start guides, installation, and initial setup.

- Installation guide
- Quick start (5 minutes)
- Environment setup
- First steps with the system

### [02. Architecture](02-architecture/README.md)

System design, patterns, and architectural decisions.

- Technical architecture
- Database design (simplified ERD)
- Multi-tenant architecture
- Simplified job modes

### [03. Development](03-development/README.md)

Developer guides, workflows, and best practices.

- Developer quick start
- Coding standards
- Git workflow
- Testing guide
- Frontend conventions

### [04. Sprints](04-sprints/README.md)

Sprint planning, weekly progress, and project management.

- Weekly progress tracking
- Sprint planning
- User stories
- Progress checkpoints

### [05. Deployment](05-deployment/README.md)

Deployment procedures and operational guides.

- Production deployment (planned)
- CI/CD pipeline (planned)
- Monitoring and maintenance (planned)

### [06. User Guide](06-user-guide/README.md)

User guides and role-specific documentation.

- User roles and permissions
- Admin Officer (Pentadbiran) guide (planned)
- Supervisor (Penyelia) guide (planned)
- Inspector (Pemeriksa) guide (planned)
- Approver (Pelulus) guide (planned)
- Technician (Juruteknik) guide (planned)

### [07. Testing](07-testing/README.md)

Testing documentation and quality assurance.

- KEW.PA-10 workflow testing
- Test strategies and patterns
- Running tests

### [08. Business & Sales](08-business-sales/README.md)

Business documentation and sales materials.

- Value evaluation report
- Demo strategy
- Client presentations

### [09. Project Planning](09-plan/README.md)

Project planning documents, daily logs, and sprint management.

- Daily achievement logs
- Sprint planning
- Work checkpoints
- AI artifacts

## Quick Start

New to the project? Start with [Getting Started Guide](01-getting-started/01-quick-start.md).

**For Solution Architects & Teams**: See [Architecture & Capability Overview](ARCHITECTURE_CAPABILITY_OVERVIEW.md) for a comprehensive guide to the system's architecture and capabilities as implemented in code.

Need to set up development environment? See [Installation Guide](01-getting-started/02-installation.md).

Working on a feature? Check [Development Guide](03-development/README.md).

## Finding Information

- **Concepts and Design**: Check [Architecture](02-architecture/README.md) section
- **Job Workflows**: Check [Simplified Job Modes](02-architecture/16-simplified-job-modes.md)
- **Database ERD**: Check [Simplified ERD](02-architecture/erd-simplified.md)
- **User Roles**: Check [User Roles and Permissions](06-user-guide/01-user-roles.md)
- **How-to Guides**: Check [Development](03-development/README.md) section
- **Project Planning**: Check [Sprints](04-sprints/README.md) section
- **Deployment**: Check [Deployment](05-deployment/README.md) section
- **Testing**: Check [Testing](07-testing/README.md) section
- **Business Docs**: Check [Business & Sales](08-business-sales/README.md) section

## Documentation Standards

This documentation follows the claude-docs standards:

- Context-based organization
- Numbered priority folders (01-, 02-, 03-)
- Progressive detail (overview -> specifics)
- Self-documenting (each folder has README.md)
- Markdown linting with markdownlint

## Contributing to Documentation

When adding or updating documentation:

1. Place files in the appropriate context folder
2. Use numbered prefixes (01-, 02-, 03-)
3. Follow kebab-case naming (e.g., `01-feature-name.md`)
4. Update the context README.md with new entries
5. Run markdown linter: `markdownlint docs/**/*.md`
6. Keep documentation synchronized with code changes

## Need Help?

- Check existing documentation first
- Search GitHub Issues
- Ask in team discussions
- Create a detailed issue if needed

---

**Last Updated**: 2026-02-07
**Documentation Version**: 3.1
**Following**: claude-docs standards
