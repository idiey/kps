<script setup lang="ts">
import CustomerController from '@/actions/App/Http/Controllers/CustomerController';
import DashboardController from '@/actions/App/Http/Controllers/DashboardController';
import JobController from '@/actions/App/Http/Controllers/JobController';
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
import {
    BarChart3,
    Briefcase,
    ClipboardCheck,
    LayoutGrid,
    ListChecks,
    Package,
    Settings,
    Shield,
    TrendingUp,
    Users,
    Warehouse,
} from 'lucide-vue-next';
import { computed } from 'vue';
import AppLogo from './AppLogo.vue';

interface Props {
    variant?: SidebarProps['variant'];
}

const props = withDefaults(defineProps<Props>(), {
    variant: 'inset',
});

const { hasPermission } = usePermission();

// All navigation items - items WITHOUT permission are shown to all users
// Only add permission when you want to restrict access
const allNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
        // No permission = shown to all
    },
    {
        title: 'Create Job',
        href: JobController.selectMode().url,
        icon: Briefcase,
        // No permission = shown to all
    },
    {
        title: 'Inspections',
        href: '/inspections',
        icon: ClipboardCheck,
        // No permission = shown to all
    },
    {
        title: 'Jobs',
        href: JobController.index().url,
        icon: Briefcase,
        // No permission = shown to all
    },
    {
        title: 'Analytics',
        href: '/analytics',
        icon: TrendingUp,
        // No permission = shown to all
    },
    {
        title: 'Customers',
        href: CustomerController.index(),
        icon: Users,
        // No permission = shown to all
    },
    {
        title: 'My Jobs',
        href: DashboardController.myJobs(),
        icon: ListChecks,
        // No permission = shown to all
    },
    {
        title: 'Workload',
        href: DashboardController.workload(),
        icon: BarChart3,
        // No permission = shown to all
    },
    // Administration with nested children - only admin.access permission required
    {
        title: 'Administration',
        href: '/admin',
        icon: Settings,
        permission: 'admin.access', // Only this parent needs permission
        children: [
            {
                title: 'User Management',
                href: '/admin/users',
                icon: Users,
                // No permission on children = shown if parent is accessible
            },
            {
                title: 'Sites',
                href: '/admin/workshops',
                icon: Warehouse,
            },
            {
                title: 'Role Management',
                href: '/admin/roles',
                icon: Shield,
            },
            {
                title: 'Reports',
                href: '/admin/reports',
                icon: BarChart3,
            },
            {
                title: 'Assets',
                href: '/admin/assets',
                icon: Package,
            },
            {
                title: 'Part Inventory',
                href: '/admin/inventory',
                icon: Warehouse,
            },
            {
                title: 'System Settings',
                href: '/admin/settings',
                icon: Settings,
            },
        ],
    },
];

/**
 * Filter nav items by permission recursively.
 * Items WITHOUT permission are shown to all users.
 * Items with children are kept if any child is accessible.
 */
const filterByPermission = (items: NavItem[]): NavItem[] => {
    return items
        .map((item) => {
            // Clone the item to avoid mutating the original
            const clonedItem = { ...item };
            
            // Recursively filter children
            if (clonedItem.children && clonedItem.children.length > 0) {
                clonedItem.children = filterByPermission(clonedItem.children);
            }
            
            return clonedItem;
        })
        .filter((item) => {
            // Check permission (items WITHOUT permission are shown to everyone)
            const hasAccess = !item.permission || hasPermission(item.permission);
            
            // Keep parent if it has accessible children, even if parent permission fails
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
            <NavMain :items="navItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
