<script setup lang="ts">
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

import KpsShellLayout from '@/layouts/kps/KpsShellLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import type { AppPageProps, KpsMonthlyDeduction, KpsSite, KpsSiteRole } from '@/types';

interface PaginatedDeductions {
    data: (KpsMonthlyDeduction & { allocations_count?: number })[];
    links: any[];
    meta: any;
}

const props = defineProps<{
    site: KpsSite;
    siteRole?: KpsSiteRole | null;
    deductions: PaginatedDeductions;
    selectedMonth?: string | null;
    monthLabel: string;
    summary: {
        deduction_count: number;
        total_amount: number;
        total_unallocated: number;
        closed_count: number;
    };
}>();

const month = ref(props.selectedMonth || '');

const formatMoney = (value?: number | null) =>
    new Intl.NumberFormat('en-MY', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    }).format(Number(value ?? 0));

const formatDate = (value?: string | null) => {
    if (!value) {
        return '-';
    }

    return String(value).slice(0, 10);
};

const openCount = computed(() => Math.max(props.summary.deduction_count - props.summary.closed_count, 0));
const page = usePage<AppPageProps>();
const canApproveMonth = computed(() => (page.props.auth?.permissions ?? []).includes('kps.approve_month'));

const applyFilter = () => {
    router.get(`/kps/sites/${props.site.id}/allocations`, { month: month.value }, { preserveState: true, preserveScroll: true });
};

const closeMonth = () => {
    if (!month.value) return;
    router.post(`/kps/sites/${props.site.id}/allocations/close-month`, { month: month.value });
};
</script>

