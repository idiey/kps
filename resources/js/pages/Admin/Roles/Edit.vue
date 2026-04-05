<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
import KpsShellLayout from '@/layouts/kps/KpsShellLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Checkbox } from '@/components/ui/checkbox';
import { ArrowLeft, Save } from 'lucide-vue-next';
import { computed } from 'vue';

interface Permission {
    id: number;
    name: string;
    description?: string;
}

interface Role {
    id: number;
    name: string;
    description?: string;
    color?: string;
    is_active: boolean;
    is_system_role: boolean;
}

const props = defineProps<{
    role: Role;
    permissions: Permission[];
    rolePermissionIds: number[];
}>();

const form = useForm({
    name: props.role.name,
    description: props.role.description || '',
    color: props.role.color || '',
    is_active: props.role.is_active,
    permissions: [...props.rolePermissionIds],
});

const submit = () => {
    form.put(`/admin/roles/${props.role.id}`, {
        preserveScroll: true,
    });
};

const togglePermission = (permissionId: number) => {
    const index = form.permissions.indexOf(permissionId);
    if (index > -1) {
        form.permissions.splice(index, 1);
    } else {
        form.permissions.push(permissionId);
    }
};

const isPermissionSelected = (permissionId: number) => {
    return form.permissions.includes(permissionId);
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

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Roles', href: '/admin/roles' },
    { title: 'Edit Role', href: `/admin/roles/${props.role.id}/edit` },
];
</script>

<template>
    <Head :title="`Edit Role: ${role.name}`" />

    <KpsShellLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight">Edit Role</h2>
                    <p class="text-muted-foreground">Update role details and permissions</p>
                </div>
                <Button variant="outline" @click="router.visit('/admin/roles')">
                    <ArrowLeft class="mr-2 h-4 w-4" />
                    Back to Roles
                </Button>
            </div>

            <!-- System Role Warning -->
            <div
                v-if="role.is_system_role"
                class="rounded-md border border-yellow-200 bg-yellow-50 p-4"
            >
                <p class="text-sm text-yellow-800">
                    <strong>Notice:</strong> This is a system role. You can manage permissions but
                    cannot modify the role name or other properties.
                </p>
            </div>

            <!-- Form -->
            <form @submit.prevent="submit" class="space-y-6">
                <div class="rounded-lg border bg-card p-6 space-y-4">
                    <h3 class="text-lg font-semibold">Role Information</h3>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="space-y-2">
                            <Label for="name">Role Name *</Label>
                            <Input
                                id="name"
                                v-model="form.name"
                                :disabled="role.is_system_role"
                                placeholder="Enter role name"
                                required
                            />
                            <p v-if="form.errors.name" class="text-sm text-destructive">
                                {{ form.errors.name }}
                            </p>
                        </div>

                        <div class="space-y-2">
                            <Label for="color">Color (Optional)</Label>
                            <Input
                                id="color"
                                v-model="form.color"
                                :disabled="role.is_system_role"
                                placeholder="e.g., blue, green, red"
                            />
                            <p v-if="form.errors.color" class="text-sm text-destructive">
                                {{ form.errors.color }}
                            </p>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <Label for="description">Description (Optional)</Label>
                        <Textarea
                            id="description"
                            v-model="form.description"
                            :disabled="role.is_system_role"
                            placeholder="Describe the role's purpose and responsibilities"
                            rows="3"
                        />
                        <p v-if="form.errors.description" class="text-sm text-destructive">
                            {{ form.errors.description }}
                        </p>
                    </div>

                    <div class="flex items-center space-x-2">
                        <Checkbox
                            id="is_active"
                            :checked="form.is_active"
                            @update:checked="form.is_active = $event"
                            :disabled="role.is_system_role"
                        />
                        <Label for="is_active" class="cursor-pointer">Active</Label>
                    </div>
                </div>

                <!-- Permissions -->
                <div class="rounded-lg border bg-card p-6 space-y-4">
                    <h3 class="text-lg font-semibold">Permissions</h3>
                    <p class="text-sm text-muted-foreground">
                        Select the permissions this role should have
                    </p>

                    <div class="space-y-6">
                        <div v-for="[groupName, permissions] in groupedPermissions" :key="groupName" class="space-y-3">
                            <h4 class="font-semibold text-sm text-primary border-b pb-2">{{ groupName }}</h4>
                            <div class="grid gap-3 md:grid-cols-2 lg:grid-cols-3 pl-2">
                                <div
                                    v-for="permission in permissions"
                                    :key="permission.id"
                                    class="flex items-start space-x-2 rounded-md border p-3 hover:bg-accent transition-colors cursor-pointer"
                                    @click="togglePermission(permission.id)"
                                >
                                    <Checkbox
                                        :id="`permission-${permission.id}`"
                                        :checked="isPermissionSelected(permission.id)"
                                        @click.stop
                                        @update:checked="() => togglePermission(permission.id)"
                                    />
                                    <div class="flex-1">
                                        <Label
                                            :for="`permission-${permission.id}`"
                                            class="cursor-pointer font-medium"
                                        >
                                            {{ permission.name }}
                                        </Label>
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
                    </div>

                    <p v-if="form.errors.permissions" class="text-sm text-destructive">
                        {{ form.errors.permissions }}
                    </p>
                </div>

                <!-- Actions -->
                <div class="flex justify-end gap-3">
                    <Button type="button" variant="outline" @click="router.visit('/admin/roles')">
                        Cancel
                    </Button>
                    <Button type="submit" :disabled="form.processing">
                        <Save class="mr-2 h-4 w-4" />
                        {{ form.processing ? 'Saving...' : 'Save Changes' }}
                    </Button>
                </div>
            </form>
        </div>
    </KpsShellLayout>
</template>
