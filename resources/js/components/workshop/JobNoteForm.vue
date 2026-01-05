<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { useToast } from '@/composables/useToast';
import notes from '@/routes/jobs/notes';
import type { JobNote, WorkshopJob } from '@/types';
import { useForm } from '@inertiajs/vue3';

interface Props {
    job: WorkshopJob;
    note?: JobNote;
}

const props = defineProps<Props>();

const emit = defineEmits<{
    success: [];
    cancel: [];
}>();

const { success, error } = useToast();

const form = useForm({
    note: props.note?.note || '',
    is_internal: props.note?.is_internal || false,
});

const submitForm = () => {
    if (props.note) {
        form.put(notes.update.url([props.job.id, props.note.id]), {
            onSuccess: () => {
                success(
                    'Note Updated',
                    'Job note has been updated successfully.',
                );
                emit('success');
            },
            onError: () => {
                error('Update Failed', 'Failed to update job note.');
            },
        });
    } else {
        form.post(notes.store.url(props.job.id), {
            onSuccess: () => {
                success('Note Added', 'Job note has been added successfully.');
                form.reset();
                emit('success');
            },
            onError: () => {
                error('Add Failed', 'Failed to add job note.');
            },
        });
    }
};
</script>

<template>
    <div class="space-y-4">
        <div class="space-y-2">
            <Label for="note">Note</Label>
            <Textarea
                id="note"
                v-model="form.note"
                placeholder="Enter job note..."
                rows="4"
                :disabled="form.processing"
            />
            <p v-if="form.errors.note" class="text-sm text-destructive">
                {{ form.errors.note }}
            </p>
        </div>

        <div class="flex items-center space-x-2">
            <Checkbox
                id="is_internal"
                v-model:checked="form.is_internal"
                :disabled="form.processing"
            />
            <Label for="is_internal" class="cursor-pointer text-sm font-normal">
                Internal note (not visible to customers)
            </Label>
        </div>

        <div class="flex gap-2">
            <Button
                @click="submitForm"
                :disabled="form.processing || !form.note"
                class="flex-1"
            >
                {{ note ? 'Update Note' : 'Add Note' }}
            </Button>
            <Button
                v-if="note"
                variant="outline"
                @click="emit('cancel')"
                :disabled="form.processing"
            >
                Cancel
            </Button>
        </div>
    </div>
</template>
