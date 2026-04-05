<script setup lang="ts">
import type { KpsSite, KpsSiteRole } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import {
    BarChart3,
    ClipboardList,
    FileText,
    History,
    LayoutGrid,
    Settings,
    Users,
    Wallet,
    type LucideIcon,
} from 'lucide-vue-next';
import { computed } from 'vue';
import { usePermission } from '@/composables/usePermission';

interface NavItem {
    title: string;
    href: string;
    icon: LucideIcon;
    active: boolean;
    permission?: string;
}

const props = withDefaults(
    defineProps<{
        site?: KpsSite | null;
        siteRole?: KpsSiteRole | null;
        withRail?: boolean;
    }>(),
    {
        site: null,
        siteRole: null,
        withRail: false,
    },
);

const page = usePage();
const { hasPermission } = usePermission();
const siteBaseUrl = computed(() =>
    props.site ? `/kps/sites/${props.site.id}` : '/kps/sites',
);

const navItems = computed<NavItem[]>(() => {
    if (!props.site) return [];

    const baseUrl = `/kps/sites/${props.site.id}`;

    return [
        {
            title: 'Site Dashboard',
            href: baseUrl,
            icon: LayoutGrid,
            active: page.url.startsWith(baseUrl),
        },
        {
            title: 'Peneroka',
            href: `${baseUrl}/peneroka`,
            icon: Users,
            active: page.url.startsWith(`${baseUrl}/peneroka`),
            permission: 'kps.manage_peneroka',
        },
        {
            title: 'Hutang',
            href: `${baseUrl}/hutang`,
            icon: ClipboardList,
            active: page.url.startsWith(`${baseUrl}/hutang`),
            permission: 'kps.manage_hutang',
        },
        {
            title: 'Potongan Bulanan',
            href: `${baseUrl}/potongan`,
            icon: Wallet,
            active: page.url.startsWith(`${baseUrl}/potongan`),
            permission: 'kps.manage_potongan',
        },
        {
            title: 'Allocation Review',
            href: `${baseUrl}/allocations`,
            icon: BarChart3,
            active: page.url.startsWith(`${baseUrl}/allocations`),
            permission: 'kps.manage_potongan',
        },
        {
            title: 'Reports',
            href: `${baseUrl}/reports`,
            icon: FileText,
            active: page.url.startsWith(`${baseUrl}/reports`),
            permission: 'kps.view_reports',
        },
        {
            title: 'Audit Trail',
            href: `${baseUrl}/audit-logs`,
            icon: History,
            active: page.url.startsWith(`${baseUrl}/audit-logs`),
            permission: 'kps.approve_month',
        },
        {
            title: 'Site Settings',
            href: `${baseUrl}/edit`,
            icon: Settings,
            active: page.url.startsWith(`${baseUrl}/edit`),
            permission: 'kps.manage_sites',
        },
    ].filter((item) => !item.permission || hasPermission(item.permission));
});

const siteRoleLabel = computed(() =>
    (props.siteRole ?? 'staff').replace('_', ' '),
);

const siteStatusLabel = computed(() =>
    props.site?.is_active ? 'Active site' : 'Paused site',
);
</script>

