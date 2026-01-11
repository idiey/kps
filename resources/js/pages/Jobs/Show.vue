<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import AssignmentHistory from '@/components/workshop/AssignmentHistory.vue';
import JobNoteForm from '@/components/workshop/JobNoteForm.vue';
import JobNotesList from '@/components/workshop/JobNotesList.vue';
import JobPriorityBadge from '@/components/workshop/JobPriorityBadge.vue';
import JobStatusBadge from '@/components/workshop/JobStatusBadge.vue';
import JobStatusTransition from '@/components/workshop/JobStatusTransition.vue';
import TechnicianSelect from '@/components/workshop/TechnicianSelect.vue';
import TimelineView from '@/components/workshop/TimelineView.vue';
import DynamicJobForm from '@/components/workshop/DynamicJobForm.vue';
import WorkflowTemplatesDisplay from '@/components/workshop/WorkflowTemplatesDisplay.vue';
import { useConfirmDialog } from '@/composables/useConfirmDialog';
import { useToast } from '@/composables/useToast';
import AppLayout from '@/layouts/AppLayout.vue';
import { destroy, edit, index } from '@/routes/jobs';
import type {
    JobAssignment,
    JobNote,
    JobStatusHistory,
    User as UserType,
    WorkshopJob,
} from '@/types';
import { formatCurrency } from '@/utils/currency';
import { formatDate, formatDateTime } from '@/utils/date';
import { Head, Link, router } from '@inertiajs/vue3';
import {
    ArrowLeft,
    Calendar,
    DollarSign,
    Edit,
    MapPin,
    Trash2,
    User,
} from 'lucide-vue-next';
import { computed, ref } from 'vue';

interface Props {
    job: WorkshopJob;
    notes: JobNote[];
    assignments: JobAssignment[];
    statusHistory: JobStatusHistory[];
    technicians?: UserType[];
    canEdit?: boolean;
    canDelete?: boolean;
    dynamicData?: any;
}

const props = withDefaults(defineProps<Props>(), {
    canEdit: false,
    canDelete: false,
});

const { confirm } = useConfirmDialog();
const { success, error } = useToast();

// Dynamic form data
const formData = ref<Record<string, any>>({});

// Check if there's an active status form
const hasActiveForm = computed(() => {
    return props.dynamicData?.active_status_form?.fields_by_section;
});

// Flatten fields for easier rendering
const activeFormFields = computed(() => {
    if (!hasActiveForm.value) return [];
    const sections = props.dynamicData.active_status_form.fields_by_section;
    return Object.values(sections).flat();
});

const deleteJob = async () => {
    const confirmed = await confirm({
        title: 'Delete Job',
        description:
            'Are you sure you want to delete this job? This action cannot be undone.',
        confirmText: 'Delete',
        cancelText: 'Cancel',
        variant: 'destructive',
    });

    if (confirmed) {
        router.delete(destroy.url(props.job.id), {
            onSuccess: () => {
                success('Job Deleted', 'Job has been deleted successfully.');
            },
            onError: () => {
                error('Delete Failed', 'Failed to delete job.');
            },
        });
    }
};

const currentTechnicianId = computed(() => {
    const activeAssignment = props.assignments.find((a) => a.is_current);
    // Cast to any to handle runtime type mismatch where assigned_to might be an object
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    const assigned = (activeAssignment as any)?.assigned_to;
    return typeof assigned === 'object' ? assigned?.id : assigned;
});
</script>

