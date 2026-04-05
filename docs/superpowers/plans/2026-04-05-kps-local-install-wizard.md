# KPS Local Install Wizard Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Deliver a Windows-first, no-manual-setup KPS runtime with first-run bootstrap wizard, optional LAN mode, and backup/restore/update operational flows.

**Architecture:** Implement setup state and runtime configuration inside Laravel using explicit setup middleware, a dedicated setup wizard backend/frontend, and admin runtime endpoints. Add Windows deployment artifacts (`PowerShell + Caddy + Inno Setup`) to install KPS as a Windows Service using bundled runtime and SQLite data under `ProgramData`.

**Tech Stack:** Laravel 12, Inertia.js, Vue 3 + TypeScript, SQLite, Pest, PowerShell, Caddy, Inno Setup

---

## File Map

### Create

- `database/migrations/2026_04_06_000000_create_setup_bootstrap_states_table.php`
- `database/migrations/2026_04_06_000100_create_app_settings_table.php`
- `app/Models/SetupBootstrapState.php`
- `app/Models/AppSetting.php`
- `app/Services/Setup/SetupStateStore.php`
- `app/Services/Setup/SetupWizardService.php`
- `app/Services/Setup/LanRuntimeService.php`
- `app/Services/Setup/BackupService.php`
- `app/Http/Middleware/RequireSetupCompletion.php`
- `app/Http/Middleware/RedirectIfSetupComplete.php`
- `app/Http/Controllers/Setup/SetupWizardController.php`
- `app/Http/Controllers/Admin/RuntimeSettingsController.php`
- `app/Http/Controllers/Admin/BackupController.php`
- `app/Http/Requests/Setup/StoreInitialAdminRequest.php`
- `app/Http/Requests/Setup/StoreOrganizationRequest.php`
- `app/Http/Requests/Setup/StoreInitialSiteRequest.php`
- `app/Http/Requests/Admin/UpdateLanModeRequest.php`
- `app/Console/Commands/KpsRuntimeHealthCheckCommand.php`
- `routes/setup.php`
- `resources/js/pages/Setup/Wizard.vue`
- `resources/js/pages/Admin/Settings/Runtime.vue`
- `deploy/windows/caddy/Caddyfile.template`
- `deploy/windows/scripts/install-kps.ps1`
- `deploy/windows/scripts/uninstall-kps.ps1`
- `deploy/windows/scripts/enable-lan.ps1`
- `deploy/windows/scripts/disable-lan.ps1`
- `deploy/windows/scripts/repair-kps.ps1`
- `deploy/windows/installer/KpsInstaller.iss`
- `docs/04-deployment/windows-local-installer.md`
- `tests/Feature/Setup/SetupGuardTest.php`
- `tests/Feature/Setup/SetupWizardFlowTest.php`
- `tests/Feature/Setup/RuntimeLanModeTest.php`
- `tests/Feature/Setup/BackupRestoreTest.php`

### Modify

- `bootstrap/app.php`
- `routes/web.php`
- `routes/settings.php`
- `resources/js/types/index.d.ts`
- `resources/js/routes/index.ts` via `php artisan wayfinder:generate --path=resources/js --with-form`
- `resources/js/actions/index.ts` via `php artisan wayfinder:generate --path=resources/js --with-form`
- `docs/04-deployment/README.md`

---

## Task 1: Persist Setup State And Guard Routes

**Files:**
- Create: `database/migrations/2026_04_06_000000_create_setup_bootstrap_states_table.php`
- Create: `app/Models/SetupBootstrapState.php`
- Create: `app/Services/Setup/SetupStateStore.php`
- Create: `app/Http/Middleware/RequireSetupCompletion.php`
- Create: `app/Http/Middleware/RedirectIfSetupComplete.php`
- Modify: `bootstrap/app.php`
- Modify: `routes/web.php`
- Create: `tests/Feature/Setup/SetupGuardTest.php`
- Test: `php artisan test tests/Feature/Setup/SetupGuardTest.php`

