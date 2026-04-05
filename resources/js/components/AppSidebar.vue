<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    type SidebarProps,
} from '@/components/ui/sidebar';
import { usePermission } from '@/composables/usePermission';
import { dashboard } from '@/routes';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { LayoutGrid, Settings, Shield, Users, Warehouse } from 'lucide-vue-next';
import { computed } from 'vue';
import AppLogo from './AppLogo.vue';

interface Props {
    variant?: SidebarProps['variant'];
}

const props = withDefaults(defineProps<Props>(), {
    variant: 'inset',
});

const { hasPermission } = usePermission();

const allNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/kps/dashboard',
        icon: LayoutGrid,
        permission: 'kps.view',
    },
    {
        title: 'Sites',
        href: '/kps/sites',
        icon: Warehouse,
        permission: 'kps.manage_sites',
    },
    {
        title: 'User Management',
        href: '/admin/users',
        icon: Users,
        permission: 'view-users',
    },
    {
        title: 'Role Management',
        href: '/admin/roles',
        icon: Shield,
        permission: 'view-roles',
    },
    {
        title: 'Profile Settings',
        href: '/settings/profile',
        icon: Settings,
    },
];

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

const navItems = computed(() => filterByPermission(allNavItems));
</script>

<template>
    <Sidebar collapsible="icon" :variant="variant">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="navItems" label="Navigation" />
        </SidebarContent>

        <SidebarFooter>
            <NavUser />
            <NavFooter />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
