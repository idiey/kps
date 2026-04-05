<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

import KpsShellLayout from '@/layouts/kps/KpsShellLayout.vue';
import type { KpsPeneroka, KpsSite, KpsSiteRole } from '@/types';

interface SitePriorityMix {
    priority: number;
    debt_count: number;
    outstanding: number;
    share_of_outstanding: number;
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
    currentMonth: string;
    monthLabel: string;
    summary: {
        peneroka_count: number;
        outstanding_total: number;
        current_month_deductions: number;
    };
    penerokas: KpsPeneroka[];
    priorityMix: SitePriorityMix[];
    recentActivity: SiteActivityItem[];
}>();

const query = ref('');

const formatMoney = (value?: number | null) =>
    new Intl.NumberFormat('en-MY', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    }).format(Number(value ?? 0));

const formatMonth = (value?: string | null) => {
    if (!value) {
        return '-';
    }

    const date = new Date(value);

    if (Number.isNaN(date.getTime())) {
        return String(value).slice(0, 10);
    }

    return new Intl.DateTimeFormat('en-MY', {
        month: 'short',
        year: 'numeric',
    }).format(date);
};

const filteredPenerokas = computed(() => {
    const needle = query.value.trim().toLowerCase();

    if (!needle) {
        return props.penerokas;
    }

    return props.penerokas.filter((peneroka) =>
        [peneroka.name, peneroka.ic_number, peneroka.phone]
            .filter(Boolean)
            .some((value) => String(value).toLowerCase().includes(needle)),
    );
});

const averageOutstanding = computed(() =>
    props.summary.peneroka_count > 0
        ? props.summary.outstanding_total / props.summary.peneroka_count
        : 0,
);
</script>

