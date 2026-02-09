<script setup lang="ts">
import AppContent from '@/components/AppContent.vue';
import AppShell from '@/components/AppShell.vue';
import AppSidebar from '@/components/AppSidebar.vue';
import AppSidebarHeader from '@/components/AppSidebarHeader.vue';
import type { BreadcrumbItemType } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

const page = usePage();
const isGlobalAdmin = computed(() => page.props.auth?.isGlobalAdmin ?? false);
const isCompanyAdmin = computed(() => page.props.auth?.isCompanyAdmin ?? false);
const showMainSidebar = computed(() => isCompanyAdmin.value);
const lockSidebarOpen = computed(() => isGlobalAdmin.value);
const forcedSidebarOpen = computed<boolean | undefined>(() =>
    lockSidebarOpen.value ? true : undefined,
);
const sidebarDefaultOpen = computed(() =>
    showMainSidebar.value ? true : undefined,
);
const showSidebarTrigger = computed(
    () => showMainSidebar.value && !lockSidebarOpen.value,
);
</script>

<template>
    <AppShell
        variant="sidebar"
        :sidebar-open="forcedSidebarOpen"
        :sidebar-default-open="sidebarDefaultOpen"
    >
        <AppSidebar v-if="showMainSidebar" />
        <AppContent variant="sidebar" class="overflow-x-hidden">
            <AppSidebarHeader
                :breadcrumbs="breadcrumbs"
                :show-sidebar-trigger="showSidebarTrigger"
            />
            <slot />
        </AppContent>
    </AppShell>
</template>
