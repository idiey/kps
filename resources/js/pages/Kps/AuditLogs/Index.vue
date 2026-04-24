<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { useLocale } from '@/composables/useLocale';

import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { useServerTable } from '@/composables/useServerTable';
import KpsShellLayout from '@/layouts/kps/KpsShellLayout.vue';
import type { KpsAuditLog, KpsSite, KpsSiteRole } from '@/types';

interface PaginatedAuditLogs {
    data: KpsAuditLog[];
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
    auditLogs: PaginatedAuditLogs;
    availableActions: string[];
    filters: {
        action?: string;
        search?: string;
    };
}>();

const { globalFilter, extraFilters, setFilter, goToPage } = useServerTable(
    `/kps/sites/${props.site.id}/audit-logs`,
    props.filters,
);

const selectedAction = computed({
    get: () => extraFilters.value.action ?? 'all',
    set: (action: string) => setFilter('action', action === 'all' ? undefined : action),
});

const formatAction = (action: string) =>
    action
        .split('_')
        .map((segment) => segment.charAt(0).toUpperCase() + segment.slice(1))
        .join(' ');

const formatDateTime = (value?: string | null) => {
    if (!value) {
        return '-';
    }

    const date = new Date(value);
    if (Number.isNaN(date.getTime())) {
        return value;
    }

    return new Intl.DateTimeFormat('en-MY', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    }).format(date);
};

const formatMetadata = (log: KpsAuditLog) => {
    if (!log.metadata) {
        return '-';
    }

    if (log.action === 'month_closed') {
        return `Month ${log.metadata.month || '-'} | Deductions closed ${log.metadata.deductions_closed || 0}`;
    }

    return Object.entries(log.metadata)
        .map(([key, value]) => `${key}: ${value}`)
        .join(' | ');
};

const latestActivity = computed(() =>
    props.auditLogs.data[0]?.created_at
        ? formatDateTime(props.auditLogs.data[0].created_at)
        : 'No activity recorded',
);

const tableColumns = [
    { accessorKey: 'created_at', header: 'Timestamp' },
    { accessorKey: 'action', header: 'Action' },
    { accessorKey: 'user', header: 'User' },
    { accessorKey: 'target', header: 'Target' },
    { accessorKey: 'metadata', header: 'Metadata' },
];

const paginationMeta = computed(() => {
    const source = props.auditLogs;
    const meta = source.meta ?? source;

    return {
        currentPage: Number(meta.current_page ?? 1),
        lastPage: Number(meta.last_page ?? 1),
        perPage: Number(meta.per_page ?? 20),
        total: Number(meta.total ?? source.data.length),
    };
});

const { t } = useLocale();
</script>

<template>
    <Head :title="`${t('kps.audit_trail', 'Audit Trail')} - ${site.name}`" />

    <KpsShellLayout :site="site" :site-role="siteRole">
        <div class="space-y-6 px-4 pb-8 lg:px-8">
            <section class="flex flex-col gap-5 2xl:flex-row 2xl:items-end 2xl:justify-between">
                <div class="max-w-3xl">
                    <p class="text-[11px] font-bold uppercase tracking-[0.32em] text-[#b7654b]">
                        Site Audit
                    </p>
                    <h1 class="mt-3 text-4xl font-black tracking-[-0.04em] text-[#1b1b1b] md:text-5xl" style="font-family: Manrope, Inter, sans-serif;">
                        Audit Trail
                    </h1>
                    <p class="mt-4 max-w-2xl text-base leading-7 text-[#65534d] md:text-lg">
                        Recent operational activity for {{ site.name }} with the same warm editorial presentation as the rest of the live site experience.
                    </p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <Button variant="outline" as-child class="rounded-full border-[#e1cbc2] bg-white px-6 py-3 text-[#6d5952] shadow-[0_10px_30px_rgba(157,80,53,0.06)] hover:border-[#c77d62] hover:text-[#1b1b1b]">
                        <Link :href="`/kps/sites/${site.id}`">Back to Site Dashboard</Link>
                    </Button>
                </div>
            </section>

            <section class="grid gap-4 md:grid-cols-2">
                <Card class="border-[#efdcd5] bg-white/90 shadow-[0_16px_40px_rgba(157,80,53,0.08)]">
                    <CardHeader class="pb-3">
                        <CardTitle class="text-xs font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Log Entries</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="text-4xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">{{ paginationMeta.total }}</div>
                    </CardContent>
                </Card>
                <Card class="border-[#efdcd5] bg-white/90 shadow-[0_16px_40px_rgba(157,80,53,0.08)]">
                    <CardHeader class="pb-3">
                        <CardTitle class="text-xs font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Latest Activity</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="text-lg font-semibold text-[#1b1b1b]">{{ latestActivity }}</div>
                    </CardContent>
                </Card>
            </section>

            <Card class="border-[#efdcd5] bg-white/92 shadow-[0_18px_50px_rgba(157,80,53,0.08)]">
                <CardHeader class="border-b border-[#f0dfd8] pb-4">
                    <CardTitle class="text-[11px] font-bold uppercase tracking-[0.28em] text-[#b47b67]">Filter</CardTitle>
                </CardHeader>
                <CardContent class="pt-5">
                    <div class="flex flex-col gap-3 lg:flex-row">
                        <Input
                            v-model="globalFilter"
                            type="text"
                            placeholder="Search by user name or email..."
                            class="w-full lg:w-[320px]"
                        />
                        <select
                            v-model="selectedAction"
                            class="h-10 rounded-full border border-[#ead6ce] bg-[#fbf6f3] px-4 text-sm text-[#1b1b1b] outline-none"
                        >
                            <option value="all">All Actions</option>
                            <option
                                v-for="action in availableActions"
                                :key="action"
                                :value="action"
                            >
                                {{ formatAction(action) }}
                            </option>
                        </select>
                    </div>
                </CardContent>
            </Card>

            <Card class="overflow-hidden border-[#efdcd5] bg-white/92 shadow-[0_18px_50px_rgba(157,80,53,0.08)]">
                <CardHeader class="border-b border-[#f0dfd8] pb-4">
                    <CardTitle class="text-[11px] font-bold uppercase tracking-[0.28em] text-[#b47b67]">Audit Events</CardTitle>
                </CardHeader>
                <CardContent class="p-0">
                    <div class="overflow-x-auto px-2 py-3">
                        <UTable
                            :data="auditLogs.data"
                            :columns="tableColumns"
                            empty="No audit logs found."
                            class="min-w-full"
                        >
                            <template #created_at-cell="{ row }">
                                <span class="text-[#6d5952]">{{ formatDateTime(row.original.created_at) }}</span>
                            </template>

                            <template #action-cell="{ row }">
                                <span class="font-semibold text-[#1b1b1b]">{{ formatAction(row.original.action) }}</span>
                            </template>

                            <template #user-cell="{ row }">
                                <div>
                                    <div class="font-semibold text-[#1b1b1b]">{{ row.original.user?.name || 'System' }}</div>
                                    <div class="text-xs text-[#8d7167]">{{ row.original.user?.email || '-' }}</div>
                                </div>
                            </template>

                            <template #target-cell="{ row }">
                                <span class="text-[#6d5952]">{{ row.original.auditable_label }} {{ row.original.auditable_id }}</span>
                            </template>

                            <template #metadata-cell="{ row }">
                                <span class="text-sm text-[#6d5952]">{{ formatMetadata(row.original) }}</span>
                            </template>
                        </UTable>
                    </div>
                </CardContent>
            </Card>

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
