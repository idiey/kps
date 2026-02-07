<script setup lang="ts">
import { Head, router, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
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
];
</script>

<template>
    <Head title="User Management" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold tracking-tight">User Management</h2>
                <p class="text-muted-foreground">Manage system users and their roles</p>
            </div>
            <Button as-child>
                <Link href="/admin/users/create">
                    <Plus class="mr-2 h-4 w-4" />
                    Add User
                </Link>
            </Button>
        </div>

        <!-- Filters -->
        <div class="flex gap-4">
            <div class="flex-1">
                <Input
                    v-model="search"
                    placeholder="Search by name or email..."
                    @keyup.enter="applyFilters"
                >
                    <template #prefix>
                        <Search class="h-4 w-4 text-muted-foreground" />
                    </template>
                </Input>
            </div>
            <Select v-model="selectedRole" @update:model-value="applyFilters">
                <SelectTrigger class="w-[180px]">
                    <SelectValue placeholder="Filter by role" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem value="all">All Roles</SelectItem>
                    <SelectItem v-for="role in roles" :key="role.id" :value="role.name">
                        {{ getRoleDisplayName(role.name) }}
                    </SelectItem>
                </SelectContent>
            </Select>
            <Select v-model="selectedStatus" @update:model-value="applyFilters">
                <SelectTrigger class="w-[180px]">
                    <SelectValue placeholder="Filter by status" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem value="all">All Status</SelectItem>
                    <SelectItem value="true">Active</SelectItem>
                    <SelectItem value="false">Inactive</SelectItem>
                </SelectContent>
            </Select>
            <Button variant="outline" @click="clearFilters">Clear</Button>
        </div>

        <!-- Users Table -->
        <div class="rounded-md border">
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Name</TableHead>
                        <TableHead>Email</TableHead>
                        <TableHead>Role</TableHead>
                        <TableHead>Department</TableHead>
                        <TableHead>Status</TableHead>
                        <TableHead class="text-right">Actions</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-if="users.data.length === 0">
                        <TableCell colspan="6" class="text-center text-muted-foreground">
                            No users found
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
                                <span class="text-sm">{{ user.active ? 'Active' : 'Inactive' }}</span>
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
                    Previous
                </Button>
                <Button
                    variant="outline"
                    size="sm"
                    :disabled="users.current_page === users.last_page"
                    @click="router.get(`/admin/users?page=${users.current_page + 1}`)"
                >
                    Next
                </Button>
            </div>
        </div>
    </div>
    </AppLayout>
</template>
