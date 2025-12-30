<script setup lang="ts">
import { computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import JobFilterBar from '@/components/workshop/JobFilterBar.vue';
import JobsTable from '@/components/workshop/JobsTable.vue';
import JobCard from '@/components/workshop/JobCard.vue';
import EmptyState from '@/components/EmptyState.vue';
import { useJobFilters } from '@/composables/useJobFilters';
import { Plus, LayoutGrid, Table as TableIcon } from 'lucide-vue-next';
import type { WorkshopJob, User, PaginatedResponse } from '@/types';

interface Props {
  jobs: PaginatedResponse<WorkshopJob>;
  technicians?: User[];
  filters?: {
    search?: string;
    status?: string;
    priority?: string;
    assigned_to?: number;
    customer_id?: number;
    date_from?: string;
    date_to?: string;
  };
  canCreate?: boolean;
  canEdit?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  filters: () => ({}),
  canCreate: false,
  canEdit: false,
});

const {
  filters: localFilters,
  isLoading,
  applyFilters,
  updateFilter,
  clearFilters,
} = useJobFilters(props.filters);

const handleUpdateFilters = (newFilters: any) => {
  Object.entries(newFilters).forEach(([key, value]) => {
    updateFilter(key as any, value);
  });
};

const viewMode = computed(() => 'table');
</script>

<template>
  <AppLayout>
    <Head title="Jobs" />

    <div class="space-y-6">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-3xl font-bold tracking-tight">Workshop Jobs</h1>
          <p class="text-muted-foreground">
            Manage and track all workshop jobs
          </p>
        </div>

        <Button v-if="canCreate" as-child>
          <Link :href="route('jobs.create')">
            <Plus class="h-4 w-4 mr-2" />
            Create Job
          </Link>
        </Button>
      </div>

      <JobFilterBar
        :filters="localFilters"
        :technicians="technicians"
        :loading="isLoading"
        @update:filters="handleUpdateFilters"
        @apply="applyFilters"
        @clear="clearFilters"
      />

      <div v-if="jobs.data.length === 0 && !localFilters.search">
        <EmptyState
          title="No jobs yet"
          description="Get started by creating your first workshop job"
          :icon="Plus"
          action-text="Create Job"
          :action-href="canCreate ? route('jobs.create') : undefined"
        />
      </div>

      <div v-else-if="jobs.data.length === 0">
        <EmptyState
          title="No jobs found"
          description="Try adjusting your filters or search criteria"
        />
      </div>

      <div v-else>
        <Tabs default-value="table" class="space-y-4">
          <div class="flex items-center justify-between">
            <TabsList>
              <TabsTrigger value="table">
                <TableIcon class="h-4 w-4 mr-2" />
                Table View
              </TabsTrigger>
              <TabsTrigger value="grid">
                <LayoutGrid class="h-4 w-4 mr-2" />
                Grid View
              </TabsTrigger>
            </TabsList>

            <div class="text-sm text-muted-foreground">
              Showing {{ jobs.from }} to {{ jobs.to }} of {{ jobs.total }} jobs
            </div>
          </div>

          <TabsContent value="table" class="space-y-4">
            <JobsTable :jobs="jobs.data" :can-edit="canEdit" />
          </TabsContent>

          <TabsContent value="grid" class="space-y-4">
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
              <JobCard v-for="job in jobs.data" :key="job.id" :job="job" />
            </div>
          </TabsContent>
        </Tabs>

        <div v-if="jobs.links.length > 3" class="flex items-center justify-center gap-2 mt-6">
          <Button
            v-for="link in jobs.links"
            :key="link.label"
            variant="outline"
            size="sm"
            :disabled="!link.url || link.active"
            @click="link.url && $inertia.visit(link.url)"
            v-html="link.label"
          />
        </div>
      </div>
    </div>
  </AppLayout>
</template>
