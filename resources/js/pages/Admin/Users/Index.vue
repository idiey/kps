<script setup lang="ts">
import { Head, router, Link } from '@inertiajs/vue3';
import KpsShellLayout from '@/layouts/kps/KpsShellLayout.vue';
import { useLocale } from '@/composables/useLocale';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { computed, ref } from 'vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { CheckCircle, XCircle, Plus, Search, Edit, Trash2 } from 'lucide-vue-next';

interface User {
    id: number;
    name: string;
    email: string;
    department?: string;
    active: boolean;
    roles: Array<{ id: number; name: string }>;
    created_at: string;
}

interface Role {
    id: number;
    name: string;
}

interface PaginatedUsers {
    data: User[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
}

const props = defineProps<{
    users: PaginatedUsers;
    roles: Role[];
    filters: {
        search?: string;
        role?: string;
        active?: string;
    };
}>();
const { t } = useLocale();

const search = ref(props.filters.search || '');
const selectedRole = ref(props.filters.role || 'all');
const selectedStatus = ref(props.filters.active || 'all');

const applyFilters = () => {
    router.get(
        '/admin/users',
        {
            search: search.value || undefined,
            role: selectedRole.value === 'all' ? undefined : selectedRole.value,
            active: selectedStatus.value === 'all' ? undefined : selectedStatus.value,
        },
        {
            preserveState: true,
            preserveScroll: true,
        },
    );
};

const clearFilters = () => {
    search.value = '';
    selectedRole.value = 'all';
    selectedStatus.value = 'all';
    router.get('/admin/users');
};

const toggleActivation = (user: User) => {
    if (
        confirm(
            `Are you sure you want to ${user.active ? 'deactivate' : 'activate'} ${user.name}?`,
        )
    ) {
        router.patch(`/admin/users/${user.id}/toggle-activation`);
    }
};

const deleteUser = (user: User) => {
    if (confirm(`Are you sure you want to delete ${user.name}? This action cannot be undone.`)) {
        router.delete(`/admin/users/${user.id}`);
    }
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
];
</script>

<template>
    <Head :title="t('admin.user_management', 'User Management')" />

    <KpsShellLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold tracking-tight">{{ t('admin.user_management', 'User Management') }}</h2>
                <p class="text-muted-foreground">{{ t('admin.user_management_desc', 'Manage system users and their roles') }}</p>
            </div>
            <Button as-child>
                <Link href="/admin/users/create">
                    <Plus class="mr-2 h-4 w-4" />
                    {{ t('admin.add_user', 'Add User') }}
                </Link>
            </Button>
        </div>

        <!-- Filters -->
        <div class="flex gap-4">
            <div class="flex-1">
                <Input
                    v-model="search"
                    :placeholder="t('admin.search_name_email', 'Search by name or email...')"
                    @keyup.enter="applyFilters"
                >
                    <template #prefix>
                        <Search class="h-4 w-4 text-muted-foreground" />
                    </template>
                </Input>
            </div>
            <Select v-model="selectedRole" @update:model-value="applyFilters">
                <SelectTrigger class="w-[180px]">
                    <SelectValue :placeholder="t('admin.filter_role', 'Filter by role')" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem value="all">{{ t('admin.all_roles', 'All Roles') }}</SelectItem>
                    <SelectItem v-for="role in roles" :key="role.id" :value="role.name">
                        {{ getRoleDisplayName(role.name) }}
                    </SelectItem>
                </SelectContent>
            </Select>
            <Select v-model="selectedStatus" @update:model-value="applyFilters">
                <SelectTrigger class="w-[180px]">
                    <SelectValue :placeholder="t('admin.filter_status', 'Filter by status')" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem value="all">{{ t('admin.all_status', 'All Status') }}</SelectItem>
                    <SelectItem value="true">{{ t('admin.active', 'Active') }}</SelectItem>
                    <SelectItem value="false">{{ t('admin.inactive', 'Inactive') }}</SelectItem>
                </SelectContent>
            </Select>
            <Button variant="outline" @click="clearFilters">{{ t('common.clear', 'Clear') }}</Button>
        </div>

        <!-- Users Table -->
        <div class="rounded-md border">
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>{{ t('auth.name', 'Name') }}</TableHead>
                        <TableHead>{{ t('auth.email', 'Email') }}</TableHead>
                        <TableHead>Role</TableHead>
                        <TableHead>{{ t('admin.department', 'Department') }}</TableHead>
                        <TableHead>Status</TableHead>
                        <TableHead class="text-right">{{ t('admin.actions', 'Actions') }}</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-if="users.data.length === 0">
                        <TableCell colspan="6" class="text-center text-muted-foreground">
                            {{ t('admin.no_users', 'No users found') }}
                        </TableCell>
                    </TableRow>
                    <TableRow v-for="user in users.data" :key="user.id">
                        <TableCell class="font-medium">{{ user.name }}</TableCell>
                        <TableCell>{{ user.email }}</TableCell>
                        <TableCell>
                            <Badge variant="outline">
                                {{ getRoleDisplayName(user.roles[0]?.name || '') }}
                            </Badge>
                        </TableCell>
                        <TableCell>{{ user.department || '-' }}</TableCell>
                        <TableCell>
                            <button
                                @click="toggleActivation(user)"
                                class="inline-flex items-center gap-1 hover:opacity-70 transition-opacity"
                            >
                                <CheckCircle
                                    v-if="user.active"
                                    class="h-4 w-4 text-green-600"
                                />
                                <XCircle v-else class="h-4 w-4 text-red-600" />
                                <span class="text-sm">{{ user.active ? t('admin.active', 'Active') : t('admin.inactive', 'Inactive') }}</span>
                            </button>
                        </TableCell>
                        <TableCell class="text-right">
                            <div class="flex justify-end gap-2">
                                <Button variant="ghost" size="sm" as-child>
                                    <Link :href="`/admin/users/${user.id}/edit`">
                                        <Edit class="h-4 w-4" />
                                    </Link>
                                </Button>
                                <Button
                                    variant="ghost"
                                    size="sm"
                                    @click="deleteUser(user)"
                                >
                                    <Trash2 class="h-4 w-4 text-red-600" />
                                </Button>
                            </div>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>

        <!-- Pagination -->
        <div v-if="users.last_page > 1" class="flex items-center justify-between">
            <p class="text-sm text-muted-foreground">
                Showing {{ (users.current_page - 1) * users.per_page + 1 }} to
                {{ Math.min(users.current_page * users.per_page, users.total) }} of
                {{ users.total }} users
            </p>
            <div class="flex gap-2">
                <Button
                    variant="outline"
                    size="sm"
                    :disabled="users.current_page === 1"
                    @click="router.get(`/admin/users?page=${users.current_page - 1}`)"
                >
                    {{ t('common.previous', 'Previous') }}
                </Button>
                <Button
                    variant="outline"
                    size="sm"
                    :disabled="users.current_page === users.last_page"
                    @click="router.get(`/admin/users?page=${users.current_page + 1}`)"
                >
                    {{ t('common.next', 'Next') }}
                </Button>
            </div>
        </div>
    </div>
    </KpsShellLayout>
</template>
