<script setup lang="ts">
import { computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { useJobStatus } from '@/composables/useJobStatus';
import { useToast } from '@/composables/useToast';
import type { JobStatus, WorkshopJob } from '@/types';

interface Props {
  job: WorkshopJob;
}

const props = defineProps<Props>();

const emit = defineEmits<{
  success: [];
}>();

const { getAvailableTransitions } = useJobStatus();
const { success, error } = useToast();

const availableStatuses = computed(() => getAvailableTransitions(props.job.status));

const form = useForm({
  status: props.job.status,
  notes: '',
});

const updateStatus = () => {
  form.put(route('jobs.update-status', props.job.id), {
    onSuccess: () => {
      success('Status Updated', 'Job status has been updated successfully.');
      form.reset('notes');
      emit('success');
    },
    onError: () => {
      error('Update Failed', 'Failed to update job status.');
    },
  });
};

const canTransition = computed(() => availableStatuses.value.length > 0);
</script>

<template>
  <div class="space-y-4">
    <div class="space-y-2">
      <Label for="status">Change Status</Label>
      <Select
        v-model="form.status"
        :disabled="!canTransition || form.processing"
      >
        <SelectTrigger id="status">
          <SelectValue placeholder="Select new status" />
        </SelectTrigger>
        <SelectContent>
          <SelectItem
            v-for="status in availableStatuses"
            :key="status.value"
            :value="status.value"
          >
            {{ status.label }}
          </SelectItem>
        </SelectContent>
      </Select>
      <p v-if="!canTransition" class="text-sm text-muted-foreground">
        No status transitions available from current status.
      </p>
    </div>

    <div class="space-y-2">
      <Label for="notes">Notes (Optional)</Label>
      <Textarea
        id="notes"
        v-model="form.notes"
        placeholder="Add a note about this status change..."
        rows="3"
        :disabled="form.processing"
      />
    </div>

    <Button
      @click="updateStatus"
      :disabled="!canTransition || form.status === job.status || form.processing"
      class="w-full"
    >
      Update Status
    </Button>
  </div>
</template>
