# KPS UIUX Live Shell Migration Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Replace the live KPS shell with a rebuilt preview-inspired UIUX shell on the existing `/kps/...` routes, adopt the preview color system in production, and delete the preview stack entirely.

**Architecture:** Keep `resources/js/layouts/kps/KpsShellLayout.vue` as the stable import surface used by live pages, but rewrite its internals to compose new production-only components: `KpsUiuxRail.vue`, `KpsUiuxSitePanel.vue`, and `KpsUiuxHeader.vue`. Remove preview routes, middleware exceptions, preview pages/components/controller, and regenerate Wayfinder artifacts so no `/kps/preview` symbols remain in the production codebase.

**Tech Stack:** Laravel 12, Inertia.js, Vue 3 + TypeScript, Tailwind CSS 4, Pest, Laravel Wayfinder

---

## File Map

### Create

- `resources/js/components/kps/KpsUiuxHeader.vue`
- `resources/js/components/kps/KpsUiuxRail.vue`
- `resources/js/components/kps/KpsUiuxSitePanel.vue`
- `tests/Feature/KpsUiuxShellTest.php`

### Modify

- `routes/kps.php`
- `app/Http/Middleware/EnsureKpsSiteContext.php`
- `resources/js/layouts/kps/KpsShellLayout.vue`
- `resources/js/components/AppSidebarHeader.vue`
- `resources/js/routes/kps/index.ts` via `php artisan wayfinder:generate --path=resources/js --with-form`
- `resources/js/actions/App/Http/Controllers/Kps/index.ts` via `php artisan wayfinder:generate --path=resources/js --with-form`
- `resources/js/actions/Illuminate/Routing/RedirectController.ts` via `php artisan wayfinder:generate --path=resources/js --with-form`

### Delete

- `app/Http/Controllers/Kps/StitchPreviewController.php`
- `tests/Feature/KpsStitchPreviewTest.php`
- `resources/js/layouts/kps/StitchPreviewLayout.vue`
- `resources/js/components/kps/KpsMainSidebar.vue`
- `resources/js/components/kps/KpsSiteSidebar.vue`
- `resources/js/components/kps/preview/types.ts`
- `resources/js/components/kps/preview/StitchPreviewRail.vue`
- `resources/js/components/kps/preview/StitchPreviewPanel.vue`
- `resources/js/pages/Kps/Preview/Index.vue`
- `resources/js/pages/Kps/Preview/StitchDashboard.vue`
- `resources/js/pages/Kps/Preview/StitchSiteDashboard.vue`
- `resources/js/pages/Kps/Preview/StitchSiteReports.vue`
- `resources/js/routes/kps/preview/index.ts`
- `resources/js/routes/kps/preview/stitch/index.ts`
- `resources/js/routes/kps/preview/stitch/sites/index.ts`
- `resources/js/actions/App/Http/Controllers/Kps/StitchPreviewController.ts`

### Keep Stable

- `resources/js/layouts/kps/SiteShellLayout.vue`
- Live page imports that already point at `KpsShellLayout.vue`

---

## Task 1: Replace Preview Coverage And Remove Preview Backend Routes

**Files:**
- Create: `tests/Feature/KpsUiuxShellTest.php`
- Modify: `routes/kps.php`
- Modify: `app/Http/Middleware/EnsureKpsSiteContext.php`
- Delete: `app/Http/Controllers/Kps/StitchPreviewController.php`
- Delete: `tests/Feature/KpsStitchPreviewTest.php`
- Test: `php artisan test tests/Feature/KpsUiuxShellTest.php`

- [ ] **Step 1: Write the failing regression test**

