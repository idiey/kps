<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import JobStatusBadge from '@/components/workshop/JobStatusBadge.vue';
import JobPriorityBadge from '@/components/workshop/JobPriorityBadge.vue';
import { formatDate, formatRelative } from '@/utils/date';
import { formatCurrency } from '@/utils/currency';
import { Calendar, User, DollarSign } from 'lucide-vue-next';
import type { WorkshopJob } from '@/types';

interface Props {
  job: WorkshopJob;
}

defineProps<Props>();
</script>

<template>
  <Card class="hover:shadow-md transition-shadow">
    <CardHeader class="pb-3">
      <div class="flex items-start justify-between">
        <div class="flex-1">
          <Link :href="route('jobs.show', job.id)" class="hover:underline">
            <CardTitle class="text-base">{{ job.job_number }}</CardTitle>
          </Link>
          <p class="text-sm text-muted-foreground mt-1">
            {{ job.description }}
          </p>
        </div>
        <div class="flex flex-col items-end gap-2 ml-4">
          <JobStatusBadge :status="job.status" />
          <JobPriorityBadge :priority="job.priority" />
        </div>
      </div>
    </CardHeader>
    <CardContent>
      <div class="space-y-2 text-sm">
        <div class="flex items-center gap-2 text-muted-foreground">
          <User class="h-4 w-4" />
          <span>{{ job.customer?.name || 'N/A' }}</span>
        </div>

        <div class="flex items-center gap-2 text-muted-foreground">
          <Calendar class="h-4 w-4" />
          <span>{{ formatDate(job.expected_completion_date) }}</span>
          <span v-if="job.expected_completion_date" class="text-xs">
            ({{ formatRelative(job.expected_completion_date) }})
          </span>
        </div>

        <div v-if="job.estimated_cost" class="flex items-center gap-2 text-muted-foreground">
          <DollarSign class="h-4 w-4" />
          <span>{{ formatCurrency(job.estimated_cost) }}</span>
        </div>

        <div v-if="job.assigned_technician" class="pt-2 border-t">
          <div class="flex items-center gap-2">
            <div class="flex h-6 w-6 items-center justify-center rounded-full bg-primary/10">
              <User class="h-3 w-3 text-primary" />
            </div>
            <span class="text-sm font-medium">{{ job.assigned_technician.name }}</span>
          </div>
        </div>
      </div>
    </CardContent>
  </Card>
</template>
