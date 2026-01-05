<script setup lang="ts">
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import type { Component } from 'vue';

interface Props {
    title: string;
    value: string | number;
    description?: string;
    icon?: Component;
    trend?: {
        value: number;
        label: string;
    };
}

defineProps<Props>();
</script>

<template>
    <Card>
        <CardHeader
            class="flex flex-row items-center justify-between space-y-0 pb-2"
        >
            <CardTitle class="text-sm font-medium">{{ title }}</CardTitle>
            <component
                v-if="icon"
                :is="icon"
                class="h-4 w-4 text-muted-foreground"
            />
        </CardHeader>
        <CardContent>
            <div class="text-2xl font-bold">{{ value }}</div>
            <p v-if="description" class="mt-1 text-xs text-muted-foreground">
                {{ description }}
            </p>
            <div v-if="trend" class="mt-2 flex items-center gap-1">
                <span
                    :class="[
                        'text-xs font-medium',
                        trend.value > 0
                            ? 'text-green-600 dark:text-green-400'
                            : 'text-red-600 dark:text-red-400',
                    ]"
                >
                    {{ trend.value > 0 ? '+' : '' }}{{ trend.value }}%
                </span>
                <span class="text-xs text-muted-foreground">{{
                    trend.label
                }}</span>
            </div>
        </CardContent>
    </Card>
</template>
