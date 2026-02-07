<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import SiteLayout from '@/layouts/app/SiteLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem, type Workshop as WorkshopType, type SiteRole } from '@/types';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { ArrowLeft, Briefcase, Users, TrendingUp, CheckCircle, Clock } from 'lucide-vue-next';
import { ref, computed } from 'vue';

interface Workshop {
    id: number;
    name: string;
    company?: { name: string };
}

interface Stats {
    jobs: {
        total: number;
        completed: number;
        in_progress: number;
        period_count: number;
    };
    customers: {
        total: number;
        new_period: number;
    };
    users: {
        total_assigned: number;
        by_role: {
            supervisor: number;
            technician: number;
            staff: number;
        };
    };
    jobsByStatus: Record<string, number>;
    jobsTrend: Record<string, number>;
}

const props = defineProps<{
    workshop: Workshop;
    period: string;
    stats: Stats;
    site: WorkshopType;
    siteRole?: SiteRole;
}>();

const selectedPeriod = ref(props.period);

const periodLabels: Record<string, string> = {
    week: 'Last 7 Days',
    month: 'Last 30 Days',
    quarter: 'Last Quarter',
    year: 'Last Year',
};

const completionRate = computed(() => {
    if (props.stats.jobs.total === 0) return 0;
    return Math.round((props.stats.jobs.completed / props.stats.jobs.total) * 100);
});

const changePeriod = (period: string | null) => {
    if (period) {
        window.location.href = `/admin/workshops/${props.workshop.id}/analytics?period=${period}`;
    }
};

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Workshops', href: '/admin/workshops' },
    { title: props.workshop.name, href: `/admin/workshops/${props.workshop.id}` },
    { title: 'Analytics', href: `/admin/workshops/${props.workshop.id}/analytics` },
];
</script>

<template>
    <Head :title="`${workshop.name} - Analytics`" />

    <SiteLayout :breadcrumbs="breadcrumbs" :site="site" :site-role="siteRole">
        <div class="space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Button variant="ghost" size="icon" as-child>
                        <Link :href="`/admin/workshops/${workshop.id}`">
                            <ArrowLeft class="h-5 w-5" />
                        </Link>
                    </Button>
                    <div>
                        <h2 class="text-2xl font-bold tracking-tight">Analytics</h2>
                        <p class="text-muted-foreground">Performance metrics for {{ workshop.name }}</p>
                    </div>
                </div>
                <Select :model-value="selectedPeriod" @update:model-value="(val) => changePeriod(val as string | null)">
                    <SelectTrigger class="w-48">
                        <SelectValue />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem value="week">Last 7 Days</SelectItem>
                        <SelectItem value="month">Last 30 Days</SelectItem>
                        <SelectItem value="quarter">Last Quarter</SelectItem>
                        <SelectItem value="year">Last Year</SelectItem>
                    </SelectContent>
                </Select>
            </div>

            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Jobs -->
                <Card>
                    <CardContent class="pt-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-muted-foreground">Total Jobs</p>
                                <p class="mt-2 text-3xl font-bold">{{ stats.jobs.total }}</p>
                                <p class="text-xs text-muted-foreground mt-1">
                                    +{{ stats.jobs.period_count }} this period
                                </p>
                            </div>
                            <div class="p-3 bg-primary/10 rounded-full">
                                <Briefcase class="h-6 w-6 text-primary" />
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Completed Jobs -->
                <Card>
                    <CardContent class="pt-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-muted-foreground">Completed</p>
                                <p class="mt-2 text-3xl font-bold">{{ stats.jobs.completed }}</p>
                                <p class="text-xs text-muted-foreground mt-1">
                                    {{ completionRate }}% completion rate
                                </p>
                            </div>
                            <div class="p-3 bg-green-100 dark:bg-green-900/20 rounded-full">
                                <CheckCircle class="h-6 w-6 text-green-600" />
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- In Progress -->
                <Card>
                    <CardContent class="pt-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-muted-foreground">In Progress</p>
                                <p class="mt-2 text-3xl font-bold">{{ stats.jobs.in_progress }}</p>
                                <p class="text-xs text-muted-foreground mt-1">
                                    Active work
                                </p>
                            </div>
                            <div class="p-3 bg-yellow-100 dark:bg-yellow-900/20 rounded-full">
                                <Clock class="h-6 w-6 text-yellow-600" />
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Customers -->
                <Card>
                    <CardContent class="pt-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-muted-foreground">Customers</p>
                                <p class="mt-2 text-3xl font-bold">{{ stats.customers.total }}</p>
                                <p class="text-xs text-muted-foreground mt-1">
                                    +{{ stats.customers.new_period }} new
                                </p>
                            </div>
                            <div class="p-3 bg-blue-100 dark:bg-blue-900/20 rounded-full">
                                <Users class="h-6 w-6 text-blue-600" />
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Team & Status Distribution -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Team Stats -->
                <Card>
                    <CardHeader>
                        <CardTitle>Team Distribution</CardTitle>
                        <CardDescription>Users assigned by role</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm">Supervisors</span>
                                <Badge variant="secondary">{{ stats.users.by_role.supervisor }}</Badge>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm">Technicians</span>
                                <Badge variant="secondary">{{ stats.users.by_role.technician }}</Badge>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm">Staff</span>
                                <Badge variant="secondary">{{ stats.users.by_role.staff }}</Badge>
                            </div>
                            <div class="pt-4 border-t flex items-center justify-between font-medium">
                                <span>Total Assigned</span>
                                <span>{{ stats.users.total_assigned }}</span>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Jobs by Status -->
                <Card>
                    <CardHeader>
                        <CardTitle>Jobs by Status</CardTitle>
                        <CardDescription>Current status distribution</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-4">
                            <div 
                                v-for="(count, status) in stats.jobsByStatus" 
                                :key="status"
                                class="flex items-center justify-between"
                            >
                                <span class="text-sm capitalize">{{ status.replace('_', ' ') }}</span>
                                <Badge variant="outline">{{ count }}</Badge>
                            </div>
                            <div v-if="Object.keys(stats.jobsByStatus).length === 0" class="text-center py-4 text-muted-foreground">
                                No job data available
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Jobs Trend -->
            <Card v-if="Object.keys(stats.jobsTrend).length > 0">
                <CardHeader>
                    <CardTitle>Jobs Trend</CardTitle>
                    <CardDescription>New jobs over {{ periodLabels[period] }}</CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="flex items-end gap-2 h-32">
                        <div 
                            v-for="(count, key) in stats.jobsTrend" 
                            :key="key"
                            class="flex-1 bg-primary/20 rounded-t flex items-end justify-center"
                            :style="{ height: `${Math.max(count * 10, 10)}%` }"
                        >
                            <span class="text-xs font-medium pb-1">{{ count }}</span>
                        </div>
                    </div>
                    <div class="flex gap-2 mt-2">
                        <div 
                            v-for="(_, key) in stats.jobsTrend" 
                            :key="key"
                            class="flex-1 text-center"
                        >
                            <span class="text-xs text-muted-foreground">{{ key }}</span>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </SiteLayout>
</template>
