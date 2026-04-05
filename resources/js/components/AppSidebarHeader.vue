<script setup lang="ts">
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import { SidebarTrigger } from '@/components/ui/sidebar';
import type { BreadcrumbItemType } from '@/types';
import { computed } from 'vue';

const props = withDefaults(
    defineProps<{
        breadcrumbs?: BreadcrumbItemType[];
        showSidebarTrigger?: boolean;
        theme?: 'default' | 'kps';
    }>(),
    {
        breadcrumbs: () => [],
        showSidebarTrigger: true,
        theme: 'default',
    },
);

const isKpsTheme = computed(() => props.theme === 'kps');
const headerClass = computed(() =>
    isKpsTheme.value
        ? 'px-4 pt-4 lg:px-8 lg:pt-6'
        : 'flex h-16 shrink-0 items-center justify-between gap-3 border-b border-sidebar-border/70 px-6 transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12 md:px-4',
);
const containerClass = computed(() =>
    isKpsTheme.value
        ? 'flex min-h-[4.5rem] items-center justify-between gap-3 rounded-[28px] border border-white/70 bg-white/72 px-4 py-3 shadow-[0_18px_50px_rgba(156,81,32,0.12)] backdrop-blur-xl'
        : 'flex w-full items-center justify-between gap-3',
);
const triggerClass = computed(() =>
    isKpsTheme.value
        ? '-ml-1 rounded-full border border-[#efc29d] bg-[#fff7f1] text-[#8a3c12] shadow-sm hover:bg-white'
        : '-ml-1',
);
</script>

<template>
    <header :class="headerClass">
        <div :class="containerClass">
            <div class="flex items-center gap-2">
                <SidebarTrigger v-if="props.showSidebarTrigger" :class="triggerClass" />
                <span
                    v-if="isKpsTheme"
                    class="hidden rounded-full bg-[#171717] px-3 py-2 text-[10px] font-bold uppercase tracking-[0.24em] text-white md:inline-flex"
                >
                    KPS Live
                </span>
                <template v-if="props.breadcrumbs && props.breadcrumbs.length > 0">
                    <Breadcrumbs :breadcrumbs="props.breadcrumbs" />
                </template>
            </div>
        </div>
    </header>
</template>
