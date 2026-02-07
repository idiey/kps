<script setup lang="ts">
import EmptyState from '@/components/EmptyState.vue';
import JobsTable from '@/components/workshop/JobsTable.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { WorkshopJob } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { CheckCircle2, AlertCircle, Clock } from 'lucide-vue-next';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';

interface Props {
    pendingApprovals: WorkshopJob[];
    statistics: {
        total_pending: number;
        approved_this_month: number;
        rejected_this_month: number;
    };
}

const props = defineProps<Props>();
</script>

<template>
    <AppLayout>
        <Head title="Pending Approvals" />

        <div class="space-y-8">
            <div>
                <h1 class="text-3xl font-bold tracking-tight">KEW.PA-10 Approvals</h1>
                <p class="text-muted-foreground">
                    Review and approve vehicle inspection reports
                </p>
            </div>

            <!-- Statistics Cards -->
            <div class="grid gap-4 md:grid-cols-3">
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Pending Review</CardTitle>
                        <Clock class="h-4 w-4 text-orange-500" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ statistics.total_pending }}</div>
                        <p class="text-xs text-muted-foreground">Jobs awaiting your approval</p>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Approved This Month</CardTitle>
                        <CheckCircle2 class="h-4 w-4 text-green-500" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ statistics.approved_this_month }}</div>
                        <p class="text-xs text-muted-foreground">Successfully processed</p>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Rejected This Month</CardTitle>
                        <AlertCircle class="h-4 w-4 text-red-500" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ statistics.rejected_this_month }}</div>
                        <p class="text-xs text-muted-foreground">Returned for correction</p>
                    </CardContent>
                </Card>
            </div>

            <!-- Pending Jobs List -->
            <div v-if="pendingApprovals.length === 0">
                <EmptyState
                    title="All caught up!"
                    description="There are no pending approvals at the moment."
                    :icon="CheckCircle2"
                />
            </div>

            <div v-else class="space-y-4">
                <h2 class="text-xl font-semibold">Pending Requests</h2>
                <div class="rounded-md border bg-card">
                    <!-- Reuse JobsTable for consistency, but we might want custom columns later -->
                    <JobsTable :jobs="pendingApprovals" :can-edit="true" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
