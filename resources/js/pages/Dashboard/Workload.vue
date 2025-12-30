<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import StatCard from '@/components/workshop/StatCard.vue';
import WorkloadCard from '@/components/workshop/WorkloadCard.vue';
import EmptyState from '@/components/EmptyState.vue';
import {
  Briefcase,
  Clock,
  CheckCircle,
  AlertCircle,
  Users,
} from 'lucide-vue-next';
import type { User } from '@/types';

interface TechnicianWorkload extends User {
  jobs_count?: number;
  active_jobs?: number;
  urgent_jobs?: number;
}

interface DashboardStatistics {
  total_jobs: number;
  active_jobs: number;
  completed_jobs: number;
  urgent_jobs: number;
}

interface Props {
  technicians: TechnicianWorkload[];
  statistics: DashboardStatistics;
}

defineProps<Props>();
</script>

<template>
  <AppLayout>
    <Head title="Workload Dashboard" />

    <div class="space-y-6">
      <div>
        <h1 class="text-3xl font-bold tracking-tight">Workload Dashboard</h1>
        <p class="text-muted-foreground">
          Monitor technician workload and job distribution
        </p>
      </div>

      <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
        <StatCard
          title="Total Jobs"
          :value="statistics.total_jobs"
          :icon="Briefcase"
          description="All jobs in the system"
        />

        <StatCard
          title="Active Jobs"
          :value="statistics.active_jobs"
          :icon="Clock"
          description="Currently in progress"
        />

        <StatCard
          title="Completed"
          :value="statistics.completed_jobs"
          :icon="CheckCircle"
          description="Successfully finished"
        />

        <StatCard
          title="Urgent"
          :value="statistics.urgent_jobs"
          :icon="AlertCircle"
          description="Require immediate attention"
        />
      </div>

      <div>
        <h2 class="text-2xl font-bold tracking-tight mb-4">Technician Workload</h2>

        <div v-if="technicians.length === 0">
          <EmptyState
            title="No technicians assigned"
            description="No technicians are currently working on jobs"
            :icon="Users"
          />
        </div>

        <div v-else class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
          <WorkloadCard
            v-for="technician in technicians"
            :key="technician.id"
            :technician="technician"
          />
        </div>
      </div>
    </div>
  </AppLayout>
</template>
