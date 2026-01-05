<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Card, CardContent } from '@/components/ui/card';
import type { JobAssignment } from '@/types';
import { formatDateTime } from '@/utils/date';
import { User, UserX } from 'lucide-vue-next';

interface Props {
    assignments: JobAssignment[];
}

defineProps<Props>();
</script>

<template>
    <div class="space-y-3">
        <div
            v-if="assignments.length === 0"
            class="py-8 text-center text-muted-foreground"
        >
            <User class="mx-auto mb-2 h-12 w-12 opacity-50" />
            <p>No assignment history</p>
        </div>

        <div v-else class="relative">
            <div class="absolute top-0 bottom-0 left-4 w-0.5 bg-border" />

            <div
                v-for="assignment in assignments"
                :key="assignment.id"
                class="relative pb-6 pl-10"
            >
                <div
                    class="absolute left-0 flex h-8 w-8 items-center justify-center rounded-full border-2 border-background"
                    :class="
                        assignment.unassigned_at
                            ? 'bg-red-100 dark:bg-red-900'
                            : 'bg-blue-100 dark:bg-blue-900'
                    "
                >
                    <component
                        :is="assignment.unassigned_at ? UserX : User"
                        class="h-4 w-4"
                        :class="
                            assignment.unassigned_at
                                ? 'text-red-600 dark:text-red-400'
                                : 'text-blue-600 dark:text-blue-400'
                        "
                    />
                </div>

                <Card>
                    <CardContent class="pt-4">
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="font-medium">{{
                                    assignment.technician?.name
                                }}</span>
                                <Badge
                                    :variant="
                                        assignment.unassigned_at
                                            ? 'destructive'
                                            : 'default'
                                    "
                                    class="text-xs"
                                >
                                    {{
                                        assignment.unassigned_at
                                            ? 'Unassigned'
                                            : 'Assigned'
                                    }}
                                </Badge>
                            </div>

                            <div class="text-sm text-muted-foreground">
                                <div>
                                    Assigned:
                                    {{ formatDateTime(assignment.assigned_at) }}
                                    <span class="text-xs"
                                        >by
                                        {{ assignment.assigned_by?.name }}</span
                                    >
                                </div>
                                <div v-if="assignment.unassigned_at">
                                    Unassigned:
                                    {{
                                        formatDateTime(assignment.unassigned_at)
                                    }}
                                    <span
                                        v-if="assignment.unassigned_by"
                                        class="text-xs"
                                    >
                                        by {{ assignment.unassigned_by.name }}
                                    </span>
                                </div>
                            </div>

                            <p
                                v-if="assignment.notes"
                                class="mt-2 text-sm whitespace-pre-wrap"
                            >
                                {{ assignment.notes }}
                            </p>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </div>
</template>
