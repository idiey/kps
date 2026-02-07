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
import { cn } from '@/lib/utils';
import { digitWorkshop } from '@/styles/digit-workshop-ui';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { ChevronRight } from 'lucide-vue-next';

const props = withDefaults(
    defineProps<{
        items: NavItem[];
        label?: string;
    }>(),
    {
        label: 'Platform',
    },
);

const page = usePage();
const iconPalette = [
    'bg-[#EAF7EE] text-[#84C024]',
    'bg-[#E8F0FF] text-[#4A6FD8]',
    'bg-[#FFF4D8] text-[#C9821B]',
    'bg-[#F1E9FF] text-[#7A55D8]',
    'bg-[#E6F6F7] text-[#1F7E91]',
] as const;

const iconChipClass = (index: number) =>
    cn('flex size-8 items-center justify-center rounded-xl', iconPalette[index % iconPalette.length]);

const menuItemClass = (active: boolean) =>
    cn(digitWorkshop.sidebar.sidebarItem, active && digitWorkshop.sidebar.sidebarItemActive);

// Check if URL is active (exact match or prefix match)
const isActive = (href: string): boolean => {
    return page.url === href || page.url.startsWith(href + '/');
};

// Check if any child is active
const isChildActive = (item: NavItem): boolean => {
    return !!item.children?.some(child => isActive(child.href as string));
};
</script>

<template>
    <SidebarGroup class="px-2 py-0">
        <SidebarGroupLabel :class="digitWorkshop.sidebar.sidebarGroupLabel">
            {{ props.label }}
        </SidebarGroupLabel>
        <SidebarMenu>
            <template v-for="(item, index) in items" :key="item.title">
                <!-- Item with children: collapsible menu -->
                <Collapsible
                    v-if="item.children && item.children.length > 0"
                    as-child
                    :default-open="isActive(item.href as string) || isChildActive(item)"
                    class="group/collapsible"
                >
                    <SidebarMenuItem>
                        <CollapsibleTrigger as-child>
                            <SidebarMenuButton
                                :is-active="isActive(item.href as string) || isChildActive(item)"
                                :tooltip="item.title"
                                :class="menuItemClass(isActive(item.href as string) || isChildActive(item))"
                            >
                                <span :class="iconChipClass(index)">
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
                                        :is-active="isActive(subItem.href as string)"
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
                        :is-active="isActive(item.href as string)"
                        :tooltip="item.title"
                        :class="menuItemClass(isActive(item.href as string))"
                    >
                        <Link :href="item.href">
                            <span :class="iconChipClass(index)">
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
