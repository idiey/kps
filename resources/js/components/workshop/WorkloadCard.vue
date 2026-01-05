<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import type { User as UserType } from '@/types';
import { User } from 'lucide-vue-next';
import { computed } from 'vue';

interface Props {
    technician: UserType & {
        jobs_count?: number;
        active_jobs?: number;
        urgent_jobs?: number;
    };
}

const props = defineProps<Props>();

const workloadLevel = computed(() => {
    const activeJobs = props.technician.active_jobs || 0;

    if (activeJobs === 0)
        return {
            label: 'Available',
            color: 'bg-green-100 text-green-700 dark:bg-green-800 dark:text-green-300',
        };
    if (activeJobs <= 2)
        return {
            label: 'Light',
            color: 'bg-blue-100 text-blue-700 dark:bg-blue-800 dark:text-blue-300',
        };
    if (activeJobs <= 4)
        return {
            label: 'Moderate',
            color: 'bg-yellow-100 text-yellow-700 dark:bg-yellow-800 dark:text-yellow-300',
        };
    return {
        label: 'Heavy',
        color: 'bg-red-100 text-red-700 dark:bg-red-800 dark:text-red-300',
    };
});
</script>

<template>
    <Card>
        <CardHeader class="pb-3">
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-3">
                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-full bg-primary/10"
                    >
                        <User class="h-5 w-5 text-primary" />
                    </div>
                    <div>
                        <CardTitle class="text-base">{{
                            technician.name
                        }}</CardTitle>
                        <p class="text-sm text-muted-foreground">
                            {{ technician.email }}
                        </p>
                    </div>
                </div>
                <Badge :class="workloadLevel.color">
                    {{ workloadLevel.label }}
                </Badge>
            </div>
        </CardHeader>
        <CardContent>
            <div class="grid grid-cols-3 gap-4 text-center">
                <div>
                    <div class="text-2xl font-bold">
                        {{ technician.active_jobs || 0 }}
                    </div>
                    <div class="text-xs text-muted-foreground">Active</div>
                </div>
                <div>
                    <div class="text-2xl font-bold">
                        {{ technician.jobs_count || 0 }}
                    </div>
                    <div class="text-xs text-muted-foreground">Total</div>
                </div>
                <div>
                    <div
                        class="text-2xl font-bold text-red-600 dark:text-red-400"
                    >
                        {{ technician.urgent_jobs || 0 }}
                    </div>
                    <div class="text-xs text-muted-foreground">Urgent</div>
                </div>
            </div>
        </CardContent>
    </Card>
</template>