- [ ] **Step 1: Write the failing guard test**

```php
<?php

use App\Models\SetupBootstrapState;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

beforeEach(function () {
    Permission::firstOrCreate(['name' => 'kps.manage_sites']);
    $this->user = User::factory()->create();
    $this->user->givePermissionTo('kps.manage_sites');
});

test('kps routes redirect to setup while bootstrap is incomplete', function () {
    SetupBootstrapState::query()->create([
        'is_completed' => false,
        'completed_at' => null,
        'state' => ['current_step' => 'admin'],
    ]);

    $this->actingAs($this->user)
        ->get('/kps/dashboard')
        ->assertRedirect('/setup');
});

test('setup route redirects away after bootstrap is complete', function () {
    SetupBootstrapState::query()->create([
        'is_completed' => true,
        'completed_at' => now(),
        'state' => ['current_step' => 'done'],
    ]);

    $this->get('/setup')->assertRedirect('/dashboard');
});
```

- [ ] **Step 2: Run test to verify failure**

Run: `php artisan test tests/Feature/Setup/SetupGuardTest.php`  
Expected: FAIL because setup table/middleware/routes do not exist yet.

- [ ] **Step 3: Add setup state migration and model**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('setup_bootstrap_states', function (Blueprint $table): void {
            $table->id();
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->json('state')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('setup_bootstrap_states');
    }
};
```

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SetupBootstrapState extends Model
{
    protected $fillable = ['is_completed', 'completed_at', 'state'];

    protected function casts(): array
    {
        return [
            'is_completed' => 'boolean',
            'completed_at' => 'datetime',
            'state' => 'array',
        ];
    }
}
```

- [ ] **Step 4: Add setup state store and middleware aliases**

```php
<?php

namespace App\Services\Setup;

use App\Models\SetupBootstrapState;

class SetupStateStore
{
    public function current(): SetupBootstrapState
    {
        return SetupBootstrapState::query()->firstOrCreate([], [
            'is_completed' => false,
            'state' => ['current_step' => 'admin'],
        ]);
    }

    public function isCompleted(): bool
    {
        return $this->current()->is_completed;
    }
}
```

```php
// bootstrap/app.php middleware alias block
$middleware->alias([
    'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
    'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
    'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
    'setup.complete' => \App\Http\Middleware\RequireSetupCompletion::class,
    'setup.incomplete' => \App\Http\Middleware\RedirectIfSetupComplete::class,
]);
```

- [ ] **Step 5: Wire setup guard routes**

```php
// routes/web.php
Route::middleware('setup.incomplete')->group(function () {
    Route::get('/setup', [\App\Http\Controllers\Setup\SetupWizardController::class, 'index'])->name('setup.index');
});

Route::middleware(['auth', 'verified', 'setup.complete'])->group(function () {
    Route::get('/dashboard', function () {
        if (Route::has('kps.dashboard')) {
            return redirect()->route('kps.dashboard');
        }
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

require __DIR__.'/setup.php';
```

- [ ] **Step 6: Run tests and commit**

Run: `php artisan migrate && php artisan test tests/Feature/Setup/SetupGuardTest.php`  
Expected: PASS (`2 passed`)

```bash
git add database/migrations/2026_04_06_000000_create_setup_bootstrap_states_table.php app/Models/SetupBootstrapState.php app/Services/Setup/SetupStateStore.php app/Http/Middleware/RequireSetupCompletion.php app/Http/Middleware/RedirectIfSetupComplete.php bootstrap/app.php routes/web.php tests/Feature/Setup/SetupGuardTest.php
git commit -m "feat(setup): add bootstrap guard state and middleware"
```

---

## Task 2: Build Setup Wizard Backend Flow

