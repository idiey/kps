<script setup lang="ts">
import { computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import StatCard from '@/components/workshop/StatCard.vue';
import JobCard from '@/components/workshop/JobCard.vue';
import EmptyState from '@/components/EmptyState.vue';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import {
  Briefcase,
  Clock,
  AlertCircle,
  CheckCircle,
} from 'lucide-vue-next';
import type { WorkshopJob } from '@/types';

interface Props {
  jobs: WorkshopJob[];
  statistics: {
    total: number;
    in_progress: number;
    urgent: number;
    completed_this_week: number;
  };
}

const props = defineProps<Props>();

const activeJobs = computed(() =>
  props.jobs.filter(job => ['pending', 'in_progress', 'awaiting_parts'].includes(job.status))
);

const urgentJobs = computed(() =>
  props.jobs.filter(job => job.priority === 'urgent')
);

const completedJobs = computed(() =>
  props.jobs.filter(job => job.status === 'completed')
);
</script>

<template>
  <AppLayout>
    <Head title="My Jobs" />

    <div class="space-y-6">
      <div>
        <h1 class="text-3xl font-bold tracking-tight">My Jobs</h1>
        <p class="text-muted-foreground">
          View and manage your assigned jobs
        </p>
      </div>

      <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
        <StatCard
          title="Total Assigned"
          :value="statistics.total"
          :icon="Briefcase"
          description="All assigned jobs"
        />

        <StatCard
          title="In Progress"
          :value="statistics.in_progress"
          :icon="Clock"
          description="Currently working on"
        />

        <StatCard
          title="Urgent"
          :value="statistics.urgent"
          :icon="AlertCircle"
          description="High priority items"
        />

        <StatCard
          title="Completed This Week"
          :value="statistics.completed_this_week"
          :icon="CheckCircle"
          description="Finished this week"
        />
      </div>

      <div v-if="jobs.length === 0">
        <EmptyState
          title="No jobs assigned"
          description="You don't have any jobs assigned to you yet"
          :icon="Briefcase"
        />
      </div>

      <Tabs v-else default-value="active" class="space-y-4">
        <TabsList>
          <TabsTrigger value="active">
            Active ({{ activeJobs.length }})
          </TabsTrigger>
          <TabsTrigger value="urgent">
            Urgent ({{ urgentJobs.length }})
          </TabsTrigger>
          <TabsTrigger value="completed">
            Completed ({{ completedJobs.length }})
          </TabsTrigger>
          <TabsTrigger value="all">
            All ({{ jobs.length }})
          </TabsTrigger>
        </TabsList>

        <TabsContent value="active" class="space-y-4">
          <div v-if="activeJobs.length === 0">
            <EmptyState
              title="No active jobs"
              description="You don't have any active jobs at the moment"
            />
          </div>
          <div v-else class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            <JobCard v-for="job in activeJobs" :key="job.id" :job="job" />
          </div>
        </TabsContent>

        <TabsContent value="urgent" class="space-y-4">
          <div v-if="urgentJobs.length === 0">
            <EmptyState
              title="No urgent jobs"
              description="You don't have any urgent jobs at the moment"
            />
          </div>
          <div v-else class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            <JobCard v-for="job in urgentJobs" :key="job.id" :job="job" />
          </div>
        </TabsContent>

        <TabsContent value="completed" class="space-y-4">
          <div v-if="completedJobs.length === 0">
            <EmptyState
              title="No completed jobs"
              description="You haven't completed any jobs yet"
            />
          </div>
          <div v-else class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            <JobCard v-for="job in completedJobs" :key="job.id" :job="job" />
          </div>
        </TabsContent>

        <TabsContent value="all" class="space-y-4">
          <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            <JobCard v-for="job in jobs" :key="job.id" :job="job" />
          </div>
        </TabsContent>
      </Tabs>
    </div>
  </AppLayout>
</template>