<template>
    <div
        v-if="props.site"
        class="px-4 pt-4 lg:hidden"
    >
        <div class="rounded-[28px] border border-[#ead6ce] bg-white/88 p-4 shadow-[0_18px_50px_rgba(157,80,53,0.08)] backdrop-blur-xl">
            <div class="flex items-start justify-between gap-4">
                <Link
                    :href="siteBaseUrl"
                    class="min-w-0"
                >
                    <p class="text-[11px] font-bold uppercase tracking-[0.28em] text-[#b7654b]">Site workspace</p>
                    <h2 class="mt-1 truncate text-lg font-black text-[#1b1b1b]">
                        {{ props.site.name }}
                    </h2>
                    <p class="mt-1 text-sm text-[#65534d]">
                        {{ props.site.code }}
                    </p>
                </Link>
                <span
                    class="rounded-full px-3 py-1 text-[10px] font-bold uppercase tracking-[0.18em]"
                    :class="props.site.is_active ? 'bg-[#ebfff3] text-[#18754d]' : 'bg-[#fff1ec] text-[#b64a2b]'"
                >
                    {{ siteStatusLabel }}
                </span>
            </div>

            <div class="mt-4 flex items-center gap-2 text-xs text-[#866c64]">
                <span class="rounded-full bg-[#171717] px-3 py-1 font-semibold uppercase tracking-[0.2em] text-white">
                    {{ siteRoleLabel }}
                </span>
                <span>{{ props.site.code }}</span>
            </div>

            <div class="mt-4 flex gap-2 overflow-x-auto pb-1">
                <Link
                    v-for="item in navItems"
                    :key="item.href"
                    :href="item.href"
                    class="inline-flex items-center gap-2 rounded-full px-3 py-2 text-xs font-semibold whitespace-nowrap transition"
                    :class="item.active ? 'bg-[#171717] text-white' : 'bg-[#fff7f3] text-[#65463b] ring-1 ring-[#efd8cf]'"
                    :aria-current="item.active ? 'page' : undefined"
                >
                    <component :is="item.icon" class="h-4 w-4" />
                    <span>{{ item.title }}</span>
                </Link>
            </div>
        </div>
    </div>

    <aside
        v-if="props.site"
        class="fixed top-0 z-30 hidden h-screen w-[320px] flex-col border-r border-[#ead6ce] bg-[rgba(247,241,238,0.86)] px-6 py-8 text-[#1b1b1b] backdrop-blur-2xl lg:flex"
        :class="props.withRail ? 'left-[88px]' : 'left-0'"
    >
        <div class="rounded-[28px] border border-[#f0dbd4] bg-white/88 p-5 shadow-[0_18px_50px_rgba(157,80,53,0.08)]">
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <p class="text-[11px] font-bold uppercase tracking-[0.28em] text-[#b7654b]">Site workspace</p>
                    <Link
                        :href="siteBaseUrl"
                        class="mt-2 block"
                    >
                        <h2 class="truncate text-xl font-black text-[#1b1b1b]">
                            {{ props.site.name }}
                        </h2>
                    </Link>
                    <p class="mt-2 text-sm leading-6 text-[#65534d]">
                        {{ props.site.code }} - {{ siteRoleLabel }}
                    </p>
                </div>
                <span
                    class="rounded-full px-3 py-1 text-[10px] font-bold uppercase tracking-[0.18em]"
                    :class="props.site.is_active ? 'bg-[#ebfff3] text-[#18754d]' : 'bg-[#fff1ec] text-[#b64a2b]'"
                >
                    {{ siteStatusLabel }}
                </span>
            </div>
        </div>

        <div class="mt-7">
            <p class="mb-3 text-[11px] font-bold uppercase tracking-[0.28em] text-[#ad7d70]">Workspace</p>
            <div class="space-y-2">
                <Link
                    v-for="item in navItems"
                    :key="item.href"
                    :href="item.href"
                    class="flex items-center justify-between rounded-[22px] border px-4 py-3 transition"
                    :class="item.active
                        ? 'border-[#d6522d] bg-[#171717] text-white shadow-[0_16px_30px_rgba(0,0,0,0.18)]'
                        : 'border-[#ead6ce] bg-white/80 text-[#2a2422] hover:border-[#d8b4a6]'"
                    :aria-current="item.active ? 'page' : undefined"
                >
                    <span class="flex items-center gap-3">
                        <span
                            class="flex h-10 w-10 items-center justify-center rounded-full"
                            :class="item.active
                                ? 'bg-white/12 text-white'
                                : 'bg-[#fff1ec] text-[#d6522d]'"
                        >
                            <component :is="item.icon" class="h-4 w-4" />
                        </span>
                        <span>
                            <span class="block text-sm font-semibold">{{ item.title }}</span>
                            <span
                                class="block text-[11px] uppercase tracking-[0.24em]"
                                :class="item.active ? 'text-white/55' : 'text-[#91756e]'"
                            >
                                Live route
                            </span>
                        </span>
                    </span>
                </Link>
            </div>
        </div>
    </aside>
</template>
