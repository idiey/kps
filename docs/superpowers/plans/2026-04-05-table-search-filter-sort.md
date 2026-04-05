# Table Search, Filter, Sort — Nuxt UI Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Install Nuxt UI and replace all eight data tables with `UTable` — applying search, sort, column visibility, row selection, and pagination using Nuxt UI's built-in component features backed by server-side data fetching via Inertia `router.get()`.

**Architecture:** Nuxt UI's `UTable` (built on TanStack Table) handles all table UI. A shared `useServerTable` composable bridges `UTable`'s reactive state (sorting, globalFilter) to Inertia `router.get()` calls. Backend controllers are augmented to accept `search`, `sort_by`, `sort_dir`, and table-specific filter params. Pagination uses `UPagination` against server-returned page metadata. TanStack `manualSorting: true` and `manualFiltering: true` prevent client-side recalculation since data comes presorted/prefiltered from the server.

**Tech Stack:** `@nuxt/ui` (UTable, UInput, UPagination, UDropdownMenu, UButton, UBadge), `@tanstack/vue-table` (SortingState type), Laravel 12 Eloquent, Inertia.js `router.get()`.

---

## File Map

**New files:**
- `resources/js/composables/useServerTable.ts` — bridges UTable state to Inertia navigation

**Modified config files:**
- `vite.config.ts` — replace `tailwindcss()` with `ui()` from `@nuxt/ui/vite`
- `resources/js/app.ts` — register `@nuxt/ui` Vue plugin + wrap root with `UApp`
- `resources/css/app.css` — add `@import "@nuxt/ui"` after `@import "tailwindcss"`

**Modified PHP controllers:**
- `app/Http/Controllers/Kps/PenerokaController.php`
- `app/Http/Controllers/Kps/DebtController.php`
- `app/Http/Controllers/Kps/MonthlyDeductionController.php`
- `app/Http/Controllers/Kps/AllocationReviewController.php`
- `app/Http/Controllers/Kps/AuditLogController.php`
- `app/Http/Controllers/Kps/ReportController.php`
- `app/Services/Kps/SiteExperienceService.php`
- `app/Http/Controllers/Admin/UserManagementController.php`
- `app/Http/Controllers/Admin/RoleManagementController.php`

**Modified Vue pages (replace table section only):**
- `resources/js/pages/Kps/Peneroka/Index.vue`
- `resources/js/pages/Kps/Hutang/Index.vue`
- `resources/js/pages/Kps/Potongan/Index.vue`
- `resources/js/pages/Kps/Allocations/Index.vue`
- `resources/js/pages/Kps/AuditLogs/Index.vue`
- `resources/js/pages/Kps/Reports/Index.vue`
- `resources/js/pages/Admin/Users/Index.vue`
- `resources/js/pages/Admin/Roles/Index.vue`

---

## Task 1: Install and Configure @nuxt/ui

**Files:** `package.json`, `vite.config.ts`, `resources/js/app.ts`, `resources/css/app.css`

- [ ] **Step 1: Install the package**

```bash
npm install @nuxt/ui
```

Expected output: `added N packages` with no errors.

- [ ] **Step 2: Update `vite.config.ts`**

Replace the entire file:

```typescript
import ui from '@nuxt/ui/vite';
import { wayfinder } from '@laravel/vite-plugin-wayfinder';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import { defineConfig } from 'vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.ts'],
            ssr: 'resources/js/ssr.ts',
            refresh: true,
        }),
        ui(),
        wayfinder({
            formVariants: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            '@': '/resources/js',
        },
    },
    server: {
        host: '127.0.0.1',
        port: 5173,
        hmr: {
            host: '127.0.0.1',
        },
    },
});
```

Note: `ui()` replaces `tailwindcss()` — it bundles Tailwind v4 configuration internally.

- [ ] **Step 3: Update `resources/css/app.css`**

Add `@import "@nuxt/ui"` immediately after `@import 'tailwindcss'`:

```css
@import 'tailwindcss';
@import '@nuxt/ui';
```

Keep all existing custom properties (`:root`, `.dark`, `@theme inline`, etc.) unchanged below.

- [ ] **Step 4: Update `resources/js/app.ts`**

Replace the entire file:

```typescript
import '../css/app.css';

import ui from '@nuxt/ui/vue-plugin';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, defineComponent, h } from 'vue';
import { UApp } from '@nuxt/ui';
import { initializeTheme } from './composables/useAppearance';

const appName = import.meta.env.VITE_APP_NAME || 'KPS';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) =>
        resolvePageComponent(
            `./pages/${name}.vue`,
            import.meta.glob<DefineComponent>('./pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        const RootApp = defineComponent({
            render: () => h(UApp, {}, { default: () => h(App, props) }),
        });
        createApp(RootApp)
            .use(plugin)
            .use(ui)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

initializeTheme();
```

- [ ] **Step 5: Verify the dev server starts**

```bash
npm run dev
```

Expected: Vite starts on port 5173 with no fatal errors. Open http://localhost:8000 and confirm the existing pages still render.

- [ ] **Step 6: Commit**

```bash
git add vite.config.ts resources/js/app.ts resources/css/app.css package.json package-lock.json
git commit -m "feat: install and configure @nuxt/ui with Vue plugin"
```

---

## Task 2: Shared `useServerTable` Composable

**Files:**
- Create: `resources/js/composables/useServerTable.ts`

- [ ] **Step 1: Write the composable**

```typescript
// resources/js/composables/useServerTable.ts
import { router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import type { SortingState } from '@tanstack/vue-table';

export interface ServerTableOptions {
    search?: string;
    sort_by?: string;
    sort_dir?: 'asc' | 'desc';
    [key: string]: string | undefined;
}

export function useServerTable(baseUrl: string, initial: ServerTableOptions = {}) {
    const globalFilter = ref<string>(initial.search ?? '');

    const sorting = ref<SortingState>(
        initial.sort_by
            ? [{ id: initial.sort_by, desc: initial.sort_dir === 'desc' }]
            : [],
    );

    // Extra filters (e.g., month, status, role, action)
    const extraFilters = ref<Record<string, string | undefined>>(
        Object.fromEntries(
            Object.entries(initial).filter(([k]) => !['search', 'sort_by', 'sort_dir'].includes(k)),
        ) as Record<string, string | undefined>,
    );

    let debounceTimer: ReturnType<typeof setTimeout> | null = null;

    const buildParams = (page?: number): Record<string, string> => {
        const sort = sorting.value[0];
        const raw: Record<string, string | undefined> = {
            ...extraFilters.value,
            search: globalFilter.value || undefined,
            sort_by: sort?.id || undefined,
            sort_dir: sort ? (sort.desc ? 'desc' : 'asc') : undefined,
            page: page ? String(page) : undefined,
        };
        return Object.fromEntries(
            Object.entries(raw).filter(([, v]) => v !== undefined),
        ) as Record<string, string>;
    };

    const navigate = (page?: number) => {
        router.get(baseUrl, buildParams(page), { preserveState: true, preserveScroll: true });
    };

    // Debounce search input — 400 ms
    watch(globalFilter, () => {
        if (debounceTimer) clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => navigate(), 400);
    });

    // Immediate navigate on sort change
    watch(sorting, () => navigate(), { deep: true });

    const setFilter = (key: string, value: string | undefined) => {
        extraFilters.value = { ...extraFilters.value, [key]: value || undefined };
        navigate();
    };

    const goToPage = (page: number) => navigate(page);

    return { globalFilter, sorting, extraFilters, setFilter, goToPage };
}
```

- [ ] **Step 2: Commit**

```bash
git add resources/js/composables/useServerTable.ts
git commit -m "feat: add useServerTable composable for UTable + Inertia server-side integration"
```

---

## Task 3: Backend — Add Search/Sort/Filter to All Controllers

All eight controllers need to accept `search`, `sort_by`, `sort_dir` (and table-specific filters) and return a `filters` prop so the frontend can initialize state.

- [ ] **Step 1: Update `PenerokaController::index()`**

In `app/Http/Controllers/Kps/PenerokaController.php`, replace the query block:

```php
public function index(Request $request, Site $site, SiteContextResolver $resolver): Response
{
    $this->authorize('view', $site);
    $this->authorize('viewAny', Peneroka::class);

    $search  = $request->string('search')->trim()->toString();
    $sortBy  = in_array($request->get('sort_by'), ['name', 'ic_number']) ? $request->get('sort_by') : 'name';
    $sortDir = $request->get('sort_dir') === 'desc' ? 'desc' : 'asc';

    $penerokas = Peneroka::query()
        ->where('site_id', $site->id)
        ->withCount('debts')
        ->withSum('debts as total_outstanding', 'balance')
        ->when($search !== '', fn ($q) => $q->where(fn ($i) =>
            $i->where('name', 'like', "%{$search}%")
              ->orWhere('ic_number', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%")
        ))
        ->orderBy($sortBy, $sortDir)
        ->paginate(15)
        ->withQueryString();

    $summary = [
        'total_peneroka'  => Peneroka::query()->where('site_id', $site->id)->count(),
        'with_ic_number'  => Peneroka::query()->where('site_id', $site->id)->whereNotNull('ic_number')->where('ic_number', '!=', '')->count(),
        'with_phone'      => Peneroka::query()->where('site_id', $site->id)->whereNotNull('phone')->where('phone', '!=', '')->count(),
        'outstanding_total' => (float) Debt::query()->whereHas('peneroka', fn ($q) => $q->where('site_id', $site->id))->sum('balance'),
    ];

    $context = $resolver->resolve($request, $site);

    return Inertia::render('Kps/Peneroka/Index', [
        'site'      => $site,
        'penerokas' => $penerokas,
        'summary'   => $summary,
        'siteRole'  => $context['siteRole'],
        'filters'   => ['search' => $search, 'sort_by' => $sortBy, 'sort_dir' => $sortDir],
    ]);
}
```