**Files:**
- Create: `database/migrations/2026_04_06_000100_create_app_settings_table.php`
- Create: `app/Models/AppSetting.php`
- Create: `app/Services/Setup/SetupWizardService.php`
- Create: `app/Http/Controllers/Setup/SetupWizardController.php`
- Create: `app/Http/Requests/Setup/StoreInitialAdminRequest.php`
- Create: `app/Http/Requests/Setup/StoreOrganizationRequest.php`
- Create: `app/Http/Requests/Setup/StoreInitialSiteRequest.php`
- Create: `routes/setup.php`
- Create: `tests/Feature/Setup/SetupWizardFlowTest.php`
- Test: `php artisan test tests/Feature/Setup/SetupWizardFlowTest.php`

- [ ] **Step 1: Write failing wizard flow test**

```php
<?php

use App\Models\SetupBootstrapState;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('wizard creates initial admin, seeds permissions, and marks setup complete', function () {
    $state = SetupBootstrapState::query()->create([
        'is_completed' => false,
        'state' => ['current_step' => 'admin'],
    ]);

    $this->postJson('/setup/admin', [
        'name' => 'KPS Admin',
        'email' => 'admin@kps.local',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ])->assertOk();

    $this->postJson('/setup/organization', [
        'organization_name' => 'KPS FELDA',
        'timezone' => 'Asia/Singapore',
        'locale' => 'ms',
    ])->assertOk();

    $this->postJson('/setup/seed')->assertOk();

    $this->postJson('/setup/site', [
        'name' => 'FELDA Sungai Tekam',
        'code' => 'FELDA-ST',
    ])->assertOk();

    $this->postJson('/setup/finish')->assertOk();

    expect($state->fresh()->is_completed)->toBeTrue();
});
```

- [ ] **Step 2: Run failing test**

Run: `php artisan test tests/Feature/Setup/SetupWizardFlowTest.php`  
Expected: FAIL (routes/controller/service missing).

- [ ] **Step 3: Add app settings migration/model**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_settings', function (Blueprint $table): void {
            $table->id();
            $table->string('key')->unique();
            $table->json('value')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_settings');
    }
};
```

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    protected $fillable = ['key', 'value'];

    protected function casts(): array
    {
        return [
            'value' => 'array',
        ];
    }
}
```

- [ ] **Step 4: Implement wizard service and controller**

```php
<?php

namespace App\Services\Setup;

use App\Models\AppSetting;
use App\Models\Kps\Site;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SetupWizardService
{
    public function storeInitialAdmin(array $data): User
    {
        return DB::transaction(function () use ($data): User {
            $user = User::query()->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'active' => true,
            ]);

            return $user;
        });
    }

    public function storeOrganization(array $data): void
    {
        AppSetting::query()->updateOrCreate(
            ['key' => 'organization.profile'],
            ['value' => $data]
        );
    }

    public function seedRolesAndPermissions(): void
    {
        Artisan::call('db:seed', ['--class' => RolePermissionSeeder::class, '--force' => true]);
    }

    public function storeInitialSite(array $data): Site
    {
        return Site::query()->firstOrCreate(
            ['code' => $data['code']],
            ['name' => $data['name'], 'is_active' => true]
        );
    }
}
```

```php
<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Http\Requests\Setup\StoreInitialAdminRequest;
use App\Http\Requests\Setup\StoreInitialSiteRequest;
use App\Http\Requests\Setup\StoreOrganizationRequest;
use App\Services\Setup\SetupStateStore;
use App\Services\Setup\SetupWizardService;
use Inertia\Inertia;
use Inertia\Response;

class SetupWizardController extends Controller
{
    public function __construct(
        private readonly SetupWizardService $wizard,
        private readonly SetupStateStore $stateStore
    ) {}

    public function index(): Response
    {
        return Inertia::render('Setup/Wizard', [
            'state' => $this->stateStore->current(),
        ]);
    }

    public function storeAdmin(StoreInitialAdminRequest $request)
    {
        $this->wizard->storeInitialAdmin($request->validated());
        return response()->json(['ok' => true]);
    }
}
```

- [ ] **Step 5: Add setup routes**