```php
<?php

use App\Models\Kps\AuditLog;
use App\Models\Kps\Debt;
use App\Models\Kps\MonthlyDeduction;
use App\Models\Kps\Peneroka;
use App\Models\Kps\Site;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

beforeEach(function () {
    Carbon::setTestNow(Carbon::parse('2026-04-05 09:00:00'));

    foreach (['kps.manage_sites', 'kps.view', 'kps.view_reports'] as $permission) {
        Permission::firstOrCreate(['name' => $permission]);
    }

    $this->site = Site::factory()->create(['name' => 'FELDA Sungai Tekam', 'code' => 'FELDA-ST']);
    $this->peneroka = Peneroka::factory()->create(['site_id' => $this->site->id, 'name' => 'Ahmad bin Abdullah']);

    Debt::factory()->create([
        'peneroka_id' => $this->peneroka->id,
        'priority' => 2,
        'balance' => 260,
        'original_amount' => 600,
        'due_date' => Carbon::parse('2026-04-20')->toDateString(),
    ]);

    MonthlyDeduction::factory()->create([
        'site_id' => $this->site->id,
        'peneroka_id' => $this->peneroka->id,
        'month' => now()->startOfMonth()->toDateString(),
        'amount' => 205,
        'unallocated_amount' => 0,
        'is_closed' => true,
    ]);

    $this->hqUser = tap(User::factory()->create(), fn ($user) => $user->givePermissionTo('kps.manage_sites'));
    $this->siteReader = tap(User::factory()->create(), fn ($user) => $user->givePermissionTo(['kps.view', 'kps.view_reports']));
    $this->siteViewer = tap(User::factory()->create(), fn ($user) => $user->givePermissionTo('kps.view'));

    $this->site->assignUser($this->siteReader->id, 'site_admin');
    $this->site->assignUser($this->siteViewer->id, 'staff');

    AuditLog::create([
        'site_id' => $this->site->id,
        'user_id' => $this->siteReader->id,
        'action' => 'month_closed',
        'auditable_type' => Site::class,
        'auditable_id' => $this->site->id,
        'metadata' => ['month' => now()->startOfMonth()->toDateString(), 'deductions_closed' => 1],
    ]);
});

afterEach(fn () => Carbon::setTestNow());

test('hq users keep live routes while preview routes return 404', function () {
    $this->actingAs($this->hqUser)
        ->get('/kps/dashboard')
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('Kps/Dashboard')->where('stats.sites', 1));

    $this->actingAs($this->hqUser)
        ->get('/kps/analytics')
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('Kps/Analytics'));

    $this->actingAs($this->hqUser)->get('/kps/preview')->assertNotFound();
    $this->actingAs($this->hqUser)->get('/kps/preview/stitch/dashboard')->assertNotFound();
});

test('site users keep report boundaries after preview removal', function () {
    $this->actingAs($this->siteReader)
        ->get("/kps/sites/{$this->site->id}")
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('Kps/Sites/Show')->where('site.id', $this->site->id));

    $this->actingAs($this->siteViewer)
        ->get("/kps/sites/{$this->site->id}/reports")
        ->assertForbidden();

    $this->actingAs($this->siteReader)
        ->get("/kps/sites/{$this->site->id}/reports")
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('Kps/Reports/Index')->where('site.id', $this->site->id));

    $this->actingAs($this->siteReader)
        ->get("/kps/preview/stitch/sites/{$this->site->id}/reports")
        ->assertNotFound();
});
```

- [ ] **Step 2: Run the test to verify it fails**

Run: `php artisan test tests/Feature/KpsUiuxShellTest.php`

Expected: FAIL because `/kps/preview` and `/kps/preview/stitch/...` still return `200`.

- [ ] **Step 3: Remove preview routes from `routes/kps.php`**

```php
use App\Http\Controllers\Kps\AllocationReviewController;
use App\Http\Controllers\Kps\AnalyticsController;
use App\Http\Controllers\Kps\AuditLogController;
use App\Http\Controllers\Kps\DashboardController;
use App\Http\Controllers\Kps\DebtController;
use App\Http\Controllers\Kps\MonthlyDeductionController;
use App\Http\Controllers\Kps\PenerokaController;
use App\Http\Controllers\Kps\ReportController;
use App\Http\Controllers\Kps\SiteController;
use App\Http\Middleware\EnsureKpsSiteContext;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', EnsureKpsSiteContext::class])
    ->prefix('kps')
    ->name('kps.')
    ->group(function () {
        Route::get('/', fn () => redirect()->route('kps.dashboard'))->name('home');
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('analytics', [AnalyticsController::class, 'index'])->name('analytics');
        Route::resource('sites', SiteController::class);

        Route::prefix('sites/{site}')->group(function () {
            // existing site-scoped routes stay unchanged
        });
    });
```

