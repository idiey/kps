<script setup lang="ts">
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import LanguageSwitcher from '@/components/LanguageSwitcher.vue';
import { useLocale } from '@/composables/useLocale';
import type { AppPageProps, BreadcrumbItemType, KpsSite, KpsSiteRole } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = withDefaults(
    defineProps<{
        breadcrumbs?: BreadcrumbItemType[];
        site?: KpsSite | null;
        siteRole?: KpsSiteRole | null;
    }>(),
    {
        breadcrumbs: () => [],
        site: null,
        siteRole: null,
    },
);

const page = usePage<AppPageProps>();
const { t } = useLocale();

const currentUserName = computed(() => page.props.auth?.user?.name ?? 'KPS User');
const currentUserInitials = computed(() => {
    const name = currentUserName.value;

    return name
        .split(' ')
        .map((part) => part.charAt(0))
        .join('')
        .slice(0, 2)
        .toUpperCase();
});

const siteRoleLabel = computed(() =>
    (props.siteRole ?? 'staff').replace('_', ' '),
);
</script>

<template>
    <header class="px-4 pb-4 pt-4 lg:px-8 lg:pt-6">
        <div class="sticky top-4 z-20 rounded-[30px] border border-white/80 bg-white/82 p-4 shadow-[0_20px_70px_rgba(124,73,55,0.08)] backdrop-blur-xl">
            <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
                <div class="flex min-w-0 flex-1 items-center gap-3">
                    <!-- <span class="hidden shrink-0 rounded-full bg-[#fff1ec] px-4 py-3 text-[11px] font-bold uppercase tracking-[0.24em] text-[#c55532] md:inline-flex">
                        {{ t('kps.live', 'KPS') }}
                    </span> -->

                    <div class="min-w-0">
                        <Breadcrumbs
                            v-if="props.breadcrumbs.length > 0"
                            :breadcrumbs="props.breadcrumbs"
                        />
                        <p
                            v-if="props.site"
                            class="mt-2 flex flex-wrap items-center gap-2 text-xs text-[#866c64]"
                        >
                            <span class="rounded-full bg-[#171717] px-3 py-1.5 text-xs font-semibold uppercase tracking-[0.2em] text-white">
                                {{ siteRoleLabel }}
                            </span>
                            <span class="hidden rounded-full border border-[#ead6ce] bg-[#fff7f3] px-3 py-1.5 text-xs font-semibold text-[#5e4338] md:inline-flex">
                                {{ props.site.name }} - {{ props.site.code }}
                            </span>
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    
                    

                    <LanguageSwitcher />

                    <!-- <div class="flex items-center gap-3 rounded-full bg-[#171717] px-4 py-3 text-white shadow-[0_16px_36px_rgba(0,0,0,0.18)]">
                        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-white/10 text-xs font-black tracking-[0.12em]">
                            {{ currentUserInitials }}
                        </div>
                        <div class="min-w-0">
                            <p class="truncate text-sm font-semibold">{{ currentUserName }}</p>
                            <p class="text-[11px] uppercase tracking-[0.24em] text-white/52">
                                {{ props.site ? t('kps.site_shell', 'Site shell') : t('kps.hq_shell', 'HQ shell') }}
                            </p>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </header>
</template>
