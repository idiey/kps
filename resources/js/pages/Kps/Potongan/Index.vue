<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { useServerTable } from '@/composables/useServerTable';
import KpsShellLayout from '@/layouts/kps/KpsShellLayout.vue';
import type { AppPageProps, KpsMonthlyDeduction, KpsSite, KpsSiteRole } from '@/types';

interface PaginatedDeductions {
    data: KpsMonthlyDeduction[];
    meta?: {
        current_page?: number;
        last_page?: number;
        per_page?: number;
        total?: number;
    };
    current_page?: number;
    last_page?: number;
    per_page?: number;
    total?: number;
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
    filters: {
        search?: string;
        status?: 'all' | 'open' | 'closed';
        sort_by?: string;
        sort_dir?: 'asc' | 'desc';
        month?: string;
    };
}>();

const { globalFilter, sorting, extraFilters, setFilter, goToPage } = useServerTable(
    `/kps/sites/${props.site.id}/potongan`,
    props.filters,
);

const month = ref(extraFilters.value.month ?? props.selectedMonth ?? '');

const applyMonthFilter = () => {
    setFilter('month', month.value || undefined);
};

const statusModel = computed({
    get: () => extraFilters.value.status ?? 'all',
    set: (status: string) => setFilter('status', status === 'all' ? undefined : status),
});

const sortByModel = computed({
    get: () => sorting.value[0]?.id ?? '',
    set: (column: string) => {
        if (!column) {
            sorting.value = [];
            return;
        }

        const current = sorting.value[0];
        sorting.value = [
            {
                id: column,
                desc: current?.desc ?? (props.filters.sort_dir === 'desc'),
            },
        ];
    },
});

const sortDirModel = computed({
    get: () => (sorting.value[0]?.desc ? 'desc' : 'asc'),
    set: (direction: 'asc' | 'desc') => {
        const currentColumn = sorting.value[0]?.id;
        if (!currentColumn) {
            return;
        }

        sorting.value = [
            {
                id: currentColumn,
                desc: direction === 'desc',
            },
        ];
    },
});

const formatMoney = (value?: number | null) =>
    new Intl.NumberFormat('en-MY', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    }).format(Number(value ?? 0));

const formatMonth = (value?: string | null) => {
    if (!value) {
        return '-';
    }

    return String(value).slice(0, 10);
};

const openCount = computed(() => Math.max(props.summary.deduction_count - props.summary.closed_count, 0));
const page = usePage<AppPageProps>();
const canManagePotongan = computed(() => (page.props.auth?.permissions ?? []).includes('kps.manage_potongan'));

const tableColumns = [
    { accessorKey: 'month', header: 'Month' },
    { accessorKey: 'peneroka', header: 'Peneroka' },
    { accessorKey: 'amount', header: 'Amount' },
    { accessorKey: 'unallocated_amount', header: 'Unallocated' },
    { accessorKey: 'is_closed', header: 'Status' },
    { id: 'actions', header: 'Actions' },
];

const paginationMeta = computed(() => {
    const source = props.deductions;
    const meta = source.meta ?? source;

    return {
        currentPage: Number(meta.current_page ?? 1),
        lastPage: Number(meta.last_page ?? 1),
        perPage: Number(meta.per_page ?? 15),
        total: Number(meta.total ?? source.data.length),
    };
});
</script>

