<script setup lang="ts">
import AppContent from '@/components/AppContent.vue';
import AppShell from '@/components/AppShell.vue';
import AppSidebarHeader from '@/components/AppSidebarHeader.vue';
import KpsMainSidebar from '@/components/kps/KpsMainSidebar.vue';
import KpsSiteSidebar from '@/components/kps/KpsSiteSidebar.vue';
import type { BreadcrumbItemType, KpsSite, KpsSiteRole } from '@/types';
import { router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
    site?: KpsSite | null;
    siteRole?: KpsSiteRole | null;
    forceSiteOnly?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
    site: null,
    siteRole: null,
    forceSiteOnly: false,
});

const page = usePage();
const isAdmin = computed(() => page.props.auth?.isCompanyAdmin ?? false);
const isHq = computed(() =>
    (page.props.auth?.permissions || []).includes('kps.manage_sites'),
);
const showMainSidebar = computed(() => isHq.value && !props.forceSiteOnly);
const showSiteSidebar = computed(() => !!props.site);
const siteSidebarCollapsible = computed(() =>
    showMainSidebar.value ? 'none' : 'icon',
);

const sidebarDefaultOpen = computed<boolean | undefined>(() => {
    if (showMainSidebar.value) {
        if (!isAdmin.value) return undefined;
        return showSiteSidebar.value ? false : true;
    }

    if (showSiteSidebar.value) {
        return true;
    }

    return undefined;
});

const forcedSidebarOpen = computed<boolean | undefined>(() => {
    if (!showMainSidebar.value) return undefined;
    return isAdmin.value ? undefined : false;
});

function handleCloseSite() {
    router.visit('/kps/sites');
}
</script>

<template>
    <AppShell
        variant="sidebar"
        :sidebar-default-open="sidebarDefaultOpen"
        :sidebar-open="forcedSidebarOpen"
    >
        <KpsMainSidebar v-if="showMainSidebar" />

        <KpsSiteSidebar
            v-if="showSiteSidebar"
            :site="site"
            :site-role="siteRole"
            :collapsible="siteSidebarCollapsible"
            @close="handleCloseSite"
        />

        <AppContent variant="sidebar" class="overflow-x-hidden">
            <AppSidebarHeader
                :breadcrumbs="breadcrumbs"
                :show-sidebar-trigger="showMainSidebar || siteSidebarCollapsible !== 'none'"
            />
            <slot />
        </AppContent>
    </AppShell>
</template>
