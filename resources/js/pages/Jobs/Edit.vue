<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import CustomerAutocomplete from '@/components/workshop/CustomerAutocomplete.vue';
import { useJobStatus } from '@/composables/useJobStatus';
import { useToast } from '@/composables/useToast';
import { formatDateForInput } from '@/utils/date';
import { ArrowLeft, Save } from 'lucide-vue-next';
import type { WorkshopJob, Customer } from '@/types';

interface Props {
  job: WorkshopJob;
  customers?: Customer[];
}

const props = defineProps<Props>();

const { getAllStatuses, getAllPriorities } = useJobStatus();
const { success, error } = useToast();

const form = useForm({
  customer_id: props.job.customer_id,
  description: props.job.description,
  status: props.job.status,
  priority: props.job.priority,
  expected_completion_date: formatDateForInput(props.job.expected_completion_date),
  estimated_cost: props.job.estimated_cost?.toString() || '',
  location: props.job.location || '',
});

const submitForm = () => {
  form.put(route('jobs.update', props.job.id), {
    onSuccess: () => {
      success('Job Updated', 'Workshop job has been updated successfully.');
    },
    onError: () => {
      error('Update Failed', 'Failed to update job. Please check the form for errors.');
    },
  });
};
</script>

<template>
  <AppLayout>
    <Head title="Edit Job" />

    <div class="space-y-6">
      <div class="flex items-center gap-4">
        <Button variant="outline" size="sm" as-child>
          <Link :href="route('jobs.show', job.id)">
            <ArrowLeft class="h-4 w-4 mr-2" />
            Back
          </Link>
        </Button>
        <div>
          <h1 class="text-3xl font-bold tracking-tight">Edit Job</h1>
          <p class="text-muted-foreground">{{ job.job_number }}</p>
        </div>
      </div>

      <form @submit.prevent="submitForm">
        <Card>
          <CardHeader>
            <CardTitle>Job Details</CardTitle>
          </CardHeader>
          <CardContent class="space-y-6">
            <CustomerAutocomplete
              v-model="form.customer_id"
              label="Customer"
              required
              :error="form.errors.customer_id"
              :disabled="form.processing"
            />

            <div class="space-y-2">
              <Label for="description">
                Description
                <span class="text-destructive">*</span>
              </Label>
              <Textarea
                id="description"
                v-model="form.description"
                placeholder="Describe the work to be done..."
                rows="4"
                :disabled="form.processing"
              />
              <p v-if="form.errors.description" class="text-sm text-destructive">
                {{ form.errors.description }}
              </p>
            </div>

            <div class="grid gap-6 sm:grid-cols-2">
              <div class="space-y-2">
                <Label for="status">
                  Status
                  <span class="text-destructive">*</span>
                </Label>
                <Select
                  v-model="form.status"
                  :disabled="form.processing"
                >
                  <SelectTrigger id="status">
                    <SelectValue placeholder="Select status" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem
                      v-for="status in getAllStatuses()"
                      :key="status.value"
                      :value="status.value"
                    >
                      {{ status.label }}
                    </SelectItem>
                  </SelectContent>
                </Select>
                <p v-if="form.errors.status" class="text-sm text-destructive">
                  {{ form.errors.status }}
                </p>
              </div>

              <div class="space-y-2">
                <Label for="priority">
                  Priority
                  <span class="text-destructive">*</span>
                </Label>
                <Select
                  v-model="form.priority"
                  :disabled="form.processing"
                >
                  <SelectTrigger id="priority">
                    <SelectValue placeholder="Select priority" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem
                      v-for="priority in getAllPriorities()"
                      :key="priority.value"
                      :value="priority.value"
                    >
                      {{ priority.label }}
                    </SelectItem>
                  </SelectContent>
                </Select>
                <p v-if="form.errors.priority" class="text-sm text-destructive">
                  {{ form.errors.priority }}
                </p>
              </div>
            </div>

            <div class="grid gap-6 sm:grid-cols-2">
              <div class="space-y-2">
                <Label for="expected_completion_date">Expected Completion Date</Label>
                <Input
                  id="expected_completion_date"
                  v-model="form.expected_completion_date"
                  type="date"
                  :disabled="form.processing"
                />
                <p v-if="form.errors.expected_completion_date" class="text-sm text-destructive">
                  {{ form.errors.expected_completion_date }}
                </p>
              </div>

              <div class="space-y-2">
                <Label for="estimated_cost">Estimated Cost (RM)</Label>
                <Input
                  id="estimated_cost"
                  v-model="form.estimated_cost"
                  type="number"
                  step="0.01"
                  min="0"
                  placeholder="0.00"
                  :disabled="form.processing"
                />
                <p v-if="form.errors.estimated_cost" class="text-sm text-destructive">
                  {{ form.errors.estimated_cost }}
                </p>
              </div>
            </div>

            <div class="space-y-2">
              <Label for="location">Location</Label>
              <Input
                id="location"
                v-model="form.location"
                placeholder="Workshop location or address"
                :disabled="form.processing"
              />
              <p v-if="form.errors.location" class="text-sm text-destructive">
                {{ form.errors.location }}
              </p>
            </div>

            <div class="flex gap-2 justify-end">
              <Button
                variant="outline"
                type="button"
                :disabled="form.processing"
                as-child
              >
                <Link :href="route('jobs.show', job.id)">
                  Cancel
                </Link>
              </Button>
              <Button
                type="submit"
                :disabled="form.processing"
              >
                <Save class="h-4 w-4 mr-2" />
                Update Job
              </Button>
            </div>
          </CardContent>
        </Card>
      </form>
    </div>
  </AppLayout>
</template>
