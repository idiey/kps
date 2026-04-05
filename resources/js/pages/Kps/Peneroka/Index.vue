<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
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
import type { AppPageProps, KpsPeneroka, KpsSite, KpsSiteRole } from '@/types';

interface PaginationMeta {
    current_page?: number;
    last_page?: number;
    from?: number;
    to?: number;
    total?: number;
}

interface PaginatedPeneroka {
    data: KpsPeneroka[];
    links: any[];
    meta: PaginationMeta;
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
}>();

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
                <div class="flex flex-col gap-3 border-b border-[#f0dfd8] px-7 py-6 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-[0.28em] text-[#b47b67]">Master Data</p>
                        <h2 class="mt-2 text-2xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">Operational registry</h2>
                    </div>
                    <span class="rounded-full bg-[#f7f1ee] px-4 py-2 text-[11px] font-bold uppercase tracking-[0.22em] text-[#8d7167]">
                        {{ site.name }}
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <Table>
                        <TableHeader>
                            <TableRow class="border-[#f1dfd8] bg-[#fbf6f3] hover:bg-[#fbf6f3]">
                                <TableHead class="px-7 py-4 text-[11px] font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Name</TableHead>
                                <TableHead class="px-7 py-4 text-[11px] font-bold uppercase tracking-[0.24em] text-[#9b7d73]">IC Number</TableHead>
                                <TableHead class="px-7 py-4 text-[11px] font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Phone</TableHead>
                                <TableHead class="px-7 py-4 text-right text-[11px] font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Actions</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-if="penerokas.data.length === 0">
                                <TableCell colspan="4" class="px-7 py-10 text-center text-sm text-[#8d7167]">
                                    No peneroka found for this site.
                                </TableCell>
                            </TableRow>
                            <TableRow
                                v-for="p in penerokas.data"
                                :key="p.id"
                                class="border-[#f2e3dc] text-[#3a302d] transition hover:bg-[#fff8f3]"
                            >
                                <TableCell class="px-7 py-5">
                                    <div class="space-y-1">
                                        <p class="font-semibold text-[#1b1b1b]">{{ p.name }}</p>
                                        <p class="text-xs text-[#8d7167]">{{ p.address || 'No address captured' }}</p>
                                    </div>
                                </TableCell>
                                <TableCell class="px-7 py-5 text-[#6d5952]">{{ p.ic_number || '-' }}</TableCell>
                                <TableCell class="px-7 py-5 text-[#6d5952]">{{ p.phone || '-' }}</TableCell>
                                <TableCell class="px-7 py-5 text-right">
                                    <div class="flex justify-end gap-2">
                                        <Button
                                            v-if="canManagePeneroka"
                                            variant="ghost"
                                            size="sm"
                                            as-child
                                            class="rounded-full text-[#6d5952] hover:bg-[#fff1ec] hover:text-[#1b1b1b]"
                                        >
                                            <Link :href="`/kps/sites/${site.id}/peneroka/${p.id}/edit`">Edit</Link>
                                        </Button>
                                        <Button
                                            v-if="canViewReports"
                                            variant="outline"
                                            size="sm"
                                            as-child
                                            class="rounded-full border-[#e2c9c0] text-[#6d5952] hover:border-[#c77d62] hover:text-[#1b1b1b]"
                                        >
                                            <Link :href="`/kps/sites/${site.id}/reports/peneroka/${p.id}`">Statement</Link>
                                        </Button>
                                    </div>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>
            </section>
        </div>
    </KpsShellLayout>
</template>
