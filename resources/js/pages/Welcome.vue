<script setup lang="ts">
import { dashboard, login, register } from '@/routes';
import { useLocale } from '@/composables/useLocale';
import { Head, Link } from '@inertiajs/vue3';

withDefaults(
    defineProps<{
        canRegister: boolean;
    }>(),
    {
        canRegister: true,
    },
);

const { t } = useLocale();
</script>

<template>
    <Head :title="t('welcome.title', 'KPS')">
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link
            href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
            rel="stylesheet"
        />
    </Head>

    <div
        class="min-h-screen bg-[#F7F9FC] text-[#101828] font-sans"
        style="--brand: #8bc34a; --brand-strong: #7ab93a; --ink: #101828; --muted: #667085; --border: #e4e7ec; --surface: #f1f5f9;"
    >
        <div class="relative">
            <header class="relative z-10">
                <div
                    class="mx-auto flex w-full max-w-6xl items-center justify-between px-6 pt-6"
                >
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-2xl bg-[color:var(--brand)] text-white shadow-sm"
                        >
                            <img
                                src="/images/logo.png"
                                alt="KPS"
                                class="h-6 w-6"
                            />
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-[#101828]">
                                KPS
                            </p>
                            <p class="text-xs text-[#667085]">
                                FELDA debt and deduction operations
                            </p>
                        </div>
                    </div>

                    <nav
                        class="hidden items-center gap-6 text-sm font-medium text-[#475467] md:flex"
                    >
                        <a href="#features" class="hover:text-[#101828]"
                            >Features</a
                        >
                        <a href="#workflow" class="hover:text-[#101828]"
                            >Workflow</a
                        >
                        <a href="#roles" class="hover:text-[#101828]"
                            >Roles</a
                        >
                        <a href="#pricing" class="hover:text-[#101828]"
                            >Rollout</a
                        >
                        <a href="#faq" class="hover:text-[#101828]">FAQ</a>
                    </nav>

                    <div class="flex items-center gap-2 text-sm">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="dashboard()"
                            class="rounded-xl border border-[#e4e7ec] bg-white px-4 py-2 font-medium text-[#2f6a1f] shadow-sm transition hover:border-[#d0d5dd]"
                        >
                            Dashboard
                        </Link>
                        <template v-else>
                            <Link
                                :href="login()"
                                class="rounded-xl border border-transparent px-3 py-2 font-medium text-[#2f6a1f] transition hover:border-[#e4e7ec] hover:bg-[#f2f4f7]"
                            >
                                Log in
                            </Link>
                            <Link
                                v-if="canRegister"
                                :href="register()"
                                class="rounded-xl bg-[color:var(--brand)] px-4 py-2 font-semibold text-white shadow-sm shadow-[#8bc34a]/30 transition hover:bg-[#7ab93a]"
                            >
                                Register
                            </Link>
                        </template>
                    </div>
                </div>
            </header>

            <main class="relative z-10">
                <section
                    class="mx-auto flex w-full max-w-6xl flex-col gap-10 px-6 pb-16 pt-12 lg:flex-row lg:items-center lg:pt-16"
                >
                    <div class="max-w-xl">
                        <p
                            class="inline-flex items-center gap-2 rounded-full border border-[#d8eadb] bg-white px-4 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-[#2f6a1f]"
                        >
                            Operations-ready platform
                        </p>
                        <h1
                            class="mt-5 text-4xl font-semibold leading-tight text-[#101828] sm:text-5xl lg:text-6xl font-['Inter']"
                        >
                            Run multi-site debt allocation without spreadsheet drift
                        </h1>
                        <p class="mt-5 text-lg text-[#475467]">
                            KPS gives HQ and site teams a structured, auditable way to manage
                            peneroka, hutang, monthly potongan, and deterministic allocations.
                        </p>
                        <div class="mt-8 flex flex-wrap items-center gap-3">
                            <a
                                href="#demo"
                                class="inline-flex items-center justify-center rounded-xl bg-[color:var(--brand)] px-5 py-3 text-sm font-semibold text-white shadow-sm shadow-[#8bc34a]/30 transition hover:bg-[#7ab93a]"
                            >
                                Request a demo
                            </a>
                            <Link
                                v-if="$page.props.auth.user"
                                :href="dashboard()"
                                class="inline-flex items-center justify-center rounded-xl border border-[#e4e7ec] bg-white px-5 py-3 text-sm font-semibold text-[#2f6a1f] shadow-sm transition hover:border-[#d0d5dd]"
                            >
                                Open dashboard
                            </Link>
                            <Link
                                v-else
                                :href="login()"
                                class="inline-flex items-center justify-center rounded-xl border border-[#e4e7ec] bg-white px-5 py-3 text-sm font-semibold text-[#2f6a1f] shadow-sm transition hover:border-[#d0d5dd]"
                            >
                                Log in
                            </Link>
                        </div>
                        <p class="mt-4 text-xs text-[#667085]">
                            Month closing, site scoping, and audit trails built in.
                        </p>
                        <div class="mt-6 flex flex-wrap gap-2 text-xs font-medium">
                            <span
                                class="rounded-full border border-[#e4e7ec] bg-white px-3 py-1 text-[#2f6a1f]"
                                >Deterministic allocation</span
                            >
                            <span
                                class="rounded-full border border-[#e4e7ec] bg-white px-3 py-1 text-[#2f6a1f]"
                                >HQ and site scopes</span
                            >
                            <span
                                class="rounded-full border border-[#e4e7ec] bg-white px-3 py-1 text-[#2f6a1f]"
                                >Monthly closing</span
                            >
                        </div>
                    </div>

                    <div class="relative flex-1">
                        <div
                            class="relative rounded-[2rem] border border-[#e4e7ec] bg-white p-6 shadow-[0_20px_40px_rgba(16,24,40,0.12)]"
                        >
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span
                                        class="h-2.5 w-2.5 rounded-full bg-[#8bc34a]"
                                    ></span>
                                    <span
                                        class="h-2.5 w-2.5 rounded-full bg-[#f6c453]"
                                    ></span>
                                    <span
                                        class="h-2.5 w-2.5 rounded-full bg-[#9ec5ff]"
                                    ></span>
                                </div>
                                <span class="text-xs text-[#98a2b3]"
                                    >KPS overview</span
                                >
                            </div>
                            <div class="mt-5 grid gap-4 sm:grid-cols-2">
                                <div
                                    class="rounded-2xl border border-[#e4e7ec] bg-[#f3fbf6] p-4"
                                >
                                    <p class="text-xs text-[#667085]">
                                        Monthly close time
                                    </p>
                                    <p
                                        class="mt-2 text-2xl font-semibold text-[#101828]"
                                    >
                                        1.8 days
                                    </p>
                                    <p class="mt-1 text-xs text-[#667085]">
                                        Target: under 2 days
                                    </p>
                                </div>
                                <div
                                    class="rounded-2xl border border-[#e4e7ec] bg-white p-4"
                                >
                                    <p class="text-xs text-[#667085]">
                                        Allocation coverage
                                    </p>
                                    <div class="mt-3 space-y-2">
                                        <div class="h-2 rounded-full bg-[#f0f8f1]">
                                            <div
                                                class="h-2 w-4/5 rounded-full bg-[#8bc34a]"
                                            ></div>
                                        </div>
                                        <p
                                            class="text-xs font-medium text-[#2f6a1f]"
                                        >
                                            92% of posted deductions allocated
                                        </p>
                                    </div>
                                </div>
                                <div
                                    class="rounded-2xl border border-[#e4e7ec] bg-white p-4 sm:col-span-2"
                                >
                                    <p class="text-xs text-[#667085]">
                                        Active scope
                                    </p>
                                    <div class="mt-3 grid gap-3 sm:grid-cols-3">
                                        <div class="rounded-xl bg-[#f9fafb] p-3 text-xs">
                                            <p class="font-semibold text-[#2f6a1f]">
                                                Sites
                                            </p>
                                            <p class="text-[#98a2b3]">
                                                14 active
                                            </p>
                                        </div>
                                        <div class="rounded-xl bg-[#f9fafb] p-3 text-xs">
                                            <p class="font-semibold text-[#2f6a1f]">
                                                Peneroka
                                            </p>
                                            <p class="text-[#98a2b3]">
                                                1,280 tracked
                                            </p>
                                        </div>
                                        <div class="rounded-xl bg-[#f9fafb] p-3 text-xs">
                                            <p class="font-semibold text-[#2f6a1f]">
                                                Potongan
                                            </p>
                                            <p class="text-[#98a2b3]">
                                                April cycle open
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="mt-4 rounded-2xl border border-dashed border-[#e4e7ec] bg-[#f9fafb] p-4 text-center text-xs text-[#98a2b3]"
                            >
                                Replace with live dashboard screenshot
                            </div>
                        </div>
                    </div>
                </section>

                <section class="mx-auto w-full max-w-6xl px-6">
                    <div
                        class="grid gap-4 rounded-3xl border border-[#e4e7ec] bg-white p-6 shadow-sm sm:grid-cols-2 lg:grid-cols-4"
                    >
                        <div>
                            <p
                                class="text-xs font-semibold uppercase tracking-[0.2em] text-[#98a2b3]"
                            >
                                Audit trail
                            </p>
                            <p class="mt-2 text-2xl font-semibold text-[#101828]">
                                98%
                            </p>
                            <p class="mt-1 text-xs text-[#98a2b3]">
                                Allocation traceability
                            </p>
                        </div>
                        <div>
                            <p
                                class="text-xs font-semibold uppercase tracking-[0.2em] text-[#98a2b3]"
                            >
                                HQ visibility
                            </p>
                            <p class="mt-2 text-2xl font-semibold text-[#101828]">
                                1.8 days
                            </p>
                            <p class="mt-1 text-xs text-[#98a2b3]">
                                Across all active sites
                            </p>
                        </div>
                        <div>
                            <p
                                class="text-xs font-semibold uppercase tracking-[0.2em] text-[#98a2b3]"
                            >
                                Operating model
                            </p>
                            <p class="mt-2 text-2xl font-semibold text-[#101828]">
                                2
                            </p>
                            <p class="mt-1 text-xs text-[#98a2b3]">
                                HQ and site scopes
                            </p>
                        </div>
                        <div>
                            <p
                                class="text-xs font-semibold uppercase tracking-[0.2em] text-[#98a2b3]"
                            >
                                Deployment
                            </p>
                            <p class="mt-2 text-2xl font-semibold text-[#101828]">
                                14 days
                            </p>
                            <p class="mt-1 text-xs text-[#98a2b3]">
                                To production-ready
                            </p>
                        </div>
                    </div>
                </section>
                <section id="features" class="mx-auto mt-16 w-full max-w-6xl px-6">
                    <div
                        class="grid gap-8 rounded-[2.5rem] border border-[#e4e7ec] bg-[#f3fbf6] p-8 shadow-sm lg:grid-cols-[1.1fr_0.9fr]"
                    >
                        <div>
                            <p
                                class="text-xs font-semibold uppercase tracking-[0.2em] text-[#2f6a1f]"
                            >
                                Feature highlight
                            </p>
                            <h2
                                class="mt-4 text-3xl font-semibold text-[#101828] sm:text-4xl font-['Inter']"
                            >
                                Monthly deductions flow, allocated and reviewable
                            </h2>
                            <p class="mt-4 text-base text-[#475467]">
                                From debt capture to month closing, every step is visible to HQ
                                and site teams in one auditable workflow.
                            </p>
                            <div class="mt-6 flex flex-wrap gap-3">
                                <a
                                    href="#demo"
                                    class="inline-flex items-center justify-center rounded-xl bg-[color:var(--brand)] px-4 py-2 text-sm font-semibold text-white shadow-sm shadow-[#8bc34a]/30 transition hover:bg-[#7ab93a]"
                                >
                                    See a live walkthrough
                                </a>
                                <a
                                    href="#workflow"
                                    class="inline-flex items-center justify-center rounded-xl border border-[#e4e7ec] bg-white px-4 py-2 text-sm font-semibold text-[#2f6a1f] shadow-sm transition hover:border-[#d0d5dd]"
                                >
                                    View workflow
                                </a>
                            </div>
                        </div>
                        <div class="rounded-3xl bg-white p-5 shadow-sm">
                            <p class="text-xs font-semibold text-[#98a2b3]">
                                Live process snapshot
                            </p>
                            <div class="mt-4 space-y-3">
                                <div
                                    class="rounded-2xl border border-[#e4e7ec] bg-[#f9fafb] p-4"
                                >
                                    <p class="text-xs font-semibold text-[#2f6a1f]">
                                        Potongan entry
                                    </p>
                                    <p class="mt-2 text-sm text-[#475467]">
                                        Single and bulk monthly deduction entry
                                    </p>
                                </div>
                                <div
                                    class="rounded-2xl border border-[#e4e7ec] bg-[#f9fafb] p-4"
                                >
                                    <p class="text-xs font-semibold text-[#2f6a1f]">
                                        Allocation
                                    </p>
                                    <p class="mt-2 text-sm text-[#475467]">
                                        Priority waterfall applied consistently
                                    </p>
                                </div>
                                <div
                                    class="rounded-2xl border border-[#e4e7ec] bg-[#f9fafb] p-4"
                                >
                                    <p class="text-xs font-semibold text-[#2f6a1f]">
                                        Review
                                    </p>
                                    <p class="mt-2 text-sm text-[#475467]">
                                        Reallocation and month closing with audit logs
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-10 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                        <div class="rounded-2xl border border-[#e4e7ec] bg-gradient-to-br from-white via-[#f8fbf7] to-[#eef7f0] p-6 shadow-sm">
                            <div class="flex items-center gap-3">
                                <span
                                    class="flex h-10 w-10 items-center justify-center rounded-full bg-[#eaf7ee] text-[#2f6a1f]"
                                >
                                    <svg
                                        viewBox="0 0 24 24"
                                        class="h-5 w-5"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    >
                                        <path d="M12 3l7 4v5c0 5-3.5 9-7 10-3.5-1-7-5-7-10V7l7-4z" />
                                        <path d="M9 12l2 2 4-4" />
                                    </svg>
                                </span>
                                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#98a2b3]">
                                    Audit trail
                                </p>
                            </div>
                            <h3 class="mt-4 text-lg font-semibold text-[#101828]">
                                Tamper-evident records
                            </h3>
                            <p class="mt-2 text-sm text-[#475467]">
                                Every action is captured with a full chain of custody.
                            </p>
                        </div>
                        <div class="rounded-2xl border border-[#e4e7ec] bg-gradient-to-br from-white via-[#f5f8ff] to-[#edf3ff] p-6 shadow-sm">
                            <div class="flex items-center gap-3">
                                <span
                                    class="flex h-10 w-10 items-center justify-center rounded-full bg-[#e8efff] text-[#2563eb]"
                                >
                                    <svg
                                        viewBox="0 0 24 24"
                                        class="h-5 w-5"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    >
                                        <path d="M3 7h4l2-2h6l2 2h4v12H3z" />
                                        <circle cx="12" cy="13" r="3" />
                                    </svg>
                                </span>
                                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#98a2b3]">
                                    Multi-site
                                </p>
                            </div>
                            <h3 class="mt-4 text-lg font-semibold text-[#101828]">
                                HQ and site separation
                            </h3>
                            <p class="mt-2 text-sm text-[#475467]">
                                Dual navigation keeps HQ oversight separate from site operations.
                            </p>
                        </div>
                        <div class="rounded-2xl border border-[#e4e7ec] bg-gradient-to-br from-white via-[#fff9ed] to-[#fff1d6] p-6 shadow-sm">
                            <div class="flex items-center gap-3">
                                <span
                                    class="flex h-10 w-10 items-center justify-center rounded-full bg-[#fff3d6] text-[#b45309]"
                                >
                                    <svg
                                        viewBox="0 0 24 24"
                                        class="h-5 w-5"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    >
                                        <path d="M3 7l9-4 9 4-9 4-9-4z" />
                                        <path d="M3 7v10l9 4 9-4V7" />
                                        <path d="M12 11v10" />
                                    </svg>
                                </span>
                                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#98a2b3]">
                                    Allocation
                                </p>
                            </div>
                            <h3 class="mt-4 text-lg font-semibold text-[#101828]">
                                Deterministic waterfall
                            </h3>
                            <p class="mt-2 text-sm text-[#475467]">
                                Priority, due date, and creation order stay predictable.
                            </p>
                        </div>
                        <div class="rounded-2xl border border-[#e4e7ec] bg-gradient-to-br from-white via-[#f0fbff] to-[#e6f6ff] p-6 shadow-sm">
                            <div class="flex items-center gap-3">
                                <span
                                    class="flex h-10 w-10 items-center justify-center rounded-full bg-[#e0f2fe] text-[#0284c7]"
                                >
                                    <svg
                                        viewBox="0 0 24 24"
                                        class="h-5 w-5"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    >
                                        <rect x="3" y="5" width="18" height="16" rx="2" />
                                        <path d="M16 3v4M8 3v4M3 11h18" />
                                    </svg>
                                </span>
                                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#98a2b3]">
                                    Monthly closing
                                </p>
                            </div>
                            <h3 class="mt-4 text-lg font-semibold text-[#101828]">
                                Locked accounting periods
                            </h3>
                            <p class="mt-2 text-sm text-[#475467]">
                                Closed months stop edits and preserve the audit trail.
                            </p>
                        </div>
                        <div class="rounded-2xl border border-[#e4e7ec] bg-gradient-to-br from-white via-[#f6f0ff] to-[#efe7ff] p-6 shadow-sm">
                            <div class="flex items-center gap-3">
                                <span
                                    class="flex h-10 w-10 items-center justify-center rounded-full bg-[#efe7ff] text-[#7c3aed]"
                                >
                                    <svg
                                        viewBox="0 0 24 24"
                                        class="h-5 w-5"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    >
                                        <path d="M4 20V10" />
                                        <path d="M10 20V4" />
                                        <path d="M16 20v-6" />
                                        <path d="M22 20v-12" />
                                    </svg>
                                </span>
                                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#98a2b3]">
                                    Analytics
                                </p>
                            </div>
                            <h3 class="mt-4 text-lg font-semibold text-[#101828]">
                                Reports for leadership
                            </h3>
                            <p class="mt-2 text-sm text-[#475467]">
                                Export-ready views for finance, compliance, and oversight.
                            </p>
                        </div>
                        <div class="rounded-2xl border border-[#e4e7ec] bg-gradient-to-br from-white via-[#fff1f5] to-[#ffe4ec] p-6 shadow-sm">
                            <div class="flex items-center gap-3">
                                <span
                                    class="flex h-10 w-10 items-center justify-center rounded-full bg-[#ffe4ec] text-[#db2777]"
                                >
                                    <svg
                                        viewBox="0 0 24 24"
                                        class="h-5 w-5"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    >
                                        <rect x="5" y="11" width="14" height="10" rx="2" />
                                        <path d="M8 11V8a4 4 0 0 1 8 0v3" />
                                        <path d="M12 15v2" />
                                    </svg>
                                </span>
                                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#98a2b3]">
                                    Access control
                                </p>
                            </div>
                            <h3 class="mt-4 text-lg font-semibold text-[#101828]">
                                Site-scoped access
                            </h3>
                            <p class="mt-2 text-sm text-[#475467]">
                                Users only see the sites and actions assigned to them.
                            </p>
                        </div>
                    </div>
                </section>

                <section id="workflow" class="mx-auto mt-16 w-full max-w-6xl px-6">
                    <div class="grid gap-8 lg:grid-cols-[1fr_1.2fr]">
                        <div>
                            <p
                                class="text-xs font-semibold uppercase tracking-[0.2em] text-[#98a2b3]"
                            >
                                Workflow
                            </p>
                            <h2
                                class="mt-4 text-3xl font-semibold text-[#101828] font-['Inter']"
                            >
                                One flow from debt entry to month close
                            </h2>
                            <p class="mt-3 text-base text-[#475467]">
                                The end-to-end KPS process translated into a single, visible
                                pipeline with checkpoints for HQ and site teams.
                            </p>
                            <a
                                href="#demo"
                                class="mt-6 inline-flex items-center justify-center rounded-xl border border-[#e4e7ec] bg-white px-4 py-2 text-sm font-semibold text-[#2f6a1f] shadow-sm transition hover:border-[#d0d5dd]"
                            >
                                Walk me through it
                            </a>
                        </div>
                        <div
                            class="rounded-3xl border border-[#e4e7ec] bg-white p-6 shadow-sm"
                        >
                            <ol class="space-y-4 text-sm">
                                <li class="flex gap-4">
                                    <div
                                        class="flex h-9 w-9 items-center justify-center rounded-full bg-[#f3fbf6] text-sm font-semibold text-[#2f6a1f]"
                                    >
                                        1
                                    </div>
                                    <div>
                                        <p class="font-semibold text-[#101828]">
                                            Select site
                                        </p>
                                        <p class="text-[#667085]">
                                            Work inside the correct site boundary from the start.
                                        </p>
                                    </div>
                                </li>
                                <li class="flex gap-4">
                                    <div
                                        class="flex h-9 w-9 items-center justify-center rounded-full bg-[#f3fbf6] text-sm font-semibold text-[#2f6a1f]"
                                    >
                                        2
                                    </div>
                                    <div>
                                        <p class="font-semibold text-[#101828]">
                                            Register debt
                                        </p>
                                        <p class="text-[#667085]">
                                            Capture peneroka debts with priority and due date.
                                        </p>
                                    </div>
                                </li>
                                <li class="flex gap-4">
                                    <div
                                        class="flex h-9 w-9 items-center justify-center rounded-full bg-[#f3fbf6] text-sm font-semibold text-[#2f6a1f]"
                                    >
                                        3
                                    </div>
                                    <div>
                                        <p class="font-semibold text-[#101828]">
                                            Enter potongan
                                        </p>
                                        <p class="text-[#667085]">
                                            Post monthly deductions individually or in bulk.
                                        </p>
                                    </div>
                                </li>
                                <li class="flex gap-4">
                                    <div
                                        class="flex h-9 w-9 items-center justify-center rounded-full bg-[#f3fbf6] text-sm font-semibold text-[#2f6a1f]"
                                    >
                                        4
                                    </div>
                                    <div>
                                        <p class="font-semibold text-[#101828]">
                                            Allocate automatically
                                        </p>
                                        <p class="text-[#667085]">
                                            Apply the waterfall and record allocation line items.
                                        </p>
                                    </div>
                                </li>
                                <li class="flex gap-4">
                                    <div
                                        class="flex h-9 w-9 items-center justify-center rounded-full bg-[#f3fbf6] text-sm font-semibold text-[#2f6a1f]"
                                    >
                                        5
                                    </div>
                                    <div>
                                        <p class="font-semibold text-[#101828]">
                                            Review and close month
                                        </p>
                                        <p class="text-[#667085]">
                                            Recalculate if needed, then lock the month with audit logs.
                                        </p>
                                    </div>
                                </li>
                            </ol>
                        </div>
                    </div>
                </section>
                <section class="mx-auto mt-16 w-full max-w-6xl px-6">
                    <div
                        class="rounded-[2.5rem] border border-[#f0e2c3] bg-[#fff7e6] p-8 shadow-sm"
                    >
                        <div class="flex flex-col gap-6 lg:flex-row">
                            <div class="flex-1">
                                <p
                                    class="text-xs font-semibold uppercase tracking-[0.2em] text-[#9a6b13]"
                                >
                                    Compliance
                                </p>
                                <h2
                                    class="mt-4 text-3xl font-semibold text-[#101828] font-['Inter']"
                                >
                                    Built for cooperative-grade governance
                                </h2>
                                <p class="mt-3 text-base text-[#6a5c37]">
                                    Keep allocation logic, month locking, and cross-site visibility
                                    aligned with policy through immutable logs and clear accountability.
                                </p>
                            </div>
                            <div class="flex-1">
                                <div class="grid gap-3 sm:grid-cols-2">
                                    <div
                                        class="rounded-2xl bg-white p-4 text-sm text-[#5b4b2d]"
                                    >
                                        Tamper-evident logs
                                    </div>
                                    <div
                                        class="rounded-2xl bg-white p-4 text-sm text-[#5b4b2d]"
                                    >
                                        Month locking
                                    </div>
                                    <div
                                        class="rounded-2xl bg-white p-4 text-sm text-[#5b4b2d]"
                                    >
                                        Site scoping
                                    </div>
                                    <div
                                        class="rounded-2xl bg-white p-4 text-sm text-[#5b4b2d]"
                                    >
                                        Export-ready reports
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="roles" class="mx-auto mt-16 w-full max-w-6xl px-6">
                    <div class="text-center">
                        <p
                            class="text-xs font-semibold uppercase tracking-[0.2em] text-[#98a2b3]"
                        >
                            Role impact
                        </p>
                        <h2
                            class="mt-4 text-3xl font-semibold text-[#101828] font-['Inter']"
                        >
                            Every stakeholder sees the right scope
                        </h2>
                        <p class="mt-3 text-base text-[#475467]">
                            Focused views for HQ admins, site admins, site staff, and oversight
                            teams.
                        </p>
                    </div>
                    <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        <div class="rounded-2xl border border-[#e4e7ec] bg-white p-6 shadow-sm">
                            <p class="text-sm font-semibold text-[#101828]">
                                HQ Admin
                            </p>
                            <p class="mt-2 text-sm text-[#475467]">
                                Full visibility across all sites, dashboards, and reporting.
                            </p>
                        </div>
                        <div class="rounded-2xl border border-[#e4e7ec] bg-white p-6 shadow-sm">
                            <p class="text-sm font-semibold text-[#101828]">
                                Company Admin
                            </p>
                            <p class="mt-2 text-sm text-[#475467]">
                                Maintains site access, analytics, and operational configuration.
                            </p>
                        </div>
                        <div class="rounded-2xl border border-[#e4e7ec] bg-white p-6 shadow-sm">
                            <p class="text-sm font-semibold text-[#101828]">
                                Site Admin
                            </p>
                            <p class="mt-2 text-sm text-[#475467]">
                                Manages peneroka, debts, deductions, and closing activities.
                            </p>
                        </div>
                        <div class="rounded-2xl border border-[#e4e7ec] bg-white p-6 shadow-sm">
                            <p class="text-sm font-semibold text-[#101828]">
                                Site Staff
                            </p>
                            <p class="mt-2 text-sm text-[#475467]">
                                Handles daily entry, review support, and site-scoped updates.
                            </p>
                        </div>
                        <div class="rounded-2xl border border-[#e4e7ec] bg-white p-6 shadow-sm">
                            <p class="text-sm font-semibold text-[#101828]">
                                Oversight Teams
                            </p>
                            <p class="mt-2 text-sm text-[#475467]">
                                Uses consolidated reports to monitor collection performance.
                            </p>
                        </div>
                    </div>
                </section>

                <section class="mx-auto mt-16 w-full max-w-6xl px-6">
                    <div
                        class="grid gap-6 rounded-[2.5rem] border border-[#e4e7ec] bg-white p-8 shadow-sm lg:grid-cols-3"
                    >
                        <div>
                            <p
                                class="text-xs font-semibold uppercase tracking-[0.2em] text-[#98a2b3]"
                            >
                                Social proof
                            </p>
                            <h2
                                class="mt-4 text-3xl font-semibold text-[#101828] font-['Inter']"
                            >
                                Trusted to reduce reconciliation backlog
                            </h2>
                            <p class="mt-3 text-base text-[#475467]">
                                Short, outcome-focused feedback from cooperative rollout teams.
                            </p>
                        </div>
                        <div
                            class="rounded-2xl border border-[#e4e7ec] bg-[#f9fafb] p-5"
                        >
                            <p class="text-sm text-[#475467]">
                                "Monthly allocation review now takes minutes instead of days."
                            </p>
                            <p class="mt-4 text-xs font-semibold text-[#2f6a1f]">
                                Site Operations Team
                            </p>
                        </div>
                        <div
                            class="rounded-2xl border border-[#e4e7ec] bg-[#f9fafb] p-5"
                        >
                            <p class="text-sm text-[#475467]">
                                "HQ finally has one view across every site."
                            </p>
                            <p class="mt-4 text-xs font-semibold text-[#2f6a1f]">
                                Cooperative HQ
                            </p>
                        </div>
                    </div>
                </section>

                <section id="pricing" class="mx-auto mt-16 w-full max-w-6xl px-6">
                    <div class="text-center">
                        <p
                            class="text-xs font-semibold uppercase tracking-[0.2em] text-[#98a2b3]"
                        >
                            Procurement
                        </p>
                        <h2
                            class="mt-4 text-3xl font-semibold text-[#101828] font-['Inter']"
                        >
                            Rollout options for KPS operations
                        </h2>
                        <p class="mt-3 text-base text-[#475467]">
                            Start with one site, then scale across the network.
                        </p>
                    </div>
                    <div class="mt-10 grid gap-6 lg:grid-cols-3">
                        <div class="rounded-2xl border border-[#e4e7ec] bg-white p-6 shadow-sm">
                            <p class="text-sm font-semibold text-[#101828]">Pilot</p>
                            <p class="mt-2 text-sm text-[#475467]">
                                One site with peneroka, hutang, and potongan workflow.
                            </p>
                            <p class="mt-4 text-2xl font-semibold text-[#101828]">
                                RM 35k
                            </p>
                            <p class="mt-1 text-xs text-[#98a2b3]">
                                Procurement-ready documentation
                            </p>
                        </div>
                        <div
                            class="rounded-2xl border border-[#e4e7ec] bg-[#f3fbf6] p-6 shadow-sm"
                        >
                            <p
                                class="text-xs font-semibold uppercase tracking-[0.2em] text-[#2f6a1f]"
                            >
                                Most popular
                            </p>
                            <p class="mt-2 text-sm font-semibold text-[#101828]">
                                Multi-Site Rollout
                            </p>
                            <p class="mt-2 text-sm text-[#475467]">
                                Multi-site deployment with HQ dashboards and reporting.
                            </p>
                            <p class="mt-4 text-2xl font-semibold text-[#101828]">
                                RM 45k
                            </p>
                            <p class="mt-1 text-xs text-[#98a2b3]">
                                Training and onboarding included
                            </p>
                        </div>
                        <div class="rounded-2xl border border-[#e4e7ec] bg-white p-6 shadow-sm">
                            <p class="text-sm font-semibold text-[#101828]">Enterprise</p>
                            <p class="mt-2 text-sm text-[#475467]">
                                Network-wide rollout with integrations and governance controls.
                            </p>
                            <p class="mt-4 text-2xl font-semibold text-[#101828]">
                                RM 55k
                            </p>
                            <p class="mt-1 text-xs text-[#98a2b3]">
                                Custom governance modules
                            </p>
                        </div>
                    </div>
                </section>
                <section id="faq" class="mx-auto mt-16 w-full max-w-6xl px-6">
                    <div class="grid gap-8 lg:grid-cols-[1fr_1.2fr]">
                        <div>
                            <p
                                class="text-xs font-semibold uppercase tracking-[0.2em] text-[#98a2b3]"
                            >
                                FAQ
                            </p>
                            <h2
                                class="mt-4 text-3xl font-semibold text-[#101828] font-['Inter']"
                            >
                                Common questions, clear answers
                            </h2>
                            <p class="mt-3 text-base text-[#475467]">
                                Procurement, deployment, and bilingual support covered.
                            </p>
                        </div>
                        <div class="space-y-3">
                            <details
                                class="rounded-2xl border border-[#e4e7ec] bg-white p-4 shadow-sm"
                            >
                                <summary class="cursor-pointer text-sm font-semibold">
                                    Is on-prem deployment supported?
                                </summary>
                                <p class="mt-3 text-sm text-[#475467]">
                                    Yes, we support on-prem and private cloud deployment models.
                                </p>
                            </details>
                            <details
                                class="rounded-2xl border border-[#e4e7ec] bg-white p-4 shadow-sm"
                            >
                                <summary class="cursor-pointer text-sm font-semibold">
                                    How do we handle multi-site access?
                                </summary>
                                <p class="mt-3 text-sm text-[#475467]">
                                    HQ users can move across sites while site users stay scoped to
                                    their assigned site.
                                </p>
                            </details>
                            <details
                                class="rounded-2xl border border-[#e4e7ec] bg-white p-4 shadow-sm"
                            >
                                <summary class="cursor-pointer text-sm font-semibold">
                                    What happens when a month is closed?
                                </summary>
                                <p class="mt-3 text-sm text-[#475467]">
                                    Closed months are locked from edits and the action is audit
                                    logged.
                                </p>
                            </details>
                            <details
                                class="rounded-2xl border border-[#e4e7ec] bg-white p-4 shadow-sm"
                            >
                                <summary class="cursor-pointer text-sm font-semibold">
                                    How quickly can we go live?
                                </summary>
                                <p class="mt-3 text-sm text-[#475467]">
                                    Typical rollout is 14 days with guided onboarding.
                                </p>
                            </details>
                        </div>
                    </div>
                </section>

                <section id="demo" class="mx-auto mt-16 w-full max-w-6xl px-6 pb-20">
                    <div
                        class="rounded-[2.5rem] border border-[#e4e7ec] bg-[#f3fbf6] p-8 text-center shadow-sm"
                    >
                        <h2
                            class="text-3xl font-semibold text-[#101828] sm:text-4xl font-['Inter']"
                        >
                            Ready to move KPS off spreadsheets?
                        </h2>
                        <p class="mt-3 text-base text-[#475467]">
                            Book a demo or jump directly into the live KPS dashboard.
                        </p>
                        <div class="mt-6 flex flex-wrap justify-center gap-3">
                            <a
                                href="mailto:demo@kps.app"
                                class="inline-flex items-center justify-center rounded-xl bg-[color:var(--brand)] px-5 py-3 text-sm font-semibold text-white shadow-sm shadow-[#8bc34a]/30 transition hover:bg-[#7ab93a]"
                            >
                                Request a demo
                            </a>
                            <Link
                                v-if="$page.props.auth.user"
                                :href="dashboard()"
                                class="inline-flex items-center justify-center rounded-xl border border-[#e4e7ec] bg-white px-5 py-3 text-sm font-semibold text-[#2f6a1f] shadow-sm transition hover:border-[#d0d5dd]"
                            >
                                Open dashboard
                            </Link>
                            <Link
                                v-else
                                :href="login()"
                                class="inline-flex items-center justify-center rounded-xl border border-[#e4e7ec] bg-white px-5 py-3 text-sm font-semibold text-[#2f6a1f] shadow-sm transition hover:border-[#d0d5dd]"
                            >
                                Log in
                            </Link>
                        </div>
                    </div>
                </section>
            </main>
        </div>

        <footer class="border-t border-[#e4e7ec] bg-white">
            <div
                class="mx-auto flex w-full max-w-6xl flex-col gap-6 px-6 py-10 text-sm text-[#667085] md:flex-row md:items-center md:justify-between"
            >
                <div>
                    <p class="font-semibold text-[#101828]">
                        KPS
                    </p>
                    <p class="mt-1 text-xs">
                        FELDA debt and deduction platform
                    </p>
                </div>
                <div class="flex flex-wrap gap-4 text-xs">
                    <a href="#features" class="hover:text-[#101828]">
                        Features
                    </a>
                    <a href="#workflow" class="hover:text-[#101828]">
                        Workflow
                    </a>
                    <a href="#pricing" class="hover:text-[#101828]">
                        Rollout
                    </a>
                    <a href="#faq" class="hover:text-[#101828]">FAQ</a>
                </div>
                <p class="text-xs">Audit-first. Allocation-ready.</p>
            </div>
        </footer>
    </div>
</template>










