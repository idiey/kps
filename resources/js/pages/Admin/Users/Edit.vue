<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Checkbox } from '@/components/ui/checkbox';
import { ArrowLeft } from 'lucide-vue-next';

interface User {
    id: number;
    name: string;
    email: string;
    department?: string;
    active: boolean;
    roles: Array<{ id: number; name: string }>;
}

interface Role {
    id: number;
    name: string;
}

const props = defineProps<{
    user: User;
    roles: Role[];
}>();

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    password: '',
    password_confirmation: '',
    role: props.user.roles[0]?.name || '',
    department: props.user.department || '',
    active: props.user.active,
});

const submit = () => {
    form.put(`/admin/users/${props.user.id}`, {
        preserveScroll: true,
    });
};

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
    return roleMap[roleName] || roleName;
};

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Users', href: '/admin/users' },
    { title: 'Edit User', href: `/admin/users/${props.user.id}/edit` },
];
</script>

<template>
    <Head title="Edit User" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center gap-4">
            <Button variant="ghost" size="icon" @click="router.visit('/admin/users')">
                <ArrowLeft class="h-5 w-5" />
            </Button>
            <div>
                <h2 class="text-2xl font-bold tracking-tight">Edit User</h2>
                <p class="text-muted-foreground">Update user information and role</p>
            </div>
        </div>

        <!-- Form -->
        <form @submit.prevent="submit" class="max-w-2xl space-y-6">
            <!-- Name -->
            <div class="space-y-2">
                <Label for="name">Name *</Label>
                <Input
                    id="name"
                    v-model="form.name"
                    type="text"
                    required
                    :class="{ 'border-red-500': form.errors.name }"
                />
                <p v-if="form.errors.name" class="text-sm text-red-500">
                    {{ form.errors.name }}
                </p>
            </div>

            <!-- Email -->
            <div class="space-y-2">
                <Label for="email">Email *</Label>
                <Input
                    id="email"
                    v-model="form.email"
                    type="email"
                    required
                    :class="{ 'border-red-500': form.errors.email }"
                />
                <p v-if="form.errors.email" class="text-sm text-red-500">
                    {{ form.errors.email }}
                </p>
            </div>

            <!-- Password (Optional) -->
            <div class="space-y-2">
                <Label for="password">Password (leave blank to keep current)</Label>
                <Input
                    id="password"
                    v-model="form.password"
                    type="password"
                    :class="{ 'border-red-500': form.errors.password }"
                />
                <p v-if="form.errors.password" class="text-sm text-red-500">
                    {{ form.errors.password }}
                </p>
            </div>

            <!-- Password Confirmation -->
            <div v-if="form.password" class="space-y-2">
                <Label for="password_confirmation">Confirm Password *</Label>
                <Input
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    :class="{ 'border-red-500': form.errors.password_confirmation }"
                />
                <p v-if="form.errors.password_confirmation" class="text-sm text-red-500">
                    {{ form.errors.password_confirmation }}
                </p>
            </div>

            <!-- Role -->
            <div class="space-y-2">
                <Label for="role">Role *</Label>
                <Select v-model="form.role" required>
                    <SelectTrigger :class="{ 'border-red-500': form.errors.role }">
                        <SelectValue placeholder="Select a role" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem v-for="role in roles" :key="role.id" :value="role.name">
                            {{ getRoleDisplayName(role.name) }}
                        </SelectItem>
                    </SelectContent>
                </Select>
                <p v-if="form.errors.role" class="text-sm text-red-500">
                    {{ form.errors.role }}
                </p>
            </div>

            <!-- Department -->
            <div class="space-y-2">
                <Label for="department">Department</Label>
                <Input
                    id="department"
                    v-model="form.department"
                    type="text"
                    :class="{ 'border-red-500': form.errors.department }"
                />
                <p v-if="form.errors.department" class="text-sm text-red-500">
                    {{ form.errors.department }}
                </p>
            </div>

            <!-- Active Status -->
            <div class="flex items-center space-x-2">
                <Checkbox id="active" v-model:checked="form.active" />
                <Label for="active" class="cursor-pointer">User is active</Label>
            </div>

            <!-- Actions -->
            <div class="flex gap-4">
                <Button type="submit" :disabled="form.processing">
                    {{ form.processing ? 'Updating...' : 'Update User' }}
                </Button>
                <Button
                    type="button"
                    variant="outline"
                    @click="router.visit('/admin/users')"
                >
                    Cancel
                </Button>
            </div>
        </form>
    </div>
    </AppLayout>
</template>
