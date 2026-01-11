# Documentation

## Overview

Welcome to the **Government Workshop Management System (KEW.PA-10)** documentation.
This documentation follows a context-based organization with numbered priority folders,
making it easy to find information based on what you need to accomplish.

This system is specifically designed for Malaysian government agencies to manage asset maintenance,
repairs, and inspections using the official KEW.PA-10 procurement form.

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
- Database design
- Frontend architecture (Vue.js + Inertia.js)
- Backend architecture (Laravel)
- Security architecture

### [03. Development](03-development/README.md)

Developer guides, workflows, and best practices.

- Developer quick start
- Coding standards
- Git workflow
- Testing guide
- API development

### [04. Sprints](04-sprints/README.md)

Sprint planning, daily todos, and project management.

- Sprint planning
- Sprint 0 daily todos
- User stories
- Progress tracking

### [05. Deployment](05-deployment/README.md)

Deployment procedures and operational guides.

- Implementation roadmap
- Production deployment
- CI/CD pipeline
- Monitoring and maintenance

### [06. User Guide](06-user-guide/README.md)

User guides and role-specific documentation.

- User roles and permissions
- Admin Officer (Pentadbiran) guide
- Supervisor (Penyelia) guide
- Inspector (Pemeriksa) guide
- Approver (Pelulus) guide
- Technician (Juruteknik) guide

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

Need to set up development environment? See [Development Setup](01-getting-started/02-installation.md).

Working on a feature? Check [Development Guide](03-development/01-developer-guide.md).

## Finding Information

- **Concepts and Design**: Check [Architecture](02-architecture/README.md) section
- **Job Workflows**: Check [Workflow Option 1](02-architecture/07-workflow-option-1.md) and [Workflow Option 2](02-architecture/08-workflow-option-2.md)
- **Database ERD**: Check [Entity Relationship Diagram](02-architecture/erd.md)
- **User Roles**: Check [User Roles and Permissions](06-user-guide/01-user-roles.md)
- **How-to Guides**: Check [Development](03-development/README.md) section
- **Project Planning**: Check [Sprints](04-sprints/README.md) section
- **Deployment**: Check [Deployment](05-deployment/README.md) section
- **Testing**: Check [Testing](07-testing/README.md) section
- **Business Docs**: Check [Business & Sales](08-business-sales/README.md) section

## Documentation Standards

This documentation follows the [claude-docs](https://github.com/nasrulhazim/claude-docs) standards:

- Context-based organization
- Numbered priority (01-, 02-, 03-)
- Progressive detail (overview → specifics)
- Self-documenting (each folder has README.md)
- Markdown linting with markdownlint

## Contributing to Documentation

When adding or updating documentation:

1. Place files in appropriate context folder
2. Use numbered prefixes (01-, 02-, 03-)
3. Follow kebab-case naming (`01-feature-name.md`)
4. Update context README.md with new entries
5. Run markdown linter: `markdownlint docs/**/*.md`
6. Keep documentation synchronized with code changes

## Need Help?

- Check existing documentation first
- Search GitHub Issues
- Ask in team discussions
- Create detailed issue if needed

---

**Last Updated**: 2026-01-07
**Documentation Version**: 2.0
**Following**: [claude-docs standards](https://github.com/nasrulhazim/claude-docs)