<template>
    <Head :title="`Potongan - ${site.name}`" />

    <KpsShellLayout :site="site" :site-role="siteRole">
        <div class="space-y-6 px-4 pb-8 lg:px-8">
            <section class="flex flex-col gap-5 2xl:flex-row 2xl:items-end 2xl:justify-between">
                <div class="max-w-3xl">
                    <p class="text-[11px] font-bold uppercase tracking-[0.32em] text-[#b7654b]">
                        Deduction Workspace
                    </p>
                    <h1 class="mt-3 text-4xl font-black tracking-[-0.04em] text-[#1b1b1b] md:text-5xl" style="font-family: Manrope, Inter, sans-serif;">
                        Potongan Bulanan
                    </h1>
                    <p class="mt-4 max-w-2xl text-base leading-7 text-[#65534d] md:text-lg">
                        Manage monthly deductions for {{ site.name }} with a live operational view of allocations, closures, and unallocated balance.
                    </p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <template v-if="canManagePotongan">
                        <Link
                            :href="`/kps/sites/${site.id}/potongan/bulk`"
                            class="inline-flex items-center rounded-full border border-[#e1cbc2] bg-white px-6 py-3 text-sm font-semibold text-[#6d5952] shadow-[0_10px_30px_rgba(157,80,53,0.06)] transition hover:border-[#c77d62] hover:text-[#1b1b1b]"
                        >
                            Bulk Entry
                        </Link>
                        <Link
                            :href="`/kps/sites/${site.id}/potongan/create`"
                            class="inline-flex items-center rounded-full bg-gradient-to-br from-[#d6522d] to-[#bc3f1d] px-6 py-3 text-sm font-semibold text-white shadow-[0_16px_32px_rgba(214,82,45,0.28)] transition hover:translate-y-[-1px]"
                        >
                            Add Potongan
                        </Link>
                    </template>
                </div>
            </section>

            <section class="grid gap-4 xl:grid-cols-4">
                <div class="rounded-[30px] border border-[#efdcd5] bg-white/88 p-6 shadow-[0_16px_40px_rgba(157,80,53,0.08)]">
                    <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Selected Month</p>
                    <p class="mt-4 text-4xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">{{ monthLabel }}</p>
                    <p class="mt-4 text-sm text-[#6d5952]">Operational deduction view currently scoped to this reporting month.</p>
                </div>

                <div class="rounded-[30px] border border-[#efdcd5] bg-white/88 p-6 shadow-[0_16px_40px_rgba(157,80,53,0.08)]">
                    <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Total Amount</p>
                    <p class="mt-4 text-4xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">{{ formatMoney(summary.total_amount) }}</p>
                    <p class="mt-4 text-sm text-[#6d5952]">Gross deduction amount across the active month.</p>
                </div>

                <div class="rounded-[30px] border border-[#efdcd5] bg-white/88 p-6 shadow-[0_16px_40px_rgba(157,80,53,0.08)]">
                    <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Unallocated</p>
                    <p class="mt-4 text-4xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">{{ formatMoney(summary.total_unallocated) }}</p>
                    <p class="mt-4 text-sm text-[#6d5952]">Amounts still waiting to be absorbed by the debt waterfall.</p>
                </div>

                <div class="rounded-[30px] border border-[#efdcd5] bg-white/88 p-6 shadow-[0_16px_40px_rgba(157,80,53,0.08)]">
                    <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Open vs Closed</p>
                    <p class="mt-4 text-4xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">{{ openCount }} / {{ summary.closed_count }}</p>
                    <p class="mt-4 text-sm text-[#6d5952]">Open and closed deduction records inside the month workspace.</p>
                </div>
            </section>

            <section class="rounded-[36px] border border-[#efdcd5] bg-white/92 shadow-[0_18px_50px_rgba(157,80,53,0.08)]">
                <div class="flex flex-col gap-4 border-b border-[#f0dfd8] px-7 py-6">
                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-[0.28em] text-[#b47b67]">Month Filter</p>
                            <h2 class="mt-2 text-2xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">Live deduction ledger</h2>
                        </div>
                        <div class="flex items-end gap-3">
                            <div class="grid gap-2">
                                <label class="text-xs font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Filter by Month</label>
                                <Input type="month" v-model="month" class="w-[220px]" />
                            </div>
                            <Button class="bg-[#171717] text-white hover:bg-[#0f0f0f]" @click="applyMonthFilter">Apply</Button>
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 lg:flex-row">
                        <Input
                            v-model="globalFilter"
                            type="text"
                            placeholder="Search peneroka name..."
                            class="w-full lg:w-[280px]"
                        />
                        <select
                            v-model="statusModel"
                            class="h-10 rounded-full border border-[#ead6ce] bg-[#f7f1ee] px-4 text-sm text-[#6d5952] outline-none"
                        >
                            <option value="all">All Status</option>
                            <option value="open">Open</option>
                            <option value="closed">Closed</option>
                        </select>
                        <select
                            v-model="sortByModel"
                            class="h-10 rounded-full border border-[#ead6ce] bg-[#f7f1ee] px-4 text-sm text-[#6d5952] outline-none"
                        >
                            <option value="">Default Sort</option>
                            <option value="amount">Amount</option>
                            <option value="peneroka_name">Peneroka Name</option>
                        </select>
                        <select
                            v-model="sortDirModel"
                            :disabled="!sortByModel"
                            class="h-10 rounded-full border border-[#ead6ce] bg-[#f7f1ee] px-4 text-sm text-[#6d5952] outline-none disabled:cursor-not-allowed disabled:opacity-60"
                        >
                            <option value="asc">Asc</option>
                            <option value="desc">Desc</option>
                        </select>
                    </div>
                </div>

                <div class="overflow-x-auto px-2 py-3">
                    <UTable
                        v-model:sorting="sorting"
                        :data="deductions.data"
                        :columns="tableColumns"
                        :sorting-options="{ manualSorting: true }"
                        empty="No deductions found."
                        class="min-w-full"
                    >
                        <template #month-cell="{ row }">
                            <span class="font-semibold text-[#1b1b1b]">{{ formatMonth(row.original.month) }}</span>
                        </template>

                        <template #peneroka-cell="{ row }">
                            <div>
                                <p class="font-semibold text-[#1b1b1b]">{{ row.original.peneroka?.name }}</p>
                                <p class="text-xs text-[#8d7167]">{{ row.original.peneroka?.phone || 'No phone registered' }}</p>
                            </div>
                        </template>

                        <template #amount-cell="{ row }">
                            <span class="font-semibold text-[#1b1b1b]">{{ formatMoney(row.original.amount) }}</span>
                        </template>

                        <template #unallocated_amount-cell="{ row }">
                            <span class="text-[#6d5952]">{{ formatMoney(row.original.unallocated_amount) }}</span>
                        </template>

                        <template #is_closed-cell="{ row }">
                            <span
                                class="rounded-full px-3 py-1 text-[11px] font-bold uppercase tracking-[0.18em]"
                                :class="row.original.is_closed ? 'bg-[#171717] text-white' : 'bg-[#ebfff3] text-[#18754d]'"
                            >
                                {{ row.original.is_closed ? 'Closed' : 'Open' }}
                            </span>
                        </template>

                        <template #actions-cell="{ row }">
                            <div class="text-right">
                                <Button variant="outline" size="sm" as-child class="rounded-full border-[#e2c9c0]">
                                    <Link :href="`/kps/sites/${site.id}/allocations/${row.original.id}`">View</Link>
                                </Button>
                            </div>
                        </template>
                    </UTable>
                </div>
            </section>

            <div
                v-if="paginationMeta.lastPage > 1"
                class="rounded-[28px] border border-[#efdcd5] bg-white/90 px-5 py-4 shadow-[0_12px_30px_rgba(157,80,53,0.06)]"
            >
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-sm text-[#6d5952]">
                        Showing
                        {{ (paginationMeta.currentPage - 1) * paginationMeta.perPage + 1 }}
                        to
                        {{ Math.min(paginationMeta.currentPage * paginationMeta.perPage, paginationMeta.total) }}
                        of
                        {{ paginationMeta.total }}
                        entries
                    </p>
                    <UPagination
                        :page="paginationMeta.currentPage"
                        :items-per-page="paginationMeta.perPage"
                        :total="paginationMeta.total"
                        :sibling-count="1"
                        show-edges
                        @update:page="goToPage"
                    />
                </div>
            </div>
        </div>
    </KpsShellLayout>
</template>
