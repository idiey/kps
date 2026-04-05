<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { useServerTable } from '@/composables/useServerTable';
import KpsShellLayout from '@/layouts/kps/KpsShellLayout.vue';
import type { AppPageProps, KpsDebt, KpsSite, KpsSiteRole } from '@/types';

interface PaginatedDebts {
    data: KpsDebt[];
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
    debts: PaginatedDebts;
    summary: {
        total_debts: number;
        outstanding_total: number;
        due_this_month: number;
        highest_priority_open: number | null;
    };
    filters: {
        search?: string;
        status?: 'all' | 'open' | 'paid';
        sort_by?: string;
        sort_dir?: 'asc' | 'desc';
    };
}>();

const { globalFilter, sorting, extraFilters, setFilter, goToPage } = useServerTable(
    `/kps/sites/${props.site.id}/hutang`,
    props.filters,
);

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

const tableColumns = [
    { accessorKey: 'peneroka', header: 'Peneroka' },
    { accessorKey: 'priority', header: 'Priority' },
    { accessorKey: 'balance', header: 'Balance' },
    { accessorKey: 'due_date', header: 'Due Date' },
    { id: 'actions', header: 'Actions' },
];

const paginationMeta = computed(() => {
    const source = props.debts;
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
                <div class="flex flex-col gap-3 border-b border-[#f0dfd8] px-7 py-6">
                    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-[0.28em] text-[#b47b67]">Debt Register</p>
                            <h2 class="mt-2 text-2xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">Allocation priority list</h2>
                        </div>
                        <span class="rounded-full bg-[#f7f1ee] px-4 py-2 text-[11px] font-bold uppercase tracking-[0.22em] text-[#8d7167]">
                            {{ site.code }}
                        </span>
                    </div>

                    <div class="flex flex-col gap-3 lg:flex-row">
                        <Input
                            v-model="globalFilter"
                            type="text"
                            placeholder="Search peneroka or debt description..."
                            class="w-full lg:w-[280px]"
                        />
                        <select
                            v-model="statusModel"
                            class="h-10 rounded-full border border-[#ead6ce] bg-[#f7f1ee] px-4 text-sm text-[#6d5952] outline-none"
                        >
                            <option value="all">All Status</option>
                            <option value="open">Open</option>
                            <option value="paid">Paid</option>
                        </select>
                        <select
                            v-model="sortByModel"
                            class="h-10 rounded-full border border-[#ead6ce] bg-[#f7f1ee] px-4 text-sm text-[#6d5952] outline-none"
                        >
                            <option value="">Default Sort</option>
                            <option value="priority">Priority</option>
                            <option value="balance">Balance</option>
                            <option value="due_date">Due Date</option>
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
                        :data="debts.data"
                        :columns="tableColumns"
                        :sorting-options="{ manualSorting: true }"
                        empty="No debts found for this site."
                        class="min-w-full"
                    >
                        <template #peneroka-cell="{ row }">
                            <div class="space-y-1">
                                <p class="font-semibold text-[#1b1b1b]">{{ row.original.peneroka?.name || 'Unknown peneroka' }}</p>
                                <p class="text-xs text-[#8d7167]">{{ row.original.description || 'No description' }}</p>
                            </div>
                        </template>

                        <template #priority-cell="{ row }">
                            <span class="rounded-full bg-[#fff1ec] px-3 py-1 text-[11px] font-bold text-[#b64a2b]">
                                {{ row.original.priority }}
                            </span>
                        </template>

                        <template #balance-cell="{ row }">
                            <span class="font-semibold text-[#1b1b1b]">{{ formatMoney(row.original.balance) }}</span>
                        </template>

                        <template #due_date-cell="{ row }">
                            <span class="text-[#6d5952]">{{ formatDate(row.original.due_date) }}</span>
                        </template>

                        <template #actions-cell="{ row }">
                            <div class="text-right">
                                <Button
                                    v-if="canManageHutang"
                                    variant="ghost"
                                    size="sm"
                                    as-child
                                    class="rounded-full text-[#6d5952] hover:bg-[#fff1ec] hover:text-[#1b1b1b]"
                                >
                                    <Link :href="`/kps/sites/${site.id}/hutang/${row.original.id}/edit`">Edit</Link>
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
