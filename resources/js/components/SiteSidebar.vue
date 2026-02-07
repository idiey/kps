<script setup lang="ts">
/**
 * Site Sidebar Component
 * 
 * Secondary sidebar displayed when a site/workshop is selected.
 * Shows site-specific navigation items with permission-based filtering.
 */
import { Link } from '@inertiajs/vue3';
import {
    BarChart3,
    Briefcase,
    LayoutGrid,
    Package,
    Settings,
    Users,
    X,
} from 'lucide-vue-next';
import { computed } from 'vue';

import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuAction,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarGroup,
    SidebarGroupContent,
    type SidebarProps,
} from '@/components/ui/sidebar';
import NavUser from '@/components/NavUser.vue';
import NavMain from '@/components/NavMain.vue';
import { usePermission } from '@/composables/usePermission';
import type { Workshop, SiteRole, NavItem } from '@/types';

interface Props {
    site: Workshop;
    siteRole?: SiteRole;
    collapsible?: SidebarProps['collapsible'];
}

const props = withDefaults(defineProps<Props>(), {
    collapsible: 'none',
});
const emit = defineEmits<{
    close: [];
}>();

const { hasPermission } = usePermission();

// Site-specific navigation items
// Items WITHOUT permission are shown to all users
const siteNavItems = computed<NavItem[]>(() => {
    const baseUrl = `/admin/workshops/${props.site.id}`;
    
    const items: NavItem[] = [
        {
            title: 'Dashboard',
            href: baseUrl,
            icon: LayoutGrid,
            // No permission = shown to all
        },
        {
            title: 'Jobs',
            href: `${baseUrl}/jobs`,
            icon: Briefcase,
            // No permission = shown to all
        },
        {
            title: 'Users',
            href: `${baseUrl}/users`,
            icon: Users,
            // No permission = shown to all
        },
        {
            title: 'Inventory',
            href: `${baseUrl}/inventory`,
            icon: Package,
            // No permission = shown to all
            children: [
                {
                    title: 'Parts',
                    href: `${baseUrl}/inventory/parts`,
                },
                {
                    title: 'Stock',
                    href: `${baseUrl}/inventory/stock`,
                },
            ],
        },
        {
            title: 'Analytics',
            href: `${baseUrl}/analytics`,
            icon: BarChart3,
            // No permission = shown to all
        },
        {
            title: 'Site Settings',
            href: `${baseUrl}/edit`,
            icon: Settings,
            permission: 'site.settings.view', // Only settings require permission
        },
    ];

    return items;
});

/**
 * Filter nav items by permission recursively.
 * Items WITHOUT permission are shown to all users.
 */
const filterByPermission = (items: NavItem[]): NavItem[] => {
    return items
        .map((item) => {
            const clonedItem = { ...item };
            if (clonedItem.children && clonedItem.children.length > 0) {
                clonedItem.children = filterByPermission(clonedItem.children);
            }
            return clonedItem;
        })
        .filter((item) => {
            const hasAccess = !item.permission || hasPermission(item.permission);
            const hasAccessibleChildren = item.children && item.children.length > 0;
            return hasAccess || hasAccessibleChildren;
        });
};

const filteredNavItems = computed(() => filterByPermission(siteNavItems.value));
const siteBaseUrl = computed(() => `/admin/workshops/${props.site.id}`);
</script>

<template>
    <Sidebar :collapsible="props.collapsible" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="siteBaseUrl">
                            <div class="flex flex-col min-w-0">
                                <span class="text-xs text-muted-foreground uppercase tracking-wider">Site</span>
                                <span class="font-semibold truncate">{{ site.name }}</span>
                                <span class="text-xs text-muted-foreground">{{ site.code }}</span>
                            </div>
                        </Link>
                    </SidebarMenuButton>
                    <SidebarMenuAction as-child>
                        <button
                            type="button"
                            class="h-7 w-7"
                            @click="emit('close')"
                        >
                            <X class="h-4 w-4" />
                            <span class="sr-only">Close site sidebar</span>
                        </button>
                    </SidebarMenuAction>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="filteredNavItems" label="Site Navigation" />
        </SidebarContent>

        <SidebarFooter>
            <NavUser />
            <SidebarGroup class="px-2 py-0">
                <SidebarGroupContent>
                    <div class="px-4 py-2 text-xs text-muted-foreground">
                        <span class="uppercase tracking-wider">Your Role</span>
                        <p class="font-medium text-foreground capitalize">
                            {{ siteRole?.replace('_', ' ') || 'Staff' }}
                        </p>
                    </div>
                </SidebarGroupContent>
            </SidebarGroup>
        </SidebarFooter>
    </Sidebar>
</template>
