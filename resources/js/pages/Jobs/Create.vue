<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';
import CustomerAutocomplete from '@/components/workshop/CustomerAutocomplete.vue';
import { useJobStatus } from '@/composables/useJobStatus';
import { useToast } from '@/composables/useToast';
import AppLayout from '@/layouts/AppLayout.vue';
import { index, store } from '@/routes/jobs';
import type { Customer } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowLeft, Save } from 'lucide-vue-next';
import { onMounted, ref } from 'vue';

interface Props {
    customers?: Customer[];
}

defineProps<Props>();

const { getAllStatuses, getAllPriorities } = useJobStatus();
const { success, error } = useToast();

const form = useForm({
    customer_id: null as number | null,
    description: '',
    status: 'pending',
    priority: 'normal',
    expected_completion_date: '',
    estimated_cost: '',
    location: '',
});

const prefillSource = ref<string | null>(null);

onMounted(() => {
    const params = new URLSearchParams(window.location.search);
    const fromKewPA10 = params.get('from_kew_pa_10');
    if (fromKewPA10) {
        prefillSource.value = `Prefilled from KEW.PA-10 #${fromKewPA10}`;
        if (!form.description) {
            form.description = `Work order created from KEW.PA-10 #${fromKewPA10}`;
        }
    }
});

const submitForm = () => {
    form.post(store.url(), {
        onSuccess: () => {
            success(
                'Job Created',
                'Workshop job has been created successfully.',
            );
        },
        onError: () => {
            error(
                'Create Failed',
                'Failed to create job. Please check the form for errors.',
            );
        },
    });
};
</script>

<template>
    <AppLayout>
        <Head title="Create Job" />

        <div class="space-y-6">
            <div class="flex items-center gap-4">
                <Button variant="outline" size="sm" as-child>
                    <Link :href="index.url()">
                        <ArrowLeft class="mr-2 h-4 w-4" />
                        Back
                    </Link>
                </Button>
                <div>
                    <h1 class="text-3xl font-bold tracking-tight">
                        Create New Job
                    </h1>
                    <p class="text-muted-foreground">Add a new workshop job</p>
                </div>
            </div>

            <div v-if="prefillSource" class="rounded-md border p-3 text-sm">
                {{ prefillSource }}
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
                            <p
                                v-if="form.errors.description"
                                class="text-sm text-destructive"
                            >
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
                                        <SelectValue
                                            placeholder="Select status"
                                        />
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
                                <p
                                    v-if="form.errors.status"
                                    class="text-sm text-destructive"
                                >
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
                                        <SelectValue
                                            placeholder="Select priority"
                                        />
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
                                <p
                                    v-if="form.errors.priority"
                                    class="text-sm text-destructive"
                                >
                                    {{ form.errors.priority }}
                                </p>
                            </div>
                        </div>

                        <div class="grid gap-6 sm:grid-cols-2">
                            <div class="space-y-2">
                                <Label for="expected_completion_date"
                                    >Expected Completion Date</Label
                                >
                                <Input
                                    id="expected_completion_date"
                                    v-model="form.expected_completion_date"
                                    type="date"
                                    :disabled="form.processing"
                                />
                                <p
                                    v-if="form.errors.expected_completion_date"
                                    class="text-sm text-destructive"
                                >
                                    {{ form.errors.expected_completion_date }}
                                </p>
                            </div>

                            <div class="space-y-2">
                                <Label for="estimated_cost"
                                    >Estimated Cost (RM)</Label
                                >
                                <Input
                                    id="estimated_cost"
                                    v-model="form.estimated_cost"
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    placeholder="0.00"
                                    :disabled="form.processing"
                                />
                                <p
                                    v-if="form.errors.estimated_cost"
                                    class="text-sm text-destructive"
                                >
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
                            <p
                                v-if="form.errors.location"
                                class="text-sm text-destructive"
                            >
                                {{ form.errors.location }}
                            </p>
                        </div>

                        <div class="flex justify-end gap-2">
                            <Button
                                variant="outline"
                                type="button"
                                :disabled="form.processing"
                                as-child
                            >
                                <Link :href="index.url()"> Cancel </Link>
                            </Button>
                            <Button type="submit" :disabled="form.processing">
                                <Save class="mr-2 h-4 w-4" />
                                Create Job
                            </Button>
                        </div>
                    </CardContent>
                </Card>
            </form>
        </div>
    </AppLayout>
</template>
