<script setup lang="ts">
import ConfirmDialog from '@/components/ConfirmDialog.vue';
import Toaster from '@/components/Toaster.vue';
import KpsUiuxHeader from '@/components/kps/KpsUiuxHeader.vue';
import KpsUiuxRail from '@/components/kps/KpsUiuxRail.vue';
import KpsUiuxSitePanel from '@/components/kps/KpsUiuxSitePanel.vue';
import type { BreadcrumbItemType, KpsSite, KpsSiteRole } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { computed, onBeforeUnmount, onMounted } from 'vue';

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
const isHq = computed(() =>
    (page.props.auth?.permissions || []).includes('kps.manage_sites'),
);
const showRail = computed(() => isHq.value && !props.forceSiteOnly);
const showSitePanel = computed(() => !!props.site);
const contentOffsetClass = computed(() => {
    if (showRail.value && showSitePanel.value) return 'lg:ml-[408px]';
    if (showRail.value) return 'lg:ml-[104px]';
    if (showSitePanel.value) return 'lg:ml-[320px]';
    return '';
});
const shellThemeStyle = {
    '--background': '30 100% 96%',
    '--foreground': '20 24% 14%',
    '--card': '0 0% 100%',
    '--card-foreground': '20 24% 14%',
    '--popover': '0 0% 100%',
    '--popover-foreground': '20 24% 14%',
    '--primary': '20 20% 10%',
    '--primary-foreground': '0 0% 100%',
    '--secondary': '30 100% 90%',
    '--secondary-foreground': '20 24% 14%',
    '--muted': '29 100% 92%',
    '--muted-foreground': '20 12% 38%',
    '--accent': '28 100% 90%',
    '--accent-foreground': '20 24% 14%',
    '--destructive': '0 84.2% 60.2%',
    '--destructive-foreground': '0 0% 98%',
    '--border': '27 72% 82%',
    '--input': '27 78% 86%',
    '--ring': '24 95% 58%',
};
const shellThemeEntries = Object.entries(shellThemeStyle);
const previousBodyTheme = new Map<string, string>();

onMounted(() => {
    shellThemeEntries.forEach(([key, value]) => {
        previousBodyTheme.set(key, document.body.style.getPropertyValue(key));
        document.body.style.setProperty(key, value);
    });

    document.body.dataset.uiTheme = 'kps';
});

onBeforeUnmount(() => {
    shellThemeEntries.forEach(([key]) => {
        const previousValue = previousBodyTheme.get(key) ?? '';

        if (previousValue) {
            document.body.style.setProperty(key, previousValue);
            return;
        }

        document.body.style.removeProperty(key);
    });

    delete document.body.dataset.uiTheme;
});
</script>

<template>
    <div
        class="min-h-screen overflow-x-hidden bg-[radial-gradient(circle_at_top_right,_rgba(249,115,22,0.18),_transparent_24%),radial-gradient(circle_at_bottom_left,_rgba(253,186,116,0.32),_transparent_28%),linear-gradient(180deg,#fff5eb_0%,#ffe7cf_52%,#ffd7b3_100%)] text-foreground"
        :style="shellThemeStyle"
        data-kps-uiux-shell="true"
    >
        <KpsUiuxRail v-if="showRail" />
        <KpsUiuxSitePanel
            v-if="showSitePanel"
            :site="site"
            :site-role="siteRole"
            :with-rail="showRail"
        />

        <div :class="contentOffsetClass" class="relative z-10">
            <KpsUiuxHeader
                :breadcrumbs="props.breadcrumbs"
                :site="props.site"
                :site-role="props.siteRole"
            />
            <main class="px-4 pb-10 lg:px-8">
                <slot />
            </main>
        </div>

        <Toaster />
        <ConfirmDialog />
    </div>
</template>
