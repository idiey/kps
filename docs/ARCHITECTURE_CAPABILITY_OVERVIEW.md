# Workshop Management System - Architecture & Capability Overview

> **Version**: 3.0
> **Last Updated**: 2026-02-07
> **Audience**: Solution Architects, Technical Leads, Development Teams
> **Purpose**: Accurate overview of the current codebase architecture and capabilities

---

## Executive Summary

The Workshop Management System is a multi-tenant web application for workshop operations built on Laravel 12 and Vue 3 (Inertia.js). It supports two static job modes (KEW.PA-10 and Normal) with role-based access control, inspection and approval workflows, photo evidence, and reporting/analytics.

**Key points from the current implementation:**
- Static job modes (no dynamic workflow engine)
- Multi-tenant companies and workshops with site assignments
- Role-based access via Spatie permissions and Laravel policies
- Inspections, approvals, completion reporting, and photo evidence
- Reports exported to PDF/Excel/CSV

---

## System Architecture

### Technology Stack

**Backend**
- Laravel 12 (PHP 8.2+)
- MySQL 8.0+ / PostgreSQL 14+
- Fortify (session authentication)
- Spatie Laravel-Permission (RBAC)
- DomPDF + Maatwebsite Excel (exports)

**Frontend (Web)**
- Vue 3 + Inertia.js (TypeScript)
- Vite
- TailwindCSS v4

**Not in Repository (Planned)**
- Mobile application
- PWA/offline sync
- Dedicated public API
- SMS/WhatsApp notification integration

---

## Core Modules & Capabilities

### 1. Job Management

- `workshop_jobs` is the central entity
- Job modes: `KEW_PA_10` and `NORMAL`
- Status flow enforced by `App\Enums\JobStatus`
- Assignments, notes, and status history tracked

### 2. KEW.PA-10 Compliance

- Static KEW.PA-10 fields stored directly on `workshop_jobs`
- Approval tracking fields: status, approver, timestamps
- KEW-specific inspection fields included

### 3. Inspections

- Inspection reports stored in `inspection_reports`
- Approval/rejection actions via controller endpoints

### 4. Repair Completion

- Completion reports in `repair_completion_reports`
- Parts used tracked inside completion workflow

### 5. Photo Evidence

- Photo uploads and stages stored in `job_photos`
- Public/private toggle supported

### 6. Admin Modules

- Workshops and companies (multi-tenant)
- Users and roles
- Assets and inventory
- Reports and analytics
- Settings

### 7. Reporting & Analytics

- Admin reports for jobs, customers, and performance
- Export formats: PDF, Excel, CSV
- Analytics dashboards for jobs and customers

---

## Multi-Tenancy Model

- **Company** (HQ) -> **Workshops** (sites)
- Users can be assigned to workshops with site-level roles
- Policies enforce site and company access boundaries
- No subdomain routing implemented yet (field exists on company)

---

## Data Model Summary (Current)

Key tables in active use:

- `companies`, `workshops`, `workshop_user`
- `users` + Spatie permission tables
- `customers`, `government_departments`, `assets`
- `workshop_jobs`
- `job_assignments`, `job_notes`, `job_status_histories`, `job_photos`
- `inspection_reports`, `repair_completion_reports`
- `parts`, `stock_movements`
- `settings`

For details, see [Simplified ERD](02-architecture/erd-simplified.md).

---

## Authorization & Security

- Authentication via Fortify (session-based)
- Role permissions via Spatie
- Model policies enforce access checks
- Soft deletes for key domain tables

---

## File Structure Overview

```
workshop/
+-- app/
¦   +-- Http/Controllers/     # Controllers
¦   +-- Models/               # Eloquent models
¦   +-- Services/             # Business logic
¦   +-- Policies/             # Authorization policies
¦   +-- Enums/                # Job status/mode enums
+-- database/
¦   +-- migrations/           # Database migrations
¦   +-- seeders/              # Seed data
+-- resources/js/
¦   +-- pages/                # Inertia pages (Vue SFC)
¦   +-- components/           # Reusable UI components
¦   +-- layouts/              # Layouts
+-- docs/                     # Documentation
```

---

## Getting Started

- [Quick Start](01-getting-started/01-quick-start.md)
- [Installation Guide](01-getting-started/02-installation.md)
- [Architecture Overview](02-architecture/README.md)

---

**Next**: [Simplified Job Modes ->](02-architecture/16-simplified-job-modes.md)
