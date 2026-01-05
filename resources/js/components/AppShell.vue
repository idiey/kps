<script setup lang="ts">
import ConfirmDialog from '@/components/ConfirmDialog.vue';
import Toaster from '@/components/Toaster.vue';
import { SidebarProvider } from '@/components/ui/sidebar';
import { usePage } from '@inertiajs/vue3';

interface Props {
    variant?: 'header' | 'sidebar';
}

defineProps<Props>();

const isOpen = usePage().props.sidebarOpen;
</script>

<template>
    <div v-if="variant === 'header'" class="flex min-h-screen w-full flex-col">
        <slot />
        <Toaster />
        <ConfirmDialog />
    </div>
    <SidebarProvider v-else :default-open="isOpen">
        <slot />
        <Toaster />
        <ConfirmDialog />
    </SidebarProvider>
</template>