- [ ] **Step 2: Update `DebtController::index()`**

In `app/Http/Controllers/Kps/DebtController.php`:

```php
public function index(Request $request, Site $site, SiteContextResolver $resolver): Response
{
    $this->authorize('view', $site);
    $this->authorize('viewAny', Debt::class);

    $search  = $request->string('search')->trim()->toString();
    $status  = in_array($request->get('status'), ['open', 'paid']) ? $request->get('status') : 'all';
    $sortBy  = in_array($request->get('sort_by'), ['priority', 'balance', 'due_date']) ? $request->get('sort_by') : 'priority';
    $sortDir = $request->get('sort_dir') === 'desc' ? 'desc' : 'asc';

    $debts = Debt::query()
        ->with('peneroka')
        ->whereHas('peneroka', fn ($q) => $q->where('site_id', $site->id))
        ->when($search !== '', fn ($q) => $q->where(fn ($i) =>
            $i->whereHas('peneroka', fn ($p) => $p->where('name', 'like', "%{$search}%"))
              ->orWhere('description', 'like', "%{$search}%")
        ))
        ->when($status === 'open',  fn ($q) => $q->where('balance', '>', 0))
        ->when($status === 'paid',  fn ($q) => $q->where('balance', '<=', 0))
        ->when($sortBy === 'due_date',
            fn ($q) => $q->orderByRaw('due_date IS NULL')->orderBy('due_date', $sortDir),
            fn ($q) => $q->orderBy($sortBy, $sortDir)
        )
        ->paginate(15)
        ->withQueryString();

    $summary = [
        'total_debts'          => Debt::query()->whereHas('peneroka', fn ($q) => $q->where('site_id', $site->id))->count(),
        'outstanding_total'    => (float) Debt::query()->whereHas('peneroka', fn ($q) => $q->where('site_id', $site->id))->sum('balance'),
        'due_this_month'       => Debt::query()->whereHas('peneroka', fn ($q) => $q->where('site_id', $site->id))->whereBetween('due_date', [Carbon::now()->startOfMonth()->toDateString(), Carbon::now()->endOfMonth()->toDateString()])->count(),
        'highest_priority_open' => Debt::query()->whereHas('peneroka', fn ($q) => $q->where('site_id', $site->id))->where('balance', '>', 0)->min('priority'),
    ];

    $context = $resolver->resolve($request, $site);

    return Inertia::render('Kps/Hutang/Index', [
        'site'     => $site,
        'debts'    => $debts,
        'summary'  => $summary,
        'siteRole' => $context['siteRole'],
        'filters'  => ['search' => $search, 'status' => $status, 'sort_by' => $sortBy, 'sort_dir' => $sortDir],
    ]);
}
```

- [ ] **Step 3: Update `MonthlyDeductionController::index()`**

In `app/Http/Controllers/Kps/MonthlyDeductionController.php`:

```php
public function index(Request $request, Site $site, SiteContextResolver $resolver): Response
{
    $this->authorize('view', $site);
    $this->authorize('viewAny', MonthlyDeduction::class);

    $month     = $request->get('month');
    $monthDate = $month ? Carbon::createFromFormat('Y-m', $month)->startOfMonth()->toDateString() : Carbon::now()->startOfMonth()->toDateString();
    $selectedMonth = Carbon::parse($monthDate)->format('Y-m');

    $search  = $request->string('search')->trim()->toString();
    $status  = in_array($request->get('status'), ['open', 'closed']) ? $request->get('status') : 'all';
    $sortBy  = in_array($request->get('sort_by'), ['amount', 'peneroka_name']) ? $request->get('sort_by') : null;
    $sortDir = $request->get('sort_dir') === 'asc' ? 'asc' : 'desc';

    $query = MonthlyDeduction::query()
        ->with('peneroka')
        ->where('site_id', $site->id)
        ->whereDate('month', $monthDate)
        ->when($search !== '', fn ($q) =>
            $q->whereHas('peneroka', fn ($p) => $p->where('name', 'like', "%{$search}%"))
        )
        ->when($status === 'open',   fn ($q) => $q->where('is_closed', false))
        ->when($status === 'closed', fn ($q) => $q->where('is_closed', true))
        ->when($sortBy === 'amount',        fn ($q) => $q->orderBy('amount', $sortDir))
        ->when($sortBy === 'peneroka_name', fn ($q) =>
            $q->orderBy(\App\Models\Kps\Peneroka::select('name')->whereColumn('penerokas.id', 'monthly_deductions.peneroka_id')->limit(1), $sortDir)
        )
        ->when($sortBy === null, fn ($q) => $q->orderByDesc('month'));

    $deductions = $query->paginate(15)->withQueryString();

    $summaryQuery = MonthlyDeduction::query()->where('site_id', $site->id)->whereDate('month', $monthDate);
    $summary = [
        'deduction_count'   => (clone $summaryQuery)->count(),
        'total_amount'      => (float) (clone $summaryQuery)->sum('amount'),
        'total_unallocated' => (float) (clone $summaryQuery)->sum('unallocated_amount'),
        'closed_count'      => (clone $summaryQuery)->where('is_closed', true)->count(),
    ];

    $context = $resolver->resolve($request, $site);

    return Inertia::render('Kps/Potongan/Index', [
        'site'          => $site,
        'deductions'    => $deductions,
        'selectedMonth' => $selectedMonth,
        'monthLabel'    => Carbon::parse($monthDate)->format('F Y'),
        'summary'       => $summary,
        'siteRole'      => $context['siteRole'],
        'filters'       => ['search' => $search, 'status' => $status, 'sort_by' => $sortBy ?? '', 'sort_dir' => $sortDir, 'month' => $selectedMonth],
    ]);
}
```

- [ ] **Step 4: Update `AllocationReviewController::index()`**

In `app/Http/Controllers/Kps/AllocationReviewController.php`, replace the `$query` assignment block and add `filters` to the render call:

```php
$search  = $request->string('search')->trim()->toString();
$status  = in_array($request->get('status'), ['open', 'closed']) ? $request->get('status') : 'all';
$sortBy  = in_array($request->get('sort_by'), ['amount', 'unallocated_amount']) ? $request->get('sort_by') : null;
$sortDir = $request->get('sort_dir') === 'asc' ? 'asc' : 'desc';

$query = MonthlyDeduction::query()
    ->with('peneroka')
    ->withCount('allocations')
    ->where('site_id', $site->id)
    ->whereDate('month', $monthDate)
    ->when($search !== '', fn ($q) =>
        $q->whereHas('peneroka', fn ($p) => $p->where('name', 'like', "%{$search}%"))
    )
    ->when($status === 'open',   fn ($q) => $q->where('is_closed', false))
    ->when($status === 'closed', fn ($q) => $q->where('is_closed', true))
    ->when($sortBy !== null, fn ($q) => $q->orderBy($sortBy, $sortDir))
    ->when($sortBy === null, fn ($q) => $q->orderByDesc('month'));
```

Add to the `Inertia::render()` array:
```php
'filters' => ['search' => $search, 'status' => $status, 'sort_by' => $sortBy ?? '', 'sort_dir' => $sortDir, 'month' => $selectedMonth],
```

- [ ] **Step 5: Update `AuditLogController::index()`**

In `app/Http/Controllers/Kps/AuditLogController.php`, add search:

```php
$search = $request->string('search')->trim()->toString();

$logsQuery = AuditLog::query()
    ->where('site_id', $site->id)
    ->with('user:id,name,email')
    ->when($selectedAction !== '' && $selectedAction !== 'all', fn ($q) => $q->where('action', $selectedAction))
    ->when($search !== '', fn ($q) =>
        $q->whereHas('user', fn ($u) =>
            $u->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%")
        )
    )
    ->latest();
```

Update `filters` in render:
```php
'filters' => ['action' => $selectedAction !== '' ? $selectedAction : 'all', 'search' => $search],
```

- [ ] **Step 6: Update `ReportController::index()` and `SiteExperienceService`**

Add `siteReportsPaginated()` to `app/Services/Kps/SiteExperienceService.php`:

```php
public function siteReportsPaginated(Site $site, string $monthDate, string $search, string $sortBy, string $sortDir, int $perPage = 20): \Illuminate\Contracts\Pagination\LengthAwarePaginator
{
    $query = $this->siteReportQuery($site, $monthDate);

    if ($search !== '') {
        $query->where(fn (Builder $q) =>
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('ic_number', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%")
        );
    }

    $allowed = ['name', 'total_outstanding', 'current_month_deduction_total'];
    $query->orderBy(in_array($sortBy, $allowed) ? $sortBy : 'name', $sortDir === 'desc' ? 'desc' : 'asc');

    return $query->paginate($perPage)->withQueryString();
}
```

Replace `ReportController::index()`:

