import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import type { JobStatus, JobPriority } from '@/types';

export interface JobFilters {
  search?: string;
  status?: JobStatus;
  priority?: JobPriority;
  assigned_to?: number;
  customer_id?: number;
  date_from?: string;
  date_to?: string;
}

export const useJobFilters = (initialFilters: JobFilters = {}) => {
  const filters = ref<JobFilters>({ ...initialFilters });
  const isLoading = ref(false);

  const applyFilters = (preserveScroll = true): void => {
    isLoading.value = true;

    const params: Record<string, any> = {};

    // Only include non-empty filters
    Object.entries(filters.value).forEach(([key, value]) => {
      if (value !== undefined && value !== null && value !== '') {
        params[key] = value;
      }
    });

    router.get(
      route('jobs.index'),
      params,
      {
        preserveScroll,
        preserveState: true,
        onFinish: () => {
          isLoading.value = false;
        },
      }
    );
  };

  const updateFilter = <K extends keyof JobFilters>(
    key: K,
    value: JobFilters[K]
  ): void => {
    filters.value[key] = value;
  };

  const clearFilters = (): void => {
    filters.value = {};
    applyFilters();
  };

  const resetFilter = <K extends keyof JobFilters>(key: K): void => {
    delete filters.value[key];
    applyFilters();
  };

  const hasActiveFilters = (): boolean => {
    return Object.values(filters.value).some(
      (value) => value !== undefined && value !== null && value !== ''
    );
  };

  const getActiveFiltersCount = (): number => {
    return Object.values(filters.value).filter(
      (value) => value !== undefined && value !== null && value !== ''
    ).length;
  };

  return {
    filters,
    isLoading,
    applyFilters,
    updateFilter,
    clearFilters,
    resetFilter,
    hasActiveFilters,
    getActiveFiltersCount,
  };
};
