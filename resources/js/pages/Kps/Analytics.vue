<script setup lang="ts">
import { computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import { useLocale } from '@/composables/useLocale';
import KpsShellLayout from '@/layouts/kps/KpsShellLayout.vue';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { formatCompactCurrency, formatCurrency, formatNumber } from '@/utils/currency';

interface AnalyticsOverview {
    total_sites: number;
    active_sites: number;
    sites_with_activity: number;
    total_peneroka: number;
    total_debts: number;
    total_outstanding: number;
    total_original_amount: number;
    current_month_deductions: number;
    current_month_allocated: number;
    current_month_unallocated: number;
    current_month_closed_count: number;
    current_month_open_count: number;
    allocation_rate: number;
    average_outstanding_per_peneroka: number;
}

interface MonthlyTrendItem {
    month: string;
    label: string;
    deduction_count: number;
    amount: number;
    unallocated_amount: number;
    allocated_amount: number;
    closed_count: number;
    open_count: number;
    allocation_rate: number;
}

interface SitePerformanceItem {
    id: string;
    name: string;
    code: string;
    is_active: boolean;
    peneroka_count: number;
    debt_count: number;
    active_debt_count: number;
    outstanding: number;
    original_amount: number;
    monthly_deductions: number;
    monthly_unallocated: number;
    monthly_closed_count: number;
    monthly_deduction_count: number;
}

interface PriorityBreakdownItem {
    priority: number;
    debt_count: number;
    outstanding: number;
    original_amount: number;
    share_of_outstanding: number;
}

interface TopPenerokaItem {
    id: string;
    name: string;
    site_name: string;
    site_code: string;
    debt_count: number;
    outstanding: number;
    original_amount: number;
    share_of_outstanding: number;
}

interface Props {
    month: string;
    monthLabel: string;
    overview: AnalyticsOverview;
    monthlyTrend: MonthlyTrendItem[];
    sitePerformance: SitePerformanceItem[];
    priorityBreakdown: PriorityBreakdownItem[];
    topPeneroka: TopPenerokaItem[];
}

const props = defineProps<Props>();
const { t } = useLocale();

const trendMaxAmount = computed(() => Math.max(...props.monthlyTrend.map((item) => item.amount), 1));
const siteMaxOutstanding = computed(() => Math.max(...props.sitePerformance.map((item) => item.outstanding), 1));
const priorityMaxOutstanding = computed(() => Math.max(...props.priorityBreakdown.map((item) => item.outstanding), 1));
const penerokaMaxOutstanding = computed(() => Math.max(...props.topPeneroka.map((item) => item.outstanding), 1));

const formatRate = (value: number): string => `${value.toFixed(1)}%`;

const relativeWidth = (value: number, max: number): string => {
    if (value <= 0 || max <= 0) {
        return '0%';
    }

    return `${Math.max((value / max) * 100, 4)}%`;
};

const allocationRate = (amount: number, unallocated: number): number => {
    if (amount <= 0) {
        return 0;
    }

    return ((amount - unallocated) / amount) * 100;
};

const efficiencyVariant = (rate: number): 'default' | 'secondary' | 'outline' => {
    if (rate >= 90) {
        return 'default';
    }

    if (rate >= 75) {
        return 'secondary';
    }

    return 'outline';
};
</script>

<template>
    <Head :title="t('kps.analytics.title', 'KPS Analytics')" />

    <KpsShellLayout>
        <div class="space-y-6">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h1 class="text-2xl font-semibold">{{ t('kps.analytics.title', 'KPS Analytics') }}</h1>
                    <p class="text-muted-foreground">{{ t('kps.current_month', 'Current month:') }} {{ monthLabel }}</p>
                </div>
                <Badge variant="outline" class="w-fit">{{ t('kps.data_snapshot', 'Data snapshot:') }} {{ month }}</Badge>
            </div>

            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                <Card>
                    <CardHeader>
                        <CardDescription>Site coverage</CardDescription>
                        <CardTitle class="text-3xl tabular-nums">{{ formatNumber(overview.active_sites) }}</CardTitle>
                    </CardHeader>
                    <CardContent class="text-sm text-muted-foreground">
                        {{ formatNumber(overview.total_sites) }} total sites and {{ formatNumber(overview.sites_with_activity) }} with activity this month
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardDescription>Peneroka</CardDescription>
                        <CardTitle class="text-3xl tabular-nums">{{ formatNumber(overview.total_peneroka) }}</CardTitle>
                    </CardHeader>
                    <CardContent class="text-sm text-muted-foreground">
                        {{ formatNumber(overview.total_debts) }} debt records across the network
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardDescription>Total Outstanding</CardDescription>
                        <CardTitle class="text-3xl tabular-nums">{{ formatCompactCurrency(overview.total_outstanding) }}</CardTitle>
                    </CardHeader>
                    <CardContent class="text-sm text-muted-foreground">
                        Average outstanding: {{ formatCurrency(overview.average_outstanding_per_peneroka) }} per peneroka
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardDescription>This Month Deductions</CardDescription>
                        <CardTitle class="text-3xl tabular-nums">{{ formatCompactCurrency(overview.current_month_deductions) }}</CardTitle>
                    </CardHeader>
                    <CardContent class="text-sm text-muted-foreground">
                        {{ formatNumber(overview.current_month_closed_count) }} closed and {{ formatNumber(overview.current_month_open_count) }} open deductions
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardDescription>Allocated This Month</CardDescription>
                        <CardTitle class="text-3xl tabular-nums">{{ formatCompactCurrency(overview.current_month_allocated) }}</CardTitle>
                    </CardHeader>
                    <CardContent class="text-sm text-muted-foreground">
                        {{ formatCompactCurrency(overview.current_month_unallocated) }} remains unallocated
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardDescription>Kecekapan Agihan</CardDescription>
                        <CardTitle class="text-3xl tabular-nums">{{ formatRate(overview.allocation_rate) }}</CardTitle>
                    </CardHeader>
                    <CardContent class="text-sm text-muted-foreground">
                        Measured from the current month deductions
                    </CardContent>
                </Card>
            </div>

            <div class="grid gap-6 xl:grid-cols-[1.4fr_1fr]">
                <Card>
                    <CardHeader>
                        <CardTitle>Monthly Trend</CardTitle>
                        <CardDescription>Deductions and unallocated amounts over the last six months.</CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="flex flex-wrap gap-3 text-xs text-muted-foreground">
                            <span class="inline-flex items-center gap-2">
                                <span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                                Allocated
                            </span>
                            <span class="inline-flex items-center gap-2">
                                <span class="h-2.5 w-2.5 rounded-full bg-amber-500"></span>
                                Unallocated
                            </span>
                        </div>

                        <div class="space-y-4">
                            <div
                                v-for="item in monthlyTrend"
                                :key="item.month"
                                class="rounded-lg border p-4"
                            >
                                <div class="flex flex-wrap items-center justify-between gap-3">
                                    <div>
                                        <div class="font-medium">{{ item.label }}</div>
                                        <div class="text-sm text-muted-foreground">
                                            {{ formatNumber(item.deduction_count) }} deductions
                                            / {{ formatNumber(item.closed_count) }} closed
                                            / {{ formatNumber(item.open_count) }} open
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-medium tabular-nums">{{ formatCompactCurrency(item.amount) }}</div>
                                        <div class="text-sm text-muted-foreground">{{ formatRate(item.allocation_rate) }} allocated</div>
                                    </div>
                                </div>

                                <div class="mt-3 h-3 overflow-hidden rounded-full bg-slate-100">
                                    <div class="flex h-full" :style="{ width: relativeWidth(item.amount, trendMaxAmount) }">
                                        <div
                                            class="h-full bg-emerald-500"
                                            :style="{ width: item.amount > 0 ? `${Math.max((item.allocated_amount / item.amount) * 100, 0)}%` : '0%' }"
                                        ></div>
                                        <div
                                            class="h-full bg-amber-500"
                                            :style="{ width: item.amount > 0 ? `${Math.max((item.unallocated_amount / item.amount) * 100, 0)}%` : '0%' }"
                                        ></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Operational Health</CardTitle>
                        <CardDescription>Current month closure and allocation status.</CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="rounded-lg border bg-muted/30 p-4">
                            <div class="text-sm text-muted-foreground">Closed deductions</div>
                            <div class="mt-1 text-3xl font-semibold tabular-nums">{{ formatNumber(overview.current_month_closed_count) }}</div>
                            <div class="text-sm text-muted-foreground">Out of {{ formatNumber(overview.current_month_closed_count + overview.current_month_open_count) }} monthly deductions</div>
                        </div>

                        <div class="rounded-lg border bg-muted/30 p-4">
                            <div class="text-sm text-muted-foreground">Unallocated amount</div>
                            <div class="mt-1 text-3xl font-semibold tabular-nums">{{ formatCompactCurrency(overview.current_month_unallocated) }}</div>
                            <div class="mt-3 h-2 overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full rounded-full bg-rose-500" :style="{ width: `${Math.min(100, Math.max(0, 100 - overview.allocation_rate))}%` }"></div>
                            </div>
                            <div class="mt-2 text-sm text-muted-foreground">Lower is better. Current allocation efficiency is {{ formatRate(overview.allocation_rate) }}.</div>
                        </div>

                        <div class="rounded-lg border bg-muted/30 p-4">
                            <div class="text-sm text-muted-foreground">Average outstanding per peneroka</div>
                            <div class="mt-1 text-3xl font-semibold tabular-nums">{{ formatCurrency(overview.average_outstanding_per_peneroka) }}</div>
                            <div class="text-sm text-muted-foreground">Balanced against {{ formatNumber(overview.total_debts) }} debt records</div>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <div class="grid gap-6 xl:grid-cols-2">
                <Card>
                    <CardHeader>
                        <CardTitle>Site Performance</CardTitle>
                        <CardDescription>Top sites ranked by outstanding balance.</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="rounded-md border">
                            <Table>
                                <TableHeader>
                                    <TableRow>
                                        <TableHead>Site</TableHead>
                                        <TableHead>Coverage</TableHead>
                                        <TableHead>Outstanding</TableHead>
                                        <TableHead class="text-right">Efficiency</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    <TableRow v-if="sitePerformance.length === 0">
                                        <TableCell colspan="4" class="text-center text-muted-foreground">
                                            No site data available.
                                        </TableCell>
                                    </TableRow>
                                    <TableRow v-for="site in sitePerformance" :key="site.id">
                                        <TableCell>
                                            <div class="font-medium">{{ site.name }}</div>
                                            <div class="text-sm text-muted-foreground">
                                                {{ site.code }} / {{ site.is_active ? 'Active' : 'Inactive' }}
                                            </div>
                                        </TableCell>
                                        <TableCell>
                                            <div class="font-medium">{{ formatNumber(site.peneroka_count) }} peneroka</div>
                                            <div class="text-sm text-muted-foreground">{{ formatNumber(site.debt_count) }} debts</div>
                                        </TableCell>
                                        <TableCell>
                                            <div class="font-medium tabular-nums">{{ formatCurrency(site.outstanding) }}</div>
                                            <div class="mt-2 h-2 overflow-hidden rounded-full bg-slate-100">
                                                <div class="h-full rounded-full bg-slate-900" :style="{ width: relativeWidth(site.outstanding, siteMaxOutstanding) }"></div>
                                            </div>
                                        </TableCell>
                                        <TableCell class="text-right">
                                            <Badge :variant="efficiencyVariant(allocationRate(site.monthly_deductions, site.monthly_unallocated))">
                                                {{ formatRate(allocationRate(site.monthly_deductions, site.monthly_unallocated)) }}
                                            </Badge>
                                            <div class="mt-2 text-sm text-muted-foreground">
                                                {{ formatCurrency(site.monthly_deductions) }} this month
                                            </div>
                                        </TableCell>
                                    </TableRow>
                                </TableBody>
                            </Table>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Debt Priority Mix</CardTitle>
                        <CardDescription>Outstanding balance distribution by debt priority.</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="rounded-md border">
                            <Table>
                                <TableHeader>
                                    <TableRow>
                                        <TableHead>Priority</TableHead>
                                        <TableHead>Debts</TableHead>
                                        <TableHead>Outstanding</TableHead>
                                        <TableHead class="text-right">Share</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    <TableRow v-if="priorityBreakdown.length === 0">
                                        <TableCell colspan="4" class="text-center text-muted-foreground">
                                            No priority data available.
                                        </TableCell>
                                    </TableRow>
                                    <TableRow v-for="item in priorityBreakdown" :key="item.priority">
                                        <TableCell>
                                            <Badge variant="outline">Priority {{ item.priority }}</Badge>
                                        </TableCell>
                                        <TableCell>{{ formatNumber(item.debt_count) }}</TableCell>
                                        <TableCell>
                                            <div class="font-medium tabular-nums">{{ formatCurrency(item.outstanding) }}</div>
                                            <div class="mt-2 h-2 overflow-hidden rounded-full bg-slate-100">
                                                <div class="h-full rounded-full bg-indigo-500" :style="{ width: relativeWidth(item.outstanding, priorityMaxOutstanding) }"></div>
                                            </div>
                                        </TableCell>
                                        <TableCell class="text-right">
                                            <div class="font-medium">{{ formatRate(item.share_of_outstanding) }}</div>
                                            <div class="text-sm text-muted-foreground">
                                                of total outstanding
                                            </div>
                                        </TableCell>
                                    </TableRow>
                                </TableBody>
                            </Table>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>Largest Outstanding Peneroka</CardTitle>
                    <CardDescription>Top individual balances across all sites.</CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="rounded-md border">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>Name</TableHead>
                                    <TableHead>Site</TableHead>
                                    <TableHead>Debts</TableHead>
                                    <TableHead>Outstanding</TableHead>
                                    <TableHead class="text-right">Share</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-if="topPeneroka.length === 0">
                                    <TableCell colspan="5" class="text-center text-muted-foreground">
                                        No debtor data available.
                                    </TableCell>
                                </TableRow>
                                <TableRow v-for="item in topPeneroka" :key="item.id">
                                    <TableCell class="font-medium">{{ item.name }}</TableCell>
                                    <TableCell>
                                        <div>{{ item.site_name }}</div>
                                        <div class="text-sm text-muted-foreground">{{ item.site_code }}</div>
                                    </TableCell>
                                    <TableCell>{{ formatNumber(item.debt_count) }}</TableCell>
                                    <TableCell>
                                        <div class="font-medium tabular-nums">{{ formatCurrency(item.outstanding) }}</div>
                                        <div class="mt-2 h-2 overflow-hidden rounded-full bg-slate-100">
                                            <div class="h-full rounded-full bg-cyan-500" :style="{ width: relativeWidth(item.outstanding, penerokaMaxOutstanding) }"></div>
                                        </div>
                                    </TableCell>
                                    <TableCell class="text-right">
                                        <div class="font-medium">{{ formatRate(item.share_of_outstanding) }}</div>
                                        <div class="text-sm text-muted-foreground">of total outstanding</div>
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>
                </CardContent>
            </Card>
        </div>
    </KpsShellLayout>
</template>
