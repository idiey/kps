<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import {
    Activity,
    Calendar,
    CheckCircle,
    Clock,
    TrendingUp,
    Users,
} from 'lucide-vue-next';
import { computed, ref } from 'vue';

interface AnalyticsData {
    overview: {
        total_jobs: number;
        completed_jobs: number;
        active_jobs: number;
        total_customers: number;
        completion_rate: number;
    };
    jobTrends: Array<{
        period: string;
        total: number;
        completed: number;
        completion_rate: number;
    }>;
    statusDistribution: Array<{
        status: string;
        count: number;
        label: string;
    }>;
    customerStats: {
        types: Array<{
            type: string;
            count: number;
            label: string;
        }>;
        top_customers: Array<{
            name: string;
            customer_type: string;
            job_count: number;
        }>;
    };
    technicianPerformance: Array<{
        technician_name: string;
        total_jobs: number;
        completed_jobs: number;
        completion_rate: number;
        avg_completion_days: number;
    }>;
    completionTimes: {
        average_days: number;
        fastest_job: {
            job_number: string;
            title: string;
            days: number;
        } | null;
        slowest_job: {
            job_number: string;
            title: string;
            days: number;
        } | null;
        distribution: Record<string, number>;
    };
    monthlyRevenue: {
        total_revenue: number;
        monthly_breakdown: Array<any>;
        growth_rate: number;
    };
}

interface Filters {
    period: string;
    availablePeriods: Record<string, string>;
}

const props = defineProps<{
    analytics: AnalyticsData;
    filters: Filters;
}>();

const selectedPeriod = ref(props.filters.period);
const isLoading = ref(false);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Analitik',
        href: '/analytics',
    },
];

const changePeriod = (period: string) => {
    if (period === selectedPeriod.value) return;

    isLoading.value = true;
    router.get(
        '/analytics',
        { period },
        {
            preserveState: true,
            onFinish: () => {
                isLoading.value = false;
                selectedPeriod.value = period;
            },
        },
    );
};

const formatNumber = (num: number): string => {
    return new Intl.NumberFormat('ms-MY').format(num);
};

const formatPercentage = (num: number): string => {
    return `${num.toFixed(1)}%`;
};

const getStatusColor = (status: string): string => {
    const colors: Record<string, string> = {
        completed: 'bg-green-100 text-green-800',
        in_progress: 'bg-blue-100 text-blue-800',
        assigned: 'bg-yellow-100 text-yellow-800',
        pending_assignment: 'bg-gray-100 text-gray-800',
        inspection: 'bg-purple-100 text-purple-800',
        cancelled: 'bg-red-100 text-red-800',
        draft: 'bg-gray-100 text-gray-800',
    };
    return colors[status] || 'bg-gray-100 text-gray-800';
};

const chartData = computed(() => {
    return {
        jobTrends: props.analytics.jobTrends.map((item) => ({
            period: item.period,
            total: item.total,
            completed: item.completed,
            rate: item.completion_rate,
        })),
        statusDistribution: props.analytics.statusDistribution.map((item) => ({
            name: item.label,
            value: item.count,
            fill: getStatusColor(item.status),
        })),
        completionDistribution: Object.entries(
            props.analytics.completionTimes.distribution,
        ).map(([key, value]) => ({
            name: key,
            value: value,
        })),
    };
});
</script>

