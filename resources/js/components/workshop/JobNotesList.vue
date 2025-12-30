<script setup lang="ts">
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { Card, CardContent } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import JobNoteForm from '@/components/workshop/JobNoteForm.vue';
import { formatDateTime } from '@/utils/date';
import { useConfirmDialog } from '@/composables/useConfirmDialog';
import { useToast } from '@/composables/useToast';
import { Lock, Trash2, Edit, MessageSquare } from 'lucide-vue-next';
import type { WorkshopJob, JobNote } from '@/types';

interface Props {
  job: WorkshopJob;
  notes: JobNote[];
  canEdit?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  canEdit: false,
});

const { confirm } = useConfirmDialog();
const { success, error } = useToast();

const editingNoteId = ref<number | null>(null);

const deleteNote = async (noteId: number) => {
  const confirmed = await confirm({
    title: 'Delete Note',
    description: 'Are you sure you want to delete this note? This action cannot be undone.',
    confirmText: 'Delete',
    cancelText: 'Cancel',
    variant: 'destructive',
  });

  if (confirmed) {
    router.delete(route('jobs.notes.destroy', [props.job.id, noteId]), {
      onSuccess: () => {
        success('Note Deleted', 'Job note has been deleted successfully.');
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
    <div v-if="notes.length === 0" class="text-center py-8 text-muted-foreground">
      <MessageSquare class="h-12 w-12 mx-auto mb-2 opacity-50" />
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
                  <span class="font-medium text-sm">{{ note.created_by?.name || 'Unknown' }}</span>
                  <span class="text-xs text-muted-foreground">
                    {{ formatDateTime(note.created_at) }}
                  </span>
                  <Badge v-if="note.is_internal" variant="secondary" class="text-xs">
                    <Lock class="h-3 w-3 mr-1" />
                    Internal
                  </Badge>
                </div>
                <p class="text-sm whitespace-pre-wrap">{{ note.note }}</p>
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
