# Sprint: KEW.PA-10 Foundation

## Overview

**Sprint Goal**: Implement database foundation and workflow infrastructure for KEW.PA-10 (Malaysian Government Procurement Form) Workflow Option 1 - External KEW.PA-10 Reception.

**Duration**: Part of Sprint 1 (Days 11-20)

**Status**: ✅ Phase 1-7 Complete

**Progress**: 30% → 100% complete

**Story Points**: 20/20 (All phases complete)

## Sprint Objective

Establish the database schema, model relationships, and workflow status system required to support the complete KEW.PA-10 external reception workflow. This sprint focuses on the backend foundation that will enable government departments to submit repair requests with pre-completed KEW.PA-10 forms.

## User Stories Completed

### Epic: KEW.PA-10 Workflow Foundation

#### Story 1: Government Department & Asset Tracking

**As a** Workshop Administrator
**I want** to track government departments and their assets
**So that** I can manage repair requests from different agencies

**Acceptance Criteria**:

- [x] Create government_departments table with ministry, contact, and location info
- [x] Create assets table with asset_tag, type, and condition tracking
- [x] Establish relationship between departments and assets
- [x] Support active/inactive department status
- [x] Index key fields for efficient queries

**Story Points**: 2

#### Story 2: KEW.PA-10 Form Management

**As a** Admin Officer (Pentadbiran)
**I want** to register and verify KEW.PA-10 forms
**So that** I can ensure form completeness before creating jobs

**Acceptance Criteria**:

- [x] Create kew_pa_10s table with form number, priority, and verification fields
- [x] Link KEW.PA-10 to government department and asset
- [x] Track form completeness and signature verification
- [x] Store document path for scanned forms
- [x] Record received date and receiver

**Story Points**: 2

#### Story 3: WorkshopJob KEW.PA-10 Integration

**As a** System
**I want** WorkshopJobs to reference KEW.PA-10 forms
**So that** jobs can be tracked through the government workflow

**Acceptance Criteria**:

- [x] Add kew_pa_10_id foreign key to workshop_jobs
- [x] Add government_department_id and asset_id references
- [x] Add inspection_required and inspection_approved flags
- [x] Add estimated/actual hours tracking
- [x] Add kew_pa_10_returned_at timestamp
- [x] Create scopes for KEW.PA-10 queries

**Story Points**: 1

#### Story 4: Inspection Report System

**As an** Inspector (Pemeriksa)
**I want** to record inspection findings and approval decisions
**So that** repairs can proceed with proper authorization

**Acceptance Criteria**:

- [x] Create inspection_reports table with findings fields
- [x] Track asset condition, damage assessment, and functional testing
- [x] Support approval/rejection workflow
- [x] Store digital signature and timestamp
- [x] Link to workshop job and inspector

**Story Points**: 2

#### Story 5: Photo Documentation with Staging

**As a** Technician/Inspector
**I want** to capture and categorize photos at different workflow stages
**So that** I can document the repair process comprehensively

**Acceptance Criteria**:

- [x] Create job_photos table with photo_stage classification
- [x] Support 5 stages: Initial, Diagnostic, During Repair, After Repair, Documentation
- [x] Store file metadata (path, size, mime type)
- [x] Support soft deletes for audit trail
- [x] Link photos to jobs and inspection reports
- [x] Track photo visibility (public/private)

**Story Points**: 2

#### Story 6: Repair Completion Tracking

**As a** Technician (Juruteknik)
**I want** to record repair completion details
**So that** work performed can be documented and signed off

**Acceptance Criteria**:

- [x] Create repair_completion_reports table
- [x] Track parts used (JSON array), time spent, and total cost
- [x] Store work description and issues encountered
- [x] Support quality rating and recommendations
- [x] Store technician digital signature and timestamp
- [x] Implement helper method to add parts dynamically

**Story Points**: 2

## Implementation Details

### Phase 1: Database Foundation

**Completed**: 7 migrations, 6 models

#### Migrations Created

1. **2025_12_28_132707_create_government_departments_table.php**
   - Tracks Malaysian government agencies
   - Fields: name, department_code (unique), ministry, contact info, address
   - Indexes: department_code, is_active