- [ ] **Step 4: Remove the preview-route exception from `EnsureKpsSiteContext.php`**

```php
$isHq = $user->hasPermissionTo('kps.manage_sites');
$isAdmin = $user->hasRole(['pentadbiran', 'company_admin']);
$routeSite = $request->route('site');

if (! $routeSite && ! $isAdmin) {
    $site = $user->getFirstKpsSite();
    if ($site) {
        return redirect()->route('kps.sites.show', $site->id);
    }
}
```

- [ ] **Step 5: Delete the obsolete preview backend files**

Run:

```bash
git rm -- app/Http/Controllers/Kps/StitchPreviewController.php tests/Feature/KpsStitchPreviewTest.php
```

Expected: both files are staged for deletion.

- [ ] **Step 6: Run the regression test to verify it passes**

Run: `php artisan test tests/Feature/KpsUiuxShellTest.php`

Expected: PASS.

- [ ] **Step 7: Commit**

```bash
git add -- tests/Feature/KpsUiuxShellTest.php routes/kps.php app/Http/Middleware/EnsureKpsSiteContext.php
git commit -m "test: replace KPS preview route coverage with live shell regression tests"
```

---

## Task 2: Build The Production UIUX Shell

**Files:**
- Create: `resources/js/components/kps/KpsUiuxHeader.vue`
- Create: `resources/js/components/kps/KpsUiuxRail.vue`
- Create: `resources/js/components/kps/KpsUiuxSitePanel.vue`
- Modify: `resources/js/layouts/kps/KpsShellLayout.vue`
- Test: `npm run build`

Note: this repo has no Vue unit-test harness. Use the build as the red/green gate for the new shell components.

- [ ] **Step 1: Point `KpsShellLayout.vue` at the new production components**

```vue
<script setup lang="ts">
import KpsUiuxHeader from '@/components/kps/KpsUiuxHeader.vue';
import KpsUiuxRail from '@/components/kps/KpsUiuxRail.vue';
import KpsUiuxSitePanel from '@/components/kps/KpsUiuxSitePanel.vue';
import type { BreadcrumbItemType, KpsSite, KpsSiteRole } from '@/types';
import { computed, onBeforeUnmount, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';

const props = withDefaults(defineProps<{
    breadcrumbs?: BreadcrumbItemType[];
    site?: KpsSite | null;
    siteRole?: KpsSiteRole | null;
    forceSiteOnly?: boolean;
}>(), {
    breadcrumbs: () => [],
    site: null,
    siteRole: null,
    forceSiteOnly: false,
});

const page = usePage();
const isAdmin = computed(() => page.props.auth?.isCompanyAdmin ?? false);
const isHq = computed(() => (page.props.auth?.permissions || []).includes('kps.manage_sites'));
const showRail = computed(() => isHq.value && !props.forceSiteOnly);
const showSitePanel = computed(() => !!props.site);
const contentOffsetClass = computed(() => {
    if (showRail.value && showSitePanel.value) return 'lg:ml-[408px]';
    if (showRail.value) return 'lg:ml-[104px]';
    if (showSitePanel.value) return 'lg:ml-[320px]';
    return '';
});
</script>
```

- [ ] **Step 2: Run the build to verify it fails because the new components do not exist yet**

Run: `npm run build`

Expected: FAIL with missing module errors for the three `KpsUiux*.vue` files.

- [ ] **Step 3: Create `resources/js/components/kps/KpsUiuxRail.vue`**

