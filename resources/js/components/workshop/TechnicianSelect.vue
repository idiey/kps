<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';
import { useToast } from '@/composables/useToast';
import { assign } from '@/routes/jobs';
import type { User, WorkshopJob } from '@/types';
import { useForm } from '@inertiajs/vue3';

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

const form = useForm<{
    assigned_to: string | undefined;
    notes: string;
}>({
    assigned_to: props.currentTechnicianId?.toString() || undefined,
    notes: '',
});

// Sync form with prop changes
import { watch } from 'vue';
watch(
    () => props.currentTechnicianId,
    (newId) => {
        form.assigned_to = newId?.toString() || undefined;
    },
    { immediate: true },
);

const assignTechnician = () => {
    form.post(assign.url(props.job.id), {
        onSuccess: () => {
            success(
                'Technician Assigned',
                'Technician has been assigned successfully.',
            );
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
            <Select v-model="form.assigned_to" :disabled="form.processing">
                <SelectTrigger id="technician">
                    <SelectValue placeholder="Select technician" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem
                        v-for="tech in technicians"
                        :key="tech.id"
                        :value="tech.id.toString()"
                    >
                        {{ tech.name }}
                    </SelectItem>
                </SelectContent>
            </Select>
            <p
                v-if="form.errors.assigned_to"
                class="text-sm text-destructive"
            >
                {{ form.errors.assigned_to }}
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
            :disabled="!form.assigned_to || form.processing"
            class="w-full"
        >
            Assign Technician
        </Button>
    </div>
</template>
