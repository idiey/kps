<script setup lang="ts">
import { Card, CardContent } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { formatDateTime } from '@/utils/date';
import { User, UserX } from 'lucide-vue-next';
import type { JobAssignment } from '@/types';

interface Props {
  assignments: JobAssignment[];
}

defineProps<Props>();
</script>

<template>
  <div class="space-y-3">
    <div v-if="assignments.length === 0" class="text-center py-8 text-muted-foreground">
      <User class="h-12 w-12 mx-auto mb-2 opacity-50" />
      <p>No assignment history</p>
    </div>

    <div v-else class="relative">
      <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-border" />

      <div v-for="(assignment, index) in assignments" :key="assignment.id" class="relative pl-10 pb-6">
        <div
          class="absolute left-0 flex h-8 w-8 items-center justify-center rounded-full border-2 border-background"
          :class="assignment.unassigned_at ? 'bg-red-100 dark:bg-red-900' : 'bg-blue-100 dark:bg-blue-900'"
        >
          <component
            :is="assignment.unassigned_at ? UserX : User"
            class="h-4 w-4"
            :class="assignment.unassigned_at ? 'text-red-600 dark:text-red-400' : 'text-blue-600 dark:text-blue-400'"
          />
        </div>

        <Card>
          <CardContent class="pt-4">
            <div class="space-y-2">
              <div class="flex items-center justify-between">
                <span class="font-medium">{{ assignment.technician?.name }}</span>
                <Badge
                  :variant="assignment.unassigned_at ? 'destructive' : 'default'"
                  class="text-xs"
                >
                  {{ assignment.unassigned_at ? 'Unassigned' : 'Assigned' }}
                </Badge>
              </div>

              <div class="text-sm text-muted-foreground">
                <div>
                  Assigned: {{ formatDateTime(assignment.assigned_at) }}
                  <span class="text-xs">by {{ assignment.assigned_by?.name }}</span>
                </div>
                <div v-if="assignment.unassigned_at">
                  Unassigned: {{ formatDateTime(assignment.unassigned_at) }}
                  <span v-if="assignment.unassigned_by" class="text-xs">
                    by {{ assignment.unassigned_by.name }}
                  </span>
                </div>
              </div>

              <p v-if="assignment.notes" class="text-sm mt-2 whitespace-pre-wrap">
                {{ assignment.notes }}
              </p>
            </div>
          </CardContent>
        </Card>
      </div>
    </div>
  </div>
</template>
