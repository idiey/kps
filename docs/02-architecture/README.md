# Architecture

## Overview

This section covers the technical architecture of the Workshop Management System, including system design patterns, database structure, and technology stack decisions.

## Table of Contents

### [1. System Overview](01-system-overview.md)

High-level system architecture, technology stack, and design principles.

### [2. Database Design](02-database-design.md)

Complete database schema, table specifications, and entity relationships.

### [3. Backend Architecture](03-backend-architecture.md)

Laravel application structure, models, controllers, and services.

### [4. Frontend Architecture](04-frontend-architecture.md)

Vue.js components, Inertia.js pages, and state management with Pinia.

### [5. Security Architecture](05-security-architecture.md)

Authentication, authorization, and security best practices.

### [6. API Design](06-api-design.md)

RESTful API structure and Laravel Sanctum implementation.

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
