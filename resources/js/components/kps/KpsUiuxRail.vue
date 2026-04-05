<script setup lang="ts">
import type { AppPageProps } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import {
    BarChart3,
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

const can = (permission: string) =>
    (page.props.auth?.permissions ?? []).includes(permission);

const navItems = computed<NavItem[]>(() =>
    [
        {
            title: 'Dashboard',
            href: '/kps/dashboard',
            icon: LayoutGrid,
            active: page.url.startsWith('/kps/dashboard'),
        },
        {
            title: 'Analytics',
            href: '/kps/analytics',
            icon: BarChart3,
            active: page.url.startsWith('/kps/analytics'),
            show: can('kps.manage_sites'),
        },
        {
            title: 'Sites',
            href: '/kps/sites',
            icon: Warehouse,
            active: page.url.startsWith('/kps/sites'),
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

                <div class="flex h-11 w-11 items-center justify-center rounded-full border border-[#ead6ce] bg-[#fff1ec] text-xs font-black text-[#b64a2b]">
                    {{ currentUserInitials }}
                </div>
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

    <aside class="fixed left-0 top-0 z-40 hidden h-screen w-[88px] flex-col items-center rounded-r-[32px] border-r border-[#f0dbd4] bg-[#171717] py-7 text-white shadow-[0_22px_70px_rgba(0,0,0,0.32)] lg:flex">
        <Link
            href="/kps"
            class="mb-8 flex h-12 w-12 items-center justify-center rounded-[18px] bg-[#d84b27] text-base font-black text-white shadow-[0_10px_28px_rgba(216,75,39,0.35)]"
            aria-label="Open KPS dashboard"
        >
            K
        </Link>

        <nav class="flex flex-1 flex-col items-center gap-5">
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
            <div class="flex h-11 w-11 items-center justify-center rounded-full border border-white/12 bg-white/8 text-xs font-bold text-white/88">
                {{ currentUserInitials }}
            </div>
            <div class="flex h-11 w-11 items-center justify-center rounded-full border border-white/10 bg-white/6 text-[10px] font-bold uppercase tracking-[0.24em] text-white/55">
                HQ
            </div>
        </div>
    </aside>
</template>
