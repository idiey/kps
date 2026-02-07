<script setup lang="ts">
import { SidebarInset } from '@/components/ui/sidebar';
import { digitWorkshop } from '@/styles/digit-workshop-ui';
import { cn } from '@/lib/utils';
import { computed } from 'vue';

interface Props {
    variant?: 'header' | 'sidebar';
    class?: string;
}

const props = defineProps<Props>();
const className = computed(() =>
    cn(
        props.variant === 'sidebar'
            ? digitWorkshop.layout.contentContainer
            : 'mx-auto flex h-full w-full max-w-7xl flex-1 flex-col gap-4 rounded-xl',
        props.class,
    ),
);
</script>

<template>
    <SidebarInset v-if="props.variant === 'sidebar'" :class="className">
        <slot />
    </SidebarInset>
    <main
        v-else
        :class="className"
    >
        <slot />
    </main>
</template>
