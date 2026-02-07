# 🚀 Database Startup Checklist

> **Purpose**: Quick reference for executing Week 2-3 backend migration tasks  
> **Prerequisites**: MySQL/MariaDB server must be running  
> **Estimated Time**: 15-30 minutes

---

## ✅ Pre-Flight Checks

### 1. Verify Database Connection
```bash
# Check .env database configuration
cat .env | grep DB_

# Test database connection
php artisan migrate:status
```

**Expected Output**: List of existing migrations

---

## 📦 Step-by-Step Execution

### Step 1: Run New Migrations (5 min)

```bash
# Show what will be migrated
php artisan migrate --pretend

# Run migrations with step-by-step confirmation
php artisan migrate --step

# Verify migrations were applied
php artisan migrate:status
```

**Migrations to Execute**:
- ✅ `2026_02_02_100000_add_kew_pa_10_static_fields_to_workshop_jobs`
- ✅ `2026_02_02_110000_drop_dynamic_workflow_tables`

**Success Criteria**:
- [x] Both migrations show as "Ran"
- [x] No errors in console
- [x] `workshop_jobs` table has `job_mode` column
- [x] Old workflow tables are dropped (backed up as `_backup_*`)

---

### Step 2: Run Data Migration Seeder (10-20 min)

```bash
# Run the seeder
php artisan db:seed --class=MigrateKewPa10DynamicToStaticSeeder

# The seeder will:
# - Find all KEW.PA-10 jobs
# - Parse dynamic form data
# - Map to static columns
# - Set job_mode field
# - Verify data integrity
# - Show progress and summary
```

**Expected Output**:
```
🚀 Starting KEW.PA-10 data migration...
📋 Step 1: Migrating KEW.PA-10 jobs...
Found X KEW.PA-10 jobs to migrate
  ✓ Migrated 10 jobs...
  ✓ Migrated 20 jobs...
  
📋 Step 2: Setting NORMAL mode for non-KEW jobs...
  ✓ Set Y jobs to NORMAL mode
  
🔍 Step 3: Verifying migration...
┌──────────────────┬───────┐
│ Metric           │ Count │
├──────────────────┼───────┤
│ Total Jobs       │ Z     │
│ KEW.PA-10 Jobs   │ X     │
│ Normal Jobs      │ Y     │
│ Null Mode (ISSUE)│ 0     │
└──────────────────┴───────┘
✅ Verification passed!

═══════════════════════════════════════════════
📊 Migration Summary
═══════════════════════════════════════════════
✅ Successfully migrated: X KEW.PA-10 jobs
═══════════════════════════════════════════════
```

**Success Criteria**:
- [x] All KEW jobs migrated successfully
- [x] All other jobs set to NORMAL mode
- [x] Zero jobs with null `job_mode`
- [x] No error messages in output

---

### Step 3: Verify Data Integrity (2 min)

```bash
# Open Tinker
php artisan tinker

# Check job mode distribution
WorkshopJob::where('job_mode', 'KEW_PA_10')->count()
WorkshopJob::where('job_mode', 'NORMAL')->count()
WorkshopJob::whereNull('job_mode')->count()  // Should be 0

# Verify KEW jobs have static fields populated
$kewJob = WorkshopJob::where('job_mode', 'KEW_PA_10')->first()
$kewJob->kew_vehicle_registration  // Should have a value
$kewJob->kew_pa_10_number         // Should have a value

# Exit tinker
exit
```

**Success Criteria**:
- [x] Count of KEW_PA_10 + NORMAL = Total jobs
- [x] Zero null `job_mode` entries
- [x] KEW jobs have populated static fields

---

### Step 4: Run Unit Tests (5 min)

```bash
# Run all tests
php artisan test

# Run specific service tests
php artisan test --filter=JobStatusServiceTest
php artisan test --filter=KewPa10ValidationServiceTest
php artisan test --filter=KewPa10ApprovalServiceTest

# Check coverage (optional)
php artisan test --coverage --min=80
```

**Expected Output**:
```
Tests:    30+ passed
Duration: 2-5 seconds
```

