<script setup lang="ts">
import { computed } from 'vue';
import { Card, CardContent } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { formatDateTime } from '@/utils/date';
import { useJobStatus } from '@/composables/useJobStatus';
import {
  CheckCircle,
  Clock,
  AlertCircle,
  FileText,
  User,
  Activity,
} from 'lucide-vue-next';
import type { JobStatusHistory, JobNote, JobAssignment } from '@/types';

interface TimelineItem {
  id: string;
  type: 'status' | 'note' | 'assignment';
  timestamp: string;
  title: string;
  description?: string;
  user?: string;
  icon: any;
  color: string;
  data?: any;
}

interface Props {
  statusHistory: JobStatusHistory[];
  notes?: JobNote[];
  assignments?: JobAssignment[];
}

const props = withDefaults(defineProps<Props>(), {
  notes: () => [],
  assignments: () => [],
});

const { getStatusLabel } = useJobStatus();

const timeline = computed<TimelineItem[]>(() => {
  const items: TimelineItem[] = [];

  // Add status changes
  props.statusHistory.forEach((history) => {
    items.push({
      id: `status-${history.id}`,
      type: 'status',
      timestamp: history.changed_at,
      title: `Status changed to ${getStatusLabel(history.status)}`,
      description: history.notes,
      user: history.changed_by?.name,
      icon: Activity,
      color: 'text-blue-600 dark:text-blue-400',
      data: history,
    });
  });

  // Add notes
  props.notes.forEach((note) => {
    items.push({
      id: `note-${note.id}`,
      type: 'note',
      timestamp: note.created_at,
      title: note.is_internal ? 'Internal note added' : 'Note added',
      description: note.note,
      user: note.created_by?.name,
      icon: FileText,
      color: 'text-green-600 dark:text-green-400',
      data: note,
    });
  });

  // Add assignments
  props.assignments.forEach((assignment) => {
    items.push({
      id: `assign-${assignment.id}`,
      type: 'assignment',
      timestamp: assignment.assigned_at,
      title: `Assigned to ${assignment.technician?.name}`,
      description: assignment.notes,
      user: assignment.assigned_by?.name,
      icon: User,
      color: 'text-purple-600 dark:text-purple-400',
      data: assignment,
    });

    if (assignment.unassigned_at) {
      items.push({
        id: `unassign-${assignment.id}`,
        type: 'assignment',
        timestamp: assignment.unassigned_at,
        title: `Unassigned from ${assignment.technician?.name}`,
        user: assignment.unassigned_by?.name,
        icon: User,
        color: 'text-red-600 dark:text-red-400',
        data: assignment,
      });
    }
  });

  // Sort by timestamp descending
  return items.sort(
    (a, b) => new Date(b.timestamp).getTime() - new Date(a.timestamp).getTime()
  );
});
</script>

<template>
  <div class="space-y-3">
    <div v-if="timeline.length === 0" class="text-center py-8 text-muted-foreground">
      <Clock class="h-12 w-12 mx-auto mb-2 opacity-50" />
      <p>No activity yet</p>
    </div>

    <div v-else class="relative">
      <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-border" />

      <div
        v-for="item in timeline"
        :key="item.id"
        class="relative pl-10 pb-6 last:pb-0"
      >
        <div
          class="absolute left-0 flex h-8 w-8 items-center justify-center rounded-full border-2 border-background bg-card"
        >
          <component
            :is="item.icon"
            class="h-4 w-4"
            :class="item.color"
          />
        </div>

        <Card>
          <CardContent class="pt-4">
            <div class="space-y-2">
              <div class="flex items-start justify-between gap-4">
                <div class="flex-1">
                  <h4 class="font-medium text-sm">{{ item.title }}</h4>
                  <div class="text-xs text-muted-foreground mt-1">
                    {{ formatDateTime(item.timestamp) }}
                    <span v-if="item.user"> by {{ item.user }}</span>
                  </div>
                </div>
                <Badge
                  v-if="item.type === 'note' && item.data?.is_internal"
                  variant="secondary"
                  class="text-xs"
                >
                  Internal
                </Badge>
              </div>

              <p
                v-if="item.description"
                class="text-sm text-muted-foreground whitespace-pre-wrap"
              >
                {{ item.description }}
              </p>
            </div>
          </CardContent>
        </Card>
      </div>
    </div>
  </div>
</template>
