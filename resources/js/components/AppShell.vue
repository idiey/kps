<script setup lang="ts">
import ConfirmDialog from '@/components/ConfirmDialog.vue';
import Toaster from '@/components/Toaster.vue';
import { SidebarProvider } from '@/components/ui/sidebar';
import { digitWorkshop } from '@/styles/digit-workshop-ui';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

interface Props {
    variant?: 'header' | 'sidebar';
    sidebarDefaultOpen?: boolean;
}

const props = defineProps<Props>();

const isOpen = usePage().props.sidebarOpen;
const defaultOpen = computed(() => props.sidebarDefaultOpen ?? isOpen);
</script>

<template>
    <div
        v-if="variant === 'header'"
        class="flex min-h-screen w-full flex-col"
        :class="digitWorkshop.layout.pageWrapper"
    >
        <slot />
        <Toaster />
        <ConfirmDialog />
    </div>
    <SidebarProvider v-else :default-open="defaultOpen" :class="digitWorkshop.layout.pageWrapper">
        <slot />
        <Toaster />
        <ConfirmDialog />
    </SidebarProvider>
</template>