```php
public function index(Request $request, Site $site, SiteContextResolver $resolver, SiteExperienceService $siteExperience): InertiaResponse
{
    $this->authorizeReportAccess($request, $site);
    $context = $resolver->resolve($request, $site);

    $search  = $request->string('search')->trim()->toString();
    $sortBy  = $request->get('sort_by', 'name');
    $sortDir = $request->get('sort_dir') === 'desc' ? 'desc' : 'asc';

    $month     = $siteExperience->currentMonth();
    $monthDate = $month->toDateString();

    $penerokas = $siteExperience->siteReportsPaginated($site, $monthDate, $search, $sortBy, $sortDir);

    $summary = [
        'peneroka_count'          => \App\Models\Kps\Peneroka::query()->where('site_id', $site->id)->count(),
        'outstanding_total'       => (float) \App\Models\Kps\Debt::query()->whereHas('peneroka', fn (\Illuminate\Database\Eloquent\Builder $q) => $q->where('site_id', $site->id))->sum('balance'),
        'current_month_deductions' => (float) \App\Models\Kps\MonthlyDeduction::query()->where('site_id', $site->id)->whereDate('month', $monthDate)->sum('amount'),
    ];

    return Inertia::render('Kps/Reports/Index', [
        'site'          => $site,
        'siteRole'      => $context['siteRole'],
        'penerokas'     => $penerokas,
        'summary'       => $summary,
        'priorityMix'   => $siteExperience->siteReportsPayload($site)['priorityMix'] ?? [],
        'recentActivity' => $siteExperience->auditActivity($site->id, 5),
        'currentMonth'  => $monthDate,
        'monthLabel'    => $month->format('F Y'),
        'filters'       => ['search' => $search, 'sort_by' => $sortBy, 'sort_dir' => $sortDir],
    ]);
}
```

- [ ] **Step 7: Update `UserManagementController::index()`**

Add sort params after existing filters:

```php
$sortBy  = in_array($request->get('sort_by'), ['name', 'email', 'created_at']) ? $request->get('sort_by') : 'created_at';
$sortDir = $request->get('sort_dir') === 'asc' ? 'asc' : 'desc';

$users = $query->orderBy($sortBy, $sortDir)->paginate(15)->withQueryString();
```

Remove the old `->orderBy('created_at', 'desc')`.

Update `filters`:
```php
'filters' => $request->only(['search', 'role', 'active', 'sort_by', 'sort_dir']),
```

- [ ] **Step 8: Update `RoleManagementController::index()`**

Read the file first, then add search to the roles query. Replace the existing query with:

```php
$search = $request->string('search')->trim()->toString();

$roles = Role::query()
    ->when($search !== '', fn ($q) => $q->where('name', 'like', "%{$search}%"))
    ->withCount(['users', 'permissions'])
    ->orderBy('name')
    ->get();
```

Add to render call:
```php
'filters' => ['search' => $search],
```

- [ ] **Step 9: Run tests**

```bash
php artisan test --no-coverage
```
Expected: all existing tests pass.

- [ ] **Step 10: Commit**

```bash
git add app/Http/Controllers/Kps/ app/Http/Controllers/Admin/ app/Services/Kps/SiteExperienceService.php
git commit -m "feat: add server-side search/sort/filter params to all table controllers"
```

---

## Task 4: Peneroka Table — UTable

**Features:** global search, sort (name, ic_number), column visibility, pagination.

**Files:** `resources/js/pages/Kps/Peneroka/Index.vue`

- [ ] **Step 1: Replace the table section in `Peneroka/Index.vue`**

Replace the entire `<script setup>` and `<template>`:

```vue
<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed, h, resolveComponent } from 'vue';
import { useServerTable } from '@/composables/useServerTable';
import type { TableColumn } from '@nuxt/ui';
import KpsShellLayout from '@/layouts/kps/KpsShellLayout.vue';
import type { AppPageProps, KpsPeneroka, KpsSite, KpsSiteRole } from '@/types';

interface PaginatedPeneroka {
    data: KpsPeneroka[];
    current_page: number;
    last_page: number;
    from?: number;
    to?: number;
    total: number;
    per_page: number;
}

const props = defineProps<{
    site: KpsSite;
    siteRole?: KpsSiteRole | null;
    penerokas: PaginatedPeneroka;
    summary: { total_peneroka: number; with_ic_number: number; with_phone: number; outstanding_total: number };
    filters: { search: string; sort_by: string; sort_dir: 'asc' | 'desc' };
}>();

const baseUrl = `/kps/sites/${props.site.id}/peneroka`;
const { globalFilter, sorting, goToPage } = useServerTable(baseUrl, props.filters);

const page = usePage<AppPageProps>();
const permissions = computed(() => page.props.auth?.permissions ?? []);
const canManagePeneroka = computed(() => permissions.value.includes('kps.manage_peneroka'));
const canViewReports    = computed(() => permissions.value.includes('kps.view_reports'));

const UButton = resolveComponent('UButton');
const UBadge  = resolveComponent('UBadge');

const columns: TableColumn<KpsPeneroka>[] = [
    {
        accessorKey: 'name',
        header: 'Name',
        enableSorting: true,
        cell: ({ row }) => h('div', { class: 'space-y-1' }, [
            h('p', { class: 'font-semibold text-[#1b1b1b]' }, row.original.name),
            h('p', { class: 'text-xs text-[#8d7167]' }, row.original.address || 'No address'),
        ]),
    },
    {
        accessorKey: 'ic_number',
        header: 'IC Number',
        enableSorting: true,
        cell: ({ getValue }) => getValue() || '-',
    },
    {
        accessorKey: 'phone',
        header: 'Phone',
        enableSorting: false,
        cell: ({ getValue }) => getValue() || '-',
    },
    {
        id: 'actions',
        header: 'Actions',
        enableHiding: false,
        meta: { class: { th: 'text-right', td: 'text-right' } },
        cell: ({ row }) => {
            const p = row.original;
            const buttons = [];
            if (canManagePeneroka.value) {
                buttons.push(h(UButton, { variant: 'ghost', size: 'sm', as: 'a', href: `/kps/sites/${props.site.id}/peneroka/${p.id}/edit`, class: 'rounded-full text-[#6d5952] hover:bg-[#fff1ec]' }, () => 'Edit'));
            }
            if (canViewReports.value) {
                buttons.push(h(UButton, { variant: 'outline', size: 'sm', as: 'a', href: `/kps/sites/${props.site.id}/reports/peneroka/${p.id}`, class: 'rounded-full border-[#e2c9c0]' }, () => 'Statement'));
            }
            return h('div', { class: 'flex justify-end gap-2' }, buttons);
        },
    },
];

const formatNumber = (v?: number | null) =>
    new Intl.NumberFormat('en-MY', { minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(Number(v ?? 0));
const formatMoney = (v?: number | null) =>
    new Intl.NumberFormat('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Number(v ?? 0));
const coverage = computed(() =>
    props.summary.total_peneroka > 0
        ? Math.round((props.summary.with_phone / props.summary.total_peneroka) * 100) : 0,
);
</script>

<template>
    <Head :title="`Peneroka - ${site.name}`" />
    <KpsShellLayout :site="site" :site-role="siteRole">
        <div class="space-y-6 px-4 pb-8 lg:px-8">

            <!-- Page header -->
            <section class="flex flex-col gap-5 2xl:flex-row 2xl:items-end 2xl:justify-between">
                <div class="max-w-3xl">
                    <p class="text-[11px] font-bold uppercase tracking-[0.32em] text-[#b7654b]">Site Workspace</p>
                    <h1 class="mt-3 text-4xl font-black tracking-[-0.04em] text-[#1b1b1b] md:text-5xl" style="font-family: Manrope, Inter, sans-serif;">Peneroka registry</h1>
                    <p class="mt-4 max-w-2xl text-base leading-7 text-[#65534d] md:text-lg">Manage beneficiary records for {{ site.name }}.</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <Link :href="`/kps/sites/${site.id}`" class="inline-flex items-center rounded-full border border-[#e1cbc2] bg-white px-6 py-3 text-sm font-semibold text-[#6d5952] shadow-[0_10px_30px_rgba(157,80,53,0.06)] transition hover:border-[#c77d62] hover:text-[#1b1b1b]">Back to Dashboard</Link>
                    <UButton v-if="canManagePeneroka" as-child class="rounded-full bg-gradient-to-br from-[#d6522d] to-[#bc3f1d] px-6 py-3 text-white shadow-[0_16px_32px_rgba(214,82,45,0.28)] hover:translate-y-[-1px]">
                        <Link :href="`/kps/sites/${site.id}/peneroka/create`">Add Peneroka</Link>
                    </UButton>
                </div>
            </section>

            <!-- Summary cards -->
            <section class="grid gap-4 xl:grid-cols-4">
                <div class="rounded-[30px] border border-[#efdcd5] bg-white/88 p-6 shadow-[0_16px_40px_rgba(157,80,53,0.08)]">
                    <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Total Records</p>
                    <p class="mt-4 text-4xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">{{ formatNumber(summary.total_peneroka) }}</p>
                    <p class="mt-4 text-sm text-[#6d5952]">Peneroka assigned to {{ site.code }}.</p>
                </div>
                <div class="rounded-[30px] border border-[#efdcd5] bg-white/88 p-6 shadow-[0_16px_40px_rgba(157,80,53,0.08)]">
                    <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#9b7d73]">On This Page</p>
                    <p class="mt-4 text-4xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">{{ formatNumber(penerokas.data.length) }}</p>
                    <p class="mt-4 text-sm text-[#6d5952]">Current page records.</p>
                </div>
                <div class="rounded-[30px] border border-[#efdcd5] bg-white/88 p-6 shadow-[0_16px_40px_rgba(157,80,53,0.08)]">
                    <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Contact Coverage</p>
                    <p class="mt-4 text-4xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">{{ coverage }}%</p>
                    <p class="mt-4 text-sm text-[#6d5952]">Records with phone numbers.</p>
                </div>
                <div class="rounded-[30px] border border-[#efdcd5] bg-white/88 p-6 shadow-[0_16px_40px_rgba(157,80,53,0.08)]">
                    <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Outstanding</p>
                    <p class="mt-4 text-4xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">{{ formatMoney(summary.outstanding_total) }}</p>
                    <p class="mt-4 text-sm text-[#6d5952]">Open debt balance.</p>
                </div>
            </section>

            <!-- Table card -->
            <section class="overflow-hidden rounded-[36px] border border-[#efdcd5] bg-white/92 shadow-[0_18px_50px_rgba(157,80,53,0.08)]">
                <div class="flex flex-col gap-4 border-b border-[#f0dfd8] px-7 py-5 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-[0.28em] text-[#b47b67]">Master Data</p>
                        <h2 class="mt-1 text-2xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">Operational registry</h2>
                    </div>
                    <!-- Search -->
                    <UInput
                        v-model="globalFilter"
                        placeholder="Search name, IC, phone…"
                        icon="i-lucide-search"
                        class="w-[280px]"
                    />
                </div>

                <UTable
                    :data="penerokas.data"
                    :columns="columns"
                    v-model:sorting="sorting"
                    :sorting-options="{ manualSorting: true }"
                    :global-filter-options="{ manualFiltering: true }"
                    :empty-state="{ label: 'No peneroka found.' }"
                />

                <!-- Pagination -->
                <div v-if="penerokas.last_page > 1" class="flex items-center justify-between border-t border-[#f0dfd8] px-7 py-4">
                    <p class="text-sm text-[#6d5952]">
                        Showing {{ penerokas.from }}–{{ penerokas.to }} of {{ penerokas.total }}
                    </p>
                    <UPagination
                        :page="penerokas.current_page"
                        :total="penerokas.total"
                        :items-per-page="penerokas.per_page"
                        @update:page="goToPage"
                    />
                </div>
            </section>

        </div>
    </KpsShellLayout>
</template>
```

