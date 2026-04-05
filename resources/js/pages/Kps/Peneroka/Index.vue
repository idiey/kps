<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { useServerTable } from '@/composables/useServerTable';
import KpsShellLayout from '@/layouts/kps/KpsShellLayout.vue';
import type { AppPageProps, KpsPeneroka, KpsSite, KpsSiteRole } from '@/types';

interface PaginatedPeneroka {
    data: KpsPeneroka[];
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
    penerokas: PaginatedPeneroka;
    summary: {
        total_peneroka: number;
        with_ic_number: number;
        with_phone: number;
        outstanding_total: number;
    };
    filters: {
        search?: string;
        sort_by?: string;
        sort_dir?: 'asc' | 'desc';
    };
}>();

const { globalFilter, sorting, goToPage } = useServerTable(
    `/kps/sites/${props.site.id}/peneroka`,
    props.filters,
);

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

const formatNumber = (value?: number | null) =>
    new Intl.NumberFormat('en-MY', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(Number(value ?? 0));

const visiblePeneroka = computed(() => props.penerokas.data.length);
const coverage = computed(() =>
    props.summary.total_peneroka > 0
        ? Math.round((props.summary.with_phone / props.summary.total_peneroka) * 100)
        : 0,
);
const page = usePage<AppPageProps>();
const permissions = computed(() => page.props.auth?.permissions ?? []);
const canManagePeneroka = computed(() => permissions.value.includes('kps.manage_peneroka'));
const canViewReports = computed(() => permissions.value.includes('kps.view_reports'));

const formatMoney = (value?: number | null) =>
    new Intl.NumberFormat('en-MY', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    }).format(Number(value ?? 0));

const tableColumns = [
    { accessorKey: 'name', header: 'Name' },
    { accessorKey: 'ic_number', header: 'IC Number' },
    { accessorKey: 'phone', header: 'Phone' },
    { id: 'actions', header: 'Actions' },
];

