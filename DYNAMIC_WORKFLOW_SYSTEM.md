# Dynamic Workflow Management System

## Overview

This document describes the Dynamic Workflow Management System implemented for the Malaysian Government Workshop Application. The system transforms the static enum-based workflow into a fully dynamic, database-driven system that allows administrators to create and manage multiple workflows, templates, and field configurations without code changes.

## Table of Contents

1. [Features](#features)
2. [Architecture](#architecture)
3. [Database Schema](#database-schema)
4. [Migration Guide](#migration-guide)
5. [Usage Guide](#usage-guide)
6. [API Reference](#api-reference)
7. [Frontend Components](#frontend-components)
8. [Configuration](#configuration)
9. [Testing](#testing)
10. [Troubleshooting](#troubleshooting)
11. [Future Enhancements](#future-enhancements)
12. [Support](#support)

---

## Features

### For Administrators

- ✅ Create and manage user roles with Spatie Permissions
- ✅ Create job templates with custom fields (12 field types supported)
- ✅ Design workflows with custom statuses and transitions
- ✅ Define workflow rules (field visibility, requirements, auto-assignment)
- ✅ Associate multiple workflows with templates (e.g., KEW.PA-10 Option 1 vs Option 2)
- ✅ Configure transition permissions and conditions
- ✅ Visual workflow builder (routes defined, UI pending)

### For Users

- ✅ Select from available job templates
- ✅ Choose appropriate workflow (if multiple available)
- ✅ Fill dynamic forms with validation
- ✅ Execute workflow transitions based on permissions
- ✅ View workflow progress timeline
- ✅ See field-level visibility/requirement changes per status

---

## Architecture

### Backend Stack

```
Laravel 12
├── Spatie Laravel Permission (roles & permissions)
├── PostgreSQL/MySQL (database)
└── Inertia.js (server-side rendering)
```

### Frontend Stack

```
Vue.js 3
├── Composition API
├── Inertia.js
└── TailwindCSS
```

### Key Design Patterns

1. **Service Layer Pattern**: Business logic in services (WorkflowExecutor, TemplateRenderService)
2. **Repository Pattern**: Eloquent models with relationships
3. **Strategy Pattern**: Field type rendering via component mapping
4. **Observer Pattern**: Workflow rule engine
5. **Factory Pattern**: Dynamic form schema generation

---

## Database Schema

### Core Tables

#### 1. Roles & Permissions (Spatie)
```sql
roles (extended)
├── id
├── name (pentadbiran, penyelia, pemeriksa, pelulus, juruteknik)
├── description
├── color
├── is_system_role
└── is_active
```

#### 2. Job Templates
```sql
job_templates
├── id
├── name
├── code (unique)
├── description
├── icon
├── color
├── is_active
├── is_default
└── default_workflow_id (FK to workflows)
```

#### 3. Template Fields
```sql
template_fields
├── id
├── template_id (FK)
├── field_type_id (FK)
├── name
├── code
├── section
├── display_order
├── grid_column_span
├── is_required
├── validation_rules (JSON)
├── conditional_rules (JSON)
├── options (JSON)
├── formula (for calculated fields)
└── is_active
```

#### 4. Field Types
```sql
template_field_types
├── id
├── name
├── code (text, number, date, dropdown, etc.)
├── component_name (TextField, NumberField, etc.)
├── validation_schema (JSON)
└── is_active
```

**Supported Field Types:**
- `text` - Single-line text input
- `number` - Numeric input with min/max
- `textarea` - Multi-line text
- `date` - Date picker
- `datetime` - Date & time picker
- `dropdown` - Single select
- `radio` - Radio buttons
- `checkbox` - Boolean checkbox
- `multiselect` - Multiple selection
- `file` - File upload
- `image` - Image upload with preview
- `calculated` - Formula-based field

#### 5. Workflows
```sql
workflows
├── id
├── name
├── code (unique)
├── description
├── is_active
├── is_default
└── metadata (JSON)
```

#### 6. Workflow Statuses
```sql
workflow_statuses
├── id
├── workflow_id (FK)
├── name
├── code
├── color
├── icon
├── is_initial
├── is_final
└── display_order
```

#### 7. Workflow Transitions
```sql
workflow_transitions
├── id
├── workflow_id (FK)
├── from_status_id (FK)
├── to_status_id (FK)
├── name
├── requires_permission
├── allowed_roles (JSON array of role IDs)
├── conditions (JSON)
├── actions (JSON)
├── button_label
├── button_color
├── confirmation_message
├── requires_comment
└── display_order
```

#### 8. Workflow Rules
```sql
workflow_rules
├── id
├── workflow_id (FK)
├── status_id (FK, nullable - applies to specific status or all)
├── name
├── rule_type (field_required, field_visible, auto_assign, notification, validation)
├── conditions (JSON)
├── actions (JSON)
├── priority
└── is_active
```

#### 9. Template-Workflow Association
```sql
template_workflows
├── id
├── template_id (FK)
├── workflow_id (FK)
└── is_default
```

#### 10. Job Field Values
```sql
job_field_values
├── id
├── job_id (FK)
├── field_id (FK)
├── value_text
├── value_number
├── value_date
├── value_datetime
├── value_boolean
└── value_json
```

#### 11. Enhanced Workshop Jobs
```sql
workshop_jobs (extended)
├── ... (existing columns)
├── template_id (FK)
├── workflow_id (FK)
└── current_workflow_status_id (FK)
```

---

## Migration Guide

### Step-by-Step Migration Process

#### 1. Run Migrations
```bash
php artisan migrate
```

#### 2. Seed Field Types
```bash
php artisan db:seed --class=TemplateFieldTypeSeeder
```

#### 3. Migrate Roles from Enum to Spatie
```bash
php artisan workflow:migrate-roles --force
```

**What this does:**
- Creates 5 system roles from UserRole enum
- Assigns roles to existing users based on their `role` enum value
- Marks roles as `is_system_role` (prevents deletion)

**Output:**
```
✓ Created/Updated role: pentadbiran
✓ Created/Updated role: penyelia
✓ Created/Updated role: pemeriksa
✓ Created/Updated role: pelulus
✓ Created/Updated role: juruteknik

Assigned: 45
Skipped: 3
Errors: 0
```

#### 4. Create Default Workflow
```bash
php artisan workflow:migrate-default --force
```

**What this does:**
- Creates "KEW.PA-10 Default Workflow"
- Migrates 14 statuses from JobStatus enum
- Creates transitions based on `allowedTransitions()` matrix
- Sets role permissions for each transition

#### 5. Create Default Template
```bash
php artisan workflow:migrate-template --force
```

**What this does:**
- Creates "Standard Workshop Job" template
- Creates 14 fields across 5 sections:
  - General Information
  - Vehicle Information
  - Cost Estimation
  - Scheduling
  - Additional Information

#### 6. Migrate Existing Jobs
```bash
php artisan workflow:migrate-jobs --batch=100 --force
```

**What this does:**
- Links all existing jobs to default template
- Links all existing jobs to default workflow
- Maps current `status` enum to `current_workflow_status_id`

**Output:**
```
Migrated: 1,234
Status Mapped: 1,234
Skipped: 0
Errors: 0
```

### Rollback Strategy

If issues occur during migration:

1. **Set Migration Mode to Legacy**
```env
WORKFLOW_MIGRATION_MODE=legacy
```

2. **System Behavior:**
- Jobs without `workflow_id` use enum-based logic
- Jobs with `workflow_id` use dynamic workflow
- Dual-mode operation allows gradual rollback

3. **Fix Issues and Re-enable**
```env
WORKFLOW_MIGRATION_MODE=dual  # Both systems active
WORKFLOW_MIGRATION_MODE=dynamic  # Only dynamic workflow
```

---

## Usage Guide

### Creating a New Workflow (Admin)

#### 1. Create Workflow
```php
// Via UI or tinker
$workflow = Workflow::create([
    'name' => 'KEW.PA-10 Option 2 - Internal',
    'code' => 'kew-pa-10-option-2',
    'description' => 'For internal jobs without external KEW.PA-10 paperwork',
    'is_active' => true,
]);
```

#### 2. Create Statuses
```php
$statuses = [
    ['name' => 'New', 'code' => 'new', 'is_initial' => true],
    ['name' => 'Pending Approval', 'code' => 'pending_approval'],
    ['name' => 'Approved', 'code' => 'approved'],
    // ... more statuses
];

foreach ($statuses as $index => $status) {
    $workflow->statuses()->create(array_merge($status, [
        'display_order' => $index,
    ]));
}
```

#### 3. Create Transitions
```php
$newStatus = $workflow->statuses()->where('code', 'new')->first();
$pendingStatus = $workflow->statuses()->where('code', 'pending_approval')->first();

$workflow->transitions()->create([
    'from_status_id' => $newStatus->id,
    'to_status_id' => $pendingStatus->id,
    'name' => 'Submit for Approval',
    'button_label' => 'Submit',
    'button_color' => 'blue',
    'allowed_roles' => [1, 5], // pentadbiran, juruteknik
    'requires_comment' => false,
]);
```

#### 4. Associate with Template
```php
$template = JobTemplate::where('code', 'standard-job')->first();
$template->workflows()->attach($workflow->id, ['is_default' => false]);
```

### Creating a Job (User Flow)

#### Step 1: Select Template
```
GET /jobs/templates/select
```

User sees all available templates and selects one.

#### Step 2: Select Workflow (if multiple)
```
GET /jobs/create/{template}
```

If template has multiple workflows, user chooses:
- KEW.PA-10 Option 1 (External Reception)
- KEW.PA-10 Option 2 (Internal)

#### Step 3: Fill Dynamic Form
Form renders based on template fields with:
- Validation rules
- Conditional visibility
- Calculated fields
- Field dependencies

#### Step 4: Submit
```
POST /jobs
{
  "template_id": 1,
  "workflow_id": 2,
  "field_data": {
    "title": "Engine Repair",
    "vehicle_registration": "WXY 1234",
    "estimated_labor_cost": 500.00,
    ...
  }
}
```

### Executing Workflow Transitions

```javascript
// Frontend
const handleTransition = ({ transition, data }) => {
  form.post(route('jobs.execute-transition', {
    job: job.id,
    transition: transition.id,
  }), {
    notes: data.notes,
    field_data: data.field_data,
  });
};
```

```php
// Backend (handled by DynamicJobController)
public function executeTransition(
    Request $request,
    WorkshopJob $job,
    WorkflowTransition $transition
) {
    $this->jobService->executeTransition(
        $job,
        $transition->id,
        $request->all()
    );
}
```

---

## API Reference

### Template APIs

#### Get Template Workflows
```http
GET /api/templates/{template}/workflows
```

**Response:**
```json
[
  {
    "id": 1,
    "name": "KEW.PA-10 Option 1 - External",
    "code": "kew-pa-10-option-1",
    "description": "For jobs received via external KEW.PA-10 form",
    "is_default": true
  },
  {
    "id": 2,
    "name": "KEW.PA-10 Option 2 - Internal",
    "code": "kew-pa-10-option-2",
    "description": "For internal workshop jobs",
    "is_default": false
  }
]
```

#### Get Form Schema
```http
GET /api/templates/{template}/schema?job_id={jobId}
```

**Response:**
```json
{
  "template": {
    "id": 1,
    "name": "Standard Workshop Job",
    "code": "standard-job"
  },
  "sections": [
    {
      "name": "General Information",
      "fields": [
        {
          "code": "title",
          "name": "Job Title",
          "field_type": "text",
          "is_required": true,
          "placeholder": "e.g., Engine repair",
          "validation_rules": { "max": 255 },
          "value": "Engine Repair" // if editing
        }
      ]
    }
  ]
}
```

#### Validate Field Data
```http
POST /api/templates/{template}/validate
```

**Request:**
```json
{
  "field_data": {
    "title": "",
    "vehicle_registration": "WXY 1234"
  }
}
```

**Response:**
```json
{
  "valid": false,
  "errors": {
    "title": ["Job Title is required"]
  }
}
```

### Job APIs

#### Get Available Transitions
```http
GET /api/jobs/{job}/available-transitions
```

**Response:**
```json
{
  "transitions": [
    {
      "id": 5,
      "name": "Submit for Inspection",
      "button_label": "Request Inspection",
      "button_color": "blue",
      "requires_comment": false,
      "confirmation_message": null,
      "to_status": {
        "id": 4,
        "name": "Pending Inspection",
        "color": "yellow"
      }
    }
  ]
}
```

#### Get Field Rules for Current Status
```http
GET /api/jobs/{job}/field-rules
```

**Response:**
```json
{
  "visibilityRules": {
    "inspection_notes": true,
    "completion_date": false
  },
  "requirementRules": [
    "vehicle_registration",
    "odometer_reading"
  ]
}
```

---

## Frontend Components

### Core Components

#### 1. DynamicFormRenderer
**Path:** `resources/js/components/dynamic-form/DynamicFormRenderer.vue`

**Props:**
- `schema` (Object, required) - Form schema from backend
- `initialData` (Object) - Pre-filled values
- `jobId` (Number) - For loading field rules
- `submitLabel` (String) - Submit button text

**Events:**
- `@submit` - Emits form data
- `@cancel` - Cancel action

**Features:**
- Auto-validation
- Calculated fields
- Conditional visibility
- Field dependency management

**Usage:**
```vue
<DynamicFormRenderer
  :schema="formSchema"
  :initial-data="jobData"
  :job-id="job.id"
  @submit="handleSubmit"
  @cancel="handleCancel"
/>
```

#### 2. WorkflowSelector
**Path:** `resources/js/components/job/WorkflowSelector.vue`

**Props:**
- `workflows` (Array, required)
- `template` (Object)
- `modelValue` (Object) - Selected workflow

**Events:**
- `@update:modelValue` - Workflow selected

**Usage:**
```vue
<WorkflowSelector
  :workflows="availableWorkflows"
  :template="selectedTemplate"
  v-model="selectedWorkflow"
/>
```

#### 3. WorkflowStatusTimeline
**Path:** `resources/js/components/job/WorkflowStatusTimeline.vue`

**Props:**
- `job` (Object, required)
- `statuses` (Array)

**Features:**
- Visual progress indicator
- Status history with notes
- Current status highlighting

**Usage:**
```vue
<WorkflowStatusTimeline
  :job="job"
  :statuses="workflow.statuses"
/>
```

#### 4. WorkflowTransitionButtons
**Path:** `resources/js/components/job/WorkflowTransitionButtons.vue`

**Props:**
- `job` (Object, required)
- `availableTransitions` (Array)

**Events:**
- `@transition` - Emits `{ transition, data }`

**Features:**
- Permission-based filtering
- Confirmation modals
- Required comment validation
- Color-coded buttons

**Usage:**
```vue
<WorkflowTransitionButtons
  :job="job"
  :available-transitions="transitions"
  @transition="handleTransition"
/>
```

### Field Components

Located in `resources/js/components/dynamic-form/fields/`:

- `TextField.vue`
- `NumberField.vue`
- `TextareaField.vue`
- `DateField.vue`
- `DateTimeField.vue`
- `DropdownField.vue`
- `RadioField.vue`
- `CheckboxField.vue`
- `MultiSelectField.vue`
- `FileUploadField.vue`
- `ImageUploadField.vue`
- `CalculatedField.vue`

### Composables

#### useDynamicForm
```javascript
import { useDynamicForm } from '@/composables/useDynamicForm';

const {
  formSchema,
  formData,
  errors,
  isLoading,
  loadSchema,
  validate,
  setFieldValue,
  getFieldValue,
  reset,
} = useDynamicForm(templateId, jobId);

await loadSchema();
const isValid = await validate();
```

#### useWorkflow
```javascript
import { useWorkflow } from '@/composables/useWorkflow';

const {
  availableTransitions,
  fieldRules,
  isLoading,
  loadAvailableTransitions,
  loadFieldRules,
  executeTransition,
  canTransition,
  getTransition,
} = useWorkflow(jobId);

await loadAvailableTransitions();
const rules = await loadFieldRules();
```

---

## Configuration

### Environment Variables

```env
# Workflow System Mode
WORKFLOW_MIGRATION_MODE=dynamic  # legacy, dual, or dynamic
USE_DYNAMIC_WORKFLOWS=true
```

### Config File

**Path:** `config/workflow.php`

```php
return [
    'use_dynamic_workflows' => env('USE_DYNAMIC_WORKFLOWS', false),
    'migration_mode' => env('WORKFLOW_MIGRATION_MODE', 'dual'),

    'default_field_types' => [
        'text', 'number', 'textarea', 'date', 'datetime',
        'dropdown', 'radio', 'checkbox', 'multiselect',
        'file', 'image', 'calculated',
    ],
];
```

---

## Testing

### Running Migrations (Test)
```bash
# Test on fresh database
php artisan migrate:fresh
php artisan db:seed --class=TemplateFieldTypeSeeder
php artisan workflow:migrate-roles --force
php artisan workflow:migrate-default --force
php artisan workflow:migrate-template --force
```

### Creating Test Data
```bash
# Create test jobs
php artisan tinker

# Create jobs with dynamic workflow
$template = App\Models\Template\JobTemplate::first();
$workflow = App\Models\Workflow\Workflow::first();

$job = App\Models\WorkshopJob::create([
    'template_id' => $template->id,
    'workflow_id' => $workflow->id,
    'current_workflow_status_id' => $workflow->initialStatus->id,
    'created_by' => 1,
]);

// Set field values
$job->setFieldValue('title', 'Test Job');
$job->setFieldValue('vehicle_registration', 'TEST 123');
```

---

## Troubleshooting

### Issue: Jobs not showing in dynamic form

**Solution:**
1. Check if job has `template_id` and `workflow_id`
2. Verify field values exist in `job_field_values` table
3. Check template has active fields

### Issue: Transitions not appearing

**Solution:**
1. Verify user has required role/permission
2. Check transition is active
3. Ensure current status matches `from_status_id`
4. Check `allowed_roles` configuration

### Issue: Calculated fields not updating

**Solution:**
1. Verify formula syntax is valid JavaScript
2. Check dependent field codes are correct
3. Ensure field type is set to `calculated`
4. Test formula in template field editor

---

## Future Enhancements

- [ ] Visual Workflow Builder UI
- [ ] Workflow versioning
- [ ] Field validation rule builder
- [ ] Template import/export
- [ ] Workflow analytics dashboard
- [ ] Audit trail for workflow changes
- [ ] Workflow simulation/testing
- [ ] Multi-language support for fields
- [ ] Advanced formula editor
- [ ] Workflow triggers and webhooks

---

## Support

For questions or issues:
1. Check this documentation
2. Review migration logs
3. Check Laravel logs: `storage/logs/laravel.log`
4. Test in dual-mode before full migration

---

**Version:** Sprint 1
**Last Updated:** 2026-01-04
**Author:** Claude Code Implementation Team
