<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { computed } from 'vue';

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
import type { KpsMonthlyDeduction, KpsSite, KpsSiteRole } from '@/types';

const props = defineProps<{
    site: KpsSite;
    siteRole?: KpsSiteRole | null;
    deduction: KpsMonthlyDeduction & {
        allocations?: {
            id: string;
            debt_id: string;
            amount: number;
            debt?: {
                id: string;
                description?: string | null;
                priority: number;
                balance: number;
            } | null;
        }[];
    };
}>();

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

const allocatedTotal = computed(() =>
    (props.deduction.allocations || []).reduce((sum, allocation) => sum + Number(allocation.amount ?? 0), 0),
);

const reallocate = () => {
    router.post(`/kps/sites/${props.site.id}/allocations/${props.deduction.id}/reallocate`);
};
</script>

<template>
    <Head title="Allocation Details" />

    <KpsShellLayout :site="site" :site-role="siteRole">
        <div class="space-y-6 px-4 pb-8 lg:px-8">
            <section class="flex flex-col gap-5 2xl:flex-row 2xl:items-end 2xl:justify-between">
                <div class="max-w-3xl">
                    <p class="text-[11px] font-bold uppercase tracking-[0.32em] text-[#b7654b]">
                        Allocation Detail
                    </p>
                    <h1 class="mt-3 text-4xl font-black tracking-[-0.04em] text-[#1b1b1b] md:text-5xl" style="font-family: Manrope, Inter, sans-serif;">
                        Allocation Details
                    </h1>
                    <p class="mt-4 max-w-2xl text-base leading-7 text-[#65534d] md:text-lg">
                        {{ deduction.peneroka?.name }} for {{ formatDate(deduction.month) }}. This screen shows exactly how the deduction waterfall was applied.
                    </p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <Button
                        class="rounded-full bg-[#171717] px-6 text-white hover:bg-[#0f0f0f]"
                        :disabled="deduction.is_closed"
                        @click="reallocate"
                    >
                        Recalculate
                    </Button>
                    <Link
                        :href="`/kps/sites/${site.id}/allocations`"
                        class="inline-flex items-center rounded-full border border-[#e1cbc2] bg-white px-6 py-3 text-sm font-semibold text-[#6d5952] shadow-[0_10px_30px_rgba(157,80,53,0.06)] transition hover:border-[#c77d62] hover:text-[#1b1b1b]"
                    >
                        Back
                    </Link>
                </div>
            </section>

            <section class="grid gap-4 xl:grid-cols-4">
                <div class="rounded-[30px] border border-[#efdcd5] bg-white/88 p-6 shadow-[0_16px_40px_rgba(157,80,53,0.08)]">
                    <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Amount</p>
                    <p class="mt-4 text-4xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">{{ formatMoney(deduction.amount) }}</p>
                    <p class="mt-4 text-sm text-[#6d5952]">Gross deduction amount for the selected month.</p>
                </div>

                <div class="rounded-[30px] border border-[#efdcd5] bg-white/88 p-6 shadow-[0_16px_40px_rgba(157,80,53,0.08)]">
                    <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Allocated</p>
                    <p class="mt-4 text-4xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">{{ formatMoney(allocatedTotal) }}</p>
                    <p class="mt-4 text-sm text-[#6d5952]">Total amount already absorbed by debts.</p>
                </div>

                <div class="rounded-[30px] border border-[#efdcd5] bg-white/88 p-6 shadow-[0_16px_40px_rgba(157,80,53,0.08)]">
                    <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Unallocated</p>
                    <p class="mt-4 text-4xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">{{ formatMoney(deduction.unallocated_amount) }}</p>
                    <p class="mt-4 text-sm text-[#6d5952]">Balance left after the waterfall completes.</p>
                </div>

                <div class="rounded-[30px] border border-[#efdcd5] bg-white/88 p-6 shadow-[0_16px_40px_rgba(157,80,53,0.08)]">
                    <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Status</p>
                    <p class="mt-4 text-4xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">{{ deduction.is_closed ? 'Closed' : 'Open' }}</p>
                    <p class="mt-4 text-sm text-[#6d5952]">Closed months reject recalculation attempts.</p>
                </div>
            </section>

            <section class="grid gap-6 xl:grid-cols-[0.9fr,1.1fr]">
                <div class="rounded-[34px] border border-[#efdcd5] bg-white/92 p-7 shadow-[0_18px_50px_rgba(157,80,53,0.08)]">
                    <div class="flex items-center justify-between gap-4 border-b border-[#f0dfd8] pb-5">
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-[0.28em] text-[#b47b67]">Summary</p>
                            <h2 class="mt-2 text-2xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">Deduction ledger</h2>
                        </div>
                        <span
                            class="rounded-full px-3 py-1 text-[11px] font-bold uppercase tracking-[0.18em]"
                            :class="deduction.is_closed ? 'bg-[#171717] text-white' : 'bg-[#ebfff3] text-[#18754d]'"
                        >
                            {{ deduction.is_closed ? 'Closed' : 'Open' }}
                        </span>
                    </div>

                    <div class="mt-6 space-y-4 text-sm leading-7 text-[#65534d]">
                        <p><span class="font-semibold text-[#1b1b1b]">Peneroka:</span> {{ deduction.peneroka?.name }}</p>
                        <p><span class="font-semibold text-[#1b1b1b]">Month:</span> {{ formatDate(deduction.month) }}</p>
                        <p><span class="font-semibold text-[#1b1b1b]">Remaining balance:</span> {{ formatMoney(deduction.unallocated_amount) }}</p>
                    </div>
                </div>

                <div class="rounded-[34px] border border-[#efdcd5] bg-white/92 p-7 shadow-[0_18px_50px_rgba(157,80,53,0.08)]">
                    <div class="flex items-center justify-between gap-4 border-b border-[#f0dfd8] pb-5">
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-[0.28em] text-[#b47b67]">Allocations</p>
                            <h2 class="mt-2 text-2xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">Debt waterfall result</h2>
                        </div>
                    </div>

                    <div class="mt-6 overflow-x-auto">
                        <Table>
                            <TableHeader>
                                <TableRow class="bg-[#fbf6f3]">
                                    <TableHead class="px-4 py-3 text-[11px] font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Debt</TableHead>
                                    <TableHead class="px-4 py-3 text-[11px] font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Priority</TableHead>
                                    <TableHead class="px-4 py-3 text-[11px] font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Allocated</TableHead>
                                    <TableHead class="px-4 py-3 text-[11px] font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Remaining Balance</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-if="!deduction.allocations || deduction.allocations.length === 0">
                                    <TableCell colspan="4" class="px-4 py-8 text-center text-sm text-[#8d7167]">
                                        No allocations found.
                                    </TableCell>
                                </TableRow>
                                <TableRow
                                    v-for="allocation in deduction.allocations"
                                    :key="allocation.id"
                                    class="border-t border-[#f2e3dc] text-[#3a302d] hover:bg-[#fff8f4]"
                                >
                                    <TableCell class="px-4 py-4">
                                        <div>
                                            <p class="font-semibold text-[#1b1b1b]">{{ allocation.debt?.description || allocation.debt_id }}</p>
                                            <p class="text-xs text-[#8d7167]">ID {{ allocation.debt?.id || allocation.debt_id }}</p>
                                        </div>
                                    </TableCell>
                                    <TableCell class="px-4 py-4">
                                        <span class="rounded-full bg-[#fff1ec] px-3 py-1 text-[11px] font-bold text-[#b64a2b]">
                                            {{ allocation.debt?.priority ?? '-' }}
                                        </span>
                                    </TableCell>
                                    <TableCell class="px-4 py-4 font-semibold text-[#1b1b1b]">{{ formatMoney(allocation.amount) }}</TableCell>
                                    <TableCell class="px-4 py-4 text-[#6d5952]">{{ formatMoney(allocation.debt?.balance) }}</TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>
                </div>
            </section>
        </div>
    </KpsShellLayout>
</template>
