# Workshop Management System - Rework TODO

> **Start Date**: 2026-02-03  
> **Target Completion**: 2026-03-04 (30 days)  
> **Status**: 🚧 In Planning

---

## Phase 1: Multi-Tenant Foundation ✨

### Database
- [ ] Create migration: `create_companies_table.php`
- [ ] Create migration: `create_workshops_table.php`
- [ ] Create migration: `create_user_workshops_table.php`
- [ ] Create migration: `add_company_to_users.php`
- [ ] Create migration: `add_company_to_customers.php`
- [ ] Create migration: `add_workshop_to_jobs.php`

### Models
- [ ] Create `Company` model with relationships
- [ ] Create `Workshop` model with relationships
- [ ] Update `User` model (add company/workshop relations)
- [ ] Update `Customer` model (add company scope)
- [ ] Update `WorkshopJob` model (add workshop scope)

### Services
- [ ] Create `MultiTenantService.php`
- [ ] Create `WorkshopScopeService.php`

### Middleware
- [ ] Create `EnsureCompanyContext.php`
- [ ] Create `EnsureWorkshopContext.php`
- [ ] Register middleware in `Kernel.php`

### Frontend
- [ ] Create `Companies/Index.vue`
- [ ] Create `Companies/Create.vue`
- [ ] Create `Companies/Edit.vue`
- [ ] Create `Workshops/Index.vue`
- [ ] Create `Workshops/Create.vue`
- [ ] Create `Workshops/Edit.vue`
- [ ] Add workshop selector to AppSidebar

### Backend Routes
- [ ] Add Company routes (CRUD)
- [ ] Add Workshop routes (CRUD)
- [ ] Add User-Workshop assignment routes

---

## Phase 2: Job Mode System 🎯

### Database
- [ ] Create `JobMode` enum file
- [ ] Create migration: `add_job_mode_to_workshop_jobs.php`
- [ ] Create migration: `remove_dynamic_workflow_columns.php`

### Backend
- [ ] Create `JobModeService.php` (status transitions)
- [ ] Update `JobService.php` (job mode selection)
- [ ] Update `JobController.php` (mode-aware CRUD)

### Frontend
- [ ] Create job mode selector component
- [ ] Update `Jobs/Create.vue` (conditional fields)
- [ ] Update `Jobs/Show.vue` (mode-specific display)

### Remove Dynamic Workflow
- [ ] Drop routes for workflows CRUD
- [ ] Remove `WorkflowController.php`
- [ ] Remove `WorkflowStatusController.php`
- [ ] Remove workflow Vue pages
- [ ] Update navigation sidebar

---

## Phase 3A: Quotation System 💰

### Database
- [ ] Create migration: `create_quotations_table.php`
- [ ] Create migration: `create_quotation_items_table.php`

### Models
- [ ] Create `Quotation` model
- [ ] Create `QuotationItem` model

### Backend
- [ ] Create `QuotationController.php`
- [ ] Create `QuotationService.php`
- [ ] PDF export service (Laravel Dompdf)

### Frontend
- [ ] Create `Quotations/Index.vue`
- [ ] Create `Quotations/Create.vue`
- [ ] Create `Quotations/Show.vue`
- [ ] Create quotation item builder component

---

## Phase 3B: Invoice & Billing 🧾

### Database
- [ ] Create migration: `create_invoices_table.php`
- [ ] Create migration: `create_invoice_items_table.php`
- [ ] Create migration: `create_payments_table.php`

### Models
- [ ] Create `Invoice` model
- [ ] Create `InvoiceItem` model
- [ ] Create `Payment` model

### Backend
- [ ] Create `InvoiceController.php`
- [ ] Create `PaymentController.php`
- [ ] Create `InvoiceService.php`
- [ ] Invoice PDF export

### Frontend
- [ ] Create `Invoices/Index.vue`
- [ ] Create `Invoices/Show.vue`
- [ ] Create `Payments/Create.vue`
- [ ] Payment method selector component

---

## Phase 3C: Parts & Inventory 📦

