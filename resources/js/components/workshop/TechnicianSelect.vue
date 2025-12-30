<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';
import { Button } from '@/components/ui/button';
import { useToast } from '@/composables/useToast';
import type { User, WorkshopJob } from '@/types';

interface Props {
  job: WorkshopJob;
  technicians: User[];
  currentTechnicianId?: number | null;
}

const props = defineProps<Props>();

const emit = defineEmits<{
  success: [];
}>();

const { success, error } = useToast();

const form = useForm({
  technician_id: props.currentTechnicianId?.toString() || '',
  notes: '',
});

const assignTechnician = () => {
  form.post(route('jobs.assignments.store', props.job.id), {
    onSuccess: () => {
      success('Technician Assigned', 'Technician has been assigned successfully.');
      form.reset('notes');
      emit('success');
    },
    onError: () => {
      error('Assignment Failed', 'Failed to assign technician.');
    },
  });
};
</script>

<template>
  <div class="space-y-4">
    <div class="space-y-2">
      <Label for="technician">Assign Technician</Label>
      <Select
        v-model="form.technician_id"
        :disabled="form.processing"
      >
        <SelectTrigger id="technician">
          <SelectValue placeholder="Select technician" />
        </SelectTrigger>
        <SelectContent>
          <SelectItem value="">Unassigned</SelectItem>
          <SelectItem
            v-for="tech in technicians"
            :key="tech.id"
            :value="tech.id.toString()"
          >
            {{ tech.name }}
          </SelectItem>
        </SelectContent>
      </Select>
      <p v-if="form.errors.technician_id" class="text-sm text-destructive">
        {{ form.errors.technician_id }}
      </p>
    </div>

    <div class="space-y-2">
      <Label for="assignment_notes">Assignment Notes (Optional)</Label>
      <Textarea
        id="assignment_notes"
        v-model="form.notes"
        placeholder="Add notes about this assignment..."
        rows="3"
        :disabled="form.processing"
      />
    </div>

    <Button
      @click="assignTechnician"
      :disabled="!form.technician_id || form.processing"
      class="w-full"
    >
      Assign Technician
    </Button>
  </div>
</template>
