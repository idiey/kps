# Week 6 Testing - Obsolete Test Cleanup

## Obsolete Tests Identified

The following test files are for the removed dynamic workflow/template system and need to be deleted:

### Template Tests
- `tests/Feature/Admin/TemplateFieldControllerTest.php`

### Workflow Tests
- `tests/Feature/Admin/WorkflowImportTest.php`
- `tests/Feature/RoleBasedWorkflowAccessTest.php`
- `tests/Feature/Workflows/AlabalaWorkflowRoleGatingTest.php`

### Dynamic Job Tests
- `tests/Feature/Controllers/DynamicJobControllerTest.php`
- `tests/Feature/Services/DynamicJobServiceFormEnforcementTest.php`

## Cleanup Actions

```bash
# Remove obsolete test files
rm tests/Feature/Admin/TemplateFieldControllerTest.php
rm tests/Feature/Admin/WorkflowImportTest.php
rm tests/Feature/RoleBasedWorkflowAccessTest.php
rm tests/Feature/Workflows/AlabalaWorkflowRoleGatingTest.php
rm tests/Feature/Controllers/DynamicJobControllerTest.php
rm tests/Feature/Services/DynamicJobServiceFormEnforcementTest.php

# Remove empty directories
rmdir tests/Feature/Workflows 2>$null
rmdir tests/Feature/Services 2>$null
```

## New Tests Needed

### KEW Approval Tests
- `tests/Feature/Controllers/KewApprovalControllerTest.php`
  - Test approval workflow
  - Test rejection workflow
  - Test role-based access
  - Test approval history

### Static Job Tests
- `tests/Feature/Controllers/JobControllerTest.php` (update existing)
  - Test KEW job creation
  - Test Normal job creation
  - Test mode selection
  - Test KEW field validation

### Admin Module Tests
- `tests/Feature/Admin/UserManagementControllerTest.php`
- `tests/Feature/Admin/ReportControllerTest.php`
- `tests/Feature/Admin/AssetControllerTest.php`
- `tests/Feature/Admin/InventoryControllerTest.php`
- `tests/Feature/Admin/SettingsControllerTest.php`

## Status

- [x] Identified obsolete tests
- [ ] Remove obsolete test files
- [ ] Create new KEW approval tests
- [ ] Create admin module tests
- [ ] Run clean test suite