```vue
<script setup lang="ts">
import type { AppPageProps } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { BarChart3, LayoutGrid, Shield, Users, Warehouse, type LucideIcon } from 'lucide-vue-next';
import { computed } from 'vue';

interface NavItem { title: string; href: string; icon: LucideIcon; show?: boolean; active: boolean; }

const page = usePage<AppPageProps>();
const can = (permission: string) => (page.props.auth?.permissions ?? []).includes(permission);

const navItems = computed<NavItem[]>(() =>
    [
        { title: 'Dashboard', href: '/kps/dashboard', icon: LayoutGrid, active: page.url.startsWith('/kps/dashboard') },
        { title: 'Analytics', href: '/kps/analytics', icon: BarChart3, show: can('kps.manage_sites'), active: page.url.startsWith('/kps/analytics') },
        { title: 'Sites', href: '/kps/sites', icon: Warehouse, show: can('kps.manage_sites'), active: page.url.startsWith('/kps/sites') },
        { title: 'Users', href: '/admin/users', icon: Users, show: can('view-users'), active: page.url.startsWith('/admin/users') },
        { title: 'Roles', href: '/admin/roles', icon: Shield, show: can('view-roles'), active: page.url.startsWith('/admin/roles') },
    ].filter((item) => item.show !== false),
);
</script>

<template>
    <aside class="fixed left-0 top-0 z-40 hidden h-screen w-[88px] flex-col items-center rounded-r-[32px] bg-[#171717] py-7 text-white shadow-[0_22px_70px_rgba(0,0,0,0.32)] lg:flex">
        <div class="mb-8 flex h-12 w-12 items-center justify-center rounded-[18px] bg-[#d84b27] text-base font-black">K</div>
        <nav class="flex flex-1 flex-col items-center gap-5">
            <Link
                v-for="item in navItems"
                :key="item.href"
                :href="item.href"
                class="flex h-12 w-12 items-center justify-center rounded-full transition"
                :class="item.active ? 'bg-black text-white' : 'text-white/45 hover:bg-white/6 hover:text-white'"
            >
                <component :is="item.icon" class="h-5 w-5" />
            </Link>
        </nav>
    </aside>
</template>
```

- [ ] **Step 4: Create `resources/js/components/kps/KpsUiuxSitePanel.vue`**

```vue
<script setup lang="ts">
import type { KpsSite, KpsSiteRole, NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { BarChart3, ClipboardList, FileText, History, LayoutGrid, Settings, Users, Wallet } from 'lucide-vue-next';
import { computed } from 'vue';
import { usePermission } from '@/composables/usePermission';

const props = withDefaults(defineProps<{ site: KpsSite; siteRole?: KpsSiteRole | null; withRail?: boolean; }>(), {
    siteRole: null,
    withRail: false,
});

const page = usePage();
const { hasPermission } = usePermission();
const baseUrl = computed(() => `/kps/sites/${props.site.id}`);
const navItems = computed<NavItem[]>(() => [
    { title: 'Site Dashboard', href: baseUrl.value, icon: LayoutGrid },
    { title: 'Peneroka', href: `${baseUrl.value}/peneroka`, icon: Users, permission: 'kps.manage_peneroka' },
    { title: 'Hutang', href: `${baseUrl.value}/hutang`, icon: ClipboardList, permission: 'kps.manage_hutang' },
    { title: 'Potongan Bulanan', href: `${baseUrl.value}/potongan`, icon: Wallet, permission: 'kps.manage_potongan' },
    { title: 'Allocation Review', href: `${baseUrl.value}/allocations`, icon: BarChart3, permission: 'kps.manage_potongan' },
    { title: 'Reports', href: `${baseUrl.value}/reports`, icon: FileText, permission: 'kps.view_reports' },
    { title: 'Audit Trail', href: `${baseUrl.value}/audit-logs`, icon: History, permission: 'kps.approve_month' },
    { title: 'Site Settings', href: `${baseUrl.value}/edit`, icon: Settings, permission: 'kps.manage_sites' },
].filter((item) => !item.permission || hasPermission(item.permission)));
</script>

<template>
    <aside class="fixed top-0 z-30 hidden h-screen w-[320px] flex-col border-r border-[#ead6ce] bg-[rgba(247,241,238,0.84)] px-6 py-8 text-[#1b1b1b] backdrop-blur-2xl lg:flex" :class="props.withRail ? 'left-[88px]' : 'left-0'">
        <div class="rounded-[28px] border border-[#f0dbd4] bg-white/88 p-5">
            <p class="text-[11px] font-bold uppercase tracking-[0.28em] text-[#b7654b]">Site Workspace</p>
            <h2 class="mt-2 text-xl font-black text-[#1b1b1b]">{{ site.name }}</h2>
            <p class="mt-2 text-sm leading-6 text-[#65534d]">{{ siteRole?.replace('_', ' ') || 'staff' }} / {{ site.code }}</p>
        </div>
        <div class="mt-7 space-y-2">
            <Link
                v-for="item in navItems"
                :key="item.href"
                :href="item.href"
                class="flex items-center gap-3 rounded-[22px] border px-4 py-3 transition"
                :class="page.url.startsWith(item.href) ? 'border-[#d6522d] bg-[#171717] text-white' : 'border-[#ead6ce] bg-white/80 text-[#2a2422]'"
            >
                <component :is="item.icon" class="h-4 w-4" />
                <span class="text-sm font-semibold">{{ item.title }}</span>
            </Link>
        </div>
    </aside>
</template>
```

