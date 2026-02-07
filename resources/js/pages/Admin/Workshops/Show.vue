<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import SiteLayout from '@/layouts/app/SiteLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem, type Workshop as WorkshopType, type SiteRole } from '@/types';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { digitWorkshop } from '@/styles/digit-workshop-ui';
import { ArrowLeft, Edit, Power, Trash2, Briefcase, Users, BarChart3, UserPlus, Wallet, ClipboardList } from 'lucide-vue-next';
import { computed } from 'vue';
import { calculatePercentage, formatCompactCurrency, formatNumber } from '@/utils/currency';

interface Job {
    id: number;
    registration_no?: string;
    job_mode: string;
    status: string;
}

interface AssignedUser {
    id: number;
    name: string;
    email: string;
    pivot: {
        role: string;
    };
}

interface Workshop {
    id: number;
    name: string;
    code: string;
    phone?: string;
    email?: string;
    address?: string;
    is_active: boolean;
    jobs_count: number;
    customers_count: number;
    assigned_users_count?: number;
    company?: { name: string };
    jobs?: Job[];
    assignedUsers?: AssignedUser[];
}

interface DashboardStats {
    total_jobs: number;
    jobs_this_month: number;
    revenue_this_month: number;
    active_technicians: number;
    pending_actions: number;
}

interface TechWorkload {
    name: string;
    jobs: number;
}

const props = defineProps<{
    workshop: Workshop;
    site: WorkshopType;
    siteRole?: SiteRole;
    stats: DashboardStats;
    jobDistribution: Record<string, number>;
    techWorkload: TechWorkload[];
}>();

const toggleStatus = () => {
    router.post(`/admin/workshops/${props.workshop.id}/toggle-status`, {}, {
        preserveScroll: true,
    });
};

const deleteWorkshop = () => {
    if (confirm(`Are you sure you want to delete ${props.workshop.name}?`)) {
        router.delete(`/admin/workshops/${props.workshop.id}`);
    }
};

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Sites', href: '/admin/workshops' },
    { title: props.workshop.name, href: `/admin/workshops/${props.workshop.id}` },
];

const jobStatusItems = computed(() => {
    const entries = Object.entries(props.jobDistribution || {});
    return entries
        .map(([status, count]) => ({
            status,
            count,
            percentage: calculatePercentage(count, props.stats.total_jobs || 0, 1),
        }))
        .sort((a, b) => b.count - a.count);
});
</script>

