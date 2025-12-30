<script setup lang="ts">
import { computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import JobStatusBadge from '@/components/workshop/JobStatusBadge.vue';
import JobPriorityBadge from '@/components/workshop/JobPriorityBadge.vue';
import JobStatusTransition from '@/components/workshop/JobStatusTransition.vue';
import JobNoteForm from '@/components/workshop/JobNoteForm.vue';
import JobNotesList from '@/components/workshop/JobNotesList.vue';
import TechnicianSelect from '@/components/workshop/TechnicianSelect.vue';
import AssignmentHistory from '@/components/workshop/AssignmentHistory.vue';
import TimelineView from '@/components/workshop/TimelineView.vue';
import { formatDate, formatDateTime } from '@/utils/date';
import { formatCurrency } from '@/utils/currency';
import { useConfirmDialog } from '@/composables/useConfirmDialog';
import { useToast } from '@/composables/useToast';
import {
  ArrowLeft,
  Edit,
  Trash2,
  Calendar,
  DollarSign,
  FileText,
  User,
  MapPin,
} from 'lucide-vue-next';
import type { WorkshopJob, JobNote, JobAssignment, JobStatusHistory, User as UserType } from '@/types';

interface Props {
  job: WorkshopJob;
  notes: JobNote[];
  assignments: JobAssignment[];
  statusHistory: JobStatusHistory[];
  technicians?: UserType[];
  canEdit?: boolean;
  canDelete?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  canEdit: false,
  canDelete: false,
});

const { confirm } = useConfirmDialog();
const { success, error } = useToast();

const deleteJob = async () => {
  const confirmed = await confirm({
    title: 'Delete Job',
    description: 'Are you sure you want to delete this job? This action cannot be undone.',
    confirmText: 'Delete',
    cancelText: 'Cancel',
    variant: 'destructive',
  });

  if (confirmed) {
    router.delete(route('jobs.destroy', props.job.id), {
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
  const activeAssignment = props.assignments.find((a) => !a.unassigned_at);
  return activeAssignment?.technician?.id;
});
</script>

<template>
  <AppLayout>
    <Head :title="`Job ${job.job_number}`" />

    <div class="space-y-6">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
          <Button variant="outline" size="sm" as-child>
            <Link :href="route('jobs.index')">
              <ArrowLeft class="h-4 w-4 mr-2" />
              Back
            </Link>
          </Button>
          <div>
            <h1 class="text-3xl font-bold tracking-tight">{{ job.job_number }}</h1>
            <p class="text-muted-foreground">Job Details</p>
          </div>
        </div>

        <div class="flex items-center gap-2">
          <Button v-if="canEdit" variant="outline" as-child>
            <Link :href="route('jobs.edit', job.id)">
              <Edit class="h-4 w-4 mr-2" />
              Edit
            </Link>
          </Button>
          <Button v-if="canDelete" variant="destructive" @click="deleteJob">
            <Trash2 class="h-4 w-4 mr-2" />
            Delete
          </Button>
        </div>
      </div>

      <div class="grid gap-6 md:grid-cols-3">
        <div class="md:col-span-2 space-y-6">
          <Card>
            <CardHeader>
              <div class="flex items-start justify-between">
                <CardTitle>Job Information</CardTitle>
                <div class="flex gap-2">
                  <JobStatusBadge :status="job.status" />
                  <JobPriorityBadge :priority="job.priority" show-icon />
                </div>
              </div>
            </CardHeader>
            <CardContent class="space-y-4">
              <div class="grid gap-4 sm:grid-cols-2">
                <div>
                  <p class="text-sm font-medium text-muted-foreground">Customer</p>
                  <p class="font-medium">{{ job.customer?.name || 'N/A' }}</p>
                </div>
                <div>
                  <p class="text-sm font-medium text-muted-foreground">Contact</p>
                  <p>{{ job.customer?.email }}</p>
                  <p>{{ job.customer?.phone }}</p>
                </div>
              </div>

              <div>
                <p class="text-sm font-medium text-muted-foreground mb-1">Description</p>
                <p class="whitespace-pre-wrap">{{ job.description }}</p>
              </div>

              <div class="grid gap-4 sm:grid-cols-2">
                <div class="flex items-center gap-2">
                  <Calendar class="h-4 w-4 text-muted-foreground" />
                  <div>
                    <p class="text-sm font-medium text-muted-foreground">Expected Completion</p>
                    <p>{{ formatDate(job.expected_completion_date) }}</p>
                  </div>
                </div>
                <div class="flex items-center gap-2">
                  <DollarSign class="h-4 w-4 text-muted-foreground" />
                  <div>
                    <p class="text-sm font-medium text-muted-foreground">Estimated Cost</p>
                    <p class="font-medium">{{ formatCurrency(job.estimated_cost) }}</p>
                  </div>
                </div>
              </div>

              <div v-if="job.location" class="flex items-start gap-2">
                <MapPin class="h-4 w-4 text-muted-foreground mt-1" />
                <div>
                  <p class="text-sm font-medium text-muted-foreground">Location</p>
                  <p>{{ job.location }}</p>
                </div>
              </div>

              <div class="pt-4 border-t text-sm text-muted-foreground">
                Created {{ formatDateTime(job.created_at) }}
                <span v-if="job.updated_at !== job.created_at">
                  • Updated {{ formatDateTime(job.updated_at) }}
                </span>
              </div>
            </CardContent>
          </Card>

          <Tabs default-value="notes" class="space-y-4">
            <TabsList class="grid w-full grid-cols-3">
              <TabsTrigger value="notes">Notes</TabsTrigger>
              <TabsTrigger value="assignments">Assignments</TabsTrigger>
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

              <JobNotesList :job="job" :notes="notes" :can-edit="canEdit" />
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
          <Card>
            <CardHeader>
              <CardTitle>Status</CardTitle>
            </CardHeader>
            <CardContent>
              <JobStatusTransition :job="job" />
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
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-primary/10">
                  <User class="h-5 w-5 text-primary" />
                </div>
                <div>
                  <p class="font-medium">{{ job.assigned_technician.name }}</p>
                  <p class="text-sm text-muted-foreground">{{ job.assigned_technician.email }}</p>
                </div>
              </div>
            </CardContent>
          </Card>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
