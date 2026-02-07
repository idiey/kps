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
const showMainSidebar = computed(() => !(page.props.auth?.isSiteAdminOnly ?? false));
</script>

<template>
    <AppShell variant="sidebar">
        <AppSidebar v-if="showMainSidebar" />
        <AppContent variant="sidebar" class="overflow-x-hidden">
            <AppSidebarHeader :breadcrumbs="breadcrumbs" :show-sidebar-trigger="showMainSidebar" />
            <slot />
        </AppContent>
    </AppShell>
</template>