const paginationMeta = computed(() => {
    const source = props.penerokas;
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
    <Head :title="`Peneroka - ${site.name}`" />

    <KpsShellLayout :site="site" :site-role="siteRole">
        <div class="space-y-6 px-4 pb-8 lg:px-8">
            <section class="flex flex-col gap-5 2xl:flex-row 2xl:items-end 2xl:justify-between">
                <div class="max-w-3xl">
                    <p class="text-[11px] font-bold uppercase tracking-[0.32em] text-[#b7654b]">
                        Site Workspace
                    </p>
                    <h1 class="mt-3 text-4xl font-black tracking-[-0.04em] text-[#1b1b1b] md:text-5xl" style="font-family: Manrope, Inter, sans-serif;">
                        Peneroka registry
                    </h1>
                    <p class="mt-4 max-w-2xl text-base leading-7 text-[#65534d] md:text-lg">
                        Manage beneficiary records for {{ site.name }} with the same warm operational language used across the site workspace.
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
                        v-if="canManagePeneroka"
                        as-child
                        class="rounded-full bg-gradient-to-br from-[#d6522d] to-[#bc3f1d] px-6 py-3 text-white shadow-[0_16px_32px_rgba(214,82,45,0.28)] hover:translate-y-[-1px]"
                    >
                        <Link :href="`/kps/sites/${site.id}/peneroka/create`">Add Peneroka</Link>
                    </Button>
                </div>
            </section>

            <section class="grid gap-4 xl:grid-cols-4">
                <div class="rounded-[30px] border border-[#efdcd5] bg-white/88 p-6 shadow-[0_16px_40px_rgba(157,80,53,0.08)]">
                    <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Total Records</p>
                    <p class="mt-4 text-4xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">{{ formatNumber(summary.total_peneroka) }}</p>
                    <p class="mt-4 text-sm text-[#6d5952]">Peneroka assigned to {{ site.code }}.</p>
                </div>

                <div class="rounded-[30px] border border-[#efdcd5] bg-white/88 p-6 shadow-[0_16px_40px_rgba(157,80,53,0.08)]">
                    <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#9b7d73]">On This Page</p>
                    <p class="mt-4 text-4xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">{{ formatNumber(visiblePeneroka) }}</p>
                    <p class="mt-4 text-sm text-[#6d5952]">Current records visible in the table below.</p>
                </div>

                <div class="rounded-[30px] border border-[#efdcd5] bg-white/88 p-6 shadow-[0_16px_40px_rgba(157,80,53,0.08)]">
                    <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Contact Coverage</p>
                    <p class="mt-4 text-4xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">{{ coverage }}%</p>
                    <p class="mt-4 text-sm text-[#6d5952]">Records with phone numbers captured.</p>
                </div>

                <div class="rounded-[30px] border border-[#efdcd5] bg-white/88 p-6 shadow-[0_16px_40px_rgba(157,80,53,0.08)]">
                    <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Outstanding</p>
                    <p class="mt-4 text-4xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">{{ formatMoney(summary.outstanding_total) }}</p>
                    <p class="mt-4 text-sm text-[#6d5952]">Open debt balance carried by this site registry.</p>
                </div>
            </section>

            <section class="overflow-hidden rounded-[36px] border border-[#efdcd5] bg-white/92 shadow-[0_18px_50px_rgba(157,80,53,0.08)]">
                <div class="flex flex-col gap-3 border-b border-[#f0dfd8] px-7 py-6">
                    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-[0.28em] text-[#b47b67]">Master Data</p>
                            <h2 class="mt-2 text-2xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">Operational registry</h2>
                        </div>
                        <span class="rounded-full bg-[#f7f1ee] px-4 py-2 text-[11px] font-bold uppercase tracking-[0.22em] text-[#8d7167]">
                            {{ site.name }}
                        </span>
                    </div>

                    <div class="flex flex-col gap-3 lg:flex-row">
                        <Input
                            v-model="globalFilter"
                            type="text"
                            placeholder="Search name, IC, or phone..."
                            class="w-full lg:w-[280px]"
                        />
                        <select
                            v-model="sortByModel"
                            class="h-10 rounded-full border border-[#ead6ce] bg-[#f7f1ee] px-4 text-sm text-[#6d5952] outline-none"
                        >
                            <option value="">Default Sort</option>
                            <option value="name">Name</option>
                            <option value="ic_number">IC Number</option>
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
                        :data="penerokas.data"
                        :columns="tableColumns"
                        :sorting-options="{ manualSorting: true }"
                        empty="No peneroka found for this site."
                        class="min-w-full"
                    >
                        <template #name-cell="{ row }">
                            <div class="space-y-1">
                                <p class="font-semibold text-[#1b1b1b]">{{ row.original.name }}</p>
                                <p class="text-xs text-[#8d7167]">{{ row.original.address || 'No address captured' }}</p>
                            </div>
                        </template>

                        <template #ic_number-cell="{ row }">
                            <span class="text-[#6d5952]">{{ row.original.ic_number || '-' }}</span>
                        </template>

                        <template #phone-cell="{ row }">
                            <span class="text-[#6d5952]">{{ row.original.phone || '-' }}</span>
                        </template>

                        <template #actions-cell="{ row }">
                            <div class="flex justify-end gap-2">
                                <Button
                                    v-if="canManagePeneroka"
                                    variant="ghost"
                                    size="sm"
                                    as-child
                                    class="rounded-full text-[#6d5952] hover:bg-[#fff1ec] hover:text-[#1b1b1b]"
                                >
                                    <Link :href="`/kps/sites/${site.id}/peneroka/${row.original.id}/edit`">Edit</Link>
                                </Button>
                                <Button
                                    v-if="canViewReports"
                                    variant="outline"
                                    size="sm"
                                    as-child
                                    class="rounded-full border-[#e2c9c0] text-[#6d5952] hover:border-[#c77d62] hover:text-[#1b1b1b]"
                                >
                                    <Link :href="`/kps/sites/${site.id}/reports/peneroka/${row.original.id}`">Statement</Link>
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
