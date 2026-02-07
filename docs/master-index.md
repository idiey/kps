# Workshop Management System - Documentation Index

> **Project**: Workshop Management System
> **Version**: 3.0.0
> **Tech Stack**: Laravel 12 + Vue.js 3 + Inertia.js
> **Last Updated**: 2026-02-07
> **Status**: Week 6 - Integration Testing & UAT
> **Documentation Standard**: claude-docs

## Project Overview

The Workshop Management System is a multi-tenant web application built with Laravel 12, Vue.js 3, and Inertia.js. It supports KEW.PA-10 government workflows and normal workshop jobs with a simplified, static job-mode architecture.

### Key Characteristics

- **Multi-Tenant**: Companies -> Workshops -> Assigned users
- **Dual Job Modes**: KEW.PA-10 (Government) and Normal (Workshop)
- **Role-Based Access**: Spatie Laravel-Permission with site-level assignments
- **Job Lifecycle**: Intake -> inspection -> approval -> repair -> completion
- **Evidence Management**: Before/during/after photo capture
- **Analytics & Reporting**: Dashboards plus PDF/Excel/CSV exports

## Documentation Organization

Start with the main [Documentation README](./README.md) for navigation.

## Documentation Structure

### [01. Getting Started](01-getting-started/README.md)

Quick start, installation, and initial configuration.

- [Quick Start Guide](01-getting-started/01-quick-start.md) - Get running fast
- [Installation Guide](01-getting-started/02-installation.md) - Complete setup instructions
- [Configuration](01-getting-started/03-configuration.md) - Environment and settings

### [02. Architecture](02-architecture/README.md)

System design, patterns, and technical architecture.

- [Simplified ERD](02-architecture/erd-simplified.md) - Current database schema
- [Multi-Tenant Architecture](02-architecture/11-multi-tenant-architecture.md)
- [Simplified Job Modes](02-architecture/16-simplified-job-modes.md)
- [Job Request Swimlane](02-architecture/17-job-request-swimlane.md)
- [Workflow Option 1](02-architecture/07-workflow-option-1.md) - Reference flow
- [Workflow Option 2](02-architecture/08-workflow-option-2.md) - Conceptual flow
- [Workflow Swimlane Diagrams](02-architecture/10-workflow-swimlane-diagrams.md) - Conceptual
- [Extended ERD (Legacy)](02-architecture/erd.md)
- [PWA Requirement (Planned)](02-architecture/09-pwa-requirement.md)
- [Mobile PRD (Planned)](02-architecture/11-mobile-prd.md)
- [Mobile API Design (Planned)](02-architecture/13-mobile-api-design.md)
- [Offline Sync Strategy (Planned)](02-architecture/14-offline-sync.md)
- [Notification Architecture (Planned)](02-architecture/15-notification-architecture.md)

### [03. Development](03-development/README.md)

Developer guides, coding standards, and workflows.

- Development workflow and scripts
- Coding standards
- Testing overview
- Frontend conventions

### [04. Sprints](04-sprints/README.md)

Sprint planning, user stories, and progress tracking.

- [Week 6 - Integration Testing](04-sprints/WEEK6-PROGRESS.md)
- [Week 5 - Production Prep](04-sprints/WEEK5-COMPLETE.md)
- [Week 4 - Frontend Kickoff](04-sprints/WEEK4-COMPLETE.md)

### [05. Deployment](05-deployment/README.md)

Implementation roadmap and deployment notes.

- [Implementation Roadmap (Legacy)](05-deployment/01-implementation-roadmap.md)

### [06. User Guide](06-user-guide/README.md)

User guides and role-specific documentation.

- [User Roles and Permissions](06-user-guide/01-user-roles.md)

### [07. Testing](07-testing/README.md)

Testing documentation and quality assurance.

- [KEW.PA-10 Workflow Testing](07-testing/01-kew-pa-10-workflow-testing.md)

### [08. Business & Sales](08-business-sales/README.md)

Selling and justifying the system to government workshop clients.

- [Value Evaluation Report](08-business-sales/01-value-evaluation-report.md)
- [Master Demo Strategy](08-business-sales/02-master-demo-strategy.md)

## Quick Navigation

### For New Developers

1. [Quick Start](01-getting-started/01-quick-start.md)
2. [Installation Guide](01-getting-started/02-installation.md)
3. [Development Guide](03-development/README.md)
4. [Week 6 Progress](04-sprints/WEEK6-PROGRESS.md)

### For Project Managers

1. [Sprint Overview](04-sprints/README.md)
2. [Week 6 Progress](04-sprints/WEEK6-PROGRESS.md)
3. [Implementation Roadmap (Legacy)](05-deployment/01-implementation-roadmap.md)

### For Architects

1. [Architecture Overview](02-architecture/README.md)
2. [Simplified Job Modes](02-architecture/16-simplified-job-modes.md)
3. [Simplified ERD](02-architecture/erd-simplified.md)
4. [Multi-Tenant Architecture](02-architecture/11-multi-tenant-architecture.md)

### For Government Users

1. [User Roles and Permissions](06-user-guide/01-user-roles.md)
2. [Simplified Job Modes](02-architecture/16-simplified-job-modes.md)
3. [Workflow Option 1](02-architecture/07-workflow-option-1.md)

## Project Status

### Current Phase: Week 6 - Integration Testing

**Goal**: Test suite cleanup, integration validation, and UAT preparation.

### Completed

- Multi-tenant company/workshop structure
- Simplified job modes (KEW.PA-10 and Normal)
- Core job lifecycle (inspection, approvals, completion)
- Admin modules (workshops, users, roles, assets, inventory)

### In Progress

- Integration testing and UAT prep
- Reports and analytics validation

### Upcoming

- Stabilization and release hardening

## Technology Stack

### Backend

- **Framework**: Laravel 12
- **PHP**: 8.2+
- **Database**: MySQL 8.0+ / PostgreSQL 14+
- **Authentication**: Laravel Fortify (session-based)
- **Authorization**: Spatie Laravel-Permission
- **Exports**: DomPDF, Maatwebsite Excel

### Frontend

- **Framework**: Vue.js 3 (Composition API)
- **Bridge**: Inertia.js
- **Build Tool**: Vite
- **Styling**: TailwindCSS v4
- **Language**: TypeScript

## Standards & Conventions

### Coding Standards

- **PHP/Laravel**: PSR-12, Laravel conventions
- **Formatting**: Laravel Pint
- **JavaScript/Vue.js**: ESLint + Prettier
- **Vue**: Composition API, SFC + TypeScript

### Git Workflow

- **Main Branch**: `main` (protected)
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

## Repository

- **Repository**: `idiey/wshop_man`
- **Branch**: `claude/analyze-workshop-artifact-yd959`
- **Framework**: Laravel 12

---

**Last Review**: 2026-02-07
**Maintained By**: Workshop Management Development Team
