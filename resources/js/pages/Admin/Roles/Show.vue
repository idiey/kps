<script setup lang="ts">
import { Head, router, Link } from '@inertiajs/vue3';
import KpsShellLayout from '@/layouts/kps/KpsShellLayout.vue';
import { useLocale } from '@/composables/useLocale';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { ArrowLeft, Edit, Shield, Users, CheckCircle, XCircle } from 'lucide-vue-next';

interface Permission {
    id: number;
    name: string;
    description?: string;
}

interface User {
    id: number;
    name: string;
    email: string;
}

interface Role {
    id: number;
    name: string;
    description?: string;
    color?: string;
    is_active: boolean;
    is_system_role: boolean;
    permissions: Permission[];
    users: User[];
}

const props = defineProps<{
    role: Role;
}>();
const { t } = useLocale();

const getRoleDisplayName = (roleName: string) => {
    const roleMap: Record<string, string> = {
        pentadbiran: 'Pentadbiran',
        company_admin: 'HQ Admin',
        site_admin: 'Site Admin',
        staff: 'Site Staff',
    };
    return roleMap[roleName] || roleName;
};

const breadcrumbs: BreadcrumbItem[] = [
    { title: t('nav.dashboard', 'Dashboard'), href: dashboard().url },
    { title: t('admin.roles', 'Roles'), href: '/admin/roles' },
    { title: getRoleDisplayName(props.role.name), href: `/admin/roles/${props.role.id}` },
];
</script>

<template>
    <Head :title="`Role: ${getRoleDisplayName(role.name)}`" />

    <KpsShellLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-3">
                        <h2 class="text-2xl font-bold tracking-tight">
                            {{ getRoleDisplayName(role.name) }}
                        </h2>
                        <Badge v-if="role.is_system_role" variant="outline">System Role</Badge>
                        <div class="flex items-center gap-1">
                            <CheckCircle v-if="role.is_active" class="h-5 w-5 text-green-600" />
                            <XCircle v-else class="h-5 w-5 text-red-600" />
                            <span class="text-sm text-muted-foreground">
                                {{ role.is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                    <p v-if="role.description" class="text-muted-foreground mt-1">
                        {{ role.description }}
                    </p>
                </div>
                <div class="flex gap-2">
                    <Button variant="outline" @click="router.visit('/admin/roles')">
                        <ArrowLeft class="mr-2 h-4 w-4" />
                        {{ t('admin.roles', 'Back to Roles') }}
                    </Button>
                    <Button v-if="!role.is_system_role" as-child>
                        <Link :href="`/admin/roles/${role.id}/edit`">
                            <Edit class="mr-2 h-4 w-4" />
                            {{ t('admin.edit_role', 'Edit Role') }}
                        </Link>
                    </Button>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid gap-4 md:grid-cols-2">
                <div class="rounded-lg border bg-card p-6">
                    <div class="flex items-center gap-3">
                        <div class="rounded-full bg-primary/10 p-3">
                            <Users class="h-6 w-6 text-primary" />
                        </div>
                        <div>
                            <p class="text-sm text-muted-foreground">Users with this role</p>
                            <p class="text-2xl font-bold">{{ role.users.length }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-lg border bg-card p-6">
                    <div class="flex items-center gap-3">
                        <div class="rounded-full bg-primary/10 p-3">
                            <Shield class="h-6 w-6 text-primary" />
                        </div>
                        <div>
                            <p class="text-sm text-muted-foreground">Assigned permissions</p>
                            <p class="text-2xl font-bold">{{ role.permissions.length }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Permissions -->
            <div class="rounded-lg border bg-card p-6 space-y-4">
                <h3 class="text-lg font-semibold flex items-center gap-2">
                    <Shield class="h-5 w-5" />
                    Permissions
                </h3>

                <div v-if="role.permissions.length === 0" class="text-center py-8">
                    <p class="text-muted-foreground">No permissions assigned to this role</p>
                </div>

                <div v-else class="grid gap-3 md:grid-cols-2 lg:grid-cols-3">
                    <div
                        v-for="permission in role.permissions"
                        :key="permission.id"
                        class="flex items-start gap-2 rounded-md border p-3"
                    >
                        <CheckCircle class="h-4 w-4 text-green-600 mt-0.5 shrink-0" />
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-sm">{{ permission.name }}</p>
                            <p
                                v-if="permission.description"
                                class="text-xs text-muted-foreground mt-1"
                            >
                                {{ permission.description }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Users -->
            <div class="rounded-lg border bg-card p-6 space-y-4">
                <h3 class="text-lg font-semibold flex items-center gap-2">
                    <Users class="h-5 w-5" />
                    Users with this Role
                </h3>

                <div v-if="role.users.length === 0" class="text-center py-8">
                    <p class="text-muted-foreground">No users assigned to this role</p>
                </div>

                <div v-else class="rounded-md border">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Name</TableHead>
                                <TableHead>Email</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="user in role.users" :key="user.id">
                                <TableCell class="font-medium">{{ user.name }}</TableCell>
                                <TableCell>{{ user.email }}</TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>
            </div>
        </div>
    </KpsShellLayout>
</template>