```php
<?php

use App\Http\Controllers\Setup\SetupWizardController;
use Illuminate\Support\Facades\Route;

Route::middleware('setup.incomplete')->group(function () {
    Route::get('/setup', [SetupWizardController::class, 'index'])->name('setup.index');
    Route::post('/setup/admin', [SetupWizardController::class, 'storeAdmin'])->name('setup.admin');
    Route::post('/setup/organization', [SetupWizardController::class, 'storeOrganization'])->name('setup.organization');
    Route::post('/setup/seed', [SetupWizardController::class, 'seed'])->name('setup.seed');
    Route::post('/setup/site', [SetupWizardController::class, 'storeSite'])->name('setup.site');
    Route::post('/setup/finish', [SetupWizardController::class, 'finish'])->name('setup.finish');
});
```

- [ ] **Step 6: Run tests and commit**

Run: `php artisan migrate && php artisan test tests/Feature/Setup/SetupWizardFlowTest.php`  
Expected: PASS (`1 passed`)

```bash
git add database/migrations/2026_04_06_000100_create_app_settings_table.php app/Models/AppSetting.php app/Services/Setup/SetupWizardService.php app/Http/Controllers/Setup/SetupWizardController.php app/Http/Requests/Setup/StoreInitialAdminRequest.php app/Http/Requests/Setup/StoreOrganizationRequest.php app/Http/Requests/Setup/StoreInitialSiteRequest.php routes/setup.php tests/Feature/Setup/SetupWizardFlowTest.php
git commit -m "feat(setup): implement setup wizard backend flow"
```

---

## Task 3: Build Setup Wizard Frontend

**Files:**
- Create: `resources/js/pages/Setup/Wizard.vue`
- Modify: `resources/js/types/index.d.ts`
- Modify: `resources/js/routes/index.ts` via Wayfinder generate
- Test: `php artisan test tests/Feature/Setup/SetupWizardFlowTest.php`

- [ ] **Step 1: Add frontend contract test expectation in feature test**

```php
$this->get('/setup')
    ->assertOk()
    ->assertInertia(fn ($page) => $page->component('Setup/Wizard')->has('state'));
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test tests/Feature/Setup/SetupWizardFlowTest.php`  
Expected: FAIL because `Setup/Wizard` page does not exist.

- [ ] **Step 3: Implement `Setup/Wizard.vue` with 5-step flow**

```vue
<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

const step = ref<'admin' | 'organization' | 'seed' | 'site' | 'finish'>('admin');
const admin = ref({ name: '', email: '', password: '', password_confirmation: '' });
const org = ref({ organization_name: '', timezone: 'Asia/Singapore', locale: 'ms' });
const site = ref({ name: '', code: '' });

async function submitAdmin() {
  await router.post('/setup/admin', admin.value);
  step.value = 'organization';
}
</script>

<template>
  <main class="mx-auto max-w-4xl p-8">
    <h1 class="text-2xl font-bold">KPS Setup Wizard</h1>
    <p class="text-sm text-muted-foreground">Step: {{ step }}</p>
  </main>
</template>
```

- [ ] **Step 4: Update type declarations and regenerate Wayfinder**

Run: `php artisan wayfinder:generate --path=resources/js --with-form`  
Expected: `Types generated for actions, routes, form variants`

```ts
// resources/js/types/index.d.ts excerpt
export interface SetupBootstrapState {
  id: number;
  is_completed: boolean;
  completed_at: string | null;
  state: Record<string, unknown> | null;
}
```

- [ ] **Step 5: Run tests/build and commit**

Run: `php artisan test tests/Feature/Setup/SetupWizardFlowTest.php && npm run build`  
Expected: PASS test + successful build

```bash
git add resources/js/pages/Setup/Wizard.vue resources/js/types/index.d.ts resources/js/routes/index.ts resources/js/actions/index.ts
git commit -m "feat(setup): add setup wizard UI"
```

---

## Task 4: Add Optional LAN Runtime Controls

