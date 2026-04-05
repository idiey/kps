<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

import KpsShellLayout from '@/layouts/kps/KpsShellLayout.vue';
import type { KpsSite, KpsSiteRole } from '@/types';

interface SiteTrendPoint {
    month: string;
    label: string;
    amount: number;
    allocated_amount: number;
    unallocated_amount: number;
    closed_count: number;
    deduction_count: number;
    allocation_rate: number;
}

interface SitePenerokaExposure {
    id: string;
    name: string;
    ic_number?: string | null;
    phone?: string | null;
    debt_count: number;
    outstanding: number;
}

interface SiteActivityItem {
    id: string;
    action: string;
    action_label: string;
    created_at: string;
    actor_name: string;
    actor_email?: string | null;
    site_name?: string | null;
    site_code?: string | null;
    summary: string;
}

const props = defineProps<{
    site: KpsSite;
    siteRole?: KpsSiteRole | null;
    monthLabel: string;
    stats: {
        peneroka_count: number;
        active_debt_count: number;
        outstanding: number;
        current_month_deductions: number;
        allocation_rate: number;
    };
    monthlyTrend: SiteTrendPoint[];
    topPeneroka: SitePenerokaExposure[];
    recentActivity: SiteActivityItem[];
}>();

const formatMoney = (value?: number | null) =>
    new Intl.NumberFormat('en-MY', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    }).format(Number(value ?? 0));

const maxTrendAmount = computed(() => Math.max(...props.monthlyTrend.map((point) => point.amount), 1));
const maxOutstanding = computed(() => Math.max(...props.topPeneroka.map((peneroka) => peneroka.outstanding), 1));

const barHeight = (value: number) => `${Math.max(18, (value / maxTrendAmount.value) * 210)}px`;
const exposureWidth = (value: number) => `${Math.max(12, (value / maxOutstanding.value) * 100)}%`;
</script>

