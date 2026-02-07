<script setup lang="ts">
/**
 * Site Layout Component
 * 
 * Extended layout for site-scoped pages that includes the secondary site sidebar.
 * - Company admins (pentadbiran): see both main and site sidebars
 * - Site admins/users: see only site sidebar
 */
import AppContent from '@/components/AppContent.vue';
import AppShell from '@/components/AppShell.vue';
import AppSidebar from '@/components/AppSidebar.vue';
import AppSidebarHeader from '@/components/AppSidebarHeader.vue';
import SiteSidebar from '@/components/SiteSidebar.vue';
import type { BreadcrumbItemType, Workshop, SiteRole } from '@/types';
import { router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
    site: Workshop;
    siteRole?: SiteRole;
}

const props = withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

const page = usePage();
const roles = computed(() => (page.props.auth as any)?.roles ?? []);
const isCompanyAdmin = computed(() =>
    roles.value.includes('pentadbiran') || roles.value.includes('company_admin'),
);
const isSiteAdmin = computed(() => props.siteRole === 'site_admin');
const showMainSidebar = computed(() => isCompanyAdmin.value);
const siteSidebarCollapsible = computed(() =>
    showMainSidebar.value ? 'none' : 'icon',
);
const siteDashboardUrl = computed(() => `/admin/workshops/${props.site.id}`);
const normalizedBreadcrumbs = computed(() => {
    if (!props.breadcrumbs || props.breadcrumbs.length === 0) {
        return props.breadcrumbs;
    }

    return props.breadcrumbs.map((item) => {
        if (!item.href) {
            return item;
        }

        if (item.href === '/dashboard' || item.href === '/admin/workshops') {
            return { ...item, href: siteDashboardUrl.value };
        }

        return item;
    });
});

function handleCloseSite() {
    // Navigate back to sites list
    router.visit('/admin/workshops');
}
</script>

<template>
    <AppShell variant="sidebar">
        <!-- Company (Main) Sidebar: hidden for site admins -->
        <AppSidebar v-if="showMainSidebar" variant="sidebar" />
        
        <!-- Site (Secondary) Sidebar: always shown when accessing a site -->
        <SiteSidebar 
            :site="site" 
            :site-role="siteRole"
            :collapsible="siteSidebarCollapsible"
            @close="handleCloseSite"
        />
        
        <!-- Main Content Area -->
        <AppContent variant="sidebar" class="overflow-x-hidden">
            <AppSidebarHeader
                :breadcrumbs="normalizedBreadcrumbs"
                :show-sidebar-trigger="showMainSidebar || siteSidebarCollapsible !== 'none'"
            />
            <slot />
        </AppContent>
    </AppShell>
</template>