- [ ] **Step 5: Create `resources/js/components/kps/KpsUiuxHeader.vue`**

```vue
<script setup lang="ts">
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import type { AppPageProps, BreadcrumbItemType } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = withDefaults(defineProps<{ breadcrumbs?: BreadcrumbItemType[] }>(), { breadcrumbs: () => [] });
const page = usePage<AppPageProps>();
const currentUser = computed(() => page.props.auth?.user?.name ?? 'KPS User');
</script>

<template>
    <header class="px-4 pb-4 pt-4 lg:px-8 lg:pt-6">
        <div class="sticky top-4 z-20 rounded-[30px] border border-white/80 bg-white/82 p-4 shadow-[0_20px_70px_rgba(124,73,55,0.08)] backdrop-blur-xl">
            <div class="flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <span class="hidden rounded-full bg-[#fff1ec] px-4 py-3 text-[11px] font-bold uppercase tracking-[0.24em] text-[#c55532] md:inline-flex">KPS UIUX Shell</span>
                    <Breadcrumbs v-if="props.breadcrumbs.length > 0" :breadcrumbs="props.breadcrumbs" />
                </div>
                <div class="rounded-full bg-[#171717] px-5 py-3 text-sm font-semibold text-white">{{ currentUser }}</div>
            </div>
        </div>
    </header>
</template>
```

- [ ] **Step 6: Rewrite `resources/js/layouts/kps/KpsShellLayout.vue`**

```vue
<script setup lang="ts">
import KpsUiuxHeader from '@/components/kps/KpsUiuxHeader.vue';
import KpsUiuxRail from '@/components/kps/KpsUiuxRail.vue';
import KpsUiuxSitePanel from '@/components/kps/KpsUiuxSitePanel.vue';
import type { BreadcrumbItemType, KpsSite, KpsSiteRole } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = withDefaults(defineProps<{
    breadcrumbs?: BreadcrumbItemType[];
    site?: KpsSite | null;
    siteRole?: KpsSiteRole | null;
    forceSiteOnly?: boolean;
}>(), {
    breadcrumbs: () => [],
    site: null,
    siteRole: null,
    forceSiteOnly: false,
});

const page = usePage();
const isHq = computed(() => (page.props.auth?.permissions || []).includes('kps.manage_sites'));
const showRail = computed(() => isHq.value && !props.forceSiteOnly);
const showSitePanel = computed(() => !!props.site);
const offsetClass = computed(() => {
    if (showRail.value && showSitePanel.value) return 'lg:ml-[408px]';
    if (showRail.value) return 'lg:ml-[104px]';
    if (showSitePanel.value) return 'lg:ml-[320px]';
    return '';
});
</script>

<template>
    <div class="min-h-screen bg-[radial-gradient(circle_at_top_right,_rgba(216,82,45,0.08),_transparent_34%),linear-gradient(180deg,#f7f4f2_0%,#f7f9fc_48%,#f3eee9_100%)] text-[#1b1b1b]" data-kps-uiux-shell="true">
        <KpsUiuxRail v-if="showRail" />
        <KpsUiuxSitePanel v-if="showSitePanel" :site="site" :site-role="siteRole" :with-rail="showRail" />
        <div :class="offsetClass">
            <KpsUiuxHeader :breadcrumbs="breadcrumbs" />
            <main class="px-4 pb-10 lg:px-8">
                <slot />
            </main>
        </div>
    </div>
 </template>
```

