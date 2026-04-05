<script setup lang="ts">
import UserMenuContent from '@/components/UserMenuContent.vue';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import type { AppPageProps } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import {
    BarChart3,
    ChevronsUpDown,
    LayoutGrid,
    Shield,
    Users,
    Warehouse,
    type LucideIcon,
} from 'lucide-vue-next';
import { computed } from 'vue';

interface NavItem {
    title: string;
    href: string;
    icon: LucideIcon;
    active: boolean;
    show?: boolean;
}

const page = usePage<AppPageProps>();
const props = withDefaults(
    defineProps<{
        withSitePanel?: boolean;
    }>(),
    {
        withSitePanel: false,
    },
);

const can = (permission: string) =>
    (page.props.auth?.permissions ?? []).includes(permission);

const isExactUrlActive = (href: string) =>
    page.url === href || page.url.startsWith(`${href}?`);

const navItems = computed<NavItem[]>(() =>
    [
        {
            title: 'Dashboard',
            href: '/kps/dashboard',
            icon: LayoutGrid,
            active: isExactUrlActive('/kps/dashboard'),
        },
        {
            title: 'Analytics',
            href: '/kps/analytics',
            icon: BarChart3,
            active: isExactUrlActive('/kps/analytics'),
            show: can('kps.manage_sites'),
        },
        {
            title: 'Sites',
            href: '/kps/sites',
            icon: Warehouse,
            active: isExactUrlActive('/kps/sites'),
            show: can('kps.manage_sites'),
        },
        {
            title: 'Users',
            href: '/admin/users',
            icon: Users,
            active: page.url.startsWith('/admin/users'),
            show: can('view-users'),
        },
        {
            title: 'Roles',
            href: '/admin/roles',
            icon: Shield,
            active: page.url.startsWith('/admin/roles'),
            show: can('view-roles'),
        },
    ].filter((item) => item.show !== false),
);

const currentUserInitials = computed(() => {
    const name = page.props.auth?.user?.name ?? 'KPS';

    return name
        .split(' ')
        .map((part) => part.charAt(0))
        .join('')
        .slice(0, 2)
        .toUpperCase();
});

const currentUserName = computed(() => page.props.auth?.user?.name ?? 'KPS User');
const currentUser = computed(() => page.props.auth?.user);
</script>