- [ ] **Step 2: Run tests**

```bash
php artisan test tests/Feature/KpsSiteTest.php --no-coverage
```
Expected: PASS.

- [ ] **Step 3: Commit**

```bash
git add resources/js/pages/Kps/Peneroka/Index.vue
git commit -m "feat: replace Peneroka table with UTable (search, sort, pagination)"
```

---

## Task 5: Hutang Table — UTable

**Features:** global search, status filter (USelect), sort (priority, balance, due_date), column visibility, pagination.

**Files:** `resources/js/pages/Kps/Hutang/Index.vue`

- [ ] **Step 1: Replace `Hutang/Index.vue`**

```vue
<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed, h, ref, resolveComponent } from 'vue';
import { useServerTable } from '@/composables/useServerTable';
import type { TableColumn } from '@nuxt/ui';
import KpsShellLayout from '@/layouts/kps/KpsShellLayout.vue';
import type { AppPageProps, KpsDebt, KpsSite, KpsSiteRole } from '@/types';

interface PaginatedDebts {
    data: KpsDebt[];
    current_page: number;
    last_page: number;
    from?: number;
    to?: number;
    total: number;
    per_page: number;
}

const props = defineProps<{
    site: KpsSite;
    siteRole?: KpsSiteRole | null;
    debts: PaginatedDebts;
    summary: { total_debts: number; outstanding_total: number; due_this_month: number; highest_priority_open: number | null };
    filters: { search: string; status: string; sort_by: string; sort_dir: 'asc' | 'desc' };
}>();

const baseUrl = `/kps/sites/${props.site.id}/hutang`;
const { globalFilter, sorting, setFilter, goToPage } = useServerTable(baseUrl, props.filters);
const statusFilter = ref(props.filters.status ?? 'all');

const onStatusChange = (v: string) => {
    statusFilter.value = v;
    setFilter('status', v === 'all' ? undefined : v);
};

const UButton = resolveComponent('UButton');

const columns: TableColumn<KpsDebt>[] = [
    {
        accessorKey: 'peneroka',
        header: 'Peneroka',
        enableSorting: false,
        cell: ({ row }) => h('div', { class: 'space-y-1' }, [
            h('p', { class: 'font-semibold text-[#1b1b1b]' }, row.original.peneroka?.name ?? 'Unknown'),
            h('p', { class: 'text-xs text-[#8d7167]' }, row.original.description ?? 'No description'),
        ]),
    },
    {
        accessorKey: 'priority',
        header: 'Priority',
        enableSorting: true,
        cell: ({ getValue }) => h('span', { class: 'rounded-full bg-[#fff1ec] px-3 py-1 text-[11px] font-bold text-[#b64a2b]' }, String(getValue())),
    },
    {
        accessorKey: 'balance',
        header: 'Balance',
        enableSorting: true,
        meta: { class: { th: 'text-right', td: 'text-right font-semibold' } },
        cell: ({ getValue }) => new Intl.NumberFormat('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Number(getValue() ?? 0)),
    },
    {
        accessorKey: 'due_date',
        header: 'Due Date',
        enableSorting: true,
        cell: ({ getValue }) => getValue() ? String(getValue()).slice(0, 10) : '-',
    },
    {
        id: 'actions',
        header: 'Actions',
        enableHiding: false,
        meta: { class: { th: 'text-right', td: 'text-right' } },
        cell: ({ row }) => {
            const canManage = (usePage<AppPageProps>().props.auth?.permissions ?? []).includes('kps.manage_hutang');
            if (!canManage) return null;
            return h(UButton, {
                variant: 'ghost', size: 'sm', as: 'a',
                href: `/kps/sites/${props.site.id}/hutang/${row.original.id}/edit`,
                class: 'rounded-full text-[#6d5952] hover:bg-[#fff1ec]',
            }, () => 'Edit');
        },
    },
];

const formatMoney  = (v?: number | null) => new Intl.NumberFormat('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Number(v ?? 0));
const formatNumber = (v?: number | null) => new Intl.NumberFormat('en-MY', { minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(Number(v ?? 0));
const canManageHutang = (usePage<AppPageProps>().props.auth?.permissions ?? []).includes('kps.manage_hutang');

const statusOptions = [
    { label: 'All', value: 'all' },
    { label: 'Open (has balance)', value: 'open' },
    { label: 'Paid (zero balance)', value: 'paid' },
];
</script>

<template>
    <Head :title="`Hutang - ${site.name}`" />
    <KpsShellLayout :site="site" :site-role="siteRole">
        <div class="space-y-6 px-4 pb-8 lg:px-8">

            <section class="flex flex-col gap-5 2xl:flex-row 2xl:items-end 2xl:justify-between">
                <div class="max-w-3xl">
                    <p class="text-[11px] font-bold uppercase tracking-[0.32em] text-[#b7654b]">Site Workspace</p>
                    <h1 class="mt-3 text-4xl font-black tracking-[-0.04em] text-[#1b1b1b] md:text-5xl" style="font-family: Manrope, Inter, sans-serif;">Debt workspace</h1>
                    <p class="mt-4 max-w-2xl text-base leading-7 text-[#65534d] md:text-lg">Manage hutang for {{ site.name }}.</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <Link :href="`/kps/sites/${site.id}`" class="inline-flex items-center rounded-full border border-[#e1cbc2] bg-white px-6 py-3 text-sm font-semibold text-[#6d5952] shadow-[0_10px_30px_rgba(157,80,53,0.06)] transition hover:border-[#c77d62] hover:text-[#1b1b1b]">Back to Dashboard</Link>
                    <UButton v-if="canManageHutang" as-child class="rounded-full bg-gradient-to-br from-[#d6522d] to-[#bc3f1d] px-6 py-3 text-white shadow-[0_16px_32px_rgba(214,82,45,0.28)]">
                        <Link :href="`/kps/sites/${site.id}/hutang/create`">Add Hutang</Link>
                    </UButton>
                </div>
            </section>

            <section class="grid gap-4 xl:grid-cols-4">
                <div class="rounded-[30px] border border-[#efdcd5] bg-white/88 p-6 shadow-[0_16px_40px_rgba(157,80,53,0.08)]">
                    <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Total Records</p>
                    <p class="mt-4 text-4xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">{{ formatNumber(summary.total_debts) }}</p>
                </div>
                <div class="rounded-[30px] border border-[#efdcd5] bg-white/88 p-6 shadow-[0_16px_40px_rgba(157,80,53,0.08)]">
                    <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Due This Month</p>
                    <p class="mt-4 text-4xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">{{ formatNumber(summary.due_this_month) }}</p>
                </div>
                <div class="rounded-[30px] border border-[#efdcd5] bg-white/88 p-6 shadow-[0_16px_40px_rgba(157,80,53,0.08)]">
                    <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Outstanding</p>
                    <p class="mt-4 text-4xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">{{ formatMoney(summary.outstanding_total) }}</p>
                </div>
                <div class="rounded-[30px] border border-[#efdcd5] bg-white/88 p-6 shadow-[0_16px_40px_rgba(157,80,53,0.08)]">
                    <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Highest Priority Open</p>
                    <p class="mt-4 text-4xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">{{ summary.highest_priority_open ?? '-' }}</p>
                </div>
            </section>

            <section class="overflow-hidden rounded-[36px] border border-[#efdcd5] bg-white/92 shadow-[0_18px_50px_rgba(157,80,53,0.08)]">
                <div class="flex flex-col gap-4 border-b border-[#f0dfd8] px-7 py-5 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-[0.28em] text-[#b47b67]">Debt Register</p>
                        <h2 class="mt-1 text-2xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">Allocation priority list</h2>
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        <UInput v-model="globalFilter" placeholder="Search peneroka or description…" icon="i-lucide-search" class="w-[240px]" />
                        <USelect
                            :model-value="statusFilter"
                            :options="statusOptions"
                            value-key="value"
                            label-key="label"
                            class="w-[180px]"
                            @update:model-value="onStatusChange"
                        />
                    </div>
                </div>

                <UTable
                    :data="debts.data"
                    :columns="columns"
                    v-model:sorting="sorting"
                    :sorting-options="{ manualSorting: true }"
                    :global-filter-options="{ manualFiltering: true }"
                    :empty-state="{ label: 'No debts found.' }"
                />

                <div v-if="debts.last_page > 1" class="flex items-center justify-between border-t border-[#f0dfd8] px-7 py-4">
                    <p class="text-sm text-[#6d5952]">Showing {{ debts.from }}–{{ debts.to }} of {{ debts.total }}</p>
                    <UPagination :page="debts.current_page" :total="debts.total" :items-per-page="debts.per_page" @update:page="goToPage" />
                </div>
            </section>
        </div>
    </KpsShellLayout>
</template>
```

