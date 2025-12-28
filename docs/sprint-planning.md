# Sprint Planning - Workshop Management Module

> **Last Updated**: 2025-12-28
> **Planning Horizon**: 6 Sprints (60 days)
> **Release Version**: 1.0.0

## Sprint Overview

The Workshop Management Module development is organized into 6 sprints, each 10 days long, focusing on progressive feature delivery and quality assurance.

## Sprint Summary

| Sprint | Duration | Focus Area | Key Deliverables |
|--------|----------|------------|------------------|
| Sprint 0 | Days 1-10 | Foundation | Module structure, database, core classes |
| Sprint 1 | Days 11-20 | Core Features | Complete CRUD, job management, basic UI |
| Sprint 2 | Days 21-30 | Advanced Features | Time tracking, parts management, calculations |
| Sprint 3 | Days 31-40 | Integration | Hooks, triggers, third-party integration |
| Sprint 4 | Days 41-50 | Reporting & Documents | Reports, PDFs, exports, templates |
| Sprint 5 | Days 51-60 | Polish & Release | Testing, documentation, deployment |

---

## Sprint 0: Foundation (Days 1-10)

### Sprint Goal

Establish solid foundation with core module infrastructure, database schema, and basic entity management.

### User Stories

#### US-001: Module Installation

**As a** Dolibarr administrator
**I want to** install the Workshop Management module
**So that** I can enable workshop functionality in my Dolibarr instance

**Acceptance Criteria:**

- Module appears in module list
- Can activate/deactivate module
- Database tables create automatically on activation
- No errors during installation
- Module icon displays correctly

**Story Points:** 3

#### US-002: Workshop Entity Management

**As a** workshop manager
**I want to** create and manage workshop entities
**So that** I can organize different workshop locations or types

**Acceptance Criteria:**

- Can create new workshop
- Can edit workshop details
- Can delete workshop (with confirmation)
- Can view list of all workshops
- Can search/filter workshops

**Story Points:** 5

#### US-003: Job Creation

**As a** workshop staff
**I want to** create repair jobs
**So that** I can track customer repair requests

**Acceptance Criteria:**

- Can create job with customer reference
- Can set job description and details
- Can assign job status
- Can link job to customer (third party)
- Job gets unique reference number

**Story Points:** 5

#### US-004: Database Schema

**As a** developer
**I want** properly structured database tables
**So that** data is stored efficiently and relationships are maintained

**Acceptance Criteria:**

- All tables follow Dolibarr naming conventions
- Proper indexes for performance
- Foreign keys maintain referential integrity
- Migration scripts work correctly
- Can rollback changes if needed

**Story Points:** 3

### Sprint Backlog

#### High Priority

- [ ] Create module descriptor class
- [ ] Implement database schema
- [ ] Develop Workshop entity class (CRUD)
- [ ] Develop WorkshopJob entity class (CRUD)
- [ ] Create workshop card UI page
- [ ] Create workshop list UI page

#### Medium Priority

- [ ] Implement WorkshopItem class
- [ ] Implement WorkshopTime class
- [ ] Create basic job UI page
- [ ] Set up admin configuration pages

#### Low Priority

- [ ] Write unit tests
- [ ] Create API documentation
- [ ] Set up development guides

### Definition of Done

- [ ] Code reviewed and approved
- [ ] All acceptance criteria met
- [ ] No critical bugs
- [ ] PHPDoc comments complete
- [ ] Database migrations tested
- [ ] Module activates/deactivates cleanly
- [ ] Code committed and pushed

### Sprint Metrics

- **Estimated Velocity:** 16 story points
- **Team Capacity:** 10 days
- **Focus Factor:** 0.8

---

## Sprint 1: Core Features (Days 11-20)

### Sprint Goal

Complete core workshop and job management features with full UI functionality and workflow management.

### User Stories

#### US-005: Job Status Workflow

**As a** workshop manager
**I want to** manage job status through workflow states
**So that** I can track job progress from intake to completion

**Acceptance Criteria:**

- Can change job status (New → In Progress → Completed → Invoiced)
- Status changes logged with timestamp
- Cannot skip required status steps
- Status displayed with visual indicators
- Can filter jobs by status

**Story Points:** 5

#### US-006: Customer Integration

**As a** workshop staff
**I want to** link jobs to customers
**So that** I can track customer history and contact information

**Acceptance Criteria:**

- Can select customer from Dolibarr third parties
- Customer info displays on job card
- Job appears on customer card
- Can create new customer from job form
- Customer contact details accessible

**Story Points:** 3

#### US-007: Job Assignment

**As a** workshop manager
**I want to** assign jobs to technicians
**So that** I can distribute workload and track responsibility