<template>
    <Head :title="`Site: ${site.name}`" />

    <KpsShellLayout :site="site" :site-role="siteRole">
        <div class="space-y-6 px-4 pb-8 lg:px-8">
            <section class="flex flex-col gap-5 2xl:flex-row 2xl:items-end 2xl:justify-between">
                <div class="max-w-3xl">
                    <p class="text-[11px] font-bold uppercase tracking-[0.32em] text-[#b7654b]">
                        Site Workspace
                    </p>
                    <h1 class="mt-3 text-4xl font-black tracking-[-0.04em] text-[#1b1b1b] md:text-5xl" style="font-family: Manrope, Inter, sans-serif;">
                        {{ site.name }}
                    </h1>
                    <p class="mt-4 max-w-2xl text-base leading-7 text-[#65534d] md:text-lg">
                        Monitor deduction recovery, debt exposure, and site activity from the live KPS workflow.
                    </p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <Link
                        :href="`/kps/sites/${site.id}/reports`"
                        class="inline-flex items-center rounded-full border border-[#e1cbc2] bg-white px-6 py-3 text-sm font-semibold text-[#6d5952] shadow-[0_10px_30px_rgba(157,80,53,0.06)] transition hover:border-[#c77d62] hover:text-[#1b1b1b]"
                    >
                        Open Reports
                    </Link>
                    <Link
                        :href="`/kps/sites/${site.id}/allocations`"
                        class="inline-flex items-center rounded-full bg-gradient-to-br from-[#d6522d] to-[#bc3f1d] px-6 py-3 text-sm font-semibold text-white shadow-[0_16px_32px_rgba(214,82,45,0.28)] transition hover:translate-y-[-1px]"
                    >
                        Allocation Review
                    </Link>
                    <Link
                        href="/kps/sites"
                        class="inline-flex items-center rounded-full border border-[#e1cbc2] bg-white px-6 py-3 text-sm font-semibold text-[#6d5952] shadow-[0_10px_30px_rgba(157,80,53,0.06)] transition hover:border-[#c77d62] hover:text-[#1b1b1b]"
                    >
                        Back to Sites
                    </Link>
                </div>
            </section>

            <section class="grid gap-4 xl:grid-cols-4">
                <div class="rounded-[30px] border border-[#efdcd5] bg-white/88 p-6 shadow-[0_16px_40px_rgba(157,80,53,0.08)]">
                    <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Peneroka</p>
                    <p class="mt-4 text-4xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">{{ stats.peneroka_count }}</p>
                    <p class="mt-4 text-sm text-[#6d5952]">Assigned to {{ site.code }} in the current working set.</p>
                </div>

                <div class="rounded-[30px] border border-[#efdcd5] bg-white/88 p-6 shadow-[0_16px_40px_rgba(157,80,53,0.08)]">
                    <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Active Debts</p>
                    <p class="mt-4 text-4xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">{{ stats.active_debt_count }}</p>
                    <p class="mt-4 text-sm text-[#6d5952]">Open balances still requiring site follow-up.</p>
                </div>

                <div class="rounded-[30px] border border-[#efdcd5] bg-white/88 p-6 shadow-[0_16px_40px_rgba(157,80,53,0.08)]">
                    <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Outstanding</p>
                    <p class="mt-4 text-4xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">{{ formatMoney(stats.outstanding) }}</p>
                    <p class="mt-4 text-sm text-[#6d5952]">Live exposure across all hutang records linked to this site.</p>
                </div>

                <div class="rounded-[30px] border border-[#efdcd5] bg-white/88 p-6 shadow-[0_16px_40px_rgba(157,80,53,0.08)]">
                    <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#9b7d73]">{{ monthLabel }}</p>
                    <div class="mt-4 flex items-end gap-3">
                        <p class="text-4xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">{{ formatMoney(stats.current_month_deductions) }}</p>
                        <span class="rounded-full bg-[#ebfff3] px-2.5 py-1 text-[11px] font-bold text-[#18754d]">{{ stats.allocation_rate.toFixed(1) }}%</span>
                    </div>
                    <p class="mt-4 text-sm text-[#6d5952]">Allocation effectiveness for the current month.</p>
                </div>
            </section>

            <section class="grid gap-6 2xl:grid-cols-[1.55fr,1fr]">
                <div class="rounded-[34px] border border-[#efdcd5] bg-white/90 p-7 shadow-[0_16px_46px_rgba(157,80,53,0.08)]">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-[0.28em] text-[#b47b67]">Monthly Recovery</p>
                            <h2 class="mt-2 text-2xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">Deduction trend</h2>
                        </div>
                        <span class="rounded-full bg-[#171717] px-4 py-2 text-[11px] font-bold uppercase tracking-[0.22em] text-white">
                            {{ site.code }}
                        </span>
                    </div>

                    <div class="mt-10 flex min-h-[270px] items-end gap-4">
                        <div
                            v-for="point in monthlyTrend"
                            :key="point.month"
                            class="flex flex-1 flex-col items-center gap-3"
                        >
                            <div class="flex h-[240px] w-full items-end">
                                <div
                                    class="w-full rounded-t-[22px] bg-gradient-to-b from-[#1d4d62] to-[#143544] shadow-[0_14px_24px_rgba(29,77,98,0.22)]"
                                    :style="{ height: barHeight(point.amount) }"
                                />
                            </div>
                            <div class="text-center">
                                <p class="text-xs font-black uppercase tracking-[0.24em] text-[#1b1b1b]">{{ point.label }}</p>
                                <p class="mt-1 text-[11px] text-[#8b7066]">{{ formatMoney(point.amount) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-[34px] border border-[#efdcd5] bg-white/90 p-7 shadow-[0_16px_46px_rgba(157,80,53,0.08)]">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-[0.28em] text-[#b47b67]">Highest Outstanding</p>
                            <h2 class="mt-2 text-2xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">Peneroka watchlist</h2>
                        </div>
                        <Link :href="`/kps/sites/${site.id}/peneroka`" class="text-xs font-semibold text-[#b64a2b]">Manage peneroka</Link>
                    </div>

                    <div class="mt-8 space-y-5">
                        <div
                            v-for="peneroka in topPeneroka"
                            :key="peneroka.id"
                            class="space-y-3"
                        >
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-sm font-semibold text-[#1b1b1b]">{{ peneroka.name }}</p>
                                    <p class="text-[11px] uppercase tracking-[0.24em] text-[#8d7167]">{{ peneroka.ic_number || peneroka.phone || 'No contact data' }}</p>
                                </div>
                                <span class="rounded-full bg-[#fff1ec] px-3 py-1 text-[11px] font-bold text-[#b64a2b]">{{ peneroka.debt_count }} debts</span>
                            </div>
                            <div class="h-2 rounded-full bg-[#f3e8e3]">
                                <div
                                    class="h-2 rounded-full bg-gradient-to-r from-[#d6522d] to-[#b13d1c]"
                                    :style="{ width: exposureWidth(peneroka.outstanding) }"
                                />
                            </div>
                            <p class="text-xs text-[#6d5952]">Outstanding {{ formatMoney(peneroka.outstanding) }}</p>
                        </div>
                        <p v-if="topPeneroka.length === 0" class="text-sm text-[#8d7167]">
                            No site debt exposures are available yet.
                        </p>
                    </div>
                </div>
            </section>

            <section class="overflow-hidden rounded-[36px] border border-[#efdcd5] bg-white/92 shadow-[0_18px_50px_rgba(157,80,53,0.08)]">
                <div class="flex flex-col gap-3 border-b border-[#f0dfd8] px-7 py-6 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-[0.28em] text-[#b47b67]">Site Activity</p>
                        <h2 class="mt-2 text-2xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">Operational audit stream</h2>
                    </div>
                    <Link :href="`/kps/sites/${site.id}/audit-logs`" class="text-sm font-semibold text-[#b64a2b]">Open audit trail</Link>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-left">
                        <thead class="bg-[#fbf6f3]">
                            <tr>
                                <th class="px-7 py-4 text-[11px] font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Action</th>
                                <th class="px-7 py-4 text-[11px] font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Actor</th>
                                <th class="px-7 py-4 text-[11px] font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Summary</th>
                                <th class="px-7 py-4 text-[11px] font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Timestamp</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="item in recentActivity"
                                :key="item.id"
                                class="border-t border-[#f2e3dc] text-sm text-[#3a302d]"
                            >
                                <td class="px-7 py-5 font-semibold">{{ item.action_label }}</td>
                                <td class="px-7 py-5">
                                    <div>
                                        <p class="font-semibold text-[#1b1b1b]">{{ item.actor_name }}</p>
                                        <p class="text-xs text-[#8d7167]">{{ item.actor_email || 'System event' }}</p>
                                    </div>
                                </td>
                                <td class="px-7 py-5 text-[#6d5952]">{{ item.summary }}</td>
                                <td class="px-7 py-5 text-[#6d5952]">{{ item.created_at }}</td>
                            </tr>
                            <tr v-if="recentActivity.length === 0">
                                <td colspan="4" class="px-7 py-10 text-center text-sm text-[#8d7167]">
                                    No site audit activity has been recorded yet.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </KpsShellLayout>
</template>