- [ ] **Step 2: Commit**

```bash
git add resources/js/pages/Kps/Hutang/Index.vue
git commit -m "feat: replace Hutang table with UTable (search, status filter, sort, pagination)"
```

---

## Task 6: Potongan Table — UTable

**Features:** search, month filter (UInput type=month), status filter (USelect), sort (amount, peneroka_name), pagination.

**Files:** `resources/js/pages/Kps/Potongan/Index.vue`

- [ ] **Step 1: Replace `Potongan/Index.vue`**

```vue
<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed, h, ref, resolveComponent } from 'vue';
import { useServerTable } from '@/composables/useServerTable';
import type { TableColumn } from '@nuxt/ui';
import KpsShellLayout from '@/layouts/kps/KpsShellLayout.vue';
import type { AppPageProps, KpsMonthlyDeduction, KpsSite, KpsSiteRole } from '@/types';

interface PaginatedDeductions {
    data: KpsMonthlyDeduction[];
    current_page: number;
    last_page: number;
    from?: number;
    to?: number;
    total: number;
    per_page: number;
}

const props = defineProps<{
    site: KpsSite;
    siteRole?: KpsSiteRole | null;
    deductions: PaginatedDeductions;
    selectedMonth?: string | null;
    monthLabel: string;
    summary: { deduction_count: number; total_amount: number; total_unallocated: number; closed_count: number };
    filters: { search: string; status: string; sort_by: string; sort_dir: 'asc' | 'desc'; month: string };
}>();

const baseUrl = `/kps/sites/${props.site.id}/potongan`;
const { globalFilter, sorting, setFilter, goToPage } = useServerTable(baseUrl, props.filters);

const month        = ref(props.selectedMonth || '');
const statusFilter = ref(props.filters.status ?? 'all');

const applyMonth = () => setFilter('month', month.value || undefined);
const onStatusChange = (v: string) => {
    statusFilter.value = v;
    setFilter('status', v === 'all' ? undefined : v);
};

const UButton = resolveComponent('UButton');

const columns: TableColumn<KpsMonthlyDeduction>[] = [
    {
        accessorKey: 'month',
        header: 'Month',
        enableSorting: false,
        cell: ({ getValue }) => getValue() ? String(getValue()).slice(0, 10) : '-',
    },
    {
        accessorKey: 'peneroka',
        header: 'Peneroka',
        accessorFn: (row) => row.peneroka?.name ?? '',
        enableSorting: true,
        cell: ({ row }) => h('div', [
            h('p', { class: 'font-semibold text-[#1b1b1b]' }, row.original.peneroka?.name),
            h('p', { class: 'text-xs text-[#8d7167]' }, row.original.peneroka?.phone ?? 'No phone'),
        ]),
    },
    {
        accessorKey: 'amount',
        header: 'Amount',
        enableSorting: true,
        meta: { class: { th: 'text-right', td: 'text-right font-semibold' } },
        cell: ({ getValue }) => new Intl.NumberFormat('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Number(getValue() ?? 0)),
    },
    {
        accessorKey: 'unallocated_amount',
        header: 'Unallocated',
        enableSorting: false,
        meta: { class: { th: 'text-right', td: 'text-right' } },
        cell: ({ getValue }) => new Intl.NumberFormat('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Number(getValue() ?? 0)),
    },
    {
        accessorKey: 'is_closed',
        header: 'Status',
        enableSorting: false,
        cell: ({ getValue }) => {
            const closed = Boolean(getValue());
            return h('span', {
                class: `rounded-full px-3 py-1 text-[11px] font-bold uppercase tracking-[0.18em] ${closed ? 'bg-[#171717] text-white' : 'bg-[#ebfff3] text-[#18754d]'}`,
            }, closed ? 'Closed' : 'Open');
        },
    },
    {
        id: 'actions',
        header: 'Actions',
        enableHiding: false,
        meta: { class: { th: 'text-right', td: 'text-right' } },
        cell: ({ row }) => h(UButton, {
            variant: 'outline', size: 'sm', as: 'a',
            href: `/kps/sites/${props.site.id}/allocations/${row.original.id}`,
            class: 'rounded-full border-[#e2c9c0]',
        }, () => 'View'),
    },
];

const formatMoney = (v?: number | null) => new Intl.NumberFormat('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Number(v ?? 0));
const openCount = computed(() => Math.max(props.summary.deduction_count - props.summary.closed_count, 0));
const canManagePotongan = computed(() => (usePage<AppPageProps>().props.auth?.permissions ?? []).includes('kps.manage_potongan'));

const statusOptions = [
    { label: 'All', value: 'all' },
    { label: 'Open', value: 'open' },
    { label: 'Closed', value: 'closed' },
];
</script>

<template>
    <Head :title="`Potongan - ${site.name}`" />
    <KpsShellLayout :site="site" :site-role="siteRole">
        <div class="space-y-6 px-4 pb-8 lg:px-8">

            <section class="flex flex-col gap-5 2xl:flex-row 2xl:items-end 2xl:justify-between">
                <div class="max-w-3xl">
                    <p class="text-[11px] font-bold uppercase tracking-[0.32em] text-[#b7654b]">Deduction Workspace</p>
                    <h1 class="mt-3 text-4xl font-black tracking-[-0.04em] text-[#1b1b1b] md:text-5xl" style="font-family: Manrope, Inter, sans-serif;">Potongan Bulanan</h1>
                    <p class="mt-4 max-w-2xl text-base leading-7 text-[#65534d] md:text-lg">Manage monthly deductions for {{ site.name }}.</p>
                </div>
                <div class="flex flex-wrap gap-3" v-if="canManagePotongan">
                    <Link :href="`/kps/sites/${site.id}/potongan/bulk`" class="inline-flex items-center rounded-full border border-[#e1cbc2] bg-white px-6 py-3 text-sm font-semibold text-[#6d5952] shadow-[0_10px_30px_rgba(157,80,53,0.06)] transition hover:border-[#c77d62] hover:text-[#1b1b1b]">Bulk Entry</Link>
                    <Link :href="`/kps/sites/${site.id}/potongan/create`" class="inline-flex items-center rounded-full bg-gradient-to-br from-[#d6522d] to-[#bc3f1d] px-6 py-3 text-sm font-semibold text-white shadow-[0_16px_32px_rgba(214,82,45,0.28)] transition hover:translate-y-[-1px]">Add Potongan</Link>
                </div>
            </section>

            <section class="grid gap-4 xl:grid-cols-4">
                <div class="rounded-[30px] border border-[#efdcd5] bg-white/88 p-6 shadow-[0_16px_40px_rgba(157,80,53,0.08)]">
                    <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Selected Month</p>
                    <p class="mt-4 text-4xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">{{ monthLabel }}</p>
                </div>
                <div class="rounded-[30px] border border-[#efdcd5] bg-white/88 p-6 shadow-[0_16px_40px_rgba(157,80,53,0.08)]">
                    <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Total Amount</p>
                    <p class="mt-4 text-4xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">{{ formatMoney(summary.total_amount) }}</p>
                </div>
                <div class="rounded-[30px] border border-[#efdcd5] bg-white/88 p-6 shadow-[0_16px_40px_rgba(157,80,53,0.08)]">
                    <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Unallocated</p>
                    <p class="mt-4 text-4xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">{{ formatMoney(summary.total_unallocated) }}</p>
                </div>
                <div class="rounded-[30px] border border-[#efdcd5] bg-white/88 p-6 shadow-[0_16px_40px_rgba(157,80,53,0.08)]">
                    <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Open / Closed</p>
                    <p class="mt-4 text-4xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">{{ openCount }} / {{ summary.closed_count }}</p>
                </div>
            </section>

            <section class="overflow-hidden rounded-[36px] border border-[#efdcd5] bg-white/92 shadow-[0_18px_50px_rgba(157,80,53,0.08)]">
                <div class="flex flex-col gap-4 border-b border-[#f0dfd8] px-7 py-5 md:flex-row md:items-end md:justify-between">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-[0.28em] text-[#b47b67]">Month Filter</p>
                        <h2 class="mt-1 text-2xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">Live deduction ledger</h2>
                    </div>
                    <div class="flex flex-wrap items-end gap-3">
                        <UInput type="month" v-model="month" class="w-[200px]" @change="applyMonth" />
                        <UInput v-model="globalFilter" placeholder="Search peneroka…" icon="i-lucide-search" class="w-[200px]" />
                        <USelect
                            :model-value="statusFilter"
                            :options="statusOptions"
                            value-key="value"
                            label-key="label"
                            class="w-[140px]"
                            @update:model-value="onStatusChange"
                        />
                    </div>
                </div>

                <UTable
                    :data="deductions.data"
                    :columns="columns"
                    v-model:sorting="sorting"
                    :sorting-options="{ manualSorting: true }"
                    :global-filter-options="{ manualFiltering: true }"
                    :empty-state="{ label: 'No deductions found.' }"
                />

                <div v-if="deductions.last_page > 1" class="flex items-center justify-between border-t border-[#f0dfd8] px-7 py-4">
                    <p class="text-sm text-[#6d5952]">Showing {{ deductions.from }}–{{ deductions.to }} of {{ deductions.total }}</p>
                    <UPagination :page="deductions.current_page" :total="deductions.total" :items-per-page="deductions.per_page" @update:page="goToPage" />
                </div>
            </section>
        </div>
    </KpsShellLayout>
</template>
```

