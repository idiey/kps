<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import KpsLogo from '@/components/kps/KpsLogo.vue';
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
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { BarChart3, LayoutGrid, Settings, Users, Shield, Warehouse } from 'lucide-vue-next';
import { computed } from 'vue';

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
        title: 'Analytics',
        href: '/kps/analytics',
        icon: BarChart3,
        permission: 'kps.view',
    },
    {
        title: 'Sites',
        href: '/kps/sites',
        icon: Warehouse,
        permission: 'kps.manage_sites',
    },
    {
        title: 'Administration',
        href: '/admin',
        icon: Settings,
        permission: 'kps.manage_sites',
        children: [
            {
                title: 'User Management',
                href: '/admin/users',
                icon: Users,
            },
            {
                title: 'Role Management',
                href: '/admin/roles',
                icon: Shield,
            },
            {
                title: 'System Settings',
                href: '/admin/settings',
                icon: Settings,
            },
        ],
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
    <Sidebar collapsible="icon" :variant="props.variant">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link href="/kps">
                            <KpsLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="navItems" label="KPS" />
        </SidebarContent>

        <SidebarFooter>
            <NavUser />
            <NavFooter />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
