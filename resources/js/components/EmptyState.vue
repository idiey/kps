<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Link } from '@inertiajs/vue3';
import type { Component } from 'vue';

interface Props {
    title: string;
    description?: string;
    icon?: Component;
    actionText?: string;
    actionHref?: string;
}

defineProps<Props>();

const emit = defineEmits<{
    action: [];
}>();
</script>

<template>
    <div
        class="flex flex-col items-center justify-center px-4 py-12 text-center"
    >
        <component
            v-if="icon"
            :is="icon"
            class="mb-4 h-16 w-16 text-muted-foreground opacity-50"
        />

        <h3 class="mb-2 text-lg font-semibold">{{ title }}</h3>

        <p v-if="description" class="mb-6 max-w-md text-muted-foreground">
            {{ description }}
        </p>

        <Button v-if="actionText && actionHref" as-child>
            <Link :href="actionHref">
                {{ actionText }}
            </Link>
        </Button>

        <Button v-else-if="actionText" @click="emit('action')">
            {{ actionText }}
        </Button>
    </div>
</template>
