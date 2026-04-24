<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { useLocale } from '@/composables/useLocale';

import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import KpsShellLayout from '@/layouts/kps/KpsShellLayout.vue';
import type { KpsDebt, KpsMonthlyDeduction, KpsPeneroka, KpsSite, KpsSiteRole } from '@/types';

defineProps<{
    site: KpsSite;
    siteRole?: KpsSiteRole | null;
    peneroka: KpsPeneroka & { debts?: KpsDebt[] };
    deductions: KpsMonthlyDeduction[];
    summary: {
        debt_count: number;
        outstanding_balance: number;
        deduction_total: number;
        allocated_total: number;
        unallocated_total: number;
    };
}>();
const { t } = useLocale();

const formatNumber = (value?: number | null) =>
    new Intl.NumberFormat('en-MY', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    }).format(Number(value ?? 0));

const formatDate = (value?: string | null) => {
    if (!value) {
        return '-';
    }

    const date = new Date(value);
    if (Number.isNaN(date.getTime())) {
        return String(value).slice(0, 10);
    }

    return new Intl.DateTimeFormat('en-MY', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    }).format(date);
};

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
</script>

<template>
    <Head :title="`${t('kps.statement', 'Statement')} - ${peneroka.name}`" />

    <KpsShellLayout :site="site" :site-role="siteRole">
        <div class="space-y-6 px-4 pb-8 lg:px-8">
            <section class="flex flex-col gap-5 2xl:flex-row 2xl:items-end 2xl:justify-between">
                <div class="max-w-3xl">
                    <p class="text-[11px] font-bold uppercase tracking-[0.32em] text-[#b7654b]">
                        Statement Workspace
                    </p>
                    <h1 class="mt-3 text-4xl font-black tracking-[-0.04em] text-[#1b1b1b] md:text-5xl" style="font-family: Manrope, Inter, sans-serif;">
                        {{ peneroka.name }}
                    </h1>
                    <p class="mt-4 max-w-2xl text-base leading-7 text-[#65534d] md:text-lg">
                        Site statement view for {{ site.name }}. Exports remain available, but the layout now mirrors the editorial KPS site experience.
                    </p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <Button variant="outline" as-child class="rounded-full border-[#e1cbc2] bg-white px-6 py-3 text-[#6d5952] shadow-[0_10px_30px_rgba(157,80,53,0.06)] hover:border-[#c77d62] hover:text-[#1b1b1b]">
                        <a :href="`/kps/sites/${site.id}/reports/peneroka/${peneroka.id}/export/csv`">Export CSV</a>
                    </Button>
                    <Button as-child class="rounded-full bg-gradient-to-br from-[#d6522d] to-[#bc3f1d] px-6 py-3 text-white shadow-[0_16px_32px_rgba(214,82,45,0.28)] hover:translate-y-[-1px]">
                        <a :href="`/kps/sites/${site.id}/reports/peneroka/${peneroka.id}/export/pdf`">Download PDF</a>
                    </Button>
                    <Button variant="outline" as-child class="rounded-full border-[#e1cbc2] bg-white px-6 py-3 text-[#6d5952] shadow-[0_10px_30px_rgba(157,80,53,0.06)] hover:border-[#c77d62] hover:text-[#1b1b1b]">
                        <Link :href="`/kps/sites/${site.id}/reports`">Back to Reports</Link>
                    </Button>
                </div>
            </section>

            <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
                <Card class="border-[#efdcd5] bg-white/90 shadow-[0_16px_40px_rgba(157,80,53,0.08)]">
                    <CardHeader class="pb-3">
                        <CardTitle class="text-xs font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Debts</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="text-4xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">{{ summary.debt_count }}</div>
                    </CardContent>
                </Card>
                <Card class="border-[#efdcd5] bg-white/90 shadow-[0_16px_40px_rgba(157,80,53,0.08)]">
                    <CardHeader class="pb-3">
                        <CardTitle class="text-xs font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Outstanding</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="text-4xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">{{ formatNumber(summary.outstanding_balance) }}</div>
                    </CardContent>
                </Card>
                <Card class="border-[#efdcd5] bg-white/90 shadow-[0_16px_40px_rgba(157,80,53,0.08)]">
                    <CardHeader class="pb-3">
                        <CardTitle class="text-xs font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Total Deductions</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="text-4xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">{{ formatNumber(summary.deduction_total) }}</div>
                    </CardContent>
                </Card>
                <Card class="border-[#efdcd5] bg-white/90 shadow-[0_16px_40px_rgba(157,80,53,0.08)]">
                    <CardHeader class="pb-3">
                        <CardTitle class="text-xs font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Allocated</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="text-4xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">{{ formatNumber(summary.allocated_total) }}</div>
                    </CardContent>
                </Card>
                <Card class="border-[#efdcd5] bg-white/90 shadow-[0_16px_40px_rgba(157,80,53,0.08)]">
                    <CardHeader class="pb-3">
                        <CardTitle class="text-xs font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Unallocated</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="text-4xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">{{ formatNumber(summary.unallocated_total) }}</div>
                    </CardContent>
                </Card>
            </section>

            <section class="grid gap-6 xl:grid-cols-[1fr,1.05fr]">
                <Card class="border-[#efdcd5] bg-white/92 shadow-[0_18px_50px_rgba(157,80,53,0.08)]">
                    <CardHeader class="border-b border-[#f0dfd8] pb-4">
                        <CardTitle class="text-[11px] font-bold uppercase tracking-[0.28em] text-[#b47b67]">Peneroka Details</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4 pt-5">
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div class="rounded-[24px] bg-[#fbf6f3] px-4 py-4">
                                <p class="text-[10px] font-bold uppercase tracking-[0.22em] text-[#9b7d73]">IC</p>
                                <p class="mt-2 text-base font-semibold text-[#1b1b1b]">{{ peneroka.ic_number || '-' }}</p>
                            </div>
                            <div class="rounded-[24px] bg-[#fbf6f3] px-4 py-4">
                                <p class="text-[10px] font-bold uppercase tracking-[0.22em] text-[#9b7d73]">Phone</p>
                                <p class="mt-2 text-base font-semibold text-[#1b1b1b]">{{ peneroka.phone || '-' }}</p>
                            </div>
                        </div>
                        <div class="rounded-[24px] bg-[#fbf6f3] px-4 py-4">
                            <p class="text-[10px] font-bold uppercase tracking-[0.22em] text-[#9b7d73]">Address</p>
                            <p class="mt-2 text-sm leading-6 text-[#6d5952]">{{ peneroka.address || '-' }}</p>
                        </div>
                    </CardContent>
                </Card>

                <Card class="border-[#efdcd5] bg-white/92 shadow-[0_18px_50px_rgba(157,80,53,0.08)]">
                    <CardHeader class="border-b border-[#f0dfd8] pb-4">
                        <CardTitle class="text-[11px] font-bold uppercase tracking-[0.28em] text-[#b47b67]">Debt Summary</CardTitle>
                    </CardHeader>
                    <CardContent class="pt-5">
                        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                            <div class="rounded-[24px] bg-[#fbf6f3] px-4 py-4">
                                <p class="text-[10px] font-bold uppercase tracking-[0.22em] text-[#9b7d73]">Debt Count</p>
                                <p class="mt-2 text-2xl font-black text-[#1b1b1b]">{{ summary.debt_count }}</p>
                            </div>
                            <div class="rounded-[24px] bg-[#fbf6f3] px-4 py-4">
                                <p class="text-[10px] font-bold uppercase tracking-[0.22em] text-[#9b7d73]">Outstanding</p>
                                <p class="mt-2 text-2xl font-black text-[#1b1b1b]">{{ formatNumber(summary.outstanding_balance) }}</p>
                            </div>
                            <div class="rounded-[24px] bg-[#fbf6f3] px-4 py-4">
                                <p class="text-[10px] font-bold uppercase tracking-[0.22em] text-[#9b7d73]">Allocated</p>
                                <p class="mt-2 text-2xl font-black text-[#1b1b1b]">{{ formatNumber(summary.allocated_total) }}</p>
                            </div>
                            <div class="rounded-[24px] bg-[#fbf6f3] px-4 py-4">
                                <p class="text-[10px] font-bold uppercase tracking-[0.22em] text-[#9b7d73]">Unallocated</p>
                                <p class="mt-2 text-2xl font-black text-[#1b1b1b]">{{ formatNumber(summary.unallocated_total) }}</p>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </section>

            <Card class="overflow-hidden border-[#efdcd5] bg-white/92 shadow-[0_18px_50px_rgba(157,80,53,0.08)]">
                <CardHeader class="border-b border-[#f0dfd8] pb-4">
                    <CardTitle class="text-[11px] font-bold uppercase tracking-[0.28em] text-[#b47b67]">Hutang</CardTitle>
                </CardHeader>
                <CardContent class="p-0">
                    <div class="overflow-x-auto">
                        <Table>
                            <TableHeader class="bg-[#fbf6f3]">
                                <TableRow class="border-b border-[#f0dfd8]">
                                    <TableHead class="px-7 py-4 text-[11px] font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Description</TableHead>
                                    <TableHead class="px-7 py-4 text-[11px] font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Priority</TableHead>
                                    <TableHead class="px-7 py-4 text-[11px] font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Balance</TableHead>
                                    <TableHead class="px-7 py-4 text-[11px] font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Due Date</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-if="!peneroka.debts || peneroka.debts.length === 0" class="border-t border-[#f2e3dc]">
                                    <TableCell colspan="4" class="px-7 py-10 text-center text-sm text-[#8d7167]">
                                        No debts found.
                                    </TableCell>
                                </TableRow>
                                <TableRow
                                    v-for="debt in peneroka.debts"
                                    :key="debt.id"
                                    class="border-t border-[#f2e3dc] text-sm text-[#3a302d] hover:bg-[#fff8f3]"
                                >
                                    <TableCell class="px-7 py-5 font-semibold text-[#1b1b1b]">
                                        {{ debt.description || debt.id }}
                                    </TableCell>
                                    <TableCell class="px-7 py-5">
                                        <span class="rounded-full bg-[#fff1ec] px-3 py-1 text-[11px] font-bold text-[#b64a2b]">
                                            {{ debt.priority }}
                                        </span>
                                    </TableCell>
                                    <TableCell class="px-7 py-5 font-semibold text-[#1b1b1b]">{{ formatNumber(debt.balance) }}</TableCell>
                                    <TableCell class="px-7 py-5 text-[#6d5952]">{{ formatDate(debt.due_date) }}</TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>
                </CardContent>
            </Card>

            <Card class="overflow-hidden border-[#efdcd5] bg-white/92 shadow-[0_18px_50px_rgba(157,80,53,0.08)]">
                <CardHeader class="border-b border-[#f0dfd8] pb-4">
                    <CardTitle class="text-[11px] font-bold uppercase tracking-[0.28em] text-[#b47b67]">Monthly Deductions</CardTitle>
                </CardHeader>
                <CardContent class="space-y-5 pt-5">
                    <div v-if="deductions.length" class="space-y-4">
                        <div
                            v-for="deduction in deductions"
                            :key="deduction.id"
                            class="rounded-[28px] border border-[#ead6ce] bg-[#fffaf7] p-5 shadow-[0_12px_30px_rgba(157,80,53,0.06)]"
                        >
                            <div class="flex flex-col gap-3 border-b border-[#f0dfd8] pb-4 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-[0.22em] text-[#9b7d73]">Month</p>
                                    <p class="mt-1 text-lg font-black text-[#1b1b1b]">{{ formatMonth(deduction.month) }}</p>
                                </div>
                                <div class="text-sm text-[#6d5952]">
                                    Amount: <span class="font-semibold text-[#1b1b1b]">{{ formatNumber(deduction.amount) }}</span>
                                    |
                                    Unallocated: <span class="font-semibold text-[#1b1b1b]">{{ formatNumber(deduction.unallocated_amount) }}</span>
                                </div>
                            </div>

                            <div class="mt-4 overflow-hidden rounded-[24px] border border-[#ead6ce] bg-white">
                                <Table>
                                    <TableHeader class="bg-[#fbf6f3]">
                                        <TableRow class="border-b border-[#f0dfd8]">
                                            <TableHead class="px-5 py-4 text-[11px] font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Debt</TableHead>
                                            <TableHead class="px-5 py-4 text-[11px] font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Allocated</TableHead>
                                        </TableRow>
                                    </TableHeader>
                                    <TableBody>
                                        <TableRow v-if="!deduction.allocations || deduction.allocations.length === 0" class="border-t border-[#f2e3dc]">
                                            <TableCell colspan="2" class="px-5 py-8 text-center text-sm text-[#8d7167]">
                                                No allocations.
                                            </TableCell>
                                        </TableRow>
                                        <TableRow
                                            v-for="allocation in deduction.allocations"
                                            :key="allocation.id"
                                            class="border-t border-[#f2e3dc] text-sm text-[#3a302d]"
                                        >
                                            <TableCell class="px-5 py-4 font-medium text-[#1b1b1b]">
                                                {{ allocation.debt?.description || allocation.debt_id }}
                                            </TableCell>
                                            <TableCell class="px-5 py-4 font-semibold text-[#1b1b1b]">
                                                {{ formatNumber(allocation.amount) }}
                                            </TableCell>
                                        </TableRow>
                                    </TableBody>
                                </Table>
                            </div>
                        </div>
                    </div>

                    <div v-else class="rounded-[28px] border border-dashed border-[#e3c9bf] bg-[#fff8f3] px-6 py-10 text-center text-sm text-[#8d7167]">
                        No deductions found.
                    </div>
                </CardContent>
            </Card>
        </div>
    </KpsShellLayout>
</template>
