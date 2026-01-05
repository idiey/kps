# Architecture

## Overview

This section covers the technical architecture of the **Government Workshop Management System (KEW.PA-10)**,
including system design patterns, database structure, workflow processes,
and technology stack decisions for Malaysian government compliance.

## Table of Contents

### Core Documentation

### [1. Dynamic Workflow Management System](../../DYNAMIC_WORKFLOW_SYSTEM.md)

Complete documentation for the dynamic workflow system that allows administrators to create and manage
multiple workflows, templates, and field configurations without code changes.

### [7. Workflow Option 1: External KEW.PA-10 Reception](07-workflow-option-1.md)

Complete workflow for processing KEW.PA-10 forms received from government departments.

### [8. Workflow Option 2: Internal Inspection & KEW.PA-10 Generation](08-workflow-option-2.md)

Proactive inspection workflow that generates KEW.PA-10 forms internally.

### Planned Documentation

The following documentation is planned for upcoming sprints:

- **01-system-overview.md** - High-level system architecture, technology stack, and design principles
- **02-database-design.md** - Complete database schema, table specifications, and entity relationships
- **03-backend-architecture.md** - Laravel application structure, models, controllers, and services
- **04-frontend-architecture.md** - Vue.js components, Inertia.js pages, and state management with Pinia
- **05-security-architecture.md** - Authentication, authorization, and security best practices
- **06-api-design.md** - RESTful API structure and Laravel Sanctum implementation

## Architecture Principles

1. **Separation of Concerns** - Clear layers: presentation, business logic, data access
2. **SOLID Principles** - Object-oriented design best practices
3. **DRY (Don't Repeat Yourself)** - Reusable components and services
4. **Security First** - Security considerations at every layer
5. **Scalability** - Horizontal scaling support
6. **Testability** - Code designed for easy testing

## Technology Stack

### Backend

- **Framework**: Laravel 12
- **Language**: PHP 8.2+
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

## Architecture Diagrams

Key diagrams are included in each section:

- System context diagram
- Database ERD
- Component architecture
- Deployment architecture

## Related Documentation

- [Getting Started](../01-getting-started/README.md) - Setup and installation
- [Development](../03-development/README.md) - Developer workflows
- [Deployment](../05-deployment/README.md) - Production deployment

---

**Next**: [System Overview →](01-system-overview.md)
