<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft, FileText, User, Calendar } from 'lucide-vue-next';
import type { WorkshopJob, JobStatusHistory } from '@/types';
import { format } from 'date-fns';
import JobController from '@/actions/App/Http/Controllers/JobController';

interface Props {
    job: WorkshopJob;
    history: JobStatusHistory[];
}

const props = defineProps<Props>();

const formatDate = (date: string) => {
    return format(new Date(date), 'dd MMM yyyy, h:mm a');
};
</script>

<template>
    <AppLayout>
        <Head :title="`Approval History - ${job.job_number}`" />

        <div class="mx-auto max-w-4xl space-y-6">
            <!-- Header -->
            <div class="flex items-center gap-4">
                <Button variant="ghost" size="icon" as-child>
                    <Link :href="JobController.show(job.id).url">
                        <ArrowLeft class="h-4 w-4" />
                    </Link>
                </Button>
                <div>
                    <h1 class="text-2xl font-bold tracking-tight">Approval History</h1>
                    <p class="text-muted-foreground">
                        History of approval decisions for Job {{ job.job_number }}
                    </p>
                </div>
            </div>

            <!-- Job Summary Card -->
            <Card>
                <CardHeader>
                    <CardTitle>Job Details</CardTitle>
                    <CardDescription>Snapshot of the job context</CardDescription>
                </CardHeader>
                <CardContent class="grid gap-4 md:grid-cols-2">
                    <div class="space-y-1">
                        <span class="text-sm font-medium text-muted-foreground">Title</span>
                        <p>{{ job.title }}</p>
                    </div>
                    <div class="space-y-1">
                        <span class="text-sm font-medium text-muted-foreground">Department</span>
                        <p>{{ job.kew_department_name || job.customer?.department || '-' }}</p>
                    </div>
                    <div class="space-y-1">
                        <span class="text-sm font-medium text-muted-foreground">Vehicle Registration</span>
                        <p class="font-mono">{{ job.kew_vehicle_registration || job.vehicle_registration || '-' }}</p>
                    </div>
                    <div class="space-y-1">
                        <span class="text-sm font-medium text-muted-foreground">Current Status</span>
                        <Badge variant="outline">{{ job.status }}</Badge>
                    </div>
                </CardContent>
            </Card>

            <!-- Timeline/History List -->
            <h2 class="text-lg font-semibold">Decisions Log</h2>
            
            <div class="relative space-y-8 pl-6 before:absolute before:left-0 before:top-2 before:h-full before:w-[2px] before:bg-muted">
                <div v-for="(event, index) in history" :key="event.id" class="relative">
                    <!-- Timeline Dot -->
                    <div class="absolute -left-[31px] top-1 flex h-6 w-6 items-center justify-center rounded-full border bg-background"
                        :class="event.to_status === 'inspection_approved' ? 'border-green-500' : 'border-red-500'">
                        <div class="h-2 w-2 rounded-full" 
                            :class="event.to_status === 'inspection_approved' ? 'bg-green-500' : 'bg-red-500'"></div>
                    </div>

                    <Card>
                        <CardHeader class="pb-2">
                            <div class="flex items-start justify-between">
                                <div class="space-y-1">
                                    <CardTitle class="text-base">
                                        {{ event.to_status === 'inspection_approved' ? 'Approved' : 'Rejected' }}
                                    </CardTitle>
                                    <CardDescription class="flex items-center gap-2">
                                        <User class="h-3 w-3" />
                                        <span>{{ event.user?.name || 'Unknown User' }}</span>
                                        <span>•</span>
                                        <Calendar class="h-3 w-3" />
                                        <span>{{ formatDate(event.changed_at) }}</span>
                                    </CardDescription>
                                </div>
                                <Badge :variant="event.to_status === 'inspection_approved' ? 'default' : 'destructive'">
                                    {{ event.to_status === 'inspection_approved' ? 'APPROVED' : 'REJECTED' }}
                                </Badge>
                            </div>
                        </CardHeader>
                        <CardContent>
                            <div class="rounded-md bg-muted p-3 text-sm italic text-muted-foreground">
                                "{{ event.comment || event.notes || 'No comments provided.' }}"
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <div v-if="history.length === 0" class="text-muted-foreground italic">
                    No approval history found for this job.
                </div>
            </div>
        </div>
    </AppLayout>
</template>
