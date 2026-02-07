# Sprint Planning - Workshop Management System Rework

> **Start Date**: 2026-02-03  
> **End Date**: 2026-03-04  
> **Duration**: 30 days (6 sprints × 5 days)  
> **Team Size**: 3-4 developers

---

## Sprint Overview

| Sprint | Duration | Focus Area | Days |
|--------|----------|------------|------|
| Sprint 1 | Days 1-5 | Multi-Tenant Foundation | 5 |
| Sprint 2 | Days 6-10 | Job Mode & Quotation | 5 |
| Sprint 3 | Days 11-15 | Invoice & Inventory | 5 |
| Sprint 4 | Days 16-20 | Booking & Notifications | 5 |
| Sprint 5 | Days 21-25 | Roles, Dashboard, Testing | 5 |
| Sprint 6 | Days 26-30 | Documentation & Polish | 5 |

---

## Sprint 1: Multi-Tenant Foundation (Days 1-5)

### Goal
Establish Company → Workshop hierarchy with tenant isolation.

### User Stories
- [ ] As a **system admin**, I can create companies with multiple workshops
- [ ] As a **user**, I can be assigned to multiple workshops
- [ ] As a **workshop manager**, I see only my workshop's data

### Deliverables
- `companies` table migration
- `workshops` table migration
- `user_workshops` pivot table
- Company & Workshop models
- Multi-tenant middleware
- Company/Workshop CRUD UI

### Acceptance Criteria
- [ ] Create company with billing info
- [ ] Add 3 workshops under one company
- [ ] Assign user to 2 workshops
- [ ] Switch active workshop in UI
- [ ] Jobs scoped to active workshop

---

## Sprint 2: Job Mode & Quotation (Days 6-10)

### Goal
Implement dual job modes and quotation system.

### User Stories
- [ ] As a **receptionist**, I can choose KEW.PA-10 or Normal mode when creating a job
- [ ] As a **manager**, I can generate quotations from job inspections
- [ ] As a **customer**, I can approve quotations online

### Deliverables
- `JobMode` enum (kewpa10, normal)
- Job mode selection UI
- `quotations` & `quotation_items` tables
- Quotation CRUD operations
- PDF quotation export
- Customer approval workflow

### Acceptance Criteria
- [ ] Create KEW.PA-10 job → See government fields
- [ ] Create Normal job → See simplified form
- [ ] Generate quote with 5 line items
- [ ] Export quote as PDF
- [ ] Customer approves quote via link

---

## Sprint 3: Invoice & Inventory (Days 11-15)

### Goal
Billing and parts inventory management.

### User Stories
- [ ] As an **accountant**, I can convert approved quotes to invoices
- [ ] As a **customer**, I can make payments via multiple methods
- [ ] As a **technician**, I can record parts used per job

### Deliverables
- `invoices`, `invoice_items`, `payments` tables
- `parts`, `part_movements`, `job_parts` tables
- Invoice generation from quotation
- Payment recording (Cash, Card, Transfer)
- Parts inventory CRUD
- Parts usage tracking

### Acceptance Criteria
- [ ] Convert quote to invoice
- [ ] Record payment (RM 500 cash)
- [ ] Track 3 parts used in job
- [ ] See low stock alert (<5 qty)
- [ ] Generate payment receipt PDF

---

## Sprint 4: Booking & Notifications (Days 16-20)

### Goal
Appointment system and customer notifications.

### User Stories
- [ ] As a **customer**, I can book appointments online
- [ ] As a **receptionist**, I see bookings on calendar
- [ ] As a **customer**, I receive SMS when job is complete

### Deliverables
- `appointments` table
- `notification_templates`, `notification_logs` tables
- Calendar view UI
- Online booking form (embeddable)
- SMS integration (Twilio)
- WhatsApp integration (WATI)

### Acceptance Criteria
- [ ] Customer books slot for Feb 10, 2pm
- [ ] Receptionist sees booking in calendar
- [ ] Convert booking to job
- [ ] Send SMS on job status change
- [ ] Customer receives completion alert

---

## Sprint 5: Roles, Dashboard, Testing (Days 21-25)

### Goal
Role templates, analytics dashboard, and automated tests.

### User Stories
- [ ] As an **owner**, I can see revenue across all workshops
- [ ] As a **manager**, I can assign flexible roles to users
- [ ] As a **developer**, I can run tests to verify features

### Deliverables
- Flexible role templates (Owner, Manager, Receptionist, Technician, Accountant)
- Government role template (5-role system)
- Dashboard widgets (revenue, jobs, inventory alerts)
- Feature tests for all modules
- Integration tests

### Acceptance Criteria
- [ ] Assign "Receptionist" role with custom permissions
- [ ] Dashboard shows RM 15k revenue this month
- [ ] All feature tests passing
- [ ] Multi-tenant isolation verified

---

## Sprint 6: Documentation & Polish (Days 26-30)

### Goal
Complete documentation and final polish.

### User Stories
- [ ] As a **developer**, I can read updated docs to understand the new system
- [ ] As a **sales person**, I can demo the dual-mode workflow

### Deliverables
- Updated docs (README, master-index, architecture)
- New guides (quotation, invoice, inventory)
- `11-multi-tenant-architecture.md`
- `12-job-mode-system.md`
- Demo script
- Deployment guide

### Acceptance Criteria
- [ ] All docs updated and linted
- [ ] Demo 1: Walk-in customer journey
- [ ] Demo 2: KEW.PA-10 government job
- [ ] Production deployment checklist
- [ ] User training materials

---

## Team Assignments

| Developer | Primary Focus |
|-----------|---------------|
| **Dev 1** | Backend (Models, Migrations, Services) |
| **Dev 2** | Frontend (Vue.js, UI Components) |
| **Dev 3** | Integration (Twilio, WATI, PDF) |
| **PM/Lead** | Documentation, Testing, Coordination |

---

## Daily Standup Template

**What did I do yesterday?**
- [ ] Task 1
- [ ] Task 2

**What will I do today?**
- [ ] Task 1
- [ ] Task 2

**Any blockers?**
- None / [Blocker description]

---

## Sprint Retrospective Template

**What went well?**
- 

**What could be improved?**
- 

**Action items for next sprint:**
- [ ] 

---

## Definition of Done

- [ ] Code written and reviewed
- [ ] Tests written and passing
- [ ] UI responsive on mobile
- [ ] Documentation updated
- [ ] Demo prepared
- [ ] Deployed to staging