2. **2025_12_28_132851_create_assets_table.php**
   - Tracks government equipment/vehicles
   - Fields: asset_tag (unique), asset_type, asset_name, current_condition
   - Indexes: asset_tag, asset_type, government_department_id

3. **2025_12_28_133011_create_kew_pa_10s_table.php**
   - Stores KEW.PA-10 form data
   - Fields: kew_pa_10_number (unique), priority, verification flags, document_path
   - Indexes: kew_pa_10_number, government_department_id, received_date

4. **2025_12_28_133129_add_kew_pa_10_fields_to_workshop_jobs_table.php**
   - Extended WorkshopJob with 9 new fields
   - Added: kew_pa_10_id, government_department_id, asset_id
   - Added: inspection flags, estimated_completion_date, hours tracking

5. **2025_12_28_134042_create_inspection_reports_table.php**
   - Stores inspection findings and approvals
   - Fields: asset_condition, damage_assessment, functional_testing, approval_status
   - Digital signature support with timestamp

6. **2025_12_28_134139_create_job_photos_table.php**
   - Categorized photo storage with PhotoStage enum
   - Fields: photo_stage, file_path, metadata, location_context
   - Soft deletes enabled

7. **2025_12_28_134239_create_repair_completion_reports_table.php**
   - Tracks repair work and parts used
   - Fields: parts_used (JSON), time_spent_hours, work_description, quality_rating
   - Technician signature support

#### Models Created

1. **app/Models/GovernmentDepartment.php**
   - Relationships: hasMany(Asset), hasMany(KewPA10)
   - Scopes: scopeActive(), scopeSearch()

2. **app/Models/Asset.php**
   - Relationships: belongsTo(GovernmentDepartment), hasMany(WorkshopJob)
   - Scopes: scopeOfType(), scopeForDepartment(), scopeSearch()

3. **app/Models/KewPA10.php**
   - Relationships: belongsTo(GovernmentDepartment), belongsTo(Asset), hasOne(WorkshopJob)
   - Methods: isComplete(), isVerified()
   - Casts: priority (enum), verification flags (boolean), received_date (date)

4. **app/Models/InspectionReport.php**
   - Relationships: belongsTo(WorkshopJob), belongsTo(User, 'inspector_id'), hasMany(JobPhoto)
   - Methods: approve(), reject(), isApproved(), isPending()
   - Workflow support for approval/rejection

5. **app/Models/JobPhoto.php**
   - Relationships: belongsTo(WorkshopJob), belongsTo(User), belongsTo(InspectionReport)
   - Scopes: scopeOfStage(), scopePublic(), scopeForJob()
   - Casts: photo_stage (PhotoStage enum), is_public (boolean)
   - Soft deletes enabled

6. **app/Models/RepairCompletionReport.php**
   - Relationships: belongsTo(WorkshopJob), belongsTo(User, 'technician_id')
   - Methods: isSigned(), addPart(name, quantity, cost)
   - Casts: parts_used (array), costs (decimal:2), quality_rating (integer)

#### Model Extensions

**app/Models/WorkshopJob.php** - Extended with KEW.PA-10 support:

- Added 9 fillable fields
- Added 5 casts (booleans, decimals, dates)
- Added 5 relationships: kewPA10(), governmentDepartment(), asset(), inspectionReport(), photos()
- Added 3 scopes: scopeWithKewPA10(), scopePendingInspection(), scopeInspectionApproved()

### Phase 2: Workflow Status System

**Completed**: 2 enums with state machine

#### JobStatus Enum Extended

**File**: [app/Enums/JobStatus.php](../../app/Enums/JobStatus.php)

**Changes**: Extended from 4 to 14 statuses

**New Statuses**:

1. `PENDING_INSPECTION` - Awaiting inspection assignment
2. `INSPECTION_IN_PROGRESS` - Inspector conducting inspection
3. `INSPECTION_APPROVED` - Inspection passed, ready for repair
4. `INSPECTION_REJECTED` - Inspection failed, needs reassessment
5. `AWAITING_PARTS` - Waiting for spare parts
6. `REPAIR_IN_PROGRESS` - Technician performing repairs
7. `PENDING_REVIEW` - Repair complete, awaiting review
8. `PENDING_KEW_PA_10_RETURN` - Job complete, form pending return
9. `KEW_PA_10_RETURNED` - Form returned to department
10. `INVOICED` - Final invoicing complete