**Acceptance Criteria:**

- Can assign job to Dolibarr user
- Can reassign jobs
- Assigned technician receives notification
- Can filter jobs by assigned technician
- Assignment history tracked

**Story Points:** 5

#### US-008: Job Notes & Documentation

**As a** technician
**I want to** add notes and documentation to jobs
**So that** I can record findings and work performed

**Acceptance Criteria:**

- Can add public notes (visible to customer)
- Can add private notes (internal only)
- Notes timestamped and attributed to user
- Can attach files/photos
- Notes display in chronological order

**Story Points:** 3

### Sprint Backlog

#### High Priority

- [ ] Implement status workflow logic
- [ ] Create customer linking functionality
- [ ] Develop job assignment system
- [ ] Build notes/documentation UI
- [ ] Enhance job card page
- [ ] Create job board/kanban view

#### Medium Priority

- [ ] Add file attachment support
- [ ] Implement notifications system
- [ ] Create activity timeline
- [ ] Add job templates

#### Low Priority

- [ ] Job duplication feature
- [ ] Bulk status updates
- [ ] Advanced filtering

### Sprint Metrics

- **Estimated Velocity:** 16 story points
- **Dependencies:** Sprint 0 complete

---

## Sprint 2: Advanced Features (Days 21-30)

### Sprint Goal

Implement time tracking, parts management, and cost calculation features.

### User Stories

#### US-009: Time Tracking

**As a** technician
**I want to** record time spent on jobs
**So that** labor costs can be calculated and billed

**Acceptance Criteria:**

- Can start/stop timer for job
- Can manually enter time entries
- Can set hourly rate per entry
- Total time calculated automatically
- Time entries editable/deletable

**Story Points:** 8

#### US-010: Parts Management

**As a** workshop staff
**I want to** add parts used in repairs
**So that** parts costs are tracked and inventory updated

**Acceptance Criteria:**

- Can search and select products from catalog
- Can add multiple items to job
- Quantity and price per item captured
- Total parts cost calculated
- Stock levels update automatically

**Story Points:** 8

#### US-011: Cost Calculation

**As a** workshop manager
**I want** automatic cost calculations
**So that** I can see job profitability and pricing

**Acceptance Criteria:**

- Labor cost calculated from time entries
- Parts cost calculated from items used
- Total cost displayed prominently
- Can add additional charges
- Cost breakdown available

**Story Points:** 5

### Sprint Backlog

#### High Priority

- [ ] Build time tracking functionality
- [ ] Integrate with product catalog
- [ ] Implement cost calculation engine
- [ ] Create parts selection UI
- [ ] Add time entry interface

#### Medium Priority

- [ ] Stock integration
- [ ] Price calculation rules
- [ ] Discount handling
- [ ] Tax calculation

### Sprint Metrics

- **Estimated Velocity:** 21 story points
- **Dependencies:** Sprint 1 complete

---

## Sprint 3: Integration (Days 31-40)

### Sprint Goal

Integrate Workshop module with Dolibarr core modules (Projects, Invoices, Calendar).

### User Stories

#### US-012: Invoice Generation

**As a** workshop manager
**I want to** generate invoices from completed jobs
**So that** customers can be billed for services and parts

**Acceptance Criteria:**

- Can create invoice from job
- Labor and parts transfer to invoice lines
- Customer details pre-filled
- Invoice status syncs with job
- Multiple invoices per job supported

**Story Points:** 8

#### US-013: Project Integration

**As a** project manager
**I want to** link workshops jobs to projects
**So that** I can track maintenance as part of larger projects

**Acceptance Criteria:**

- Can select project when creating job
- Job appears in project tasks
- Time and costs sync to project
- Can filter jobs by project
- Project dashboard shows workshop activity

**Story Points:** 5

#### US-014: Calendar Integration

**As a** workshop scheduler
**I want** jobs to appear in calendar
**So that** I can plan and schedule work effectively

**Acceptance Criteria:**

- Jobs display in Dolibarr agenda
- Can drag and drop to reschedule
- Technician calendars show assigned jobs
- Can create jobs from calendar
- Status changes reflect in calendar

**Story Points:** 5

### Sprint Backlog

#### High Priority

- [ ] Invoice generation functionality
- [ ] Project linking
- [ ] Calendar integration
- [ ] Implement hooks for third-party cards
- [ ] Create trigger events

#### Medium Priority

- [ ] Email notifications
- [ ] Agenda event creation
- [ ] Project dashboard widgets

### Sprint Metrics

- **Estimated Velocity:** 18 story points
- **Dependencies:** Sprint 2 complete

---

## Sprint 4: Reporting & Documents (Days 41-50)