<template>
    <Head title="Analitik Kerja" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6 p-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight">
                        Analitik Kerja
                    </h1>
                    <p class="text-muted-foreground">
                        Pandangan keseluruhan prestasi bengkel dan metrik utama
                    </p>
                </div>

                <!-- Period Filter -->
                <div class="flex items-center gap-2">
                    <Calendar class="h-4 w-4 text-muted-foreground" />
                    <Select
                        :model-value="selectedPeriod"
                        @update:model-value="changePeriod"
                    >
                        <SelectTrigger class="w-48">
                            <SelectValue />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem
                                v-for="(
                                    label, value
                                ) in filters.availablePeriods"
                                :key="value"
                                :value="value"
                            >
                                {{ label }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                </div>
            </div>

            <!-- Overview Cards -->
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-5">
                <Card>
                    <CardHeader
                        class="flex flex-row items-center justify-between space-y-0 pb-2"
                    >
                        <CardTitle class="text-sm font-medium"
                            >Jumlah Kerja</CardTitle
                        >
                        <Activity class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">
                            {{ formatNumber(analytics.overview.total_jobs) }}
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader
                        class="flex flex-row items-center justify-between space-y-0 pb-2"
                    >
                        <CardTitle class="text-sm font-medium"
                            >Selesai</CardTitle
                        >
                        <CheckCircle class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">
                            {{
                                formatNumber(analytics.overview.completed_jobs)
                            }}
                        </div>
                        <p class="text-xs text-muted-foreground">
                            {{
                                formatPercentage(
                                    analytics.overview.completion_rate,
                                )
                            }}
                            kadar siap
                        </p>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader
                        class="flex flex-row items-center justify-between space-y-0 pb-2"
                    >
                        <CardTitle class="text-sm font-medium">Aktif</CardTitle>
                        <Clock class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">
                            {{ formatNumber(analytics.overview.active_jobs) }}
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader
                        class="flex flex-row items-center justify-between space-y-0 pb-2"
                    >
                        <CardTitle class="text-sm font-medium"
                            >Pelanggan</CardTitle
                        >
                        <Users class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">
                            {{
                                formatNumber(analytics.overview.total_customers)
                            }}
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader
                        class="flex flex-row items-center justify-between space-y-0 pb-2"
                    >
                        <CardTitle class="text-sm font-medium"
                            >Kadar Siap</CardTitle
                        >
                        <TrendingUp class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">
                            {{
                                formatPercentage(
                                    analytics.overview.completion_rate,
                                )
                            }}
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Main Content Tabs -->
            <Tabs default-value="trends" class="space-y-4">
                <TabsList>
                    <TabsTrigger value="trends">Trend</TabsTrigger>
                    <TabsTrigger value="status">Status</TabsTrigger>
                    <TabsTrigger value="customers">Pelanggan</TabsTrigger>
                    <TabsTrigger value="technicians">Juruteknik</TabsTrigger>
                    <TabsTrigger value="performance">Prestasi</TabsTrigger>
                </TabsList>

                <!-- Job Trends Tab -->
                <TabsContent value="trends" class="space-y-4">
                    <Card>
                        <CardHeader>
                            <CardTitle>Trend Kerja Mengikut Masa</CardTitle>
                            <CardDescription>
                                Jumlah kerja dan kadar siapan mengikut masa
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-4">
                                <div class="grid gap-4 md:grid-cols-2">
                                    <div class="space-y-2">
                                        <h4 class="text-sm font-medium">
                                            Jumlah Kerja
                                        </h4>
                                        <div class="space-y-1">
                                            <div
                                                v-for="item in chartData.jobTrends"
                                                :key="item.period"
                                                class="flex items-center justify-between text-sm"
                                            >
                                                <span
                                                    class="text-muted-foreground"
                                                    >{{ item.period }}</span
                                                >
                                                <span class="font-medium">{{
                                                    item.total
                                                }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <h4 class="text-sm font-medium">
                                            Kadar Siapan (%)
                                        </h4>
                                        <div class="space-y-1">
                                            <div
                                                v-for="item in chartData.jobTrends"
                                                :key="item.period"
                                                class="flex items-center justify-between text-sm"
                                            >
                                                <span
                                                    class="text-muted-foreground"
                                                    >{{ item.period }}</span
                                                >
                                                <span class="font-medium">{{
                                                    formatPercentage(item.rate)
                                                }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </TabsContent>

                <!-- Status Distribution Tab -->
                <TabsContent value="status" class="space-y-4">
                    <Card>
                        <CardHeader>
                            <CardTitle>Distribusi Status</CardTitle>
                            <CardDescription>
                                Jumlah kerja mengikut status semasa
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div
                                class="grid gap-4 md:grid-cols-2 lg:grid-cols-3"
                            >
                                <div
                                    v-for="status in analytics.statusDistribution"
                                    :key="status.status"
                                    class="flex items-center justify-between rounded-lg border p-3"
                                >
                                    <div class="flex items-center gap-2">
                                        <Badge
                                            :class="
                                                getStatusColor(status.status)
                                            "
                                        >
                                            {{ status.label }}
                                        </Badge>
                                    </div>
                                    <span class="font-bold">{{
                                        formatNumber(status.count)
                                    }}</span>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </TabsContent>

                <!-- Customer Stats Tab -->
                <TabsContent value="customers" class="space-y-4">
                    <div class="grid gap-4 md:grid-cols-2">
                        <Card>
                            <CardHeader>
                                <CardTitle>Jenis Pelanggan</CardTitle>
                                <CardDescription>
                                    Distribusi pelanggan mengikut jenis
                                </CardDescription>
                            </CardHeader>
                            <CardContent>
                                <div class="space-y-3">
                                    <div
                                        v-for="type in analytics.customerStats
                                            .types"
                                        :key="type.type"
                                        class="flex items-center justify-between"
                                    >
                                        <span class="text-sm font-medium">{{
                                            type.label
                                        }}</span>
                                        <Badge variant="secondary">{{
                                            formatNumber(type.count)
                                        }}</Badge>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>

                        <Card>
                            <CardHeader>
                                <CardTitle>Pelanggan Teratas</CardTitle>
                                <CardDescription>
                                    Pelanggan dengan jumlah kerja tertinggi
                                </CardDescription>
                            </CardHeader>
                            <CardContent>
                                <div class="space-y-3">
                                    <div
                                        v-for="(
                                            customer, index
                                        ) in analytics.customerStats.top_customers.slice(
                                            0,
                                            5,
                                        )"
                                        :key="customer.name"
                                        class="flex items-center justify-between"
                                    >
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="text-sm font-medium text-muted-foreground"
                                                >#{{ index + 1 }}</span
                                            >
                                            <span class="text-sm">{{
                                                customer.name
                                            }}</span>
                                        </div>
                                        <Badge variant="outline">{{
                                            formatNumber(customer.job_count)
                                        }}</Badge>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>
                    </div>
                </TabsContent>

                <!-- Technician Performance Tab -->
                <TabsContent value="technicians" class="space-y-4">
                    <Card>
                        <CardHeader>
                            <CardTitle>Prestasi Juruteknik</CardTitle>
                            <CardDescription>
                                Metrik prestasi untuk setiap juruteknik
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-4">
                                <div
                                    v-for="tech in analytics.technicianPerformance"
                                    :key="tech.technician_name"
                                    class="grid gap-4 rounded-lg border p-4 md:grid-cols-5"
                                >
                                    <div class="font-medium">
                                        {{ tech.technician_name }}
                                    </div>
                                    <div class="text-center">
                                        <div
                                            class="text-sm text-muted-foreground"
                                        >
                                            Jumlah Kerja
                                        </div>
                                        <div class="font-bold">
                                            {{ tech.total_jobs }}
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <div
                                            class="text-sm text-muted-foreground"
                                        >
                                            Selesai
                                        </div>
                                        <div class="font-bold">
                                            {{ tech.completed_jobs }}
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <div
                                            class="text-sm text-muted-foreground"
                                        >
                                            Kadar Siap
                                        </div>
                                        <div class="font-bold">
                                            {{
                                                formatPercentage(
                                                    tech.completion_rate,
                                                )
                                            }}
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <div
                                            class="text-sm text-muted-foreground"
                                        >
                                            Hari Rata-rata
                                        </div>
                                        <div class="font-bold">
                                            {{ tech.avg_completion_days }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </TabsContent>

                <!-- Performance Metrics Tab -->
                <TabsContent value="performance" class="space-y-4">
                    <div class="grid gap-4 md:grid-cols-2">
                        <Card>
                            <CardHeader>
                                <CardTitle>Masa Siapan</CardTitle>
                                <CardDescription>
                                    Analisis masa untuk menyelesaikan kerja
                                </CardDescription>
                            </CardHeader>
                            <CardContent>
                                <div class="space-y-4">
                                    <div
                                        class="grid gap-4 text-center md:grid-cols-3"
                                    >
                                        <div>
                                            <div class="text-2xl font-bold">
                                                {{
                                                    analytics.completionTimes
                                                        .average_days
                                                }}
                                            </div>
                                            <div
                                                class="text-sm text-muted-foreground"
                                            >
                                                Hari Rata-rata
                                            </div>
                                        </div>
                                        <div
                                            v-if="
                                                analytics.completionTimes
                                                    .fastest_job
                                            "
                                        >
                                            <div class="text-2xl font-bold">
                                                {{
                                                    analytics.completionTimes
                                                        .fastest_job.days
                                                }}
                                            </div>
                                            <div
                                                class="text-sm text-muted-foreground"
                                            >
                                                Terpantas
                                            </div>
                                        </div>
                                        <div
                                            v-if="
                                                analytics.completionTimes
                                                    .slowest_job
                                            "
                                        >
                                            <div class="text-2xl font-bold">
                                                {{
                                                    analytics.completionTimes
                                                        .slowest_job.days
                                                }}
                                            </div>
                                            <div
                                                class="text-sm text-muted-foreground"
                                            >
                                                Terlambat
                                            </div>
                                        </div>
                                    </div>

                                    <div class="space-y-2">
                                        <h4 class="text-sm font-medium">
                                            Distribusi Masa Siapan
                                        </h4>
                                        <div class="space-y-1">
                                            <div
                                                v-for="(
                                                    count, range
                                                ) in analytics.completionTimes
                                                    .distribution"
                                                :key="range"
                                                class="flex items-center justify-between text-sm"
                                            >
                                                <span
                                                    class="text-muted-foreground"
                                                    >{{ range }}</span
                                                >
                                                <div
                                                    class="flex items-center gap-2"
                                                >
                                                    <div
                                                        class="h-2 w-20 rounded-full bg-gray-200"
                                                    >
                                                        <div
                                                            class="h-2 rounded-full bg-blue-600"
                                                            :style="{
                                                                width: `${(count / Object.values(analytics.completionTimes.distribution).reduce((a, b) => a + b, 0)) * 100}%`,
                                                            }"
                                                        ></div>
                                                    </div>
                                                    <span
                                                        class="w-8 text-right font-medium"
                                                        >{{ count }}</span
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>

                        <Card>
                            <CardHeader>
                                <CardTitle
                                    >Hasil Terpantas & Terlambat</CardTitle
                                >
                                <CardDescription>
                                    Kerja yang disiapkan paling pantas dan
                                    paling lama
                                </CardDescription>
                            </CardHeader>
                            <CardContent>
                                <div class="space-y-4">
                                    <div
                                        v-if="
                                            analytics.completionTimes
                                                .fastest_job
                                        "
                                        class="rounded-lg border bg-green-50 p-3"
                                    >
                                        <h4 class="font-medium text-green-800">
                                            Terpantas
                                        </h4>
                                        <div class="text-sm text-green-700">
                                            <div>
                                                {{
                                                    analytics.completionTimes
                                                        .fastest_job.job_number
                                                }}
                                            </div>
                                            <div>
                                                {{
                                                    analytics.completionTimes
                                                        .fastest_job.title
                                                }}
                                            </div>
                                            <div class="font-bold">
                                                {{
                                                    analytics.completionTimes
                                                        .fastest_job.days
                                                }}
                                                hari
                                            </div>
                                        </div>
                                    </div>

                                    <div
                                        v-if="
                                            analytics.completionTimes
                                                .slowest_job
                                        "
                                        class="rounded-lg border bg-red-50 p-3"
                                    >
                                        <h4 class="font-medium text-red-800">
                                            Terlambat
                                        </h4>
                                        <div class="text-sm text-red-700">
                                            <div>
                                                {{
                                                    analytics.completionTimes
                                                        .slowest_job.job_number
                                                }}
                                            </div>
                                            <div>
                                                {{
                                                    analytics.completionTimes
                                                        .slowest_job.title
                                                }}
                                            </div>
                                            <div class="font-bold">
                                                {{
                                                    analytics.completionTimes
                                                        .slowest_job.days
                                                }}
                                                hari
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>
                    </div>
                </TabsContent>
            </Tabs>
        </div>
    </AppLayout>
</template>
