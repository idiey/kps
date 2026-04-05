<script setup lang="ts">
import {
    Collapsible,
    CollapsibleContent,
    CollapsibleTrigger,
} from '@/components/ui/collapsible';
import {
    SidebarGroup,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarMenuSub,
    SidebarMenuSubButton,
    SidebarMenuSubItem,
} from '@/components/ui/sidebar';
import { cn, toUrl } from '@/lib/utils';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { ChevronRight } from 'lucide-vue-next';

const props = withDefaults(
    defineProps<{
        items: NavItem[];
        label?: string;
        theme?: 'default' | 'dark' | 'warm';
    }>(),
    {
        label: 'Platform',
        theme: 'default',
    },
);

const page = usePage();
const iconPalettes = {
    default: [
        'bg-[#EAF7EE] text-[#84C024]',
        'bg-[#E8F0FF] text-[#4A6FD8]',
        'bg-[#FFF4D8] text-[#C9821B]',
        'bg-[#F1E9FF] text-[#7A55D8]',
        'bg-[#E6F6F7] text-[#1F7E91]',
    ],
    dark: [
        'bg-white/8 text-[#FFD2BF]',
        'bg-white/8 text-[#F6C45D]',
        'bg-white/8 text-[#96D6FF]',
        'bg-white/8 text-[#F9A86E]',
        'bg-white/8 text-[#D8C7FF]',
    ],
    warm: [
        'bg-white/42 text-[#8C2D08]',
        'bg-white/42 text-[#662100]',
        'bg-white/42 text-[#5E2B0A]',
        'bg-white/42 text-[#7A2D05]',
        'bg-white/42 text-[#4B1B00]',
    ],
} as const;

const themeClasses = {
    default: {
        group: 'px-2 py-0',
        label: 'mb-2 text-xs font-medium text-sidebar-foreground/70',
        item: 'group/menu-item rounded-2xl text-sidebar-foreground/90 transition-[background-color,color,box-shadow] hover:bg-sidebar-accent hover:text-sidebar-accent-foreground data-[active=true]:bg-sidebar-accent data-[active=true]:text-sidebar-accent-foreground',
        subItem: 'rounded-xl text-sidebar-foreground/80 transition-[background-color,color] hover:bg-sidebar-accent hover:text-sidebar-accent-foreground data-[active=true]:bg-sidebar-accent data-[active=true]:text-sidebar-accent-foreground',
        activeIcon: 'bg-sidebar-primary text-sidebar-primary-foreground',
        adminIcon: 'bg-[#EEF2FF] text-[#4F46E5]',
    },
    dark: {
        group: 'px-2 py-0',
        label: 'mb-3 px-2 text-[11px] font-bold uppercase tracking-[0.28em] text-white/38',
        item: 'group/menu-item rounded-[20px] text-white/68 transition-[background-color,color,box-shadow] hover:bg-white/8 hover:text-white data-[active=true]:bg-[#F97316] data-[active=true]:text-white data-[active=true]:shadow-[0_16px_30px_rgba(249,115,22,0.28)]',
        subItem: 'rounded-xl text-white/70 transition-[background-color,color] hover:bg-white/8 hover:text-white data-[active=true]:bg-white/10 data-[active=true]:text-white',
        activeIcon: 'bg-white/14 text-white',
        adminIcon: 'bg-white/10 text-[#FFB37F]',
    },
    warm: {
        group: 'px-2 py-0',
        label: 'mb-3 px-2 text-[11px] font-bold uppercase tracking-[0.28em] text-[#5A2706]/54',
        item: 'group/menu-item rounded-[20px] text-[#4B1D04]/78 transition-[background-color,color,box-shadow] hover:bg-black/8 hover:text-[#1B0B03] data-[active=true]:bg-[#171717] data-[active=true]:text-white data-[active=true]:shadow-[0_16px_28px_rgba(23,23,23,0.24)]',
        subItem: 'rounded-xl text-[#4B1D04]/76 transition-[background-color,color] hover:bg-black/8 hover:text-[#1B0B03] data-[active=true]:bg-white/45 data-[active=true]:text-[#1B0B03]',
        activeIcon: 'bg-white/14 text-white',
        adminIcon: 'bg-white/45 text-[#692300]',
    },
} as const;

