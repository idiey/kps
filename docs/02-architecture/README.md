# Architecture

## Overview

This section covers the technical architecture of the **Government Workshop Management System**,
including system design patterns, database structure, dynamic workflow processes,
and technology stack decisions for Malaysian government compliance.

## Table of Contents

### Core Documentation

### [1. Dynamic Workflow Management System](../../DYNAMIC_WORKFLOW_SYSTEM.md)

**Note**: Dynamic workflow system is being deprecated in favor of static job mode workflows.

### [7. Workflow Option 1: External KEW.PA-10 Reception](07-workflow-option-1.md)

Complete workflow for processing KEW.PA-10 forms received from government departments.

### [8. Workflow Option 2: Internal Inspection & KEW.PA-10 Generation](08-workflow-option-2.md)

Proactive inspection workflow that generates KEW.PA-10 forms internally.

### [9. PWA Requirement & Architecture (Proposed)](09-pwa-requirement.md)

Technical requirements for Progressive Web App implementation to support mobile technician workflows.

### [10. Workflow Swimlane Diagrams](10-workflow-swimlane-diagrams.md)

Comprehensive swimlane diagrams illustrating the complete job lifecycle from creation to completion,
including actor responsibilities, status transitions, and error handling flows.

### [11. Mobile Application: Product Requirements Document](11-mobile-prd.md)

Complete PRD for mobile application development using React Native + Expo. Covers business objectives,
technical architecture, feature requirements, development roadmap, testing strategy, and deployment plan
for iOS and Android field applications.

### [12. Multi-Tenant Architecture](12-multi-tenant-architecture.md) ⭐ NEW

Company → Workshop hierarchy, data isolation, user assignment, and tenant scoping implementation.

### [13. Job Mode System](13-job-mode-system.md)

Dual-mode job system (KEW.PA-10 Government vs Normal), workflow differences, and form adaptation.

### [16. Simplified Job Modes](16-simplified-job-modes.md) ⭐ CURRENT

Current implementation detailing the simplified, static approach for handling different job modes (Normal, KEW.PA-10, Warranty) without complex dynamic workflows.

### [17. Job Request Swimlane](17-job-request-swimlane.md)

Visual swimlane diagrams illustrating the job request and processing workflows for different job modes.

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

### Frontend (Web)

- **Framework**: Vue.js 3 (Composition API)
- **Bridge**: Inertia.js
- **Build Tool**: Vite
- **Styling**: TailwindCSS
- **State**: Pinia
- **Charts**: Chart.js

### Mobile (iOS & Android)

- **Framework**: React Native + Expo
- **Language**: TypeScript
- **Navigation**: React Navigation 6
- **State**: Zustand
- **API Client**: TanStack Query (React Query)
- **Styling**: NativeWind (TailwindCSS for mobile)
- **Storage**: Expo SQLite + SecureStore
- **Camera**: Expo Camera
- **Notifications**: Firebase Cloud Messaging

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
