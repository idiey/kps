# Workshop Management System - Documentation Index

> **Project**: Workshop Management System
> **Version**: 2.0.0-rework
> **Tech Stack**: Laravel 12 + Vue.js 3 + Inertia.js + React Native (Mobile)
> **Last Updated**: 2026-02-02
> **Status**: Week 6 - Integration Testing & UAT
> **Documentation Standard**: [claude-docs](https://github.com/nasrulhazim/claude-docs)

## Project Overview

The Workshop Management System is a versatile multi-tenant full-stack web application built with Laravel 12, Vue.js 3, and Inertia.js designed for workshop operations including **Malaysian government agencies** (KEW.PA-10 compliance) and **normal workshop operations** (walk-in/booked customers, quotations, invoicing, inventory management).

### Key Characteristics

- **Multi-Tenant**: Support multiple companies with multiple workshops per region
- **Dual Job Modes**: KEW.PA-10 (Government) and Normal (Walk-in/Booked)
- **Government Compliance**: Full KEW.PA-10 form processing and audit trails
- **Workshop Features**: Quotations, Invoicing, Parts Inventory, Appointment Booking
- **Bilingual**: English and Bahasa Malaysia interface
- **Flexible Roles**: Template-based roles (Government 5-role + Workshop roles)
- **Photo Documentation**: Comprehensive before/during/after photo management
- **Notifications**: SMS/WhatsApp alerts for customers

## Documentation Organization

This documentation follows a context-based organization with numbered priority folders. Start with the main [Documentation README](./README.md) for navigation.

## Documentation Structure

### [01. Getting Started](01-getting-started/README.md)

Quick start, installation, and initial configuration.

- [Quick Start Guide](01-getting-started/01-quick-start.md) - Get running in 5 minutes
- [Installation Guide](01-getting-started/02-installation.md) - Complete setup instructions
- [Configuration](01-getting-started/03-configuration.md) - Environment and settings

### [02. Architecture](02-architecture/README.md)

System design, patterns, and technical architecture.

- System Overview - Technology stack and design principles
- Database Design - Complete schema and ERD
- Backend Architecture - Laravel structure
- Frontend Architecture - Vue.js and Inertia.js
- Security Architecture - Authentication and authorization
- API Design - RESTful API structure
- **[Multi-Tenant Architecture](02-architecture/11-multi-tenant-architecture.md)** ⭐ Multi-region support
- **[Job Mode System](02-architecture/12-job-mode-system.md)** ⭐ Dual job modes (Government/Normal)
- **[Simplified Job Modes](02-architecture/16-simplified-job-modes.md)** ⭐ Current Implementation
- **[Job Request Swimlane](02-architecture/17-job-request-swimlane.md)** - Workflow visualization
- **[Workflow Option 1](02-architecture/07-workflow-option-1.md)** - External KEW.PA-10 reception
- **[Workflow Option 2](02-architecture/08-workflow-option-2.md)** - Internal inspection workflow
- **[PWA Requirement](02-architecture/09-pwa-requirement.md)** - Progressive web app architecture
- **[Mobile Application PRD](02-architecture/11-mobile-prd.md)** ⭐ React Native mobile app
- **[Mobile API Design](02-architecture/13-mobile-api-design.md)** - REST API for mobile
- **[Offline Sync Strategy](02-architecture/14-offline-sync.md)** - SQLite + background sync
- **[Notification Architecture](02-architecture/15-notification-architecture.md)** - Push notifications

### [03. Development](03-development/README.md)

Developer guides, coding standards, and workflows.

- Developer Guide - Onboarding and daily workflow
- Coding Standards - PHP, JavaScript, Vue.js conventions
- Testing Guide - PHPUnit, Pest, and Vitest
- Git Workflow - Branching and commit standards
- API Development - Building RESTful endpoints

### [04. Sprints](04-sprints/README.md)

Sprint planning, user stories, and progress tracking.

- [Rework Phase Overview](04-sprints/03-sprint-rework-overview.md) - High-level rework plan
- [Sprint 0 - Foundation](04-sprints/02-sprint-0-foundation.md) - Completed
- [Sprint 1 - Core Features](04-sprints/03-sprint-1-core.md) - Completed
- [Week 4 - Frontend Kickoff](04-sprints/WEEK4-FRONTEND-KICKOFF.md) - Completed
- [Week 5 - Production Prep](04-sprints/WEEK5-COMPLETE.md) - Completed
- **[Week 6 - Integration Testing](04-sprints/WEEK6-PROGRESS.md)** - 🟡 Current Work


### [05. Deployment](05-deployment/README.md)

Implementation roadmap, CI/CD, and production deployment.

- [Implementation Roadmap](05-deployment/01-implementation-roadmap.md) - Complete timeline
- Production Deployment - Step-by-step deployment guide
- CI/CD Pipeline - Continuous integration setup
- Server Setup - Production server configuration
- Monitoring & Maintenance - Application monitoring

### [06. User Guide](06-user-guide/README.md)

User guides and role-specific documentation.

- **[User Roles and Permissions](06-user-guide/01-user-roles.md)** - Five government roles
- Admin Officer Guide - Pentadbiran guide
- Supervisor Guide - Penyelia guide
- Inspector Guide - Pemeriksa guide
- Approver Guide - Pelulus guide
- Technician Guide - Juruteknik guide

### [07. Testing](07-testing/README.md)

TODO: Needs Content

### [08. Business & Sales](08-business-sales/README.md)

Selling and justifying the system to government workshop clients.

- [Value Evaluation Report](08-business-sales/01-value-evaluation-report.md)
- [Master Demo Strategy](08-business-sales/02-master-demo-strategy.md)

## Quick Navigation

### For New Developers

**Start here:**

1. [Quick Start](01-getting-started/01-quick-start.md) - 5-minute setup
2. [Installation Guide](01-getting-started/02-installation.md) - Detailed setup
3. [Developer Guide](03-development/01-developer-guide.md) - Daily workflow
4. [Sprint 0 Tasks](04-sprints/02-sprint-0-foundation.md) - Current sprint work

### For Project Managers

**Start here:**

1. [Implementation Roadmap](05-deployment/01-implementation-roadmap.md) - Complete timeline
2. [Sprint Overview](04-sprints/01-sprint-overview.md) - All sprint planning
3. [Sprint 0 Progress](04-sprints/02-sprint-0-foundation.md) - Current sprint status

### For Architects

**Start here:**

1. [Architecture Overview](02-architecture/README.md) - System design
2. [Workflow Option 1](02-architecture/07-workflow-option-1.md) - External KEW.PA-10 workflow
3. [Workflow Option 2](02-architecture/08-workflow-option-2.md) - Internal inspection workflow
4. [Database Design](02-architecture/02-database-design.md) - Complete schema
5. [Security Architecture](02-architecture/05-security-architecture.md) - Security design

### For Government Users

**Start here:**

1. [User Roles and Permissions](06-user-guide/01-user-roles.md) - Five role system
2. [Workflow Option 1](02-architecture/07-workflow-option-1.md) - External KEW.PA-10 reception
3. [Workflow Option 2](02-architecture/08-workflow-option-2.md) - Internal inspection
4. [User Guide](06-user-guide/README.md) - Role-specific guides

## Project Status

### Current Phase: Week 6 - Integration Testing

**Goal**: Comprehensive testing and user acceptance validation (UAT)

**Status**: In Progress (Day 1)

### Completed ✅

- **Sprint 0-2 & Week 1-5**: Foundation, Core Features, Frontend Kickoff.
- Multi-tenant architecture implemented
- Dual Job Modes (KEW/Normal/Warranty) implemented with static approach
- Unified process flow (Simplified Architecture)
- Mobile App PRD and API Design

### In Progress 🔄

- **Week 6**: Integration Testing
- Creating comprehensive test suite (Unit/Feature/Integration)
- KEW.PA-10 Workflow verification
- Admin module testing
- UAT Preparation

### Upcoming ⏳

- **Week 7**: Bug Fixes & Pre-release Polish
- **Week 8**: Production Deployment

## Technology Stack

### Backend

- **Framework**: Laravel 12
- **PHP**: 8.2+
- **Database**: MySQL 8.0+ / PostgreSQL 14+
- **Cache/Queue**: Redis
- **Authentication**: Laravel Sanctum

### Frontend

- **Framework**: Vue.js 3 (Composition API)
- **Bridge**: Inertia.js
- **Build Tool**: Vite
- **Styling**: TailwindCSS
- **State**: Pinia
- **Charts**: Chart.js

## Key Links

### External Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Vue.js Documentation](https://vuejs.org/guide/)
- [Inertia.js Documentation](https://inertiajs.com/)
- [TailwindCSS Documentation](https://tailwindcss.com/docs)
- [claude-docs Standards](https://github.com/nasrulhazim/claude-docs)

### Repository

- **Repository**: `idiey/wshop_man`
- **Branch**: `claude/analyze-workshop-artifact-yd959`
- **PHP**: 8.2+
- **Framework**: Laravel 12

## Development Team

### Roles

- **Project Lead**: [TBD]
- **Lead Developer**: [TBD]
- **Backend Developer**: [TBD]
- **Frontend Developer**: [TBD]
- **QA Engineer**: [TBD]

## Communication

- **Issues**: GitHub Issues
- **Discussions**: GitHub Discussions
- **Documentation**: `/docs` folder (this repository)

## Standards & Conventions

### Coding Standards

**PHP/Laravel:**

- PSR-12 coding standard
- Laravel best practices
- PHPDoc for all public methods
- Type hints and return types

**JavaScript/Vue.js:**

- Airbnb JavaScript Style Guide
- Vue.js Style Guide (Priority A + B)
- Composition API preferred
- Component naming conventions

### Git Workflow

- **Main Branch**: `main` (protected)
- **Development Branch**: `claude/analyze-workshop-artifact-yd959`
- **Feature Branches**: `feature/feature-name`
- **Bugfix Branches**: `bugfix/bug-description`

### Commit Format

Use conventional commits:

```text
feat: add workshop CRUD operations
fix: resolve validation error on job form
docs: update installation guide
test: add unit tests for Workshop model
chore: update dependencies
```

### Documentation

- All docs in Markdown format
- Follow [claude-docs](https://github.com/nasrulhazim/claude-docs) standards
- Numbered folders (01-, 02-, 03-)
- Lint with markdownlint
- Keep docs synchronized with code

## Version History

### v1.0.0-dev (Current)

- Initial project setup
- Laravel + Vue.js + Inertia.js stack
- Comprehensive documentation (claude-docs standard)
- Database schema design
- Sprint 0 planning

## Documentation Features

### Following claude-docs Standards

✅ **Numbered folder structure** - Context-based with priority numbering
✅ **Progressive detail** - Overview → Specifics
✅ **Self-documenting** - Each folder has README.md with TOC
✅ **Markdown linting** - Automated validation
✅ **Single source of truth** - All docs in `/docs` directory

### Linting

Lint documentation:

```bash
# Install markdownlint
npm install -g markdownlint-cli

# Lint all markdown
markdownlint docs/**/*.md

# Auto-fix issues
markdownlint --fix docs/**/*.md
```

## License

MIT License - See [LICENSE](../LICENSE) file

## Support

For questions and support:

1. Check [Documentation](./README.md)
2. Search [GitHub Issues](https://github.com/idiey/wshop_man/issues)
3. Create new issue with details
4. Tag appropriately (bug, question, enhancement)

---

**Last Review**: 2025-12-28
**Next Review**: 2026-01-04
**Maintained By**: Workshop Management Development Team
**Documentation Standard**: [claude-docs v1.1.0](https://github.com/nasrulhazim/claude-docs)