const iconChipClass = (item: NavItem, index: number, active: boolean) => {
    const palette = iconPalettes[props.theme];
    const theme = themeClasses[props.theme];

    if (active) {
        return cn('flex size-8 items-center justify-center rounded-xl transition-colors', theme.activeIcon);
    }

    if (item.title === 'Administration') {
        return cn('flex size-8 items-center justify-center rounded-xl transition-colors', theme.adminIcon);
    }

    return cn('flex size-8 items-center justify-center rounded-xl transition-colors', palette[index % palette.length]);
};

const menuItemClass = () => themeClasses[props.theme].item;
const subItemClass = () => themeClasses[props.theme].subItem;

const normalizeUrl = (url: string) => url.split(/[?#]/)[0];

// Check if URL is active (exact match or prefix match)
const isActive = (item: NavItem): boolean => {
    if (typeof item.isActive === 'boolean') {
        return item.isActive;
    }

    const href = toUrl(item.href);
    if (!href) {
        return false;
    }

    const currentUrl = normalizeUrl(page.url);
    const targetUrl = normalizeUrl(href);
    if (item.children && item.children.length > 0) {
        return currentUrl === targetUrl;
    }

    return currentUrl === targetUrl || currentUrl.startsWith(targetUrl + '/');
};

// Check if any child is active
const isChildActive = (item: NavItem): boolean => {
    return !!item.children?.some(child => isActive(child));
};
</script>

<template>
    <SidebarGroup :class="themeClasses[props.theme].group">
        <SidebarGroupLabel :class="themeClasses[props.theme].label">
            {{ props.label }}
        </SidebarGroupLabel>
        <SidebarMenu>
            <template v-for="(item, index) in items" :key="item.title">
                <!-- Item with children: collapsible menu -->
                <Collapsible
                    v-if="item.children && item.children.length > 0"
                    as-child
                    :default-open="isActive(item) || isChildActive(item)"
                    class="group/collapsible"
                >
                    <SidebarMenuItem>
                        <CollapsibleTrigger as-child>
                            <SidebarMenuButton
                                :is-active="isActive(item) || isChildActive(item)"
                                :tooltip="item.title"
                                :class="menuItemClass()"
                            >
                                <span :class="iconChipClass(item, index, isActive(item) || isChildActive(item))">
                                    <component :is="item.icon" class="size-4" />
                                </span>
                                <span>{{ item.title }}</span>
                                <ChevronRight class="ml-auto transition-transform duration-200 group-data-[state=open]/collapsible:rotate-90" />
                            </SidebarMenuButton>
                        </CollapsibleTrigger>
                        <CollapsibleContent>
                            <SidebarMenuSub>
                                <SidebarMenuSubItem v-for="subItem in item.children" :key="subItem.title">
                                    <SidebarMenuSubButton
                                        as-child
                                        :is-active="isActive(subItem)"
                                        :class="subItemClass()"
                                    >
                                        <Link :href="subItem.href">
                                            <component v-if="subItem.icon" :is="subItem.icon" />
                                            <span>{{ subItem.title }}</span>
                                        </Link>
                                    </SidebarMenuSubButton>
                                </SidebarMenuSubItem>
                            </SidebarMenuSub>
                        </CollapsibleContent>
                    </SidebarMenuItem>
                </Collapsible>

                <!-- Item without children: simple link -->
                <SidebarMenuItem v-else>
                    <SidebarMenuButton
                        as-child
                        :is-active="isActive(item)"
                        :tooltip="item.title"
                        :class="menuItemClass()"
                    >
                        <Link :href="item.href">
                            <span :class="iconChipClass(item, index, isActive(item))">
                                <component :is="item.icon" class="size-4" />
                            </span>
                            <span>{{ item.title }}</span>
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </template>
        </SidebarMenu>
    </SidebarGroup>
</template>