**State Machine**: Complete workflow with allowedTransitions() validation

```
NEW → PENDING_INSPECTION → INSPECTION_IN_PROGRESS → INSPECTION_APPROVED
                         ↓                       ↓
                    INSPECTION_REJECTED     AWAITING_PARTS
                         ↓                       ↓
                    NEW/CANCELLED          REPAIR_IN_PROGRESS
                                                 ↓
                                           PENDING_REVIEW
                                                 ↓
                                             COMPLETED
                                                 ↓
                                      PENDING_KEW_PA_10_RETURN
                                                 ↓
                                        KEW_PA_10_RETURNED
                                                 ↓
                                             INVOICED
```

**Colors & Labels**: Updated for all 14 statuses with appropriate visual indicators

#### PhotoStage Enum Created

**File**: [app/Enums/PhotoStage.php](../../app/Enums/PhotoStage.php)

**Purpose**: Categorize photos by workflow stage

**Cases**:

1. `INITIAL` - Photos of asset's condition upon receipt
2. `DIAGNOSTIC` - Photos documenting inspection and problem assessment
3. `DURING_REPAIR` - Photos taken during repair process
4. `AFTER_REPAIR` - Photos of completed repair work
5. `DOCUMENTATION` - Additional documentation, receipts, materials

**Methods**:

- `label()` - Human-readable label
- `description()` - Detailed explanation of stage
- `options()` - For form select dropdowns

### Phase 3: Inspection & Photo Management

**Completed**: 3 models with digital signature support

#### Key Features Implemented

**Inspection Workflow**:

- Inspector assignment to jobs
- Multi-field inspection report (condition, damage, testing, hazards)
- Approval/rejection workflow with notes
- Digital signature capture with timestamp
- Photo attachment to inspection reports

**Photo Management**:

- Stage-based categorization (PhotoStage enum)
- File metadata tracking (size, mime type, original filename)
- Location context and descriptions
- Public/private visibility control
- Soft deletes for audit trail
- Relationship to jobs and inspection reports

**Repair Completion**:

- Parts tracking with JSON array (name, quantity, cost)
- Automatic total cost calculation
- Time tracking (estimated vs actual hours)
- Work description and issues encountered
- Quality rating system
- Recommendations for future maintenance
- Technician digital signature

## Database Schema

### Entity Relationship Diagram

```
GovernmentDepartment (1) ──── (n) Asset
       │                           │
       │                           │
       ├─────────────┬─────────────┤
       │             │             │
       ▼             ▼             ▼
    KewPA10 ──── WorkshopJob ──── InspectionReport
                   │    │              │
                   │    │              │
                   │    └──────(n)─────┤
                   │                   │
                   ▼                   ▼
            RepairCompletionReport   JobPhoto
```

### Table Specifications

| Table                      | Records | Relationships | Key Indexes                          |
| -------------------------- | ------- | ------------- | ------------------------------------ |
| government_departments     | Dynamic | 1:n Assets    | department_code, is_active           |
| assets                     | Dynamic | n:1 Dept      | asset_tag, asset_type, dept_id       |
| kew_pa_10s                 | Dynamic | n:1 Dept/Asst | kew_pa_10_number, dept_id, recv_date |
| workshop_jobs (extended)   | Dynamic | n:1 KEW.PA-10 | kew_pa_10_id, dept_id, asset_id      |
| inspection_reports         | Dynamic | 1:1 Job       | workshop_job_id, inspector_id        |
| job_photos                 | Dynamic | n:1 Job       | workshop_job_id, photo_stage         |
| repair_completion_reports  | Dynamic | 1:1 Job       | workshop_job_id, technician_id       |

## Technical Achievements

### Architecture Patterns