<template>
    <Head :title="`Allocations - ${site.name}`" />

    <KpsShellLayout :site="site" :site-role="siteRole">
        <div class="space-y-6 px-4 pb-8 lg:px-8">
            <section class="flex flex-col gap-5 2xl:flex-row 2xl:items-end 2xl:justify-between">
                <div class="max-w-3xl">
                    <p class="text-[11px] font-bold uppercase tracking-[0.32em] text-[#b7654b]">
                        Allocation Workspace
                    </p>
                    <h1 class="mt-3 text-4xl font-black tracking-[-0.04em] text-[#1b1b1b] md:text-5xl" style="font-family: Manrope, Inter, sans-serif;">
                        Allocation Review
                    </h1>
                    <p class="mt-4 max-w-2xl text-base leading-7 text-[#65534d] md:text-lg">
                        Review live deduction allocations for {{ site.name }}. Each row reflects the waterfall results and whether the month is still open.
                    </p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <Button
                        v-if="canApproveMonth"
                        class="rounded-full bg-[#171717] px-6 text-white hover:bg-[#0f0f0f]"
                        :disabled="!month"
                        @click="closeMonth"
                    >
                        Close Month
                    </Button>
                </div>
            </section>

            <section class="grid gap-4 xl:grid-cols-4">
                <div class="rounded-[30px] border border-[#efdcd5] bg-white/88 p-6 shadow-[0_16px_40px_rgba(157,80,53,0.08)]">
                    <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Selected Month</p>
                    <p class="mt-4 text-4xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">{{ monthLabel }}</p>
                    <p class="mt-4 text-sm text-[#6d5952]">Allocation review is scoped to this month before closure.</p>
                </div>

                <div class="rounded-[30px] border border-[#efdcd5] bg-white/88 p-6 shadow-[0_16px_40px_rgba(157,80,53,0.08)]">
                    <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Total Amount</p>
                    <p class="mt-4 text-4xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">{{ formatMoney(summary.total_amount) }}</p>
                    <p class="mt-4 text-sm text-[#6d5952]">Gross deduction amount in the active review month.</p>
                </div>

                <div class="rounded-[30px] border border-[#efdcd5] bg-white/88 p-6 shadow-[0_16px_40px_rgba(157,80,53,0.08)]">
                    <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Unallocated</p>
                    <p class="mt-4 text-4xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">{{ formatMoney(summary.total_unallocated) }}</p>
                    <p class="mt-4 text-sm text-[#6d5952]">Balance waiting to be absorbed or reviewed.</p>
                </div>

                <div class="rounded-[30px] border border-[#efdcd5] bg-white/88 p-6 shadow-[0_16px_40px_rgba(157,80,53,0.08)]">
                    <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Open vs Closed</p>
                    <p class="mt-4 text-4xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">{{ openCount }} / {{ summary.closed_count }}</p>
                    <p class="mt-4 text-sm text-[#6d5952]">Operational status for deductions in the active month review.</p>
                </div>
            </section>

            <section class="rounded-[36px] border border-[#efdcd5] bg-white/92 shadow-[0_18px_50px_rgba(157,80,53,0.08)]">
                <div class="flex flex-col gap-4 border-b border-[#f0dfd8] px-7 py-6 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-[0.28em] text-[#b47b67]">Month Filter</p>
                        <h2 class="mt-2 text-2xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">Live allocation ledger</h2>
                    </div>
                    <div class="flex flex-wrap items-end gap-3">
                        <div class="grid gap-2">
                            <label class="text-xs font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Month</label>
                            <Input type="month" v-model="month" class="w-[220px] rounded-full border-[#ead6ce] bg-[#fffaf7]" />
                        </div>
                        <Button variant="outline" class="rounded-full border-[#e2c9c0]" @click="applyFilter">Apply</Button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <Table>
                        <TableHeader>
                            <TableRow class="bg-[#fbf6f3]">
                                <TableHead class="px-7 py-4 text-[11px] font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Month</TableHead>
                                <TableHead class="px-7 py-4 text-[11px] font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Peneroka</TableHead>
                                <TableHead class="px-7 py-4 text-[11px] font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Amount</TableHead>
                                <TableHead class="px-7 py-4 text-[11px] font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Allocations</TableHead>
                                <TableHead class="px-7 py-4 text-[11px] font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Unallocated</TableHead>
                                <TableHead class="px-7 py-4 text-[11px] font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Status</TableHead>
                                <TableHead class="px-7 py-4 text-[11px] font-bold uppercase tracking-[0.24em] text-[#9b7d73] text-right">Actions</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-if="deductions.data.length === 0">
                                <TableCell colspan="7" class="px-7 py-10 text-center text-sm text-[#8d7167]">
                                    No allocations found.
                                </TableCell>
                            </TableRow>
                            <TableRow
                                v-for="deduction in deductions.data"
                                :key="deduction.id"
                                class="border-t border-[#f2e3dc] text-[#3a302d] hover:bg-[#fff8f4]"
                            >
                                <TableCell class="px-7 py-5 font-semibold text-[#1b1b1b]">{{ formatDate(deduction.month) }}</TableCell>
                                <TableCell class="px-7 py-5">
                                    <div>
                                        <p class="font-semibold text-[#1b1b1b]">{{ deduction.peneroka?.name }}</p>
                                        <p class="text-xs text-[#8d7167]">{{ deduction.peneroka?.phone || 'No phone registered' }}</p>
                                    </div>
                                </TableCell>
                                <TableCell class="px-7 py-5 font-semibold text-[#1b1b1b]">{{ formatMoney(deduction.amount) }}</TableCell>
                                <TableCell class="px-7 py-5">
                                    <span class="rounded-full bg-[#fff1ec] px-3 py-1 text-[11px] font-bold text-[#b64a2b]">
                                        {{ deduction.allocations_count || 0 }}
                                    </span>
                                </TableCell>
                                <TableCell class="px-7 py-5 text-[#6d5952]">{{ formatMoney(deduction.unallocated_amount) }}</TableCell>
                                <TableCell class="px-7 py-5">
                                    <span
                                        class="rounded-full px-3 py-1 text-[11px] font-bold uppercase tracking-[0.18em]"
                                        :class="deduction.is_closed ? 'bg-[#171717] text-white' : 'bg-[#ebfff3] text-[#18754d]'"
                                    >
                                        {{ deduction.is_closed ? 'Closed' : 'Open' }}
                                    </span>
                                </TableCell>
                                <TableCell class="px-7 py-5 text-right">
                                    <Button variant="outline" size="sm" as-child class="rounded-full border-[#e2c9c0]">
                                        <Link :href="`/kps/sites/${site.id}/allocations/${deduction.id}`">View</Link>
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
