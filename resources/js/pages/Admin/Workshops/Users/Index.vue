<script setup lang="ts">
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import SiteLayout from '@/layouts/app/SiteLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem, type Workshop as WorkshopType, type SiteRole } from '@/types';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Input } from '@/components/ui/input';
import { ArrowLeft, UserPlus, Trash2 } from 'lucide-vue-next';
import { ref } from 'vue';

interface User {
    id: number;
    name: string;
    email: string;
}

interface AssignedUser extends User {
    pivot: {
        role: string;
    };
}

interface Workshop {
    id: number;
    name: string;
    company?: { name: string };
}

const props = defineProps<{
    workshop: Workshop;
    assignedUsers: AssignedUser[];
    availableUsers: User[];
    site: WorkshopType;
    siteRole?: SiteRole;
}>();

const form = useForm({
    user_id: '',
    role: 'staff',
});

const createForm = useForm({
    role: 'site_admin',
    new_user: {
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        role: 'penyelia',
    },
});

const roleLabels: Record<string, string> = {
    site_admin: 'Site Admin',
    supervisor: 'Supervisor',
    technician: 'Technician',
    staff: 'Staff',
};

const assignUser = () => {
    form.post(`/admin/workshops/${props.workshop.id}/users`, {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
    });
};

const createSiteAdmin = () => {
    createForm.post(`/admin/workshops/${props.workshop.id}/users`, {
        preserveScroll: true,
        onSuccess: () => {
            createForm.reset();
            createForm.role = 'site_admin';
            createForm.new_user.role = 'penyelia';
        },
    });
};

const updateRole = (userId: number, newRole: string) => {
    router.patch(`/admin/workshops/${props.workshop.id}/users/${userId}`, {
        role: newRole,
    }, {
        preserveScroll: true,
    });
};

const removeUser = (userId: number) => {
    if (confirm('Are you sure you want to remove this user from the workshop?')) {
        router.delete(`/admin/workshops/${props.workshop.id}/users/${userId}`, {
            preserveScroll: true,
        });
    }
};

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Workshops', href: '/admin/workshops' },
    { title: props.workshop.name, href: `/admin/workshops/${props.workshop.id}` },
    { title: 'Users', href: `/admin/workshops/${props.workshop.id}/users` },
];
</script>

<template>
    <Head :title="`${workshop.name} - Users`" />

    <SiteLayout :breadcrumbs="breadcrumbs" :site="site" :site-role="siteRole">
        <div class="space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Button variant="ghost" size="icon" as-child>
                        <Link :href="`/admin/workshops/${workshop.id}`">
                            <ArrowLeft class="h-5 w-5" />
                        </Link>
                    </Button>
                    <div>
                        <h2 class="text-2xl font-bold tracking-tight">Manage Users</h2>
                        <p class="text-muted-foreground">Assign and manage users for {{ workshop.name }}</p>
                    </div>
                </div>
            </div>

            <!-- Assign User Form -->
            <Card>
                <CardHeader>
                    <CardTitle>Assign New User</CardTitle>
                    <CardDescription>Add a user to this workshop with a specific role</CardDescription>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="assignUser" class="flex items-end gap-4">
                        <div class="flex-1">
                            <label class="block text-sm font-medium mb-2">User</label>
                            <Select v-model="form.user_id">
                                <SelectTrigger>
                                    <SelectValue placeholder="Select a user" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem 
                                        v-for="user in availableUsers" 
                                        :key="user.id" 
                                        :value="String(user.id)"
                                    >
                                        {{ user.name }} ({{ user.email }})
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                            <p v-if="form.errors.user_id" class="text-sm text-destructive mt-1">
                                {{ form.errors.user_id }}
                            </p>
                        </div>
                        <div class="w-48">
                            <label class="block text-sm font-medium mb-2">Role</label>
                            <Select v-model="form.role">
                                <SelectTrigger>
                                    <SelectValue />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="site_admin">Site Admin</SelectItem>
                                    <SelectItem value="supervisor">Supervisor</SelectItem>
                                    <SelectItem value="technician">Technician</SelectItem>
                                    <SelectItem value="staff">Staff</SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                        <Button type="submit" :disabled="form.processing || !form.user_id">
                            <UserPlus class="mr-2 h-4 w-4" />
                            Assign
                        </Button>
                    </form>
                </CardContent>
            </Card>

            <!-- Create Site Admin -->
            <Card>
                <CardHeader>
                    <CardTitle>Create Site Admin</CardTitle>
                    <CardDescription>Create a new user and assign them as site admin for this workshop</CardDescription>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="createSiteAdmin" class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium mb-2">Name</label>
                            <Input v-model="createForm.new_user.name" type="text" />
                            <p v-if="createForm.errors['new_user.name']" class="text-sm text-destructive mt-1">
                                {{ createForm.errors['new_user.name'] }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Email</label>
                            <Input v-model="createForm.new_user.email" type="email" />
                            <p v-if="createForm.errors['new_user.email']" class="text-sm text-destructive mt-1">
                                {{ createForm.errors['new_user.email'] }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Password</label>
                            <Input v-model="createForm.new_user.password" type="password" />
                            <p v-if="createForm.errors['new_user.password']" class="text-sm text-destructive mt-1">
                                {{ createForm.errors['new_user.password'] }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Confirm Password</label>
                            <Input v-model="createForm.new_user.password_confirmation" type="password" />
                        </div>
                        <div class="md:col-span-2 flex items-center justify-between">
                            <div class="text-sm text-muted-foreground">
                                Role: Site Admin (base role: Penyelia)
                            </div>
                            <Button type="submit" :disabled="createForm.processing">
                                <UserPlus class="mr-2 h-4 w-4" />
                                Create & Assign
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>

            <!-- Assigned Users List -->
            <Card>
                <CardHeader>
                    <CardTitle>Assigned Users</CardTitle>
                    <CardDescription>{{ assignedUsers.length }} user(s) assigned to this workshop</CardDescription>
                </CardHeader>
                <CardContent>
                    <div v-if="assignedUsers.length === 0" class="text-center py-8 text-muted-foreground">
                        No users assigned yet. Use the form above to assign users.
                    </div>
                    <div v-else class="space-y-4">
                        <div
                            v-for="user in assignedUsers"
                            :key="user.id"
                            class="flex items-center justify-between p-4 border rounded-lg"
                        >
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center">
                                    <span class="font-semibold text-primary">
                                        {{ user.name.charAt(0).toUpperCase() }}
                                    </span>
                                </div>
                                <div>
                                    <p class="font-medium">{{ user.name }}</p>
                                    <p class="text-sm text-muted-foreground">{{ user.email }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <Select 
                                    :model-value="user.pivot.role" 
                                    @update:model-value="(val) => updateRole(user.id, String(val))"
                                >
                                    <SelectTrigger class="w-36">
                                        <SelectValue />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="site_admin">Site Admin</SelectItem>
                                        <SelectItem value="supervisor">Supervisor</SelectItem>
                                        <SelectItem value="technician">Technician</SelectItem>
                                        <SelectItem value="staff">Staff</SelectItem>
                                    </SelectContent>
                                </Select>
                                <Button 
                                    variant="ghost" 
                                    size="icon" 
                                    class="text-destructive hover:text-destructive"
                                    @click="removeUser(user.id)"
                                >
                                    <Trash2 class="h-4 w-4" />
                                </Button>
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </SiteLayout>
</template>
