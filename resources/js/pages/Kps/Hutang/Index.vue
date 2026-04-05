<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';

import KpsShellLayout from '@/layouts/kps/KpsShellLayout.vue';
import { Button } from '@/components/ui/button';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import type { AppPageProps, KpsDebt, KpsSite, KpsSiteRole } from '@/types';

interface PaginationMeta {
    current_page?: number;
    last_page?: number;
    from?: number;
    to?: number;
    total?: number;
}

interface PaginatedDebts {
    data: KpsDebt[];
    links: any[];
    meta: PaginationMeta;
}

const props = defineProps<{
    site: KpsSite;
    siteRole?: KpsSiteRole | null;
    debts: PaginatedDebts;
    summary: {
        total_debts: number;
        outstanding_total: number;
        due_this_month: number;
        highest_priority_open: number | null;
    };
}>();

const formatMoney = (value?: number | null) =>
    new Intl.NumberFormat('en-MY', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    }).format(Number(value ?? 0));

const formatNumber = (value?: number | null) =>
    new Intl.NumberFormat('en-MY', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(Number(value ?? 0));

const formatDate = (value?: string | null) => {
    if (!value) {
        return '-';
    }

    return String(value).slice(0, 10);
};

const page = usePage<AppPageProps>();
const canManageHutang = (page.props.auth?.permissions ?? []).includes('kps.manage_hutang');
</script>

<template>
    <Head :title="`Hutang - ${site.name}`" />

    <KpsShellLayout :site="site" :site-role="siteRole">
        <div class="space-y-6 px-4 pb-8 lg:px-8">
            <section class="flex flex-col gap-5 2xl:flex-row 2xl:items-end 2xl:justify-between">
                <div class="max-w-3xl">
                    <p class="text-[11px] font-bold uppercase tracking-[0.32em] text-[#b7654b]">
                        Site Workspace
                    </p>
                    <h1 class="mt-3 text-4xl font-black tracking-[-0.04em] text-[#1b1b1b] md:text-5xl" style="font-family: Manrope, Inter, sans-serif;">
                        Debt workspace
                    </h1>
                    <p class="mt-4 max-w-2xl text-base leading-7 text-[#65534d] md:text-lg">
                        Manage hutang for {{ site.name }} with a stronger operational hierarchy and clearer allocation context.
                    </p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <Link
                        :href="`/kps/sites/${site.id}`"
                        class="inline-flex items-center rounded-full border border-[#e1cbc2] bg-white px-6 py-3 text-sm font-semibold text-[#6d5952] shadow-[0_10px_30px_rgba(157,80,53,0.06)] transition hover:border-[#c77d62] hover:text-[#1b1b1b]"
                    >
                        Back to Dashboard
                    </Link>
                    <Button
                        v-if="canManageHutang"
                        as-child
                        class="rounded-full bg-gradient-to-br from-[#d6522d] to-[#bc3f1d] px-6 py-3 text-white shadow-[0_16px_32px_rgba(214,82,45,0.28)] hover:translate-y-[-1px]"
                    >
                        <Link :href="`/kps/sites/${site.id}/hutang/create`">Add Hutang</Link>
                    </Button>
                </div>
            </section>

            <section class="grid gap-4 xl:grid-cols-4">
                <div class="rounded-[30px] border border-[#efdcd5] bg-white/88 p-6 shadow-[0_16px_40px_rgba(157,80,53,0.08)]">
                    <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Total Records</p>
                    <p class="mt-4 text-4xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">{{ formatNumber(summary.total_debts) }}</p>
                    <p class="mt-4 text-sm text-[#6d5952]">Debt records visible in the site portfolio.</p>
                </div>

                <div class="rounded-[30px] border border-[#efdcd5] bg-white/88 p-6 shadow-[0_16px_40px_rgba(157,80,53,0.08)]">
                    <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Due This Month</p>
                    <p class="mt-4 text-4xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">{{ formatNumber(summary.due_this_month) }}</p>
                    <p class="mt-4 text-sm text-[#6d5952]">Scheduled debt items reaching their due date this month.</p>
                </div>

                <div class="rounded-[30px] border border-[#efdcd5] bg-white/88 p-6 shadow-[0_16px_40px_rgba(157,80,53,0.08)]">
                    <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Outstanding</p>
                    <p class="mt-4 text-4xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">{{ formatMoney(summary.outstanding_total) }}</p>
                    <p class="mt-4 text-sm text-[#6d5952]">Total outstanding balance across the site portfolio.</p>
                </div>

                <div class="rounded-[30px] border border-[#efdcd5] bg-white/88 p-6 shadow-[0_16px_40px_rgba(157,80,53,0.08)]">
                    <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Highest Priority Open</p>
                    <p class="mt-4 text-4xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">{{ summary.highest_priority_open ?? '-' }}</p>
                    <p class="mt-4 text-sm text-[#6d5952]">Next recovery priority still carrying a balance.</p>
                </div>
            </section>

            <section class="overflow-hidden rounded-[36px] border border-[#efdcd5] bg-white/92 shadow-[0_18px_50px_rgba(157,80,53,0.08)]">
                <div class="flex flex-col gap-3 border-b border-[#f0dfd8] px-7 py-6 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-[0.28em] text-[#b47b67]">Debt Register</p>
                        <h2 class="mt-2 text-2xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">Allocation priority list</h2>
                    </div>
                    <span class="rounded-full bg-[#f7f1ee] px-4 py-2 text-[11px] font-bold uppercase tracking-[0.22em] text-[#8d7167]">
                        {{ site.code }}
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <Table>
                        <TableHeader>
                            <TableRow class="border-[#f1dfd8] bg-[#fbf6f3] hover:bg-[#fbf6f3]">
                                <TableHead class="px-7 py-4 text-[11px] font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Peneroka</TableHead>
                                <TableHead class="px-7 py-4 text-[11px] font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Priority</TableHead>
                                <TableHead class="px-7 py-4 text-[11px] font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Balance</TableHead>
                                <TableHead class="px-7 py-4 text-[11px] font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Due Date</TableHead>
                                <TableHead class="px-7 py-4 text-right text-[11px] font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Actions</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-if="debts.data.length === 0">
                                <TableCell colspan="5" class="px-7 py-10 text-center text-sm text-[#8d7167]">
                                    No debts found for this site.
                                </TableCell>
                            </TableRow>
                            <TableRow
                                v-for="debt in debts.data"
                                :key="debt.id"
                                class="border-[#f2e3dc] text-[#3a302d] transition hover:bg-[#fff8f3]"
                            >
                                <TableCell class="px-7 py-5">
                                    <div class="space-y-1">
                                        <p class="font-semibold text-[#1b1b1b]">{{ debt.peneroka?.name || 'Unknown peneroka' }}</p>
                                        <p class="text-xs text-[#8d7167]">{{ debt.description || 'No description' }}</p>
                                    </div>
                                </TableCell>
                                <TableCell class="px-7 py-5">
                                    <span class="rounded-full bg-[#fff1ec] px-3 py-1 text-[11px] font-bold text-[#b64a2b]">
                                        {{ debt.priority }}
                                    </span>
                                </TableCell>
                                <TableCell class="px-7 py-5 font-semibold text-[#1b1b1b]">{{ formatMoney(debt.balance) }}</TableCell>
                                <TableCell class="px-7 py-5 text-[#6d5952]">{{ formatDate(debt.due_date) }}</TableCell>
                                <TableCell class="px-7 py-5 text-right">
                                    <Button
                                        v-if="canManageHutang"
                                        variant="ghost"
                                        size="sm"
                                        as-child
                                        class="rounded-full text-[#6d5952] hover:bg-[#fff1ec] hover:text-[#1b1b1b]"
                                    >
                                        <Link :href="`/kps/sites/${site.id}/hutang/${debt.id}/edit`">Edit</Link>
                                    </Button>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>
            </section>
        </div>
    </KpsShellLayout>
</template>