- [ ] **Step 2: Commit**

```bash
git add resources/js/pages/Kps/Potongan/Index.vue
git commit -m "feat: replace Potongan table with UTable (search, month filter, status filter, sort, pagination)"
```

---

## Task 7: Allocations Table — UTable

**Features:** search, month filter, status filter, sort (amount, unallocated_amount), pagination.

**Files:** `resources/js/pages/Kps/Allocations/Index.vue`

- [ ] **Step 1: Replace `Allocations/Index.vue`**

Apply the same pattern as Potongan. The differences are: columns include `allocations_count`, the action button links to `/allocations/{id}`, and there's a "Close Month" button for `kps.approve_month` users.

```vue
<script setup lang="ts">
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { computed, h, ref, resolveComponent } from 'vue';
import { useServerTable } from '@/composables/useServerTable';
import type { TableColumn } from '@nuxt/ui';
import KpsShellLayout from '@/layouts/kps/KpsShellLayout.vue';
import type { AppPageProps, KpsMonthlyDeduction, KpsSite, KpsSiteRole } from '@/types';

interface PaginatedDeductions {
    data: (KpsMonthlyDeduction & { allocations_count?: number })[];
    current_page: number;
    last_page: number;
    from?: number;
    to?: number;
    total: number;
    per_page: number;
}

const props = defineProps<{
    site: KpsSite;
    siteRole?: KpsSiteRole | null;
    deductions: PaginatedDeductions;
    selectedMonth?: string | null;
    monthLabel: string;
    summary: { deduction_count: number; total_amount: number; total_unallocated: number; closed_count: number };
    filters: { search: string; status: string; sort_by: string; sort_dir: 'asc' | 'desc'; month: string };
}>();

const baseUrl = `/kps/sites/${props.site.id}/allocations`;
const { globalFilter, sorting, setFilter, goToPage } = useServerTable(baseUrl, props.filters);

const month        = ref(props.selectedMonth || '');
const statusFilter = ref(props.filters.status ?? 'all');
const isClosing    = ref(false);

const applyMonth = () => setFilter('month', month.value || undefined);
const onStatusChange = (v: string) => {
    statusFilter.value = v;
    setFilter('status', v === 'all' ? undefined : v);
};

const UButton = resolveComponent('UButton');

const canApproveMonth = (usePage<AppPageProps>().props.auth?.permissions ?? []).includes('kps.approve_month');

const closeMonth = () => {
    if (!confirm(`Close all open deductions for ${props.monthLabel}?`)) return;
    isClosing.value = true;
    router.post(`/kps/sites/${props.site.id}/allocations/close-month`, { month: props.selectedMonth }, {
        onFinish: () => { isClosing.value = false; },
    });
};

const columns: TableColumn<PaginatedDeductions['data'][number]>[] = [
    {
        accessorKey: 'month',
        header: 'Month',
        enableSorting: false,
        cell: ({ getValue }) => getValue() ? String(getValue()).slice(0, 10) : '-',
    },
    {
        accessorKey: 'peneroka',
        header: 'Peneroka',
        accessorFn: (row) => row.peneroka?.name ?? '',
        enableSorting: false,
        cell: ({ row }) => h('div', [
            h('p', { class: 'font-semibold text-[#1b1b1b]' }, row.original.peneroka?.name),
            h('p', { class: 'text-xs text-[#8d7167]' }, row.original.peneroka?.phone ?? 'No phone'),
        ]),
    },
    {
        accessorKey: 'amount',
        header: 'Amount',
        enableSorting: true,
        meta: { class: { th: 'text-right', td: 'text-right font-semibold' } },
        cell: ({ getValue }) => new Intl.NumberFormat('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Number(getValue() ?? 0)),
    },
    {
        accessorKey: 'allocations_count',
        header: 'Allocations',
        enableSorting: false,
        cell: ({ getValue }) => h('span', { class: 'rounded-full bg-[#fff1ec] px-2 py-0.5 text-xs font-bold text-[#b64a2b]' }, String(getValue() ?? 0)),
    },
    {
        accessorKey: 'unallocated_amount',
        header: 'Unallocated',
        enableSorting: true,
        meta: { class: { th: 'text-right', td: 'text-right' } },
        cell: ({ getValue }) => new Intl.NumberFormat('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Number(getValue() ?? 0)),
    },
    {
        accessorKey: 'is_closed',
        header: 'Status',
        enableSorting: false,
        cell: ({ getValue }) => {
            const closed = Boolean(getValue());
            return h('span', {
                class: `rounded-full px-3 py-1 text-[11px] font-bold uppercase tracking-[0.18em] ${closed ? 'bg-[#171717] text-white' : 'bg-[#ebfff3] text-[#18754d]'}`,
            }, closed ? 'Closed' : 'Open');
        },
    },
    {
        id: 'actions',
        header: 'Actions',
        enableHiding: false,
        meta: { class: { th: 'text-right', td: 'text-right' } },
        cell: ({ row }) => h(UButton, {
            variant: 'outline', size: 'sm', as: 'a',
            href: `/kps/sites/${props.site.id}/allocations/${row.original.id}`,
            class: 'rounded-full border-[#e2c9c0]',
        }, () => 'View'),
    },
];

const formatMoney = (v?: number | null) => new Intl.NumberFormat('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Number(v ?? 0));
const openCount = computed(() => Math.max(props.summary.deduction_count - props.summary.closed_count, 0));

const statusOptions = [
    { label: 'All', value: 'all' },
    { label: 'Open', value: 'open' },
    { label: 'Closed', value: 'closed' },
];
</script>

<template>
    <Head :title="`Allocations - ${site.name}`" />
    <KpsShellLayout :site="site" :site-role="siteRole">
        <div class="space-y-6 px-4 pb-8 lg:px-8">

            <section class="flex flex-col gap-5 2xl:flex-row 2xl:items-end 2xl:justify-between">
                <div class="max-w-3xl">
                    <p class="text-[11px] font-bold uppercase tracking-[0.32em] text-[#b7654b]">Allocation Workspace</p>
                    <h1 class="mt-3 text-4xl font-black tracking-[-0.04em] text-[#1b1b1b] md:text-5xl" style="font-family: Manrope, Inter, sans-serif;">Pengagihan Bulanan</h1>
                    <p class="mt-4 max-w-2xl text-base leading-7 text-[#65534d] md:text-lg">Review and manage allocations for {{ site.name }}.</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <Link :href="`/kps/sites/${site.id}`" class="inline-flex items-center rounded-full border border-[#e1cbc2] bg-white px-6 py-3 text-sm font-semibold text-[#6d5952] shadow-[0_10px_30px_rgba(157,80,53,0.06)] transition hover:border-[#c77d62] hover:text-[#1b1b1b]">Back to Dashboard</Link>
                    <UButton v-if="canApproveMonth" color="error" variant="solid" class="rounded-full" :loading="isClosing" @click="closeMonth">Close Month</UButton>
                </div>
            </section>

            <section class="grid gap-4 xl:grid-cols-4">
                <div class="rounded-[30px] border border-[#efdcd5] bg-white/88 p-6 shadow-[0_16px_40px_rgba(157,80,53,0.08)]">
                    <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Selected Month</p>
                    <p class="mt-4 text-4xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">{{ monthLabel }}</p>
                </div>
                <div class="rounded-[30px] border border-[#efdcd5] bg-white/88 p-6 shadow-[0_16px_40px_rgba(157,80,53,0.08)]">
                    <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Total Amount</p>
                    <p class="mt-4 text-4xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">{{ formatMoney(summary.total_amount) }}</p>
                </div>
                <div class="rounded-[30px] border border-[#efdcd5] bg-white/88 p-6 shadow-[0_16px_40px_rgba(157,80,53,0.08)]">
                    <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Unallocated</p>
                    <p class="mt-4 text-4xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">{{ formatMoney(summary.total_unallocated) }}</p>
                </div>
                <div class="rounded-[30px] border border-[#efdcd5] bg-white/88 p-6 shadow-[0_16px_40px_rgba(157,80,53,0.08)]">
                    <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Open / Closed</p>
                    <p class="mt-4 text-4xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">{{ openCount }} / {{ summary.closed_count }}</p>
                </div>
            </section>

            <section class="overflow-hidden rounded-[36px] border border-[#efdcd5] bg-white/92 shadow-[0_18px_50px_rgba(157,80,53,0.08)]">
                <div class="flex flex-col gap-4 border-b border-[#f0dfd8] px-7 py-5 md:flex-row md:items-end md:justify-between">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-[0.28em] text-[#b47b67]">Month Filter</p>
                        <h2 class="mt-1 text-2xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">Allocation review ledger</h2>
                    </div>
                    <div class="flex flex-wrap items-end gap-3">
                        <UInput type="month" v-model="month" class="w-[200px]" @change="applyMonth" />
                        <UInput v-model="globalFilter" placeholder="Search peneroka…" icon="i-lucide-search" class="w-[200px]" />
                        <USelect :model-value="statusFilter" :options="statusOptions" value-key="value" label-key="label" class="w-[140px]" @update:model-value="onStatusChange" />
                    </div>
                </div>

                <UTable
                    :data="deductions.data"
                    :columns="columns"
                    v-model:sorting="sorting"
                    :sorting-options="{ manualSorting: true }"
                    :global-filter-options="{ manualFiltering: true }"
                    :empty-state="{ label: 'No allocations found.' }"
                />

                <div v-if="deductions.last_page > 1" class="flex items-center justify-between border-t border-[#f0dfd8] px-7 py-4">
                    <p class="text-sm text-[#6d5952]">Showing {{ deductions.from }}–{{ deductions.to }} of {{ deductions.total }}</p>
                    <UPagination :page="deductions.current_page" :total="deductions.total" :items-per-page="deductions.per_page" @update:page="goToPage" />
                </div>
            </section>
        </div>
    </KpsShellLayout>
</template>
```

