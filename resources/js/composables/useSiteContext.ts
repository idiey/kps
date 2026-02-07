/**
 * Site Context Composable
 *
 * Manages the currently selected site/workshop context for dual sidebar navigation.
 * Provides reactive state and helper methods for site-scoped operations.
 */
import { computed, ref } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import type { Workshop, SiteRole, SiteContext } from '@/types';

// Global state for selected site (persists across navigation)
const selectedSite = ref<Workshop | null>(null);
const selectedSiteRole = ref<SiteRole | null>(null);

export function useSiteContext(): SiteContext & {
    selectSite: (site: Workshop, role?: SiteRole) => void;
    clearSite: () => void;
    navigateToSite: (siteId: string) => void;
    navigateToSiteDashboard: () => void;
} {
    const page = usePage();

    // Check if site is passed via page props (from controller)
    const pageSite = computed(() => (page.props as any).site as Workshop | undefined);
    const pageSiteRole = computed(() => (page.props as any).siteRole as SiteRole | undefined);

    // Use page prop if available, otherwise use global state
    const site = computed(() => pageSite.value ?? selectedSite.value);
    const siteRole = computed(() => pageSiteRole.value ?? selectedSiteRole.value);

    const isSiteSelected = computed(() => site.value !== null);
    const isSiteAdmin = computed(() => siteRole.value === 'site_admin');
    const isSupervisor = computed(() => siteRole.value === 'supervisor');

    /**
     * Select a site and optionally set the user's role at that site.
     */
    function selectSite(newSite: Workshop, role?: SiteRole) {
        selectedSite.value = newSite;
        selectedSiteRole.value = role ?? null;
    }

    /**
     * Clear the site selection (return to company context).
     */
    function clearSite() {
        selectedSite.value = null;
        selectedSiteRole.value = null;
    }

    /**
     * Navigate to a specific site's dashboard.
     */
    function navigateToSite(siteId: string) {
        router.visit(`/admin/workshops/${siteId}`);
    }

    /**
     * Navigate to the current site's dashboard.
     */
    function navigateToSiteDashboard() {
        if (site.value) {
            router.visit(`/admin/workshops/${site.value.id}`);
        }
    }

    return {
        site: site.value,
        siteRole: siteRole.value,
        isSiteSelected: isSiteSelected.value,
        isSiteAdmin: isSiteAdmin.value,
        isSupervisor: isSupervisor.value,
        selectSite,
        clearSite,
        navigateToSite,
        navigateToSiteDashboard,
    };
}

/**
 * Get reactive site context for use in templates.
 * Returns computed refs instead of raw values.
 */
export function useSiteContextReactive() {
    const page = usePage();

    const pageSite = computed(() => (page.props as any).site as Workshop | undefined);
    const pageSiteRole = computed(() => (page.props as any).siteRole as SiteRole | undefined);

    const site = computed(() => pageSite.value ?? selectedSite.value);
    const siteRole = computed(() => pageSiteRole.value ?? selectedSiteRole.value);

    return {
        site,
        siteRole,
        isSiteSelected: computed(() => site.value !== null),
        isSiteAdmin: computed(() => siteRole.value === 'site_admin'),
        isSupervisor: computed(() => siteRole.value === 'supervisor'),
        selectSite: (newSite: Workshop, role?: SiteRole) => {
            selectedSite.value = newSite;
            selectedSiteRole.value = role ?? null;
        },
        clearSite: () => {
            selectedSite.value = null;
            selectedSiteRole.value = null;
        },
    };
}