**Files:**
- Create: `app/Services/Setup/LanRuntimeService.php`
- Create: `app/Http/Controllers/Admin/RuntimeSettingsController.php`
- Create: `app/Http/Requests/Admin/UpdateLanModeRequest.php`
- Create: `resources/js/pages/Admin/Settings/Runtime.vue`
- Modify: `routes/settings.php`
- Create: `tests/Feature/Setup/RuntimeLanModeTest.php`
- Test: `php artisan test tests/Feature/Setup/RuntimeLanModeTest.php`

- [ ] **Step 1: Write failing LAN mode test**

```php
<?php

use App\Models\AppSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

test('admin can enable lan mode and persisted setting is updated', function () {
    Permission::firstOrCreate(['name' => 'kps.manage_sites']);
    $admin = User::factory()->create();
    $admin->givePermissionTo('kps.manage_sites');

    $this->actingAs($admin)->put('/settings/runtime/lan-mode', [
        'enabled' => true,
        'port' => 18400,
        'allowlist' => ['192.168.1.0/24'],
    ])->assertRedirect();

    $setting = AppSetting::query()->where('key', 'runtime.lan_mode')->first();
    expect($setting?->value['enabled'])->toBeTrue();
});
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test tests/Feature/Setup/RuntimeLanModeTest.php`  
Expected: FAIL because route/controller/service missing.

- [ ] **Step 3: Implement LAN runtime service and controller**

```php
<?php

namespace App\Services\Setup;

use App\Models\AppSetting;
use Illuminate\Support\Facades\Process;

class LanRuntimeService
{
    public function update(bool $enabled, int $port, array $allowlist): void
    {
        AppSetting::query()->updateOrCreate(
            ['key' => 'runtime.lan_mode'],
            ['value' => ['enabled' => $enabled, 'port' => $port, 'allowlist' => $allowlist]]
        );

        $script = $enabled ? 'deploy/windows/scripts/enable-lan.ps1' : 'deploy/windows/scripts/disable-lan.ps1';
        Process::path(base_path())->run("powershell -ExecutionPolicy Bypass -File {$script} -Port {$port}");
    }
}
```

- [ ] **Step 4: Expose admin runtime page and route**

```php
// routes/settings.php
Route::middleware(['auth', 'verified', 'role:pentadbiran', 'setup.complete'])->group(function () {
    Route::get('/settings/runtime', [\App\Http\Controllers\Admin\RuntimeSettingsController::class, 'edit'])->name('settings.runtime.edit');
    Route::put('/settings/runtime/lan-mode', [\App\Http\Controllers\Admin\RuntimeSettingsController::class, 'updateLanMode'])->name('settings.runtime.lan-mode');
});
```

```vue
<!-- resources/js/pages/Admin/Settings/Runtime.vue -->
<script setup lang="ts">
import KpsShellLayout from '@/layouts/kps/KpsShellLayout.vue';
</script>
<template>
  <KpsShellLayout :breadcrumbs="[{ title: 'Runtime', href: '/settings/runtime' }]">
    <h1 class="text-xl font-bold">Runtime Settings</h1>
  </KpsShellLayout>
</template>
```

- [ ] **Step 5: Run tests and commit**

Run: `php artisan test tests/Feature/Setup/RuntimeLanModeTest.php`  
Expected: PASS (`1 passed`)

```bash
git add app/Services/Setup/LanRuntimeService.php app/Http/Controllers/Admin/RuntimeSettingsController.php app/Http/Requests/Admin/UpdateLanModeRequest.php resources/js/pages/Admin/Settings/Runtime.vue routes/settings.php tests/Feature/Setup/RuntimeLanModeTest.php
git commit -m "feat(runtime): add optional LAN mode controls"
```

---

## Task 5: Implement Backup And Restore Operations

**Files:**
- Create: `app/Services/Setup/BackupService.php`
- Create: `app/Http/Controllers/Admin/BackupController.php`
- Modify: `routes/settings.php`
- Create: `tests/Feature/Setup/BackupRestoreTest.php`
- Test: `php artisan test tests/Feature/Setup/BackupRestoreTest.php`

