<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
import KpsShellLayout from '@/layouts/kps/KpsShellLayout.vue';
import { useLocale } from '@/composables/useLocale';
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

interface Role {
    id: number;
    name: string;
}

const props = defineProps<{
    roles: Role[];
}>();
const { t } = useLocale();

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: '',
    department: '',
    active: true,
});

const submit = () => {
    form.post('/admin/users', {
        preserveScroll: true,
    });
};

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
    { title: t('admin.users', 'Users'), href: '/admin/users' },
    { title: t('admin.create_user', 'Create User'), href: '/admin/users/create' },
];
</script>

<template>
    <Head :title="t('admin.create_user', 'Create User')" />

    <KpsShellLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center gap-4">
            <Button variant="ghost" size="icon" @click="router.visit('/admin/users')">
                <ArrowLeft class="h-5 w-5" />
            </Button>
            <div>
                <h2 class="text-2xl font-bold tracking-tight">{{ t('admin.create_user', 'Create User') }}</h2>
                <p class="text-muted-foreground">{{ t('admin.add_user', 'Add a new user to the system') }}</p>
            </div>
        </div>

        <!-- Form -->
        <form @submit.prevent="submit" class="max-w-2xl space-y-6">
            <!-- Name -->
            <div class="space-y-2">
                <Label for="name">{{ t('auth.name', 'Name') }} *</Label>
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
                <Label for="email">{{ t('auth.email', 'Email') }} *</Label>
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

            <!-- Password -->
            <div class="space-y-2">
                <Label for="password">{{ t('auth.password', 'Password') }} *</Label>
                <Input
                    id="password"
                    v-model="form.password"
                    type="password"
                    required
                    :class="{ 'border-red-500': form.errors.password }"
                />
                <p v-if="form.errors.password" class="text-sm text-red-500">
                    {{ form.errors.password }}
                </p>
            </div>

            <!-- Password Confirmation -->
            <div class="space-y-2">
                <Label for="password_confirmation">{{ t('auth.confirm_password', 'Confirm Password') }} *</Label>
                <Input
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    required
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
                <Label for="active" class="cursor-pointer">{{ t('admin.active', 'User is active') }}</Label>
            </div>

            <!-- Actions -->
            <div class="flex gap-4">
                <Button type="submit" :disabled="form.processing">
                    {{ form.processing ? t('auth.creating', 'Creating...') : t('admin.create_user', 'Create User') }}
                </Button>
                <Button
                    type="button"
                    variant="outline"
                    @click="router.visit('/admin/users')"
                >
                    {{ t('common.cancel', 'Cancel') }}
                </Button>
            </div>
        </form>
    </div>
    </KpsShellLayout>
</template>
