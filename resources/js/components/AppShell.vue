<script setup lang="ts">
import ConfirmDialog from '@/components/ConfirmDialog.vue';
import Toaster from '@/components/Toaster.vue';
import { SidebarProvider } from '@/components/ui/sidebar';
import { digitWorkshop } from '@/styles/digit-workshop-ui';
import { usePage } from '@inertiajs/vue3';

interface Props {
    variant?: 'header' | 'sidebar';
}

defineProps<Props>();

const isOpen = usePage().props.sidebarOpen;
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
    <SidebarProvider v-else :default-open="isOpen" :class="digitWorkshop.layout.pageWrapper">
        <slot />
        <Toaster />
        <ConfirmDialog />
    </SidebarProvider>
</template>
