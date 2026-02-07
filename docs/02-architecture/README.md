# Architecture

## Overview

This section covers the technical architecture of the Workshop Management System as implemented in the current codebase. The system uses a simplified, static job-mode approach (KEW.PA-10 and Normal) and a multi-tenant structure (company -> workshop -> assigned users).

Dynamic workflow and template tables were removed in the 2026-02-02 architecture simplification migration.

## Table of Contents

### Current (Implemented)

- [Simplified ERD](erd-simplified.md)
- [Multi-Tenant Architecture](11-multi-tenant-architecture.md)
- [Simplified Job Modes](16-simplified-job-modes.md)
- [Job Request Swimlane](17-job-request-swimlane.md)
- [Workflow Option 1](07-workflow-option-1.md) - Reference flow for KEW.PA-10 intake

### Conceptual or Planned

- [Workflow Option 2](08-workflow-option-2.md) - Conceptual internal inspection flow
- [Workflow Swimlane Diagrams](10-workflow-swimlane-diagrams.md) - Conceptual
- [PWA Requirement](09-pwa-requirement.md) - Planned
- [Mobile Application PRD](11-mobile-prd.md) - Planned
- [Mobile API Design](13-mobile-api-design.md) - Planned
- [Offline Sync Strategy](14-offline-sync.md) - Planned
- [Notification Architecture](15-notification-architecture.md) - Planned
- [Extended ERD (Legacy)](erd.md) - Legacy/extended schema reference

## Architecture Principles

1. Separation of concerns: controllers, services, and policies
2. Role-based access control with policy enforcement
3. Static job modes to reduce operational complexity
4. Auditability through status history, notes, and photo evidence
5. Multi-tenant data separation by company and workshop

## Technology Stack

### Backend

- **Framework**: Laravel 12
- **Language**: PHP 8.2+
- **Database**: MySQL 8.0+ / PostgreSQL 14+
- **Authentication**: Laravel Fortify (session-based)
- **Authorization**: Spatie Laravel-Permission
- **Exports**: DomPDF and Maatwebsite Excel

### Frontend (Web)

- **Framework**: Vue.js 3 (Composition API)
- **Bridge**: Inertia.js
- **Build Tool**: Vite
- **Styling**: TailwindCSS v4
- **Language**: TypeScript

## Related Documentation

- [Getting Started](../01-getting-started/README.md) - Setup and installation
- [Development](../03-development/README.md) - Developer workflows
- [Deployment](../05-deployment/README.md) - Production deployment

---

**Next**: [Simplified Job Modes ->](16-simplified-job-modes.md)