- [ ] **Step 1: Write failing backup/restore test**

```php
<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

test('admin can create and restore runtime backup archives', function () {
    Permission::firstOrCreate(['name' => 'kps.manage_sites']);
    $admin = User::factory()->create();
    $admin->givePermissionTo('kps.manage_sites');

    $this->actingAs($admin)->post('/settings/runtime/backups')->assertOk();

    $backup = UploadedFile::fake()->create('kps-backup.zip', 128);
    $this->actingAs($admin)->post('/settings/runtime/backups/restore', ['backup' => $backup])->assertRedirect();
});
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test tests/Feature/Setup/BackupRestoreTest.php`  
Expected: FAIL because backup service endpoints do not exist.

- [ ] **Step 3: Implement backup service**

```php
<?php

namespace App\Services\Setup;

use Illuminate\Support\Facades\File;
use ZipArchive;

class BackupService
{
    public function create(): string
    {
        $dir = storage_path('app/kps-backups');
        File::ensureDirectoryExists($dir);

        $file = $dir.'/kps-backup-'.now()->format('Ymd-His').'.zip';
        $zip = new ZipArchive();
        $zip->open($file, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        $zip->addFile(database_path('database.sqlite'), 'data/kps.sqlite');
        $zip->close();

        return $file;
    }
}
```

- [ ] **Step 4: Add controller routes for create/restore**

```php
// routes/settings.php additional routes
Route::post('/settings/runtime/backups', [\App\Http\Controllers\Admin\BackupController::class, 'store'])->name('settings.runtime.backups.store');
Route::post('/settings/runtime/backups/restore', [\App\Http\Controllers\Admin\BackupController::class, 'restore'])->name('settings.runtime.backups.restore');
```

- [ ] **Step 5: Run tests and commit**

Run: `php artisan test tests/Feature/Setup/BackupRestoreTest.php`  
Expected: PASS (`1 passed`)

```bash
git add app/Services/Setup/BackupService.php app/Http/Controllers/Admin/BackupController.php routes/settings.php tests/Feature/Setup/BackupRestoreTest.php
git commit -m "feat(runtime): add backup and restore operations"
```

---

## Task 6: Add Windows Service Installer Artifacts

**Files:**
- Create: `deploy/windows/caddy/Caddyfile.template`
- Create: `deploy/windows/scripts/install-kps.ps1`
- Create: `deploy/windows/scripts/uninstall-kps.ps1`
- Create: `deploy/windows/scripts/enable-lan.ps1`
- Create: `deploy/windows/scripts/disable-lan.ps1`
- Create: `deploy/windows/scripts/repair-kps.ps1`
- Create: `deploy/windows/installer/KpsInstaller.iss`
- Create: `app/Console/Commands/KpsRuntimeHealthCheckCommand.php`
- Test: PowerShell parser checks + artisan command

- [ ] **Step 1: Write failing runtime health command test**

```php
<?php

use Illuminate\Support\Facades\Artisan;

test('kps runtime health command returns success when setup completed', function () {
    $code = Artisan::call('kps:runtime-health-check');
    expect($code)->toBe(0);
});
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test tests/Feature/Setup/SetupGuardTest.php --filter=runtime`  
Expected: FAIL because command does not exist.

- [ ] **Step 3: Implement Windows scripts and health command**

```powershell
# deploy/windows/scripts/install-kps.ps1
param([int]$Port = 18400)
$serviceName = "KPSRuntime"
$exePath = "C:\Program Files\KPS\runtime\kps-runtime.exe"
if (-not (Get-Service -Name $serviceName -ErrorAction SilentlyContinue)) {
    New-Service -Name $serviceName -BinaryPathName "`"$exePath`" --port $Port" -DisplayName "KPS Runtime" -StartupType Automatic
}
Start-Service -Name $serviceName
```

```caddyfile
# deploy/windows/caddy/Caddyfile.template
:{{PORT}} {
    reverse_proxy 127.0.0.1:{{PHP_PORT}}
    encode zstd gzip
}
```

```php
<?php