<template>
    <div class="px-4 pt-4 lg:hidden">
        <div class="rounded-[28px] border border-[#f0dbd4] bg-white/86 p-4 shadow-[0_18px_50px_rgba(157,80,53,0.08)] backdrop-blur-xl">
            <div class="flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <Link
                        href="/kps"
                        class="flex h-11 w-11 items-center justify-center rounded-[18px] bg-[#d84b27] text-sm font-black text-white shadow-[0_10px_28px_rgba(216,75,39,0.28)]"
                        aria-label="Open KPS dashboard"
                    >
                        K
                    </Link>
                    <div>
                        <p class="text-sm font-semibold text-[#1b1b1b]">KPS Live</p>
                        <p class="text-[11px] uppercase tracking-[0.28em] text-[#b7654b]">HQ rail</p>
                    </div>
                </div>

                <DropdownMenu v-if="currentUser">
                    <DropdownMenuTrigger as-child>
                        <button
                            type="button"
                            class="flex h-11 w-11 items-center justify-center rounded-full border border-[#ead6ce] bg-[#fff1ec] text-xs font-black text-[#b64a2b]"
                        >
                            {{ currentUserInitials }}
                        </button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent
                        align="end"
                        class="w-56 rounded-xl border border-[#e7d5ce] bg-[#fffaf7] text-[#2d241f] shadow-[0_20px_45px_rgba(68,34,20,0.18)]"
                    >
                        <UserMenuContent :user="currentUser" settings-href="/kps/profile" />
                    </DropdownMenuContent>
                </DropdownMenu>
            </div>

            <nav class="mt-4 flex gap-2 overflow-x-auto pb-1">
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
            </nav>

        </div>
    </div>

    <aside
        v-if="props.withSitePanel"
        class="fixed left-0 top-0 z-40 hidden h-screen w-[88px] flex-col items-center overflow-hidden rounded-r-[32px] border-r border-[#f0dbd4] bg-[#171717] py-7 text-white shadow-[0_22px_70px_rgba(0,0,0,0.32)] lg:flex"
    >
        <Link
            href="/kps"
            class="mb-8 flex h-12 w-12 items-center justify-center rounded-[18px] bg-[#d84b27] text-base font-black text-white shadow-[0_10px_28px_rgba(216,75,39,0.35)]"
            aria-label="Open KPS dashboard"
        >
            K
        </Link>

        <nav class="scrollbar-hide flex min-h-0 flex-1 flex-col items-center gap-5 overflow-y-auto px-1">
            <div
                v-for="item in navItems"
                :key="item.href"
                class="group relative"
            >
                <Link
                    :href="item.href"
                    class="flex h-12 w-12 items-center justify-center rounded-full transition"
                    :class="item.active
                        ? 'bg-white text-[#171717] shadow-[0_12px_30px_rgba(0,0,0,0.28)]'
                        : 'text-white/52 hover:bg-white/8 hover:text-white'"
                    :aria-current="item.active ? 'page' : undefined"
                >
                    <component :is="item.icon" class="h-5 w-5" />
                </Link>
                <span class="pointer-events-none absolute left-16 top-1/2 hidden -translate-y-1/2 rounded-full bg-black px-3 py-1 text-[10px] font-bold uppercase tracking-[0.24em] text-white opacity-0 transition group-hover:block group-hover:opacity-100">
                    {{ item.title }}
                </span>
            </div>
        </nav>

        <div class="mt-6 flex flex-col items-center gap-4">
            <DropdownMenu v-if="currentUser">
                <DropdownMenuTrigger as-child>
                    <button
                        type="button"
                        class="flex h-11 w-11 items-center justify-center rounded-full border border-white/12 bg-white/8 text-xs font-bold text-white/88 transition hover:bg-white/15"
                    >
                        {{ currentUserInitials }}
                    </button>
                </DropdownMenuTrigger>
                <DropdownMenuContent
                    side="right"
                    align="end"
                    class="w-56 rounded-xl border border-[#e7d5ce] bg-[#fffaf7] text-[#2d241f] shadow-[0_20px_45px_rgba(68,34,20,0.18)]"
                >
                    <UserMenuContent :user="currentUser" settings-href="/kps/profile" />
                </DropdownMenuContent>
            </DropdownMenu>
            <div class="flex h-11 w-11 items-center justify-center rounded-full border border-white/10 bg-white/6 text-[10px] font-bold uppercase tracking-[0.24em] text-white/55">
                HQ
            </div>
        </div>
    </aside>

    <aside
        v-else
        class="fixed left-0 top-0 z-40 hidden h-screen w-[280px] flex-col overflow-hidden border-r border-[#f0dbd4] bg-[#171717] px-6 py-7 text-white shadow-[0_22px_70px_rgba(0,0,0,0.32)] lg:flex"
    >
        <Link
            href="/kps"
            class="flex items-center gap-3 rounded-[22px] border border-white/10 bg-white/5 px-4 py-4 shadow-[0_10px_28px_rgba(0,0,0,0.12)]"
            aria-label="Open KPS dashboard"
        >
            <span class="flex h-12 w-12 items-center justify-center rounded-[18px] bg-[#d84b27] text-base font-black text-white shadow-[0_10px_28px_rgba(216,75,39,0.35)]">
                K
            </span>
            <span class="min-w-0">
                <span class="block text-sm font-semibold text-white">KPS Live</span>
                <span class="block text-[11px] uppercase tracking-[0.24em] text-white/45">HQ shell</span>
            </span>
        </Link>

        <nav class="scrollbar-hide mt-8 flex min-h-0 flex-1 flex-col gap-2 overflow-y-auto pr-1">
            <Link
                v-for="item in navItems"
                :key="item.href"
                :href="item.href"
                class="flex items-center gap-3 rounded-[22px] px-4 py-3 transition"
                :class="item.active
                    ? 'bg-white text-[#171717] shadow-[0_12px_30px_rgba(0,0,0,0.22)]'
                    : 'text-white/72 hover:bg-white/8 hover:text-white'"
                :aria-current="item.active ? 'page' : undefined"
            >
                <span
                    class="flex h-11 w-11 items-center justify-center rounded-full"
                    :class="item.active ? 'bg-[#171717] text-white' : 'bg-white/10 text-white/68'"
                >
                    <component :is="item.icon" class="h-5 w-5" />
                </span>
                <span class="min-w-0">
                    <span class="block text-sm font-semibold">{{ item.title }}</span>
                    <span class="block text-[11px] uppercase tracking-[0.24em] opacity-70">Live route</span>
                </span>
            </Link>
        </nav>

        <DropdownMenu v-if="currentUser">
            <DropdownMenuTrigger as-child>
                <button
                    type="button"
                    class="mt-6 flex w-full items-center gap-3 rounded-[22px] border border-white/10 bg-white/5 px-4 py-4 text-left transition hover:bg-white/10"
                >
                    <div class="flex h-11 w-11 items-center justify-center rounded-full border border-white/12 bg-white/8 text-xs font-bold text-white/88">
                        {{ currentUserInitials }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-semibold text-white">{{ currentUserName }}</p>
                        <p class="text-[11px] uppercase tracking-[0.24em] text-white/45">HQ shell</p>
                    </div>
                    <ChevronsUpDown class="h-4 w-4 text-white/70" />
                </button>
            </DropdownMenuTrigger>
            <DropdownMenuContent
                align="end"
                class="w-56 rounded-xl border border-[#e7d5ce] bg-[#fffaf7] text-[#2d241f] shadow-[0_20px_45px_rgba(68,34,20,0.18)]"
            >
                <UserMenuContent :user="currentUser" settings-href="/kps/profile" />
            </DropdownMenuContent>
        </DropdownMenu>
    </aside>
</template>
