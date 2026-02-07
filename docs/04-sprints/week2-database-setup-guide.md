# Week 2: Database Setup & Migration Execution Guide

> **Sprint**: Architecture Simplification  
> **Week**: 2 - Backend Migration  
> **Status**: Awaiting Database Setup  

---

## Current Situation

**Migration Files Created**: ✅ Ready
- `2026_02_02_100000_add_kew_pa_10_static_fields_to_workshop_jobs.php`
- `MigrateKewPa10DynamicToStaticSeeder.php`

**Database Status**: ⚠️ Connection Issue
- Error: `Access denied for user 'root'@'172.18.0.1' (using password: NO)`
- Indicates Docker MySQL container with authentication issue

---

## Option 1: Fix Docker MySQL (Recommended)

### Check if Docker is Running

```bash
# Check Docker services
docker ps

# Look for MySQL container
docker ps | findstr mysql
```

### Start/Restart MySQL Container

```bash
# If container exists but stopped
docker start mysql-container-name

# If no container, start from docker-compose
docker-compose up -d mysql

# Check logs
docker logs mysql-container-name
```

### Fix Password Issue

If using Docker, update `.env` with Docker MySQL password:

```env
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=workshop
DB_USERNAME=root
DB_PASSWORD=your_mysql_password_here
```

Or use Docker environment variables:

```bash
# Check docker-compose.yml for MYSQL_ROOT_PASSWORD
# Update .env to match
```

---

## Option 2: Use Local MySQL (Alternative)

### Install MySQL Locally

**Windows**:
1. Download MySQL Installer from mysql.com
2. Install MySQL Server 8.0+
3. Set root password during installation
4. Update `.env` with password

**Update .env**:
```env
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=workshop
DB_USERNAME=root
DB_PASSWORD=your_password_here
```

---

## Migration Execution Steps (Once DB is Ready)

### Step 1: Verify Connection

```bash
php artisan migrate:status
```

**Expected**: List of migrations with Ran/Pending status

---

### Step 2: Create Database Backup

**Option A: Laravel Backup (if installed)**
```bash
php artisan backup:run
```

**Option B: MySQL Dump**
```bash
# From project root
mysqldump -u root -p workshop > backup_pre_migration_$(date +%Y%m%d).sql

# Or using Docker
docker exec mysql-container-name mysqldump -u root -p workshop > backup_pre_migration_$(date +%Y%m%d).sql
```

**Keep backup file safe** - Required for rollback if migration fails

---

### Step 3: Run Column Addition Migration

```bash
# Dry run (recommended first)
php artisan migrate:status

# Execute migration
php artisan migrate --path=database/migrations/2026_02_02_100000_add_kew_pa_10_static_fields_to_workshop_jobs.php

# Check if successful
php artisan migrate:status
```

**Expected Output**:
```
Migration ran successfully!
Added 11 columns to workshop_jobs table
```

---

### Step 4: Verify Schema Changes

```bash
php artisan tinker
```

Then run:
```php
// Check new columns exist
DB::select("DESCRIBE workshop_jobs");

// Should see new columns:
// - job_mode
// - kew_pa_10_number
// - kew_pa_10_received_date
// ... (9 more)
```

---

### Step 5: Run Data Migration Seeder

```bash
php artisan db:seed --class=MigrateKewPa10DynamicToStaticSeeder
```

**Expected Output**:
```
Starting KEW.PA-10 dynamic → static migration...
Setting job modes...
  ✓ Set X jobs to KEW_PA_10
  ✓ Set Y jobs to NORMAL
Migrating KEW.PA-10 numbers...
  ✓ Migrated N KEW.PA-10 numbers
...
Validation Results:
  Total KEW.PA-10 jobs: X
  With KEW number: X
  With received date: X
  With description: X
  ✅ All required fields populated!
✅ KEW.PA-10 data migration completed successfully!
```

---

### Step 6: Validate Data Integrity

```bash
php artisan tinker
```

Run validation queries:

```php
// Count KEW.PA-10 jobs
DB::table('workshop_jobs')->where('job_mode', 'KEW_PA_10')->count();

// Check for missing required fields
DB::table('workshop_jobs')
    ->where('job_mode', 'KEW_PA_10')
    ->whereNull('kew_pa_10_number')
    ->count(); // Should be 0

// Sample data check
DB::table('workshop_jobs')
    ->where('job_mode', 'KEW_PA_10')
    ->select('id', 'job_number', 'kew_pa_10_number', 'kew_pa_10_received_date', 'kew_pa_10_priority')
    ->limit(5)
    ->get();
```

**Success Criteria**:
- All KEW.PA-10 jobs have required fields filled
- Foreign keys valid (government_department_id, asset_id)
- No orphaned field values

---

### Step 7: Run Remaining Migrations (Files to be created)

```bash
# Migration: Drop old template/workflow tables
php artisan migrate --path=database/migrations/2026_02_02_110000_drop_dynamic_workflow_tables.php

# Migration: Update WorkshopJob model references
php artisan migrate --path=database/migrations/2026_02_02_120000_cleanup_job_foreign_keys.php
```

---

## Rollback Procedure (If Migration Fails)

### Option 1: Laravel Rollback

```bash
# Rollback last migration
php artisan migrate:rollback --step=1
```

This will:
- Drop all 11 new columns
- Remove foreign keys and indexes
- Restore to pre-migration state

**Note**: Dynamic data is NOT restored (still in job_field_values)

---

### Option 2: Full Database Restore

```bash
# Stop application
# Restore from backup
mysql -u root -p workshop < backup_pre_migration_YYYYMMDD.sql

# Verify restore
php artisan migrate:status
```

---

## Next Steps After Successful Migration

1. ✅ **Drop Old Tables** (separate migration file)
2. ✅ **Update WorkshopJob Model** (add fillable, casts, relationships)
3. ✅ **Create Service Classes** (JobStatusService, KewPa10ValidationService)
4. ✅ **Write Unit Tests** (80%+ coverage)

---

## Troubleshooting

### Issue: "Access denied for user 'root'"

**Solution**: Update `.env` with correct MySQL password

---

### Issue: "Unknown database 'workshop'"

**Solution**:
```bash
# Create database
mysql -u root -p
CREATE DATABASE workshop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
exit;

# Run migrations
php artisan migrate
```

---

### Issue: Migration file not found

**Solution**: Verify file exists in `database/migrations/` with exact filename

---

### Issue: Foreign key constraint fails

**Possible causes**:
- Referenced table doesn't exist (government_departments, assets)
- Invalid data in job_field_values

**Solution**: Run database seeder to create reference data first:
```bash
php artisan db:seed --class=GovernmentDepartmentSeeder
php artisan db:seed --class=AssetSeeder
```

---

## Current Status

**Completed**:
- [x] Migration file created
- [x] Seeder created
- [x] This setup guide created

**Waiting for**:
- [ ] MySQL database accessible
- [ ] Execute migration
- [ ] Validate data

**Estimated Time**: 30 minutes once database is accessible

---

**Ready to proceed when database issue is resolved!**