- [ ] **Step 2: Commit**

```bash
git add resources/js/pages/Kps/Allocations/Index.vue
git commit -m "feat: replace Allocations table with UTable (search, month filter, status filter, sort, pagination)"
```

---

## Task 8: Audit Logs Table — UTable

**Features:** search (user name/email), action filter (USelect, existing), column visibility, pagination with filter preservation.

**Files:** `resources/js/pages/Kps/AuditLogs/Index.vue`

- [ ] **Step 1: Replace `AuditLogs/Index.vue`**

```vue
<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { computed, h, ref } from 'vue';
import { useServerTable } from '@/composables/useServerTable';
import type { TableColumn } from '@nuxt/ui';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import KpsShellLayout from '@/layouts/kps/KpsShellLayout.vue';
import type { KpsAuditLog, KpsSite, KpsSiteRole } from '@/types';

interface PaginatedAuditLogs {
    data: KpsAuditLog[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    from?: number;
    to?: number;
}

const props = defineProps<{
    site: KpsSite;
    siteRole?: KpsSiteRole | null;
    auditLogs: PaginatedAuditLogs;
    availableActions: string[];
    filters: { action?: string; search: string };
}>();

const baseUrl = `/kps/sites/${props.site.id}/audit-logs`;
const { globalFilter, setFilter, goToPage } = useServerTable(baseUrl, {
    search: props.filters.search,
    action: props.filters.action && props.filters.action !== 'all' ? props.filters.action : undefined,
});

const actionFilter = ref(props.filters.action || 'all');
const onActionChange = (v: string) => {
    actionFilter.value = v;
    setFilter('action', v === 'all' ? undefined : v);
};

const formatAction   = (a: string) => a.split('_').map(s => s.charAt(0).toUpperCase() + s.slice(1)).join(' ');
const formatDateTime = (v?: string | null) => {
    if (!v) return '-';
    const d = new Date(v);
    return Number.isNaN(d.getTime()) ? v : new Intl.DateTimeFormat('en-MY', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' }).format(d);
};
const formatMetadata = (log: KpsAuditLog) => {
    if (!log.metadata) return '-';
    if (log.action === 'month_closed') return `Month ${log.metadata.month || '-'} | Deductions closed ${log.metadata.deductions_closed || 0}`;
    return Object.entries(log.metadata).map(([k, v]) => `${k}: ${v}`).join(' | ');
};

const columns: TableColumn<KpsAuditLog>[] = [
    {
        accessorKey: 'created_at',
        header: 'Timestamp',
        enableSorting: false,
        cell: ({ getValue }) => h('span', { class: 'text-[#6d5952]' }, formatDateTime(String(getValue()))),
    },
    {
        accessorKey: 'action',
        header: 'Action',
        enableSorting: false,
        cell: ({ getValue }) => h('span', { class: 'font-semibold text-[#1b1b1b]' }, formatAction(String(getValue()))),
    },
    {
        id: 'user',
        header: 'User',
        enableSorting: false,
        cell: ({ row }) => h('div', [
            h('div', { class: 'font-semibold text-[#1b1b1b]' }, row.original.user?.name ?? 'System'),
            h('div', { class: 'text-xs text-[#8d7167]' }, row.original.user?.email ?? '-'),
        ]),
    },
    {
        id: 'target',
        header: 'Target',
        enableSorting: false,
        cell: ({ row }) => h('span', { class: 'text-[#6d5952]' }, `${row.original.auditable_label} ${row.original.auditable_id}`),
    },
    {
        id: 'metadata',
        header: 'Metadata',
        enableSorting: false,
        cell: ({ row }) => h('span', { class: 'text-sm text-[#6d5952]' }, formatMetadata(row.original)),
    },
];

const latestActivity = computed(() =>
    props.auditLogs.data[0]?.created_at ? formatDateTime(props.auditLogs.data[0].created_at) : 'No activity',
);

const actionOptions = computed(() => [
    { label: 'All Actions', value: 'all' },
    ...props.availableActions.map(a => ({ label: formatAction(a), value: a })),
]);
</script>

<template>
    <Head :title="`Audit Trail - ${site.name}`" />
    <KpsShellLayout :site="site" :site-role="siteRole">
        <div class="space-y-6 px-4 pb-8 lg:px-8">

            <section class="flex flex-col gap-5 2xl:flex-row 2xl:items-end 2xl:justify-between">
                <div class="max-w-3xl">
                    <p class="text-[11px] font-bold uppercase tracking-[0.32em] text-[#b7654b]">Site Audit</p>
                    <h1 class="mt-3 text-4xl font-black tracking-[-0.04em] text-[#1b1b1b] md:text-5xl" style="font-family: Manrope, Inter, sans-serif;">Audit Trail</h1>
                    <p class="mt-4 max-w-2xl text-base leading-7 text-[#65534d] md:text-lg">Recent operational activity for {{ site.name }}.</p>
                </div>
                <UButton variant="outline" as-child class="rounded-full border-[#e1cbc2] bg-white px-6 py-3 text-[#6d5952] shadow-[0_10px_30px_rgba(157,80,53,0.06)] hover:border-[#c77d62] hover:text-[#1b1b1b]">
                    <Link :href="`/kps/sites/${site.id}`">Back to Site Dashboard</Link>
                </UButton>
            </section>

            <section class="grid gap-4 md:grid-cols-2">
                <Card class="border-[#efdcd5] bg-white/90 shadow-[0_16px_40px_rgba(157,80,53,0.08)]">
                    <CardHeader class="pb-3"><CardTitle class="text-xs font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Log Entries</CardTitle></CardHeader>
                    <CardContent><div class="text-4xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">{{ auditLogs.total }}</div></CardContent>
                </Card>
                <Card class="border-[#efdcd5] bg-white/90 shadow-[0_16px_40px_rgba(157,80,53,0.08)]">
                    <CardHeader class="pb-3"><CardTitle class="text-xs font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Latest Activity</CardTitle></CardHeader>
                    <CardContent><div class="text-lg font-semibold text-[#1b1b1b]">{{ latestActivity }}</div></CardContent>
                </Card>
            </section>

            <div class="overflow-hidden rounded-[36px] border border-[#efdcd5] bg-white/92 shadow-[0_18px_50px_rgba(157,80,53,0.08)]">
                <div class="flex flex-wrap items-end gap-4 border-b border-[#f0dfd8] px-7 py-5">
                    <UInput v-model="globalFilter" placeholder="Search by user name or email…" icon="i-lucide-search" class="w-[260px]" />
                    <USelect :model-value="actionFilter" :options="actionOptions" value-key="value" label-key="label" class="w-[220px]" @update:model-value="onActionChange" />
                </div>

                <UTable
                    :data="auditLogs.data"
                    :columns="columns"
                    :global-filter-options="{ manualFiltering: true }"
                    :empty-state="{ label: 'No audit logs found.' }"
                />

                <div v-if="auditLogs.last_page > 1" class="flex items-center justify-between border-t border-[#f0dfd8] px-7 py-4">
                    <p class="text-sm text-[#6d5952]">Showing {{ auditLogs.from }}–{{ auditLogs.to }} of {{ auditLogs.total }}</p>
                    <UPagination :page="auditLogs.current_page" :total="auditLogs.total" :items-per-page="auditLogs.per_page" @update:page="goToPage" />
                </div>
            </div>
        </div>
    </KpsShellLayout>
</template>
```

- [ ] **Step 2: Commit**

