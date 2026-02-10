<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import {
    BarChart3,
    ClipboardList,
    FileText,
    LayoutGrid,
    Settings,
    Users,
    Wallet,
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
import type { KpsSite, KpsSiteRole, NavItem } from '@/types';

interface Props {
    site: KpsSite;
    siteRole?: KpsSiteRole | null;
    collapsible?: SidebarProps['collapsible'];
}

const props = withDefaults(defineProps<Props>(), {
    collapsible: 'none',
    siteRole: null,
});

const emit = defineEmits<{
    close: [];
}>();

const page = usePage();
const { hasPermission } = usePermission();
const isHq = computed(() => (page.props.auth?.permissions || []).includes('kps.manage_sites'));

const siteNavItems = computed<NavItem[]>(() => {
    const baseUrl = `/kps/sites/${props.site.id}`;

    return [
        {
            title: 'Site Dashboard',
            href: baseUrl,
            icon: LayoutGrid,
        },
        {
            title: 'Peneroka',
            href: `${baseUrl}/peneroka`,
            icon: Users,
            permission: 'kps.manage_peneroka',
        },
        {
            title: 'Hutang',
            href: `${baseUrl}/hutang`,
            icon: ClipboardList,
            permission: 'kps.manage_hutang',
        },
        {
            title: 'Potongan Bulanan',
            href: `${baseUrl}/potongan`,
            icon: Wallet,
            permission: 'kps.manage_potongan',
        },
        {
            title: 'Allocation Review',
            href: `${baseUrl}/allocations`,
            icon: BarChart3,
            permission: 'kps.manage_potongan',
        },
        {
            title: 'Reports',
            href: `${baseUrl}/reports`,
            icon: FileText,
            permission: 'kps.view_reports',
        },
        {
            title: 'Site Settings',
            href: `${baseUrl}/edit`,
            icon: Settings,
            permission: 'kps.manage_sites',
        },
    ];
});

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
const siteBaseUrl = computed(() => `/kps/sites/${props.site.id}`);
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
                    <SidebarMenuAction v-if="isHq" as-child>
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
