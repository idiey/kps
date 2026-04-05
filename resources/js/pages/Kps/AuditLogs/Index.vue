<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import KpsShellLayout from '@/layouts/kps/KpsShellLayout.vue';
import type { KpsAuditLog, KpsSite, KpsSiteRole } from '@/types';

interface PaginatedAuditLogs {
    data: KpsAuditLog[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
}

const props = defineProps<{
    site: KpsSite;
    siteRole?: KpsSiteRole | null;
    auditLogs: PaginatedAuditLogs;
    availableActions: string[];
    filters: {
        action?: string;
    };
}>();

const selectedAction = ref(props.filters.action || 'all');

const applyFilters = () => {
    router.get(
        `/kps/sites/${props.site.id}/audit-logs`,
        {
            action: selectedAction.value === 'all' ? undefined : selectedAction.value,
        },
        {
            preserveState: true,
            preserveScroll: true,
        },
    );
};

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
</script>

<template>
    <Head :title="`Audit Trail - ${site.name}`" />

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
                        <div class="text-4xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">{{ auditLogs.total }}</div>
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
                    <div class="flex flex-wrap items-end gap-4">
                        <div class="grid gap-2">
                            <label class="text-xs font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Action</label>
                            <Select v-model="selectedAction" @update:model-value="applyFilters">
                                <SelectTrigger class="w-[220px] rounded-full border-[#ead6ce] bg-[#fbf6f3] text-[#1b1b1b]">
                                    <SelectValue placeholder="Filter by action" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="all">All Actions</SelectItem>
                                    <SelectItem
                                        v-for="action in availableActions"
                                        :key="action"
                                        :value="action"
                                    >
                                        {{ formatAction(action) }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <Card class="overflow-hidden border-[#efdcd5] bg-white/92 shadow-[0_18px_50px_rgba(157,80,53,0.08)]">
                <CardHeader class="border-b border-[#f0dfd8] pb-4">
                    <CardTitle class="text-[11px] font-bold uppercase tracking-[0.28em] text-[#b47b67]">Audit Events</CardTitle>
                </CardHeader>
                <CardContent class="p-0">
                    <div class="overflow-x-auto">
                        <Table>
                            <TableHeader class="bg-[#fbf6f3]">
                                <TableRow class="border-b border-[#f0dfd8]">
                                    <TableHead class="px-7 py-4 text-[11px] font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Timestamp</TableHead>
                                    <TableHead class="px-7 py-4 text-[11px] font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Action</TableHead>
                                    <TableHead class="px-7 py-4 text-[11px] font-bold uppercase tracking-[0.24em] text-[#9b7d73]">User</TableHead>
                                    <TableHead class="px-7 py-4 text-[11px] font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Target</TableHead>
                                    <TableHead class="px-7 py-4 text-[11px] font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Metadata</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-if="auditLogs.data.length === 0" class="border-t border-[#f2e3dc]">
                                    <TableCell colspan="5" class="px-7 py-10 text-center text-sm text-[#8d7167]">
                                        No audit logs found.
                                    </TableCell>
                                </TableRow>
                                <TableRow
                                    v-for="log in auditLogs.data"
                                    :key="log.id"
                                    class="border-t border-[#f2e3dc] text-sm text-[#3a302d] hover:bg-[#fff8f3]"
                                >
                                    <TableCell class="px-7 py-5 text-[#6d5952]">{{ formatDateTime(log.created_at) }}</TableCell>
                                    <TableCell class="px-7 py-5 font-semibold text-[#1b1b1b]">{{ formatAction(log.action) }}</TableCell>
                                    <TableCell class="px-7 py-5">
                                        <div class="font-semibold text-[#1b1b1b]">{{ log.user?.name || 'System' }}</div>
                                        <div class="text-xs text-[#8d7167]">{{ log.user?.email || '-' }}</div>
                                    </TableCell>
                                    <TableCell class="px-7 py-5 text-[#6d5952]">{{ log.auditable_label }} {{ log.auditable_id }}</TableCell>
                                    <TableCell class="px-7 py-5 text-sm text-[#6d5952]">{{ formatMetadata(log) }}</TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>
                </CardContent>
            </Card>

            <div v-if="auditLogs.last_page > 1" class="flex flex-col gap-3 rounded-[28px] border border-[#efdcd5] bg-white/90 px-5 py-4 shadow-[0_12px_30px_rgba(157,80,53,0.06)] sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm text-[#6d5952]">
                    Showing {{ (auditLogs.current_page - 1) * auditLogs.per_page + 1 }} to
                    {{ Math.min(auditLogs.current_page * auditLogs.per_page, auditLogs.total) }} of
                    {{ auditLogs.total }} entries
                </p>
                <div class="flex gap-2">
                    <Button
                        variant="outline"
                        size="sm"
                        class="rounded-full border-[#e1cbc2] bg-white text-[#6d5952] hover:border-[#c77d62] hover:text-[#1b1b1b]"
                        :disabled="auditLogs.current_page === 1"
                        @click="router.get(`/kps/sites/${site.id}/audit-logs?page=${auditLogs.current_page - 1}`)"
                    >
                        Previous
                    </Button>
                    <Button
                        variant="outline"
                        size="sm"
                        class="rounded-full border-[#e1cbc2] bg-white text-[#6d5952] hover:border-[#c77d62] hover:text-[#1b1b1b]"
                        :disabled="auditLogs.current_page === auditLogs.last_page"
                        @click="router.get(`/kps/sites/${site.id}/audit-logs?page=${auditLogs.current_page + 1}`)"
                    >
                        Next
                    </Button>
                </div>
            </div>
        </div>
    </KpsShellLayout>
</template>