- [ ] **Step 7: Run the build to verify the shell compiles**

Run: `npm run build`

Expected: PASS.

- [ ] **Step 8: Commit**

```bash
git add -- resources/js/layouts/kps/KpsShellLayout.vue resources/js/components/kps/KpsUiuxHeader.vue resources/js/components/kps/KpsUiuxRail.vue resources/js/components/kps/KpsUiuxSitePanel.vue
git commit -m "feat: rebuild the live KPS shell with UIUX components"
```

---

## Task 3: Remove Preview Frontend Files And Regenerate Wayfinder

**Files:**
- Modify: `resources/js/components/AppSidebarHeader.vue`
- Delete: preview layout, preview pages, preview components, preview routes, preview action file, and old KPS sidebar files
- Modify: Wayfinder-generated route/action files
- Test: `npm run build`
- Test: `php artisan wayfinder:generate --path=resources/js --with-form`

- [ ] **Step 1: Remove the KPS-specific preview button logic from `AppSidebarHeader.vue`**

```vue
<script setup lang="ts">
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import { SidebarTrigger } from '@/components/ui/sidebar';
import type { BreadcrumbItemType } from '@/types';
import { computed } from 'vue';

const props = withDefaults(defineProps<{ breadcrumbs?: BreadcrumbItemType[]; showSidebarTrigger?: boolean; theme?: 'default' | 'kps' }>(), {
    breadcrumbs: () => [],
    showSidebarTrigger: true,
    theme: 'default',
});

const isKpsTheme = computed(() => props.theme === 'kps');
</script>
```

- [ ] **Step 2: Delete the preview and old sidebar files**

Run:

```bash
git rm -r -- resources/js/layouts/kps/StitchPreviewLayout.vue resources/js/components/kps/preview resources/js/pages/Kps/Preview resources/js/components/kps/KpsMainSidebar.vue resources/js/components/kps/KpsSiteSidebar.vue resources/js/routes/kps/preview resources/js/actions/App/Http/Controllers/Kps/StitchPreviewController.ts
```

Expected: all preview interface files and unused old sidebars are staged for deletion.

- [ ] **Step 3: Run the build to confirm stale generated imports still fail**

Run: `npm run build`

Expected: FAIL because generated Wayfinder files still reference preview modules.

- [ ] **Step 4: Regenerate Wayfinder artifacts**

Run: `php artisan wayfinder:generate --path=resources/js --with-form`

Expected: exit `0` and the generated files remove preview imports from:

```ts
// resources/js/routes/kps/index.ts
const kps = {
    home: Object.assign(home, home),
    dashboard: Object.assign(dashboard, dashboard),
    analytics: Object.assign(analytics, analytics),
    sites: Object.assign(sites, sites),
    peneroka: Object.assign(peneroka, peneroka),
    hutang: Object.assign(hutang, hutang),
    potongan: Object.assign(potongan, potongan),
    allocations: Object.assign(allocations, allocations),
    reports: Object.assign(reports, reports),
    auditLogs: Object.assign(auditLogs, auditLogs),
}
```

- [ ] **Step 5: Run the build again to verify preview symbols are gone**

Run: `npm run build`

Expected: PASS.

- [ ] **Step 6: Confirm no preview symbols remain in production code**

Run: `rg -n "/kps/preview|preview/stitch|StitchPreviewController|StitchPreviewLayout|Preview Library|Preview Mode" app resources routes tests -S`

Expected: no matches outside migration docs.

- [ ] **Step 7: Commit**