```bash
git add resources/js/pages/Kps/AuditLogs/Index.vue
git commit -m "feat: replace Audit Logs table with UTable (user search, action filter, pagination)"
```

---

## Task 9: Reports Table — UTable

**Features:** server-side search, sort (name, outstanding, this_month), pagination. Replaces client-side filtering.

**Files:** `resources/js/pages/Kps/Reports/Index.vue`

- [ ] **Step 1: Replace the peneroka table section in `Reports/Index.vue`**

Update the `<script setup>` to use `useServerTable` and define columns. Remove the old `query` ref and `filteredPenerokas` computed:

```vue
<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed, h, resolveComponent } from 'vue';
import { useServerTable } from '@/composables/useServerTable';
import type { TableColumn } from '@nuxt/ui';
import KpsShellLayout from '@/layouts/kps/KpsShellLayout.vue';
import type { AppPageProps, KpsPeneroka, KpsSite, KpsSiteRole } from '@/types';

interface PaginatedPeneroka {
    data: KpsPeneroka[];
    current_page: number;
    last_page: number;
    from?: number;
    to?: number;
    total: number;
    per_page: number;
}

const props = defineProps<{
    site: KpsSite;
    siteRole?: KpsSiteRole | null;
    penerokas: PaginatedPeneroka;
    summary: { peneroka_count: number; outstanding_total: number; current_month_deductions: number };
    priorityMix: any[];
    recentActivity: any[];
    currentMonth: string;
    monthLabel: string;
    filters: { search: string; sort_by: string; sort_dir: 'asc' | 'desc' };
}>();

const baseUrl = `/kps/sites/${props.site.id}/reports`;
const { globalFilter, sorting, goToPage } = useServerTable(baseUrl, props.filters);

const UButton = resolveComponent('UButton');

const columns: TableColumn<KpsPeneroka>[] = [
    {
        accessorKey: 'name',
        header: 'Peneroka',
        enableSorting: true,
        cell: ({ row }) => h('div', [
            h('p', { class: 'font-semibold text-[#1b1b1b]' }, row.original.name),
            h('p', { class: 'text-xs text-[#8d7167]' }, row.original.phone ?? '-'),
        ]),
    },
    {
        accessorKey: 'ic_number',
        header: 'Identity',
        enableSorting: false,
        cell: ({ getValue }) => getValue() ?? '-',
    },
    {
        accessorKey: 'debts_count',
        header: 'Debts',
        enableSorting: false,
        cell: ({ getValue }) => {
            const n = Number(getValue() ?? 0);
            return h('span', { class: `rounded-full px-2 py-0.5 text-xs font-bold ${n > 0 ? 'bg-[#fff1ec] text-[#b64a2b]' : 'bg-gray-100 text-gray-500'}` }, String(n));
        },
    },
    {
        accessorKey: 'total_outstanding',
        header: 'Outstanding',
        enableSorting: true,
        meta: { class: { th: 'text-right', td: 'text-right font-semibold' } },
        cell: ({ getValue }) => new Intl.NumberFormat('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Number(getValue() ?? 0)),
    },
    {
        accessorKey: 'current_month_deduction_total',
        header: `${props.monthLabel}`,
        enableSorting: true,
        meta: { class: { th: 'text-right', td: 'text-right' } },
        cell: ({ getValue }) => new Intl.NumberFormat('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Number(getValue() ?? 0)),
    },
    {
        id: 'actions',
        header: 'Statement',
        enableHiding: false,
        meta: { class: { th: 'text-right', td: 'text-right' } },
        cell: ({ row }) => h(UButton, {
            variant: 'outline', size: 'sm', as: 'a',
            href: `/kps/sites/${props.site.id}/reports/peneroka/${row.original.id}`,
            class: 'rounded-full border-[#e2c9c0]',
        }, () => 'View'),
    },
];

const formatMoney = (v?: number | null) => new Intl.NumberFormat('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Number(v ?? 0));
const formatNumber = (v?: number | null) => new Intl.NumberFormat('en-MY', { minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(Number(v ?? 0));
const averageExposure = computed(() => props.summary.peneroka_count > 0 ? props.summary.outstanding_total / props.summary.peneroka_count : 0);
</script>
```

In the template, replace the old peneroka table section with:

```html
<!-- Search bar -->
<div class="flex items-center gap-3 mb-4">
    <UInput v-model="globalFilter" placeholder="Find peneroka, IC, or phone…" icon="i-lucide-search" class="w-[300px]" />
</div>

<!-- UTable -->
<UTable
    :data="penerokas.data"
    :columns="columns"
    v-model:sorting="sorting"
    :sorting-options="{ manualSorting: true }"
    :global-filter-options="{ manualFiltering: true }"
    :empty-state="{ label: 'No peneroka found.' }"
/>

<!-- Pagination -->
<div v-if="penerokas.last_page > 1" class="flex items-center justify-between border-t border-[#f0dfd8] px-7 py-4">
    <p class="text-sm text-[#6d5952]">Showing {{ penerokas.from }}–{{ penerokas.to }} of {{ penerokas.total }}</p>
    <UPagination :page="penerokas.current_page" :total="penerokas.total" :items-per-page="penerokas.per_page" @update:page="goToPage" />
</div>
```

- [ ] **Step 2: Commit**

```bash
git add resources/js/pages/Kps/Reports/Index.vue
git commit -m "feat: replace Reports table with UTable (server-side search, sort, pagination)"
```

---

## Task 10: Admin Users Table — UTable with Column Visibility

**Features:** existing search/role/status filter, add sort (name, email), column visibility dropdown.

**Files:** `resources/js/pages/Admin/Users/Index.vue`

- [ ] **Step 1: Read `Admin/Users/Index.vue`** to understand the existing structure before modifying.

- [ ] **Step 2: Replace the table section with UTable**

Add to `<script setup>`:
```typescript
import { useServerTable } from '@/composables/useServerTable';
import type { TableColumn } from '@nuxt/ui';
import { upperFirst } from 'scule'; // installed with @nuxt/ui

// ... keep existing props and state
const baseUrl = '/admin/users'; // adjust to match your route
const { globalFilter, sorting, setFilter, goToPage } = useServerTable(baseUrl, {
    search: props.filters.search ?? '',
    sort_by: props.filters.sort_by ?? 'created_at',
    sort_dir: props.filters.sort_dir ?? 'desc',
    role: props.filters.role,
    active: props.filters.active,
});

const table = useTemplateRef('table');
const columnVisibility = ref<Record<string, boolean>>({});
```

Define columns matching the existing table (name, email, role badge, department, status, actions). Use `enableSorting: true` on name and email.

Replace the existing `<table>` markup with:
```html
<UInput v-model="globalFilter" placeholder="Search users…" icon="i-lucide-search" class="w-[260px]" />

<UDropdownMenu :items="table?.tableApi?.getAllColumns().filter(c => c.getCanHide()).map(c => ({
    label: upperFirst(c.id),
    type: 'checkbox',
    checked: c.getIsVisible(),
    onUpdateChecked: (v) => c.toggleVisibility(!!v),
    onSelect: (e) => e.preventDefault(),
}))" :content="{ align: 'end' }">
    <UButton label="Columns" color="neutral" variant="outline" trailing-icon="i-lucide-chevron-down" />
</UDropdownMenu>

<UTable
    ref="table"
    :data="users.data"
    :columns="columns"
    v-model:sorting="sorting"
    v-model:column-visibility="columnVisibility"
    :sorting-options="{ manualSorting: true }"
    :global-filter-options="{ manualFiltering: true }"
/>

<UPagination :page="users.current_page" :total="users.total" :items-per-page="users.per_page" @update:page="goToPage" />
```

- [ ] **Step 3: Commit**

```bash
git add resources/js/pages/Admin/Users/Index.vue
git commit -m "feat: replace Admin Users table with UTable (sort, column visibility)"
```

---

## Task 11: Admin Roles Table — UTable

**Features:** search (name), display all roles (no pagination — small dataset).

**Files:** `resources/js/pages/Admin/Roles/Index.vue`

- [ ] **Step 1: Read the file** to understand current structure.

- [ ] **Step 2: Add UInput search and replace table with UTable**

Add `useServerTable` with `search` only. Define columns for role name (with system badge), description, user count, permission count, status, actions.

```typescript
const baseUrl = '/admin/roles';
const { globalFilter } = useServerTable(baseUrl, { search: props.filters?.search ?? '' });
```

Replace table with:
```html
<UInput v-model="globalFilter" placeholder="Search roles…" icon="i-lucide-search" class="w-[260px]" />
<UTable :data="roles" :columns="columns" :global-filter-options="{ manualFiltering: true }" />
```

- [ ] **Step 3: Commit**

```bash
git add resources/js/pages/Admin/Roles/Index.vue
git commit -m "feat: replace Admin Roles table with UTable (search)"
```

---

## Final Verification

- [ ] **Run full test suite**

```bash
php artisan test --no-coverage
```
Expected: all tests pass.

- [ ] **Build production assets**

```bash
npm run build
```
Expected: build completes with no TypeScript or Vite errors.

- [ ] **Manual smoke test in browser**

Start dev server and verify:
1. All 8 tables render with `UTable` styling
2. Typing in search triggers debounced `router.get()` (check Network tab — request fires ~400ms after typing stops)
3. Clicking a sortable column header updates the sort indicator and re-fetches sorted data
4. `UPagination` navigates between pages while preserving search/filter state
5. The existing KPS warm-brown visual style is intact (custom CSS variables still apply)