### Sprint Goal

Implement comprehensive reporting and document generation capabilities.

### User Stories

#### US-015: Job Reports

**As a** workshop manager
**I want** detailed reports on workshop activity
**So that** I can analyze performance and make data-driven decisions

**Acceptance Criteria:**

- Can generate job status report
- Can generate revenue report
- Can generate technician performance report
- Can filter by date range
- Can export to PDF/Excel

**Story Points:** 8

#### US-016: Work Order PDF

**As a** workshop staff
**I want to** print work orders
**So that** technicians have paper references for jobs

**Acceptance Criteria:**

- Can generate PDF work order
- Includes all job details
- Includes parts list
- Professional formatting
- Can customize template

**Story Points:** 5

#### US-017: Customer Service History

**As a** customer service
**I want to** view complete service history
**So that** I can assist customers and track patterns

**Acceptance Criteria:**

- Can view all jobs for a customer
- Timeline view of service history
- Summary statistics available
- Can export history
- Search within history

**Story Points:** 3

### Sprint Backlog

#### High Priority

- [ ] Create report generation system
- [ ] Build PDF templates
- [ ] Implement service history view
- [ ] Add export functionality

#### Medium Priority

- [ ] Custom report builder
- [ ] Chart/graph visualizations
- [ ] Email report scheduling

### Sprint Metrics

- **Estimated Velocity:** 16 story points
- **Dependencies:** Sprint 3 complete

---

## Sprint 5: Polish & Release (Days 51-60)

### Sprint Goal

Complete testing, documentation, bug fixes, and prepare for production release.

### User Stories

#### US-018: User Documentation

**As a** end user
**I want** comprehensive documentation
**So that** I can learn to use the module effectively

**Acceptance Criteria:**

- User manual complete
- Video tutorials available
- FAQ section created
- Help tooltips in UI
- Quick start guide available

**Story Points:** 5

#### US-019: Module Performance

**As a** system administrator
**I want** the module to perform efficiently
**So that** it doesn't slow down Dolibarr

**Acceptance Criteria:**

- Page load times under 2 seconds
- Database queries optimized
- No N+1 query problems
- Large dataset handling tested
- Caching implemented where appropriate

**Story Points:** 5

#### US-020: Security Audit

**As a** security officer
**I want** the module to be secure
**So that** customer data is protected

**Acceptance Criteria:**

- No SQL injection vulnerabilities
- No XSS vulnerabilities
- Proper permission checks
- Input validation on all forms
- Security audit passed

**Story Points:** 3

### Sprint Backlog

#### High Priority

- [ ] Complete test coverage
- [ ] Fix all critical bugs
- [ ] Performance optimization
- [ ] Security audit and fixes
- [ ] Documentation completion

#### Medium Priority

- [ ] UI polish and refinement
- [ ] Accessibility improvements
- [ ] Mobile responsiveness
- [ ] Translation updates

#### Low Priority

- [ ] Feature requests for v1.1
- [ ] Enhancement ideas
- [ ] Community feedback

### Sprint Metrics

- **Estimated Velocity:** 13 story points
- **Dependencies:** Sprint 4 complete

---

## Release Criteria

### Version 1.0.0 Release Requirements

- [ ] All Sprint 0-5 user stories completed
- [ ] Zero critical bugs
- [ ] Less than 5 major bugs
- [ ] Test coverage > 80%
- [ ] All documentation complete
- [ ] Security audit passed
- [ ] Performance benchmarks met
- [ ] Compatible with Dolibarr 12.0+
- [ ] Installation tested on fresh Dolibarr instance
- [ ] Upgrade path tested
- [ ] User acceptance testing completed

## Risk Management

### Identified Risks

| Risk | Probability | Impact | Mitigation |
|------|------------|--------|------------|
| Dolibarr API changes | Medium | High | Stay on LTS version, monitor changelogs |
| Database migration issues | Medium | High | Extensive testing, rollback scripts |
| Performance with large datasets | Low | Medium | Early performance testing |
| Third-party module conflicts | Medium | Low | Isolation, namespace properly |
| Scope creep | High | Medium | Strict sprint planning, prioritization |

## Success Metrics

### Sprint Success Criteria

- Velocity within 10% of estimate
- All high-priority items completed
- No sprint rollover of critical items
- Team morale positive
- Technical debt managed

### Project Success Criteria

- Launch on schedule (Day 60)
- User satisfaction > 4/5
- Bug report rate < 5/week post-launch
- Performance benchmarks met
- Adoption rate > 50% of target users

---

**Sprint Master**: [TBD]
**Product Owner**: [TBD]
**Last Updated**: 2025-12-28