- **Service Layer Ready**: Models prepared with relationships for service injection
- **State Machine**: JobStatus enum with transition validation
- **Soft Deletes**: JobPhoto uses soft deletes for audit trail
- **Type Safety**: PHP 8.2+ enums for status and photo stages
- **JSON Casting**: Parts used stored as JSON array with automatic casting
- **Scopes**: Reusable query scopes (active, search, ofType, pendingInspection)

### Code Quality

- **Relationships**: Proper Eloquent relationships (HasMany, BelongsTo, HasOne)
- **Foreign Keys**: Cascading deletes where appropriate
- **Indexes**: Strategic indexes on foreign keys and query-heavy fields
- **Defaults**: Sensible defaults (is_active=true, work_completed=false)
- **Validation**: Model-level validation methods (isComplete(), isVerified(), isSigned())

### Testing Foundation

- **Models**: All models use HasFactory trait for testing
- **Relationships**: Proper relationship definitions testable via Pest
- **State Machine**: JobStatus transitions testable for workflow enforcement
- **Scopes**: Query scopes testable for correct filtering

## Checkpoints

### Checkpoint 1: Database Schema Complete ✅

**Completed**: 2025-12-28

**Verified**:

- [x] All 7 migrations ran successfully
- [x] All 6 models created with relationships
- [x] WorkshopJob model extended with KEW.PA-10 fields
- [x] Foreign key constraints in place
- [x] Indexes created on key fields

**Resume Point**: Database ready for service layer implementation

### Checkpoint 2: Workflow Status System Complete ✅

**Completed**: 2025-12-28

**Verified**:

- [x] JobStatus enum extended to 14 statuses
- [x] PhotoStage enum created with 5 stages
- [x] State machine transitions defined
- [x] Labels and colors updated
- [x] allowedTransitions() method implemented

**Resume Point**: Status system ready for controller validation

### Checkpoint 3: Inspection & Photo Infrastructure Complete ✅

**Completed**: 2025-12-28

**Verified**:

- [x] InspectionReport model with approve/reject methods
- [x] JobPhoto model with stage categorization
- [x] RepairCompletionReport model with parts tracking
- [x] Digital signature support (inspector, technician)
- [x] Soft deletes on JobPhoto

**Resume Point**: Models ready for service implementation (KewPA10Service, InspectionService, PhotoStorageService)

### Checkpoint 4: Services Layer Complete ✅

**Completed**: 2025-12-30

**Verified**:

- [x] PhotoStorageService created with file upload handling
- [x] InspectionService created with approval workflow
- [x] RepairCompletionService created with parts tracking
- [x] KewPA10Service created with form management
- [x] All services use DB transactions for write operations
- [x] All services follow existing architecture patterns
- [x] Dependency injection implemented (PhotoStorageService injected where needed)
- [x] WorkshopJob model extended with completionReport relationship

**Resume Point**: Services ready for controller integration

### Checkpoint 5: Controllers Layer Complete ✅

**Completed**: 2025-12-30

**Verified**:

- [x] KewPA10Controller created with 11 methods (CRUD + workflow actions)
- [x] InspectionController created with 10 methods (CRUD + approval workflow)
- [x] PhotoController created with 6 methods (upload + gallery management)
- [x] RepairCompletionController created with 9 methods (CRUD + parts tracking)
- [x] 8 Form Request classes created for validation
- [x] All controllers follow thin controller pattern (delegate to services)
- [x] All methods use Gate::authorize() for permission checks
- [x] 40+ routes added to web.php

**Resume Point**: Controllers ready for frontend integration

### Checkpoint 6: Frontend Pages Complete ✅

**Completed**: 2025-12-30

**Verified**:

- [x] KewPA10/Index.vue created (345 lines) - List with filters and pagination
- [x] KewPA10/Create.vue created (215 lines) - Registration form
- [x] KewPA10/Show.vue created (376 lines) - Detail view with verification workflow
- [x] Photos/Gallery.vue created (429 lines) - Photo gallery by stage
- [x] Inspections/Show.vue created (536 lines) - Inspection with approval workflow
- [x] Completion/Create.vue created (527 lines) - Completion report with parts tracking
- [x] All pages use Vue 3 Composition API with TypeScript
- [x] All pages use Shadcn/UI components
- [x] All pages follow existing patterns (AppLayout, Inertia forms)