<template>
    <AppLayout>
        <Head :title="`Job ${job.job_number}`" />

        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Button variant="outline" size="sm" as-child>
                        <Link :href="index.url()">
                            <ArrowLeft class="mr-2 h-4 w-4" />
                            Back
                        </Link>
                    </Button>
                    <div>
                        <h1 class="text-3xl font-bold tracking-tight">
                            {{ job.job_number }}
                        </h1>
                        <p class="text-muted-foreground">Job Details</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <Button v-if="canEdit" variant="outline" as-child>
                        <Link :href="edit.url(job.id)">
                            <Edit class="mr-2 h-4 w-4" />
                            Edit Job
                        </Link>
                    </Button>
                    <Button
                        v-if="canDelete"
                        variant="destructive"
                        @click="deleteJob"
                    >
                        <Trash2 class="mr-2 h-4 w-4" />
                        Delete
                    </Button>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-3">
                <div class="space-y-6 md:col-span-2">
                    <Card>
                        <CardHeader>
                            <div class="flex items-start justify-between">
                                <CardTitle>Job Information</CardTitle>
                                <div class="flex gap-2">
                                    <JobStatusBadge :status="job.status" />
                                    <JobPriorityBadge
                                        :priority="job.priority"
                                        show-icon
                                    />
                                </div>
                            </div>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <p
                                        class="text-sm font-medium text-muted-foreground"
                                    >
                                        Customer
                                    </p>
                                    <p class="font-medium">
                                        {{ job.customer?.name || 'N/A' }}
                                    </p>
                                </div>
                                <div>
                                    <p
                                        class="text-sm font-medium text-muted-foreground"
                                    >
                                        Contact
                                    </p>
                                    <p>{{ job.customer?.email }}</p>
                                    <p>{{ job.customer?.phone }}</p>
                                </div>
                            </div>

                            <div>
                                <p
                                    class="mb-1 text-sm font-medium text-muted-foreground"
                                >
                                    Description
                                </p>
                                <p class="whitespace-pre-wrap">
                                    {{ job.description }}
                                </p>
                            </div>

                            <div class="grid gap-4 sm:grid-cols-2">
                                <div class="flex items-center gap-2">
                                    <Calendar
                                        class="h-4 w-4 text-muted-foreground"
                                    />
                                    <div>
                                        <p
                                            class="text-sm font-medium text-muted-foreground"
                                        >
                                            Expected Completion
                                        </p>
                                        <p>
                                            {{
                                                formatDate(
                                                    job.expected_completion_date,
                                                )
                                            }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <DollarSign
                                        class="h-4 w-4 text-muted-foreground"
                                    />
                                    <div>
                                        <p
                                            class="text-sm font-medium text-muted-foreground"
                                        >
                                            Estimated Cost
                                        </p>
                                        <p class="font-medium">
                                            {{
                                                formatCurrency(
                                                    job.estimated_cost,
                                                )
                                            }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div
                                v-if="job.location"
                                class="flex items-start gap-2"
                            >
                                <MapPin
                                    class="mt-1 h-4 w-4 text-muted-foreground"
                                />
                                <div>
                                    <p
                                        class="text-sm font-medium text-muted-foreground"
                                    >
                                        Location
                                    </p>
                                    <p>{{ job.location }}</p>
                                </div>
                            </div>

                            <div
                                class="border-t pt-4 text-sm text-muted-foreground"
                            >
                                Created {{ formatDateTime(job.created_at) }}
                                <span v-if="job.updated_at !== job.created_at">
                                    • Updated
                                    {{ formatDateTime(job.updated_at) }}
                                </span>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Workflow Templates Display -->
                    <WorkflowTemplatesDisplay
                        v-if="dynamicData?.workflow_templates"
                        :templates="dynamicData.workflow_templates"
                    />

                    <Tabs default-value="notes" class="space-y-4">
                        <TabsList class="grid w-full grid-cols-3">
                            <TabsTrigger value="notes">Notes</TabsTrigger>
                            <TabsTrigger value="assignments"
                                >Assignments</TabsTrigger
                            >
                            <TabsTrigger value="timeline">Timeline</TabsTrigger>
                        </TabsList>

                        <TabsContent value="notes" class="space-y-4">
                            <Card>
                                <CardHeader>
                                    <CardTitle>Add Note</CardTitle>
                                </CardHeader>
                                <CardContent>
                                    <JobNoteForm :job="job" />
                                </CardContent>
                            </Card>

                            <JobNotesList
                                :job="job"
                                :notes="notes"
                                :can-edit="canEdit"
                            />
                        </TabsContent>

                        <TabsContent value="assignments" class="space-y-4">
                            <AssignmentHistory :assignments="assignments" />
                        </TabsContent>

                        <TabsContent value="timeline" class="space-y-4">
                            <TimelineView
                                :status-history="statusHistory"
                                :notes="notes"
                                :assignments="assignments"
                            />
                        </TabsContent>
                    </Tabs>
                </div>

                <div class="space-y-6">
                    <!-- Required Form for Current Status -->
                    <Card v-if="hasActiveForm">
                        <CardHeader>
                            <CardTitle>{{ dynamicData.active_status_form.name }}</CardTitle>
                            <p class="text-sm text-muted-foreground mt-1">
                                {{ dynamicData.active_status_form.description || 'Complete this form to proceed with workflow transitions.' }}
                            </p>
                        </CardHeader>
                        <CardContent>
                            <DynamicJobForm
                                v-model="formData"
                                :fields="activeFormFields"
                                :readonly="!canEdit"
                            />
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader>
                            <CardTitle>Status</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <JobStatusTransition :job="job" :form-data="formData" />
                        </CardContent>
                    </Card>

                    <Card v-if="technicians">
                        <CardHeader>
                            <CardTitle>Assignment</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <TechnicianSelect
                                :job="job"
                                :technicians="technicians"
                                :current-technician-id="currentTechnicianId"
                            />
                        </CardContent>
                    </Card>

                    <Card v-if="job.assigned_technician">
                        <CardHeader>
                            <CardTitle>Current Technician</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="flex items-center gap-3">
                                <div
                                    class="flex h-10 w-10 items-center justify-center rounded-full bg-primary/10"
                                >
                                    <User class="h-5 w-5 text-primary" />
                                </div>
                                <div>
                                    <p class="font-medium">
                                        {{ job.assigned_technician.name }}
                                    </p>
                                    <p class="text-sm text-muted-foreground">
                                        {{ job.assigned_technician.email }}
                                    </p>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
