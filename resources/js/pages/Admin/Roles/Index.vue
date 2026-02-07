<script setup lang="ts">
import { Head, router, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
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
import { Plus, Edit, Users, Shield, CheckCircle, XCircle } from 'lucide-vue-next';

interface Role {
    id: number;
    name: string;
    description?: string;
    color?: string;
    is_active: boolean;
    is_system_role: boolean;
    users_count: number;
    permissions_count: number;
    created_at: string;
}

const props = defineProps<{
    roles: Role[];
}>();

const getRoleDisplayName = (roleName: string) => {
    const roleMap: Record<string, string> = {
        pentadbiran: 'Pentadbiran',
        company_admin: 'Admin Company',
        penyelia: 'Penyelia',
        pemeriksa: 'Pemeriksa',
        pelulus: 'Pelulus',
        juruteknik: 'Juruteknik',
    };
    return roleMap[roleName] || roleName;
};

const getRoleBadgeVariant = (roleName: string) => {
    const variantMap: Record<string, 'default' | 'secondary' | 'destructive' | 'outline'> = {
        pentadbiran: 'destructive',
        company_admin: 'secondary',
        penyelia: 'default',
        pemeriksa: 'secondary',
        pelulus: 'default',
        juruteknik: 'outline',
    };
    return variantMap[roleName] || 'outline';
};

const toggleActivation = (role: Role) => {
    if (role.is_system_role) {
        alert('Cannot modify system roles');
        return;
    }

    if (
        confirm(
            `Are you sure you want to ${role.is_active ? 'deactivate' : 'activate'} ${getRoleDisplayName(role.name)}?`,
        )
    ) {
        const endpoint = role.is_active
            ? `/admin/roles/${role.id}/deactivate`
            : `/admin/roles/${role.id}/activate`;
        router.post(endpoint);
    }
};

const deleteRole = (role: Role) => {
    if (role.is_system_role) {
        alert('Cannot delete system roles');
        return;
    }

    if (role.users_count > 0) {
        alert(`Cannot delete role with ${role.users_count} assigned users`);
        return;
    }

    if (
        confirm(
            `Are you sure you want to delete ${getRoleDisplayName(role.name)}? This action cannot be undone.`,
        )
    ) {
        router.delete(`/admin/roles/${role.id}`);
    }
};

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Roles', href: '/admin/roles' },
];
</script>

<template>
    <Head title="Role Management" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight">Role Management</h2>
                    <p class="text-muted-foreground">Manage system roles and permissions</p>
                </div>
                <div class="flex gap-2">
                    <Button variant="outline" as-child>
                        <Link href="/admin/roles-permissions">
                            <Shield class="mr-2 h-4 w-4" />
                            Permission Matrix
                        </Link>
                    </Button>
                    <Button as-child>
                        <Link href="/admin/roles/create">
                            <Plus class="mr-2 h-4 w-4" />
                            Add Role
                        </Link>
                    </Button>
                </div>
            </div>

            <!-- Roles Table -->
            <div class="rounded-md border">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Role Name</TableHead>
                            <TableHead>Description</TableHead>
                            <TableHead class="text-center">Users</TableHead>
                            <TableHead class="text-center">Permissions</TableHead>
                            <TableHead>Status</TableHead>
                            <TableHead class="text-right">Actions</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-if="roles.length === 0">
                            <TableCell colspan="6" class="text-center text-muted-foreground">
                                No roles found
                            </TableCell>
                        </TableRow>
                        <TableRow v-for="role in roles" :key="role.id">
                            <TableCell class="font-medium">
                                <div class="flex items-center gap-2">
                                    <Badge :variant="getRoleBadgeVariant(role.name)">
                                        {{ getRoleDisplayName(role.name) }}
                                    </Badge>
                                    <Badge v-if="role.is_system_role" variant="outline" class="text-xs">
                                        System
                                    </Badge>
                                </div>
                            </TableCell>
                            <TableCell>
                                <span class="text-sm text-muted-foreground">
                                    {{ role.description || '-' }}
                                </span>
                            </TableCell>
                            <TableCell class="text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <Users class="h-4 w-4 text-muted-foreground" />
                                    <span class="text-sm font-medium">{{ role.users_count }}</span>
                                </div>
                            </TableCell>
                            <TableCell class="text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <Shield class="h-4 w-4 text-muted-foreground" />
                                    <span class="text-sm font-medium">{{ role.permissions_count }}</span>
                                </div>
                            </TableCell>
                            <TableCell>
                                <button
                                    @click="toggleActivation(role)"
                                    class="inline-flex items-center gap-1 hover:opacity-70 transition-opacity"
                                    :disabled="role.is_system_role"
                                >
                                    <CheckCircle
                                        v-if="role.is_active"
                                        class="h-4 w-4 text-green-600"
                                    />
                                    <XCircle v-else class="h-4 w-4 text-red-600" />
                                    <span class="text-sm">{{
                                        role.is_active ? 'Active' : 'Inactive'
                                    }}</span>
                                </button>
                            </TableCell>
                            <TableCell class="text-right">
                                <div class="flex justify-end gap-2">
                                    <Button variant="ghost" size="sm" as-child>
                                        <Link :href="`/admin/roles/${role.id}`">
                                            <Shield class="h-4 w-4" />
                                        </Link>
                                    </Button>
                                    <Button
                                        v-if="!role.is_system_role"
                                        variant="ghost"
                                        size="sm"
                                        as-child
                                    >
                                        <Link :href="`/admin/roles/${role.id}/edit`">
                                            <Edit class="h-4 w-4" />
                                        </Link>
                                    </Button>
                                    <Button
                                        v-else
                                        variant="ghost"
                                        size="sm"
                                        disabled
                                        class="cursor-not-allowed opacity-50"
                                    >
                                        <Edit class="h-4 w-4" />
                                    </Button>
                                </div>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
        </div>
    </AppLayout>
</template>