<template>
    <Head :title="`Site: ${workshop.name}`" />

    <SiteLayout :breadcrumbs="breadcrumbs" :site="site" :site-role="siteRole">

        <div class="space-y-8">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <Button variant="ghost" size="icon" as-child :class="digitWorkshop.button.iconButton">
                    <Link href="/admin/workshops">
                        <ArrowLeft class="h-5 w-5" />
                    </Link>
                </Button>
                <div>
                    <h2 :class="digitWorkshop.typography.pageTitle">Workshop Details</h2>
                    <p :class="digitWorkshop.typography.pageSubtitle">View workshop information and statistics</p>
                </div>
            </div>
            <Button as-child :class="digitWorkshop.button.btnPrimary">
                <Link :href="`/admin/workshops/${workshop.id}/edit`">
                    <Edit class="mr-2 h-4 w-4" />
                    Edit Workshop
                </Link>
            </Button>
        </div>

        <!-- Site Dashboard Summary -->
        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
            <div class="space-y-6 xl:col-span-2">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
                    <Card :class="digitWorkshop.card.cardGradientHero">
                        <CardContent class="pt-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-medium text-[#6B6B6B]">Total Jobs</p>
                                    <p class="mt-2 text-3xl font-bold">{{ formatNumber(stats.total_jobs) }}</p>
                                </div>
                                <span :class="digitWorkshop.card.iconChipSoft + ' size-10'">
                                    <Briefcase class="h-5 w-5" />
                                </span>
                            </div>
                        </CardContent>
                    </Card>

                    <Card :class="digitWorkshop.card.cardGradientBlue">
                        <CardContent class="pt-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-medium text-[#6B6B6B]">Jobs This Month</p>
                                    <p class="mt-2 text-3xl font-bold">{{ formatNumber(stats.jobs_this_month) }}</p>
                                </div>
                                <span :class="digitWorkshop.card.iconChipBlue + ' size-10'">
                                    <BarChart3 class="h-5 w-5" />
                                </span>
                            </div>
                        </CardContent>
                    </Card>

                    <Card :class="digitWorkshop.card.cardGradientMint">
                        <CardContent class="pt-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-medium text-[#6B6B6B]">Collections This Month</p>
                                    <p class="mt-2 text-3xl font-bold">{{ formatCompactCurrency(stats.revenue_this_month) }}</p>
                                </div>
                                <span :class="digitWorkshop.card.iconChip + ' size-10'">
                                    <Wallet class="h-5 w-5" />
                                </span>
                            </div>
                        </CardContent>
                    </Card>

                    <Card :class="digitWorkshop.card.cardGradientYellow">
                        <CardContent class="pt-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-medium text-[#6B6B6B]">Pending Actions</p>
                                    <p class="mt-2 text-3xl font-bold">{{ formatNumber(stats.pending_actions) }}</p>
                                </div>
                                <span :class="digitWorkshop.card.iconChipYellow + ' size-10'">
                                    <ClipboardList class="h-5 w-5" />
                                </span>
                            </div>
                        </CardContent>
                    </Card>

                    <Card :class="digitWorkshop.card.cardGradientMint">
                        <CardContent class="pt-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-medium text-[#6B6B6B]">Active Technicians</p>
                                    <p class="mt-2 text-3xl font-bold">{{ formatNumber(stats.active_technicians) }}</p>
                                </div>
                                <span :class="digitWorkshop.card.iconChipSoft + ' size-10'">
                                    <Users class="h-5 w-5" />
                                </span>
                            </div>
                        </CardContent>
                    </Card>

                    <Card :class="digitWorkshop.card.cardGradientBlue">
                        <CardContent class="pt-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-medium text-[#6B6B6B]">Assigned Users</p>
                                    <p class="mt-2 text-3xl font-bold">{{ formatNumber(workshop.assigned_users_count || 0) }}</p>
                                </div>
                                <span :class="digitWorkshop.card.iconChipBlue + ' size-10'">
                                    <UserPlus class="h-5 w-5" />
                                </span>
                            </div>
                            <Button variant="link" size="sm" class="mt-2 px-0 text-[#2E6B35]" as-child>
                                <Link :href="`/admin/workshops/${workshop.id}/users`">
                                    Manage Users ->
                                </Link>
                            </Button>
                        </CardContent>
                    </Card>
                </div>
            </div>

            <Card class="xl:row-span-2" :class="digitWorkshop.card.cardBase">
                <CardHeader>
                    <CardTitle>Job Status</CardTitle>
                    <CardDescription>Status distribution for this site</CardDescription>
                </CardHeader>
                <CardContent>
                    <div v-if="jobStatusItems.length === 0" class="text-sm text-muted-foreground">
                        No job status data available.
                    </div>
                    <div v-else class="space-y-3">
                        <div
                            v-for="item in jobStatusItems"
                            :key="item.status"
                            class="flex items-center justify-between rounded-xl border border-[#E0E0E0] bg-white/70 px-3 py-2"
                        >
                            <div class="min-w-0">
                                <p class="text-sm font-medium capitalize">{{ item.status.replaceAll('_', ' ') }}</p>
                                <p class="text-xs text-muted-foreground">{{ item.percentage }}</p>
                            </div>
                            <Badge :class="digitWorkshop.badge.badgeMuted">{{ formatNumber(item.count) }}</Badge>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>

        <Card v-if="techWorkload && techWorkload.length > 0" :class="digitWorkshop.card.cardBase">
            <CardHeader>
                <CardTitle>Technician Workload</CardTitle>
                <CardDescription>Active jobs assigned to each technician</CardDescription>
            </CardHeader>
            <CardContent>
                <div class="grid grid-cols-1 gap-3 md:grid-cols-2 xl:grid-cols-3">
                    <div
                        v-for="tech in techWorkload"
                        :key="tech.name"
                        class="flex items-center justify-between rounded-xl border border-[#E0E0E0] bg-white/70 px-4 py-3"
                    >
                        <div>
                            <p class="font-medium">{{ tech.name }}</p>
                            <p class="text-xs text-muted-foreground">Active jobs</p>
                        </div>
                        <Badge :class="digitWorkshop.badge.badgeMuted">{{ formatNumber(tech.jobs) }}</Badge>
                    </div>
                </div>
            </CardContent>
        </Card>

        <!-- Workshop Info Card -->
        <Card :class="digitWorkshop.card.cardBase">
            <CardHeader>
                <div class="flex items-start justify-between">
                    <div>
                        <CardTitle class="text-2xl">{{ workshop.name }}</CardTitle>
                        <CardDescription class="mt-2">
                            <code class="px-2 py-1 bg-[#F2F2F2] rounded text-sm">{{ workshop.code }}</code>
                        </CardDescription>
                    </div>
                    <Badge :class="workshop.is_active ? digitWorkshop.badge.badgeSuccess : digitWorkshop.badge.badgeOutline" class="text-sm">
                        {{ workshop.is_active ? 'Active' : 'Inactive' }}
                    </Badge>
                </div>
            </CardHeader>
            <CardContent class="space-y-6">
                <!-- Details Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Company -->
                    <div v-if="workshop.company">
                        <h4 class="text-sm font-medium text-muted-foreground">Company</h4>
                        <p class="mt-1 text-base">{{ workshop.company.name }}</p>
                    </div>

                    <!-- Contact Info -->
                    <div v-if="workshop.phone || workshop.email">
                        <h4 class="text-sm font-medium text-muted-foreground">Contact</h4>
                        <div class="mt-1 space-y-1">
                            <p v-if="workshop.phone">📞 {{ workshop.phone }}</p>
                            <p v-if="workshop.email">✉️ {{ workshop.email }}</p>
                        </div>
                    </div>

                    <!-- Address -->
                    <div v-if="workshop.address" class="md:col-span-2">
                        <h4 class="text-sm font-medium text-muted-foreground">Address</h4>
                        <p class="mt-1 whitespace-pre-line">{{ workshop.address }}</p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-3 pt-6 border-t border-[#EDEDED]">
                    <Button
                        :variant="workshop.is_active ? 'outline' : 'default'"
                        @click="toggleStatus"
                        :class="workshop.is_active ? digitWorkshop.button.btnSecondary : digitWorkshop.button.btnPrimary"
                    >
                        <Power class="mr-2 h-4 w-4" />
                        {{ workshop.is_active ? 'Deactivate' : 'Activate' }}
                    </Button>
                    <Button variant="outline" @click="deleteWorkshop" class="border-[#F2D2D2] text-[#D14B4B] hover:bg-[#FBECEC]">
                        <Trash2 class="mr-2 h-4 w-4" />
                        Delete Workshop
                    </Button>
                </div>
            </CardContent>
        </Card>

        <!-- Quick Links -->
        <div class="flex gap-4">
            <Button variant="outline" as-child :class="digitWorkshop.button.btnSecondary">
                <Link :href="`/admin/workshops/${workshop.id}/users`">
                    <Users class="mr-2 h-4 w-4" />
                    Manage Users ->
                </Link>
            </Button>
            <Button variant="outline" as-child :class="digitWorkshop.button.btnSecondary">
                <Link :href="`/admin/workshops/${workshop.id}/analytics`">
                    <BarChart3 class="mr-2 h-4 w-4" />
                    View Analytics
                </Link>
            </Button>
        </div>

        <!-- Assigned Users List -->
        <Card v-if="workshop.assignedUsers && workshop.assignedUsers.length > 0" :class="digitWorkshop.card.cardBase">
            <CardHeader>
                <div class="flex items-center justify-between">
                    <div>
                        <CardTitle>Assigned Users</CardTitle>
                        <CardDescription>Users assigned to this workshop with their roles</CardDescription>
                    </div>
                    <Button size="sm" as-child :class="digitWorkshop.button.btnPrimary">
                        <Link :href="`/admin/workshops/${workshop.id}/users`">
                            <UserPlus class="mr-2 h-4 w-4" />
                            Assign User
                        </Link>
                    </Button>
                </div>
            </CardHeader>
            <CardContent>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div
                        v-for="user in workshop.assignedUsers"
                        :key="user.id"
                        class="p-4 border border-[#E0E0E0] rounded-2xl bg-white/70"
                    >
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-medium">{{ user.name }}</p>
                                <p class="text-sm text-muted-foreground">{{ user.email }}</p>
                            </div>
                            <Badge :class="digitWorkshop.badge.badgeMuted" class="capitalize">
                                {{ user.pivot.role }}
                            </Badge>
                        </div>
                    </div>
                </div>
            </CardContent>
        </Card>

        <!-- Recent Jobs -->
        <Card v-if="workshop.jobs && workshop.jobs.length > 0" :class="digitWorkshop.card.cardBase">
            <CardHeader>
                <CardTitle>Recent Jobs</CardTitle>
                <CardDescription>Latest jobs assigned to this workshop</CardDescription>
            </CardHeader>
            <CardContent>
                <div class="space-y-3">
                    <div
                        v-for="job in workshop.jobs"
                        :key="job.id"
                        class="p-4 border border-[#E0E0E0] rounded-2xl hover:bg-[#F8FCFC] transition-colors"
                    >
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-medium">{{ job.registration_no || 'N/A' }}</p>
                                <p class="text-sm text-muted-foreground">{{ job.job_mode }}</p>
                            </div>
                            <Badge :class="digitWorkshop.badge.badgeMuted">{{ job.status }}</Badge>
                        </div>
                    </div>
                </div>
            </CardContent>
        </Card>
    </div>
    </SiteLayout>
</template>