```bash
git add -- resources/js/components/AppSidebarHeader.vue resources/js/routes/kps/index.ts resources/js/actions/App/Http/Controllers/Kps/index.ts resources/js/actions/Illuminate/Routing/RedirectController.ts
git commit -m "refactor: remove KPS preview interface and stale generated artifacts"
```

---

## Task 4: Final Verification

**Files:**
- Test only: `tests/Feature/KpsUiuxShellTest.php`
- Test only: `tests/Feature/KpsSiteTest.php`
- Test only: `tests/Feature/DashboardTest.php`

- [ ] **Step 1: Run the targeted Laravel regression suite**

Run:

```bash
php artisan test tests/Feature/KpsUiuxShellTest.php tests/Feature/KpsSiteTest.php tests/Feature/DashboardTest.php
```

Expected: PASS.

- [ ] **Step 2: Run the frontend build as the final UI gate**

Run: `npm run build`

Expected: PASS.

- [ ] **Step 3: Inspect the final diff**

Run:

```bash
git diff -- app/Http/Middleware/EnsureKpsSiteContext.php routes/kps.php resources/js/layouts/kps/KpsShellLayout.vue resources/js/components/kps resources/js/components/AppSidebarHeader.vue resources/js/routes/kps resources/js/actions/App/Http/Controllers/Kps resources/js/actions/Illuminate/Routing/RedirectController.ts tests/Feature/KpsUiuxShellTest.php tests/Feature/KpsSiteTest.php tests/Feature/DashboardTest.php
```

Expected: only live-shell rebuild, preview-removal, generated artifact cleanup, and regression coverage changes remain.

- [ ] **Step 4: Create the final integration commit**

```bash
git add -- app/Http/Middleware/EnsureKpsSiteContext.php routes/kps.php resources/js/layouts/kps/KpsShellLayout.vue resources/js/components/kps resources/js/components/AppSidebarHeader.vue resources/js/routes/kps resources/js/actions/App/Http/Controllers/Kps resources/js/actions/Illuminate/Routing/RedirectController.ts tests/Feature/KpsUiuxShellTest.php tests/Feature/KpsSiteTest.php tests/Feature/DashboardTest.php
git commit -m "feat: replace the live KPS shell with the UIUX workspace"
```

---

## Parallel Execution Split

### Worker 1: Live Shell Rebuild

- Own:
  - `resources/js/layouts/kps/KpsShellLayout.vue`
  - `resources/js/components/kps/KpsUiuxHeader.vue`
  - `resources/js/components/kps/KpsUiuxRail.vue`
  - `resources/js/components/kps/KpsUiuxSitePanel.vue`
- Do not touch:
  - backend route files
  - test files
  - Wayfinder-generated files

### Worker 2: Preview Removal And Regression Coverage

- Own:
  - `tests/Feature/KpsUiuxShellTest.php`
  - `routes/kps.php`
  - `app/Http/Middleware/EnsureKpsSiteContext.php`
  - preview deletions
  - Wayfinder regeneration outputs
  - `resources/js/components/AppSidebarHeader.vue`
- Do not touch:
  - the implementation details inside the new `KpsUiux*.vue` components

### Main Thread

- Review Worker 1 and Worker 2 diffs
- Resolve any overlap in `KpsShellLayout.vue`
- Run Task 4 verification

---

## Plan Self-Review

### Spec Coverage

- Live shell replacement:
  - Task 2
- Preview route/controller/component removal:
  - Task 1 and Task 3
- Permission boundary preservation:
  - Task 1 and Task 4
- Wayfinder regeneration:
  - Task 3
- Final verification:
  - Task 4

### Placeholder Scan

- No `TODO`, `TBD`, or `similar to` instructions remain
- Every task has exact file paths and commands
- Every verification step has an expected outcome

### Type And Naming Consistency

- `KpsShellLayout.vue` stays the public layout contract
- `KpsUiuxHeader.vue`, `KpsUiuxRail.vue`, and `KpsUiuxSitePanel.vue` are the only new production shell component names
- Preview-specific names are removed rather than reused