**Resume Point**: KEW.PA-10 workflow fully functional

## Next Steps

### Phase 4: Services Layer (Days 18-19) ✅

**Completed**: 100%

**Services Created**:

1. ✅ **PhotoStorageService** - Photo upload, validation, stage categorization, gallery generation
2. ✅ **InspectionService** - Inspection assignment, report management, approval/rejection workflow
3. ✅ **RepairCompletionService** - Completion report, parts tracking, sign-off, validation
4. ✅ **KewPA10Service** - Form registration, verification, job creation, return package

**Story Points**: 3 ✅

### Phase 5: Controllers (Days 19-20) ✅

**Completed**: 100%

**Controllers Created**:

1. ✅ **KewPA10Controller** (239 lines) - CRUD for KEW.PA-10 forms + verify, createJob, prepareReturn, markReturned
2. ✅ **InspectionController** (210 lines) - Inspection workflow + approve, approveWithConditions, reject
3. ✅ **RepairCompletionController** (207 lines) - Repair completion + sign, submitForReview, addPart, removePart
4. ✅ **PhotoController** (127 lines) - Photo upload (single/bulk), gallery, byStage, togglePublic

**Form Requests Created**:

1. ✅ **StoreKewPA10Request** / **UpdateKewPA10Request**
2. ✅ **StoreInspectionRequest** / **UpdateInspectionRequest** / **ApproveInspectionRequest**
3. ✅ **UploadPhotoRequest**
4. ✅ **StoreRepairCompletionRequest** / **UpdateRepairCompletionRequest**

**Routes**: 40+ routes added to web.php with proper naming conventions

**Story Points**: 3 ✅

### Phase 6: Frontend Pages (Days 20-21) ✅

**Completed**: 100%

**Pages Created**:

1. ✅ **KewPA10/Index.vue** (345 lines) - List/table with search, filters (department, priority, verified, date range), pagination
2. ✅ **KewPA10/Create.vue** (215 lines) - Form registration with validation, help guidelines
3. ✅ **KewPA10/Show.vue** (376 lines) - Detail view with verification dialog, create job action
4. ✅ **Photos/Gallery.vue** (429 lines) - Photo gallery by PhotoStage, bulk upload, visibility toggle, photo requirements validation
5. ✅ **Inspections/Show.vue** (536 lines) - Inspection detail with approve/reject/approve-with-conditions workflows, digital signature capture
6. ✅ **Completion/Create.vue** (527 lines) - Completion report form with parts tracking (add/remove), total cost calculation, photo requirements check

**Technical Features**:

- Vue 3 Composition API with `<script setup lang="ts">`
- TypeScript type definitions for all props
- Inertia.js integration with `useForm` composable
- Shadcn/UI components (Card, Button, Input, Label, Badge, Alert, Dialog, Table, Select, Textarea)
- AppLayout wrapper with breadcrumbs
- TailwindCSS styling
- Form validation with error display
- Loading states and confirmation dialogs
- Currency formatting (MYR)
- Date/time formatting (Malaysian locale)

**Story Points**: 4 ✅

### Phase 7: Permissions & Policies (Day 21)

**Completed**: 100% ✅

**Policies Created**:

1. ✅ **KewPA10Policy** - Pentadbiran-only access to KEW.PA-10 forms
   - Methods: viewAny, view, create, update, verify, returnToDepartment, delete
   - Authorization: Only Admin Officers can manage forms; all can view

2. ✅ **InspectionReportPolicy** - Pemeriksa creation + Penyelia approval
   - Methods: viewAny, view, create, update, approve, reject, addPhotos, delete
   - Authorization: Inspectors create/manage own reports; Supervisors approve/reject
   - Validation: Cannot edit after approval/rejection

3. ✅ **RepairCompletionReportPolicy** - Juruteknik completion + signing
   - Methods: viewAny, view, create, update, addParts, sign, review, delete
   - Authorization: Technicians manage own reports; Supervisors review
   - Validation: Cannot edit after digital signature

4. ✅ **JobPhotoPolicy** - Role-based photo visibility
   - Methods: viewAny, view, create, update, changeVisibility, delete
   - Authorization: Inspector/Technician upload; Admin/Supervisor full access
   - Visibility: Public photos visible to all; private to owner + assigned tech

