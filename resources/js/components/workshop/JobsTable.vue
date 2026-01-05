<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import JobPriorityBadge from '@/components/workshop/JobPriorityBadge.vue';
import JobStatusBadge from '@/components/workshop/JobStatusBadge.vue';
import { edit, show } from '@/routes/jobs';
import type { WorkshopJob } from '@/types';
import { formatCurrency } from '@/utils/currency';
import { formatDate } from '@/utils/date';
import { Link } from '@inertiajs/vue3';
import { Edit, Eye, User } from 'lucide-vue-next';

interface Props {
    jobs: WorkshopJob[];
    canEdit?: boolean;
}

withDefaults(defineProps<Props>(), {
    canEdit: false,
});
</script>

<template>
    <div class="rounded-md border">
        <Table>
            <TableHeader>
                <TableRow>
                    <TableHead>Job Number</TableHead>
                    <TableHead>Customer</TableHead>
                    <TableHead>Description</TableHead>
                    <TableHead>Status</TableHead>
                    <TableHead>Priority</TableHead>
                    <TableHead>Assigned To</TableHead>
                    <TableHead>Expected Completion</TableHead>
                    <TableHead>Est. Cost</TableHead>
                    <TableHead class="text-right">Actions</TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                <TableRow v-if="jobs.length === 0">
                    <TableCell
                        colspan="9"
                        class="py-8 text-center text-muted-foreground"
                    >
                        No jobs found
                    </TableCell>
                </TableRow>
                <TableRow
                    v-for="job in jobs"
                    :key="job.id"
                    class="hover:bg-muted/50"
                >
                    <TableCell class="font-medium">
                        <Link :href="show.url(job.id)" class="hover:underline">
                            {{ job.job_number }}
                        </Link>
                    </TableCell>
                    <TableCell>{{ job.customer?.name || 'N/A' }}</TableCell>
                    <TableCell class="max-w-xs truncate">{{
                        job.description
                    }}</TableCell>
                    <TableCell>
                        <JobStatusBadge :status="job.status" />
                    </TableCell>
                    <TableCell>
                        <JobPriorityBadge :priority="job.priority" />
                    </TableCell>
                    <TableCell>
                        <div
                            v-if="job.assigned_technician"
                            class="flex items-center gap-2"
                        >
                            <User class="h-4 w-4 text-muted-foreground" />
                            <span class="text-sm">{{
                                job.assigned_technician.name
                            }}</span>
                        </div>
                        <span v-else class="text-sm text-muted-foreground"
                            >Unassigned</span
                        >
                    </TableCell>
                    <TableCell>{{
                        formatDate(job.expected_completion_date)
                    }}</TableCell>
                    <TableCell>{{
                        formatCurrency(job.estimated_cost)
                    }}</TableCell>
                    <TableCell class="text-right">
                        <div class="flex justify-end gap-1">
                            <Button variant="ghost" size="sm" as-child>
                                <Link :href="show.url(job.id)">
                                    <Eye class="h-4 w-4" />
                                </Link>
                            </Button>
                            <Button
                                v-if="canEdit"
                                variant="ghost"
                                size="sm"
                                as-child
                            >
                                <Link :href="edit.url(job.id)">
                                    <Edit class="h-4 w-4" />
                                </Link>
                            </Button>
                        </div>
                    </TableCell>
                </TableRow>
            </TableBody>
        </Table>
    </div>
</template>
