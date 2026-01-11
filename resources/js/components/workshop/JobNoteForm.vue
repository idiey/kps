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
    content: props.note?.content || '',
    is_public: props.note?.is_public || false,
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
            <Label for="content">Note Content</Label>
            <Textarea
                id="content"
                v-model="form.content"
                placeholder="Enter job note..."
                rows="4"
                :disabled="form.processing"
            />
            <p v-if="form.errors.content" class="text-sm text-destructive">
                {{ form.errors.content }}
            </p>
        </div>

        <div class="flex items-center space-x-2">
            <Checkbox
                id="is_public"
                v-model:checked="form.is_public"
                :disabled="form.processing"
            />
            <Label for="is_public" class="cursor-pointer text-sm font-normal">
                Public note (visible to customers)
            </Label>
        </div>

        <div class="flex gap-2">
            <Button
                @click="submitForm"
                :disabled="form.processing || !form.content"
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