### Database
- [ ] Create migration: `create_parts_table.php`
- [ ] Create migration: `create_part_movements_table.php`
- [ ] Create migration: `create_job_parts_table.php`

### Models
- [ ] Create `Part` model
- [ ] Create `PartMovement` model
- [ ] Create `JobPart` model

### Backend
- [ ] Create `PartController.php`
- [ ] Create `InventoryService.php`
- [ ] Low stock alert job (scheduler)

### Frontend
- [ ] Create `Parts/Index.vue`
- [ ] Create `Parts/Create.vue`
- [ ] Create `Parts/Edit.vue`
- [ ] Parts usage component (in job form)

---

## Phase 3D: Appointment Booking 📅

### Database
- [ ] Create migration: `create_appointments_table.php`

### Models
- [ ] Create `Appointment` model

### Backend
- [ ] Create `AppointmentController.php`
- [ ] Create `BookingController.php` (public endpoint)

### Frontend
- [ ] Create `Appointments/Calendar.vue`
- [ ] Create `Appointments/Create.vue`
- [ ] Create public booking widget

---

## Phase 3E: Notifications 📱

### Database
- [ ] Create migration: `create_notification_templates_table.php`
- [ ] Create migration: `create_notification_logs_table.php`

### Backend
- [ ] Create `NotificationService.php`
- [ ] Twilio SMS integration
- [ ] WATI WhatsApp integration
- [ ] Create notification templates seeder

### Jobs
- [ ] Create `SendJobStatusNotification.php` job
- [ ] Create `SendAppointmentReminder.php` job

---

## Phase 4: Role System 👥

### Backend
- [ ] Create role templates seeder
- [ ] Update permissions for new features
- [ ] Role assignment UI

### Frontend
- [ ] Update role selector
- [ ] Permission matrix UI

---

## Phase 5: Dashboard & Analytics 📊

### Backend
- [ ] Create `DashboardController.php`
- [ ] Create analytics queries

### Frontend
- [ ] Create dashboard widgets:
  - [ ] Revenue chart
  - [ ] Jobs overview
  - [ ] Pending quotes
  - [ ] Outstanding invoices
  - [ ] Inventory alerts
  - [ ] Technician load

---

## Phase 6: Documentation 📚

### Update Existing Docs
- [x] `docs/README.md`
- [x] `docs/master-index.md`
- [x] `docs/ARCHITECTURE_CAPABILITY_OVERVIEW.md` (header)
- [ ] `docs/02-architecture/README.md`
- [ ] `docs/06-user-guide/01-user-roles.md`

### Create New Docs
- [ ] `docs/02-architecture/11-multi-tenant-architecture.md`
- [ ] `docs/02-architecture/12-job-mode-system.md`
- [ ] `docs/06-user-guide/07-quotation-guide.md`
- [ ] `docs/06-user-guide/08-invoice-guide.md`
- [ ] `docs/06-user-guide/09-inventory-guide.md`
- [ ] `docs/06-user-guide/10-booking-guide.md`

### Sprint Docs
- [x] `docs/04-sprints/03-sprint-rework-overview.md`
- [ ] Daily sprint logs

---

## Testing ✅

### Unit Tests
- [ ] Company model tests
- [ ] Workshop model tests
- [ ] Job mode service tests
- [ ] Quotation service tests
- [ ] Invoice service tests

### Feature Tests
- [ ] Multi-tenant isolation
- [ ] Job mode workflows
- [ ] Quote to invoice conversion
- [ ] Payment recording
- [ ] Inventory tracking

### Integration Tests
- [ ] SMS notification
- [ ] WhatsApp notification
- [ ] PDF generation

---

## Deployment 🚀

- [ ] Update `.env.example`
- [ ] Create migration guide
- [ ] Data migration script
- [ ] Production deployment checklist
- [ ] Rollback plan

---

## Progress Tracking

**Total Tasks**: ~80  
**Completed**: 3  
**Progress**: 3.75%

---

**Last Updated**: 2026-02-02
