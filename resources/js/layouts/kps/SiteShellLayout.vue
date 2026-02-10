<script setup lang="ts">
import KpsShellLayout from '@/layouts/kps/KpsShellLayout.vue';
import type { BreadcrumbItemType, KpsSite, KpsSiteRole } from '@/types';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
    site: KpsSite;
    siteRole?: KpsSiteRole | null;
}

const props = withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
    siteRole: null,
});
</script>

<template>
    <KpsShellLayout
        :breadcrumbs="props.breadcrumbs"
        :site="props.site"
        :site-role="props.siteRole"
        :force-site-only="true"
    >
        <slot />
    </KpsShellLayout>
</template>