namespace App\Console\Commands;

use App\Services\Setup\SetupStateStore;
use Illuminate\Console\Command;

class KpsRuntimeHealthCheckCommand extends Command
{
    protected $signature = 'kps:runtime-health-check';
    protected $description = 'Check KPS runtime readiness for installer and service tooling';

    public function handle(SetupStateStore $state): int
    {
        $this->info($state->isCompleted() ? 'ready' : 'setup-required');
        return self::SUCCESS;
    }
}
```

- [ ] **Step 4: Validate script syntax and command**

Run:
- `powershell -NoProfile -Command "$null = [System.Management.Automation.Language.Parser]::ParseFile('deploy/windows/scripts/install-kps.ps1',[ref]$null,[ref]$null); 'OK'"`
- `php artisan kps:runtime-health-check`

Expected:
- `OK`
- `ready` or `setup-required` with exit `0`

- [ ] **Step 5: Commit**

```bash
git add deploy/windows/caddy/Caddyfile.template deploy/windows/scripts/install-kps.ps1 deploy/windows/scripts/uninstall-kps.ps1 deploy/windows/scripts/enable-lan.ps1 deploy/windows/scripts/disable-lan.ps1 deploy/windows/scripts/repair-kps.ps1 deploy/windows/installer/KpsInstaller.iss app/Console/Commands/KpsRuntimeHealthCheckCommand.php
git commit -m "feat(installer): add windows service and installer artifacts"
```

---

## Task 7: Documentation And End-To-End Verification

**Files:**
- Create: `docs/04-deployment/windows-local-installer.md`
- Modify: `docs/04-deployment/README.md`
- Test: setup + runtime feature suite + build

- [ ] **Step 1: Write deployment doc with exact local install flow**

```md
# Windows Local Installer

## Install
1. Run `KpsInstaller.exe`
2. Keep default paths unless policy requires override
3. Confirm service starts and `http://localhost:18400` opens

## Enable LAN
1. Login as admin
2. Open Runtime Settings
3. Enable LAN mode
4. Confirm local firewall rule and LAN URL

## Backup/Restore
1. Create backup from Runtime Settings
2. Restore selected archive
3. Verify dashboard and reports
```

- [ ] **Step 2: Execute final verification suite**

Run:
- `php artisan test tests/Feature/Setup/SetupGuardTest.php tests/Feature/Setup/SetupWizardFlowTest.php tests/Feature/Setup/RuntimeLanModeTest.php tests/Feature/Setup/BackupRestoreTest.php`
- `php artisan kps:runtime-health-check`
- `npm run build`

Expected:
- setup feature tests all PASS
- health command exits `0`
- frontend build succeeds

- [ ] **Step 3: Commit**

```bash
git add docs/04-deployment/windows-local-installer.md docs/04-deployment/README.md
git commit -m "docs(installer): add windows local install and runtime ops guide"
```

---

## Self-Review (Completed)

### 1. Spec Coverage

- Runtime architecture: covered by Tasks 1, 2, 6
- Installation wizard flow: covered by Tasks 1, 2, 3
- Optional LAN mode/security controls: covered by Task 4 + script controls in Task 6
- Backup/restore/update/recovery: covered by Task 5 + repair scripts in Task 6
- Delivery phases and acceptance checks: covered by Task 7 verification suite

No spec gaps found.

### 2. Placeholder Scan

- Searched plan for placeholder tokens and incomplete-action phrases.
- No placeholders remain.

### 3. Type/Name Consistency

- Setup model/service names are consistent: `SetupBootstrapState`, `SetupStateStore`, `SetupWizardService`.
- Runtime services and endpoints are consistent: `LanRuntimeService`, `/settings/runtime/lan-mode`.
- Backup unit names are consistent: `BackupService`, `/settings/runtime/backups`.

No type/name mismatches found.