**Success Criteria**:
- [x] All tests passing
- [x] 80%+ code coverage on new services
- [x] No errors or warnings

---

### Step 5: Add Routes to web.php (2 min)

**File**: `routes/web.php`

**Add these routes**:
```php
use App\Http\Controllers\KewApprovalController;

Route::prefix('jobs/kew')
    ->middleware(['auth'])
    ->name('jobs.kew.')
    ->group(function () {
        // List pending approvals (supervisor dashboard)
        Route::get('/pending', [KewApprovalController::class, 'index'])
            ->middleware('role:supervisor|admin|super_admin')
            ->name('pending');
        
        // Approve inspection
        Route::post('/{job}/approve', [KewApprovalController::class, 'approve'])
            ->middleware('role:supervisor|admin|super_admin')
            ->name('approve');
        
        // Reject inspection
        Route::post('/{job}/reject', [KewApprovalController::class, 'reject'])
            ->middleware('role:supervisor|admin|super_admin')
            ->name('reject');
        
        // View approval history
        Route::get('/{job}/history', [KewApprovalController::class, 'history'])
            ->name('history');
    });
```

**Verify routes**:
```bash
php artisan route:list --name=kew
```

**Success Criteria**:
- [x] 4 new routes registered
- [x] All routes have correct middleware
- [x] No routing conflicts

---

### Step 6: Test API Endpoints (Optional - 5 min)

**Using Artisan Tinker**:
```bash
php artisan tinker

# Get a KEW job
$job = WorkshopJob::where('job_mode', 'KEW_PA_10')->first()

# Get a supervisor user
$supervisor = User::role('supervisor')->first()

# Test approval service
app(KewPa10ApprovalService::class)->getPendingApprovals()

# Exit
exit
```

**Or using HTTP client** (Postman/Insomnia):
```
GET http://localhost/jobs/kew/pending
Authorization: Session Cookie
```

---

## 🎯 Success Summary

After completing all steps, you should have:

✅ **Database**:
- [x] Static KEW columns added
- [x] Old workflow tables dropped (backed up)
- [x] All jobs have `job_mode` set
- [x] KEW jobs have populated static fields

✅ **Code**:
- [x] 4 new services operational
- [x] 1 new controller configured
- [x] Routes registered
- [x] 30+ tests passing

✅ **Metrics**:
- [x] 80%+ test coverage
- [x] Zero data loss
- [x] Backend migration **100% complete**

---

## 🚨 Troubleshooting

### Issue: Migration Fails

**Error**: `SQLSTATE[42S02]: Base table or view not found`

**Solution**:
```bash
# Check if old tables exist
php artisan tinker
DB::table('job_templates')->exists()  // Should be true BEFORE migration 4

# If tables don't exist, skip Migration 4
php artisan migrate --path=database/migrations/2026_02_02_100000_add_kew_pa_10_static_fields_to_workshop_jobs.php
```

### Issue: Seeder Fails

**Error**: `Undefined index: kew_pa_10_number`

**Solution**:
- Check if `job_field_values` table exists
- Verify field keys match seeder expectations
- Review seeder output for specific failing jobs
- Check `storage/logs/laravel.log` for details

### Issue: Tests Failing

**Error**: `Class 'KewPa10ApprovalService' not found`

**Solution**:
```bash
# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Regenerate autoload
composer dump-autoload

# Run tests again
php artisan test
```

---

## 📞 Support

**Documentation**:
- Main progress: `docs/04-sprints/week2-3-backend-progress.md`
- Summary: `docs/04-sprints/WEEK2-3-SUMMARY.md`
- Route guide: `routes/kew-approval-routes-guide.php`

**Logs**:
- Laravel log: `storage/logs/laravel.log`
- Migration log: Console output during `php artisan migrate`
- Seeder log: Console output during `db:seed`

---

**Ready to Execute?** ✅  
**Estimated Total Time**: 15-30 minutes  
**Risk Level**: Low (all migrations have rollback support)

🚀 **Let's go!**
