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
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import { type NavItem, type UserRole } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import {
    BarChart3,
    BookOpen,
    Briefcase,
    ClipboardCheck,
    FileText,
    Folder,
    LayoutGrid,
    ListChecks,
    Users,
} from 'lucide-vue-next';
import { computed } from 'vue';
import AppLogo from './AppLogo.vue';

const page = usePage();
const userRole = computed(() => page.props.auth.user.role as UserRole);

const allNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
        roles: [
            'pentadbiran',
            'penyelia',
            'pemeriksa',
            'pelulus',
            'juruteknik',
        ],
    },
    {
        title: 'KEW.PA-10 Forms',
        href: '/kew-pa-10',
        icon: FileText,
        roles: ['pentadbiran', 'penyelia', 'pemeriksa'],
    },
    {
        title: 'Inspections',
        href: '/inspections',
        icon: ClipboardCheck,
        roles: ['pentadbiran', 'penyelia', 'pemeriksa'],
    },
    {
        title: 'Jobs',
        href: JobController.index(),
        icon: Briefcase,
        roles: ['pentadbiran', 'penyelia', 'pemeriksa', 'pelulus'],
    },
    {
        title: 'Customers',
        href: CustomerController.index(),
        icon: Users,
        roles: ['pentadbiran', 'penyelia'],
    },
    {
        title: 'My Jobs',
        href: DashboardController.myJobs(),
        icon: ListChecks,
        roles: ['juruteknik', 'pentadbiran', 'penyelia'],
    },
    {
        title: 'Workload',
        href: DashboardController.workload(),
        icon: BarChart3,
        roles: ['pentadbiran', 'penyelia'],
    },
];

const mainNavItems = computed(() => {
    return allNavItems.filter(
        (item) => !item.roles || item.roles.includes(userRole.value),
    );
});

const footerNavItems: NavItem[] = [
    {
        title: 'Github Repo',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: Folder,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#vue',
        icon: BookOpen,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
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
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
