<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import JobNoteForm from '@/components/workshop/JobNoteForm.vue';
import { useConfirmDialog } from '@/composables/useConfirmDialog';
import { useToast } from '@/composables/useToast';
import notesRoutes from '@/routes/jobs/notes';
import type { JobNote, WorkshopJob } from '@/types';
import { formatDateTime } from '@/utils/date';
import { router } from '@inertiajs/vue3';
import { Edit, Lock, MessageSquare, Trash2 } from 'lucide-vue-next';
import { ref } from 'vue';

interface Props {
    job: WorkshopJob;
    notes: JobNote[];
    canEdit?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    canEdit: false,
});

// Debug logs
import { onMounted } from 'vue';
onMounted(() => {
    console.log('JobNotesList mounted with notes:', props.notes);
});

const { confirm } = useConfirmDialog();
const { success, error } = useToast();

const editingNoteId = ref<number | null>(null);

const deleteNote = async (noteId: number) => {
    const confirmed = await confirm({
        title: 'Delete Note',
        description:
            'Are you sure you want to delete this note? This action cannot be undone.',
        confirmText: 'Delete',
        cancelText: 'Cancel',
        variant: 'destructive',
    });

    if (confirmed) {
        router.delete(notesRoutes.destroy.url([props.job.id, noteId]), {
            onSuccess: () => {
                success(
                    'Note Deleted',
                    'Job note has been deleted successfully.',
                );
            },
            onError: () => {
                error('Delete Failed', 'Failed to delete job note.');
            },
        });
    }
};

const startEdit = (noteId: number) => {
    editingNoteId.value = noteId;
};

const cancelEdit = () => {
    editingNoteId.value = null;
};

const handleSuccess = () => {
    editingNoteId.value = null;
};
</script>

<template>
    <div class="space-y-4">
        <div
            v-if="notes.length === 0"
            class="py-8 text-center text-muted-foreground"
        >
            <MessageSquare class="mx-auto mb-2 h-12 w-12 opacity-50" />
            <p>No notes yet</p>
        </div>

        <div v-else class="space-y-3">
            <Card v-for="note in notes" :key="note.id">
                <CardContent class="pt-4">
                    <div v-if="editingNoteId === note.id">
                        <JobNoteForm
                            :job="job"
                            :note="note"
                            @success="handleSuccess"
                            @cancel="cancelEdit"
                        />
                    </div>
                    <div v-else>
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1 space-y-2">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-medium">{{
                                        note.user?.name || 'Unknown'
                                    }}</span>
                                    <span class="text-xs text-muted-foreground">
                                        {{ formatDateTime(note.created_at) }}
                                    </span>
                                    <Badge
                                        v-if="!note.is_public"
                                        variant="secondary"
                                        class="text-xs"
                                    >
                                        <Lock class="mr-1 h-3 w-3" />
                                        Internal
                                    </Badge>
                                </div>
                                <p class="text-sm whitespace-pre-wrap">
                                    {{ note.content }}
                                </p>
                            </div>

                            <div v-if="canEdit" class="flex gap-1">
                                <Button
                                    variant="ghost"
                                    size="sm"
                                    @click="startEdit(note.id)"
                                >
                                    <Edit class="h-4 w-4" />
                                </Button>
                                <Button
                                    variant="ghost"
                                    size="sm"
                                    @click="deleteNote(note.id)"
                                >
                                    <Trash2 class="h-4 w-4 text-destructive" />
                                </Button>
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </div>
</template>