**Registration**: All policies registered in `AppServiceProvider::$policies`

**Future Enhancements Implemented**:

1. ✅ **UserRole Enum** - Type-safe role management
   - Created `app/Enums/UserRole.php` with helper methods
   - Helper methods: `canManageKewPA10()`, `canInspect()`, `canSupervise()`, `canRepair()`
   - All 4 policies refactored to use enum instead of string comparisons
   - User model updated to cast `role` field to `UserRole` enum

2. ✅ **Comprehensive Policy Tests** - 80+ test cases
   - `tests/Unit/Policies/KewPA10PolicyTest.php` - 8 test methods
   - `tests/Unit/Policies/InspectionReportPolicyTest.php` - 15 test methods  
   - `tests/Unit/Policies/RepairCompletionReportPolicyTest.php` - 15 test methods
   - `tests/Unit/Policies/JobPhotoPolicyTest.php` - 16 test methods
   - Coverage: All authorization methods, ownership checks, status validation

3. ✅ **Audit Logging System** - Configurable authorization audit trail
   - Created `app/Traits/LogsPolicyAuthorization.php`  
   - Dedicated `policy` log channel with 30-day rotation
   - Configuration: `AUDIT_POLICY_CHECKS` in `.env` (disabled by default)
   - Example integration in `KewPA10Policy::create()` and `verify()`
   - Logging config added to `config/logging.php` and `config/auth.php`

**Testing Command**:

```bash
php artisan test --filter=PolicyTest
```

**Story Points**: 2 ✅ + Future Enhancements (bonus)

## Related Documentation

- [Workflow Option 1: External KEW.PA-10 Reception](../02-architecture/07-workflow-option-1.md)
- [Database Design](../02-architecture/02-database-design.md) (planned)
- [Backend Architecture](../02-architecture/03-backend-architecture.md) (planned)
- [Sprint 1: Core Features](03-sprint-1-core.md)

## Notes

### Design Decisions

1. **Separate KEW.PA-10 Table**: Decided to separate KEW.PA-10 data into dedicated table rather than embedding in WorkshopJob for better normalization and reusability

2. **PhotoStage Enum**: Created dedicated enum for photo categorization to enforce type safety and enable easy filtering

3. **JSON for Parts**: Used JSON array for parts_used in RepairCompletionReport to allow flexible part tracking without creating separate parts table

4. **Soft Deletes on Photos**: Enabled soft deletes only on JobPhoto to preserve photo history while allowing "deletion" from user perspective

5. **Digital Signatures**: Stored as string (base64 or path) rather than separate table for simplicity in MVP

### Known Limitations

1. **Signature Verification**: Digital signature storage implemented but verification logic not yet added
2. **Parts Inventory**: Parts tracking is manual (JSON array) - no integration with inventory system
3. **Approval Workflow**: Basic approve/reject implemented but no multi-level approval chain yet
4. **Policy Tests**: Authorization policy tests not yet created (future enhancement)
5. **E2E Tests**: End-to-end workflow tests not yet created (future enhancement)
6. **File Upload Validation**: Server-side file validation could be enhanced with virus scanning

### Future Enhancements

1. **Photo Compression**: Implement automatic image compression on upload to save storage
2. **PDF Generation**: Generate KEW.PA-10 return packages as PDF documents
3. **Email Notifications**: Notify stakeholders on workflow transitions
4. **Audit Logging**: Track all changes to KEW.PA-10 forms and reports
5. **Bulk Operations**: Support bulk approval/rejection of inspections
6. **Advanced Search**: Full-text search across all KEW.PA-10 related data

---

**Sprint Status**: ✅ All Phases Complete (100%)
**Completion Date**: 2025-12-30
**Total Story Points**: 20/20
**Total Files Created**:

- 7 Migrations
- 6 Models (+ 1 extended)
- 2 Enums
- 4 Services
- 4 Controllers
- 8 Form Requests
- 6 Vue.js Pages
- 4 Policies
- 40+ Routes

**Achievement**: Full KEW.PA-10 Workflow Option 1 implemented from database to frontend!