<template>
    <Head :title="`Reports - ${site.name}`" />

    <KpsShellLayout :site="site" :site-role="siteRole">
        <div class="space-y-6 px-4 pb-8 lg:px-8">
            <section class="flex flex-col gap-5 2xl:flex-row 2xl:items-end 2xl:justify-between">
                <div class="max-w-3xl">
                    <p class="text-[11px] font-bold uppercase tracking-[0.32em] text-[#b7654b]">
                        History & Reports
                    </p>
                    <h1 class="mt-3 text-4xl font-black tracking-[-0.04em] text-[#1b1b1b] md:text-5xl" style="font-family: Manrope, Inter, sans-serif;">
                        {{ site.name }} reporting workspace.
                    </h1>
                    <p class="mt-4 max-w-2xl text-base leading-7 text-[#65534d] md:text-lg">
                        Review site-level statements, monitor debt mix, and export the live reporting dataset without leaving KPS.
                    </p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <a
                        :href="`/kps/sites/${site.id}/reports/export/csv`"
                        class="inline-flex items-center rounded-full border border-[#e1cbc2] bg-white px-6 py-3 text-sm font-semibold text-[#6d5952] shadow-[0_10px_30px_rgba(157,80,53,0.06)] transition hover:border-[#c77d62] hover:text-[#1b1b1b]"
                    >
                        Export Site CSV
                    </a>
                    <Link
                        :href="`/kps/sites/${site.id}`"
                        class="inline-flex items-center rounded-full bg-gradient-to-br from-[#d6522d] to-[#bc3f1d] px-6 py-3 text-sm font-semibold text-white shadow-[0_16px_32px_rgba(214,82,45,0.28)] transition hover:translate-y-[-1px]"
                    >
                        Back to Dashboard
                    </Link>
                </div>
            </section>

            <section class="grid gap-4 xl:grid-cols-4">
                <div class="rounded-[30px] border border-[#efdcd5] bg-white/88 p-6 shadow-[0_16px_40px_rgba(157,80,53,0.08)]">
                    <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Peneroka Count</p>
                    <p class="mt-4 text-4xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">{{ summary.peneroka_count }}</p>
                    <p class="mt-4 text-sm text-[#6d5952]">Tracked records available for statement-level export.</p>
                </div>

                <div class="rounded-[30px] border border-[#efdcd5] bg-white/88 p-6 shadow-[0_16px_40px_rgba(157,80,53,0.08)]">
                    <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Outstanding</p>
                    <p class="mt-4 text-4xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">{{ formatMoney(summary.outstanding_total) }}</p>
                    <p class="mt-4 text-sm text-[#6d5952]">Total balance still open in this site portfolio.</p>
                </div>

                <div class="rounded-[30px] border border-[#efdcd5] bg-white/88 p-6 shadow-[0_16px_40px_rgba(157,80,53,0.08)]">
                    <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#9b7d73]">{{ monthLabel }}</p>
                    <p class="mt-4 text-4xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">{{ formatMoney(summary.current_month_deductions) }}</p>
                    <p class="mt-4 text-sm text-[#6d5952]">Deductions posted in the active month window.</p>
                </div>

                <div class="rounded-[30px] border border-[#efdcd5] bg-white/88 p-6 shadow-[0_16px_40px_rgba(157,80,53,0.08)]">
                    <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Average Exposure</p>
                    <p class="mt-4 text-4xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">{{ formatMoney(averageOutstanding) }}</p>
                    <p class="mt-4 text-sm text-[#6d5952]">Average outstanding per peneroka in {{ site.code }}.</p>
                </div>
            </section>

            <section class="overflow-hidden rounded-[36px] border border-[#efdcd5] bg-white/92 shadow-[0_18px_50px_rgba(157,80,53,0.08)]">
                <div class="flex flex-col gap-4 border-b border-[#f0dfd8] px-7 py-6 lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex flex-wrap items-center gap-3 text-xs font-semibold text-[#6d5952]">
                        <span class="rounded-full bg-[#f7f1ee] px-4 py-2">Month {{ currentMonth }}</span>
                        <span class="rounded-full bg-[#f7f1ee] px-4 py-2">{{ filteredPenerokas.length }} rows shown</span>
                        <span class="rounded-full bg-[#f7f1ee] px-4 py-2">Statements + CSV export</span>
                    </div>

                    <label class="relative block w-full max-w-sm">
                        <input
                            v-model="query"
                            type="text"
                            placeholder="Find peneroka, IC, or phone..."
                            class="w-full rounded-full border border-[#ead6ce] bg-[#f7f1ee] px-4 py-3 text-sm text-[#6d5952] outline-none"
                        />
                    </label>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-left">
                        <thead class="bg-[#fbf6f3]">
                            <tr>
                                <th class="px-7 py-4 text-[11px] font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Peneroka</th>
                                <th class="px-7 py-4 text-[11px] font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Identity</th>
                                <th class="px-7 py-4 text-[11px] font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Debts</th>
                                <th class="px-7 py-4 text-[11px] font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Outstanding</th>
                                <th class="px-7 py-4 text-[11px] font-bold uppercase tracking-[0.24em] text-[#9b7d73]">This Month</th>
                                <th class="px-7 py-4 text-[11px] font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Latest Deduction</th>
                                <th class="px-7 py-4 text-[11px] font-bold uppercase tracking-[0.24em] text-[#9b7d73] text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="peneroka in filteredPenerokas"
                                :key="peneroka.id"
                                class="border-t border-[#f2e3dc] text-sm text-[#3a302d]"
                            >
                                <td class="px-7 py-5">
                                    <div>
                                        <p class="font-semibold text-[#1b1b1b]">{{ peneroka.name }}</p>
                                        <p class="text-xs text-[#8d7167]">{{ peneroka.phone || 'No phone registered' }}</p>
                                    </div>
                                </td>
                                <td class="px-7 py-5 text-[#6d5952]">{{ peneroka.ic_number || '-' }}</td>
                                <td class="px-7 py-5">
                                    <span class="rounded-full bg-[#fff1ec] px-3 py-1 text-[11px] font-bold text-[#b64a2b]">
                                        {{ peneroka.debts_count || 0 }}
                                    </span>
                                </td>
                                <td class="px-7 py-5 font-semibold text-[#1b1b1b]">{{ formatMoney(peneroka.total_outstanding) }}</td>
                                <td class="px-7 py-5 text-[#6d5952]">{{ formatMoney(peneroka.current_month_deduction_total) }}</td>
                                <td class="px-7 py-5 text-[#6d5952]">{{ formatMonth(peneroka.latest_deduction_month) }}</td>
                                <td class="px-7 py-5 text-right">
                                    <Link
                                        :href="`/kps/sites/${site.id}/reports/peneroka/${peneroka.id}`"
                                        class="inline-flex rounded-full border border-[#e2c9c0] px-4 py-2 text-xs font-semibold text-[#6d5952]"
                                    >
                                        Statement
                                    </Link>
                                </td>
                            </tr>
                            <tr v-if="filteredPenerokas.length === 0">
                                <td colspan="7" class="px-7 py-10 text-center text-sm text-[#8d7167]">
                                    No peneroka records match the current filter.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="grid gap-6 xl:grid-cols-[1.05fr,0.95fr]">
                <div class="rounded-[34px] border border-[#efdcd5] bg-white/88 p-7 shadow-[0_16px_46px_rgba(157,80,53,0.08)]">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-[0.28em] text-[#b47b67]">Debt Mix</p>
                            <h2 class="mt-2 text-2xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">Priority breakdown</h2>
                        </div>
                        <span class="rounded-full bg-[#171717] px-4 py-2 text-[11px] font-bold uppercase tracking-[0.22em] text-white">
                            {{ site.code }}
                        </span>
                    </div>

                    <div class="mt-8 space-y-5">
                        <div
                            v-for="priority in priorityMix"
                            :key="priority.priority"
                            class="space-y-3"
                        >
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-sm font-semibold text-[#1b1b1b]">Priority {{ priority.priority }}</p>
                                    <p class="text-[11px] uppercase tracking-[0.24em] text-[#8d7167]">{{ priority.debt_count }} debts</p>
                                </div>
                                <span class="rounded-full bg-[#eef8ff] px-3 py-1 text-[11px] font-bold text-[#215b7a]">
                                    {{ priority.share_of_outstanding.toFixed(1) }}%
                                </span>
                            </div>
                            <div class="h-2 rounded-full bg-[#f3e8e3]">
                                <div
                                    class="h-2 rounded-full bg-gradient-to-r from-[#d6522d] to-[#b13d1c]"
                                    :style="{ width: `${Math.max(10, priority.share_of_outstanding)}%` }"
                                />
                            </div>
                            <p class="text-xs text-[#6d5952]">Outstanding {{ formatMoney(priority.outstanding) }}</p>
                        </div>
                        <p v-if="priorityMix.length === 0" class="text-sm text-[#8d7167]">
                            No debt priority data is available for this site yet.
                        </p>
                    </div>
                </div>

                <div class="rounded-[34px] border border-[#efdcd5] bg-white/88 p-7 shadow-[0_16px_46px_rgba(157,80,53,0.08)]">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-[0.28em] text-[#b47b67]">Recent Activity</p>
                            <h2 class="mt-2 text-2xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">Audit highlights</h2>
                        </div>
                        <Link :href="`/kps/sites/${site.id}/audit-logs`" class="text-xs font-semibold text-[#b64a2b]">Live audit</Link>
                    </div>

                    <div class="mt-8 space-y-4">
                        <div
                            v-for="item in recentActivity"
                            :key="item.id"
                            class="rounded-[24px] border border-[#ead6ce] bg-white p-4"
                        >
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-sm font-semibold text-[#1b1b1b]">{{ item.action_label }}</p>
                                    <p class="mt-1 text-xs text-[#8d7167]">{{ item.actor_name }} · {{ item.created_at }}</p>
                                </div>
                                <span class="rounded-full bg-[#fff1ec] px-3 py-1 text-[11px] font-bold text-[#b64a2b]">{{ item.site_code || site.code }}</span>
                            </div>
                            <p class="mt-3 text-sm leading-6 text-[#65534d]">{{ item.summary }}</p>
                        </div>
                        <p v-if="recentActivity.length === 0" class="text-sm text-[#8d7167]">
                            No audit activity has been recorded for this site yet.
                        </p>
                    </div>
                </div>
            </section>
        </div>
    </KpsShellLayout>
</template>
