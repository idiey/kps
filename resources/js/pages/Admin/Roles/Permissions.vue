<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
import KpsShellLayout from '@/layouts/kps/KpsShellLayout.vue';
import { useLocale } from '@/composables/useLocale';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { ArrowLeft, Save } from 'lucide-vue-next';
import { ref, computed } from 'vue';

interface Permission {
    id: number;
    name: string;
    description?: string;
}

interface Role {
    id: number;
    name: string;
    is_active: boolean;
}

interface MatrixItem {
    permission: Permission;
    roles: Record<number, boolean>;
}

const props = defineProps<{
    roles: Role[];
    permissions: Permission[];
    matrix: Record<number, MatrixItem>;
}>();
const { t } = useLocale();

// Initialize matrix state from props
const matrixState = ref<Record<number, Record<number, boolean>>>({});

// Initialize the matrix state
Object.entries(props.matrix).forEach(([permissionId, item]) => {
    matrixState.value[Number(permissionId)] = { ...item.roles };
});

const form = useForm({
    matrix: {} as Record<number, Record<number, boolean>>,
});

const getRoleDisplayName = (roleName: string) => {
    const roleMap: Record<string, string> = {
        pentadbiran: 'Pentadbiran',
        company_admin: 'HQ Admin',
        site_admin: 'Site Admin',
        staff: 'Site Staff',
    };
    return roleMap[roleName] || roleName;
};

const togglePermission = (permissionId: number, roleId: number) => {
    if (!matrixState.value[permissionId]) {
        matrixState.value[permissionId] = {};
    }
    matrixState.value[permissionId][roleId] = !matrixState.value[permissionId][roleId];
};

const isPermissionChecked = (permissionId: number, roleId: number): boolean => {
    return matrixState.value[permissionId]?.[roleId] ?? false;
};

// Group permissions by module
const groupedPermissions = computed(() => {
    const groups: Record<string, Permission[]> = {
        KPS: [],
        Users: [],
        'Roles & Permissions': [],
        Other: [],
    };

    props.permissions.forEach((permission) => {
        const name = permission.name.toLowerCase();
        if (name.startsWith('kps.')) {
            groups.KPS.push(permission);
        } else if (name.includes('user')) {
            groups.Users.push(permission);
        } else if (name.includes('role') || name.includes('permission')) {
            groups['Roles & Permissions'].push(permission);
        } else {
            groups.Other.push(permission);
        }
    });

    // Remove empty groups
    return Object.entries(groups).filter(([_, perms]) => perms.length > 0);
});

const submit = () => {
    // Transform matrix to role-permission format for backend
    const transformedMatrix: Record<number, Record<number, boolean>> = {};

    props.roles.forEach((role) => {
        transformedMatrix[role.id] = {};
        props.permissions.forEach((permission) => {
            transformedMatrix[role.id][permission.id] =
                matrixState.value[permission.id]?.[role.id] ?? false;
        });
    });

    form.matrix = transformedMatrix;

    form.post('/admin/roles-permissions/update', {
        preserveScroll: true,
    });
};

const breadcrumbs: BreadcrumbItem[] = [
    { title: t('nav.dashboard', 'Dashboard'), href: dashboard().url },
    { title: t('admin.roles', 'Roles'), href: '/admin/roles' },
    { title: 'Permission Matrix', href: '/admin/roles-permissions' },
];
</script>

<template>
    <Head title="Permission Matrix" />

    <KpsShellLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight">Permission Matrix</h2>
                    <p class="text-muted-foreground">
                        Manage permissions for all roles in a single view
                    </p>
                </div>
                <Button variant="outline" @click="router.visit('/admin/roles')">
                    <ArrowLeft class="mr-2 h-4 w-4" />
                    {{ t('admin.roles', 'Back to Roles') }}
                </Button>
            </div>

            <!-- Matrix -->
            <form @submit.prevent="submit">
                <div class="rounded-lg border bg-card overflow-hidden">
                    <div class="overflow-x-auto">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead class="sticky left-0 bg-card z-10 min-w-[250px]">
                                        Permission
                                    </TableHead>
                                    <TableHead
                                        v-for="role in roles"
                                        :key="role.id"
                                        class="text-center min-w-[120px]"
                                    >
                                        {{ getRoleDisplayName(role.name) }}
                                    </TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <template v-for="[groupName, permissions] in groupedPermissions" :key="groupName">
                                    <!-- Group Header -->
                                    <TableRow class="bg-muted/50">
                                        <TableCell colspan="100" class="sticky left-0 z-10">
                                            <h4 class="font-semibold text-sm text-primary">{{ groupName }}</h4>
                                        </TableCell>
                                    </TableRow>
                                    <!-- Permissions in Group -->
                                    <TableRow v-for="permission in permissions" :key="permission.id">
                                        <TableCell class="sticky left-0 bg-card z-10 pl-6">
                                            <div>
                                                <p class="font-medium">{{ permission.name }}</p>
                                                <p
                                                    v-if="permission.description"
                                                    class="text-xs text-muted-foreground mt-1"
                                                >
                                                    {{ permission.description }}
                                                </p>
                                            </div>
                                        </TableCell>
                                        <TableCell
                                            v-for="role in roles"
                                            :key="role.id"
                                            class="text-center"
                                        >
                                            <div class="flex justify-center">
                                                <Checkbox
                                                    :id="`permission-${permission.id}-role-${role.id}`"
                                                    :checked="isPermissionChecked(permission.id, role.id)"
                                                    @update:checked="
                                                        togglePermission(permission.id, role.id)
                                                    "
                                                />
                                            </div>
                                        </TableCell>
                                    </TableRow>
                                </template>
                            </TableBody>
                        </Table>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end gap-3 mt-6">
                    <Button type="button" variant="outline" @click="router.visit('/admin/roles')">
                        {{ t('common.cancel', 'Cancel') }}
                    </Button>
                    <Button type="submit" :disabled="form.processing">
                        <Save class="mr-2 h-4 w-4" />
                        {{ form.processing ? t('auth.updating', 'Saving...') : t('common.save', 'Save Changes') }}
                    </Button>
                </div>
            </form>
        </div>
    </KpsShellLayout>
</template>
