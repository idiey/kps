<script setup lang="ts">
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Checkbox } from '@/components/ui/checkbox';
import { ArrowLeft } from 'lucide-vue-next';

interface Company {
    id: number;
    name: string;
}

const props = defineProps<{
    companies: Company[];
}>();

const form = useForm({
    company_id: 'none',
    name: '',
    code: '',
    address: '',
    phone: '',
    email: '',
    is_active: true,
});

const submit = () => {
    form.post('/admin/workshops');
};

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Workshops', href: '/admin/workshops' },
    { title: 'Create Workshop', href: '/admin/workshops/create' },
];
</script>

<template>
    <Head title="Create Workshop" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center gap-4">
            <Button variant="ghost" size="icon" @click="router.visit('/admin/workshops')">
                <ArrowLeft class="h-5 w-5" />
            </Button>
            <div>
                <h2 class="text-2xl font-bold tracking-tight">Create Workshop</h2>
                <p class="text-muted-foreground">Add a new workshop to the system</p>
            </div>
        </div>

        <!-- Form -->
        <form @submit.prevent="submit" class="max-w-2xl space-y-6">
            <!-- Company Selection -->
            <div class="space-y-2">
                <Label for="company_id">Company (Optional)</Label>
                <Select v-model="form.company_id">
                    <SelectTrigger>
                        <SelectValue placeholder="Select a company" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem value="none">No company</SelectItem>
                        <SelectItem v-for="company in companies" :key="company.id" :value="company.id.toString()">
                            {{ company.name }}
                        </SelectItem>
                    </SelectContent>
                </Select>
                <p v-if="form.errors.company_id" class="text-sm text-red-500">{{ form.errors.company_id }}</p>
            </div>

            <!-- Workshop Name -->
            <div class="space-y-2">
                <Label for="name">Workshop Name *</Label>
                <Input id="name" v-model="form.name" required autofocus />
                <p v-if="form.errors.name" class="text-sm text-red-500">{{ form.errors.name }}</p>
            </div>

            <!-- Code -->
            <div class="space-y-2">
                <Label for="code">Workshop Code *</Label>
                <Input id="code" v-model="form.code" required placeholder="e.g., WS-KL-001" />
                <p class="text-xs text-muted-foreground">Unique identifier for this workshop</p>
                <p v-if="form.errors.code" class="text-sm text-red-500">{{ form.errors.code }}</p>
            </div>

            <!-- Address -->
            <div class="space-y-2">
                <Label for="address">Address</Label>
                <Textarea id="address" v-model="form.address" rows="3" />
                <p v-if="form.errors.address" class="text-sm text-red-500">{{ form.errors.address }}</p>
            </div>

            <!-- Contact Info -->
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <Label for="phone">Phone</Label>
                    <Input id="phone" v-model="form.phone" placeholder="+60123456789" />
                    <p v-if="form.errors.phone" class="text-sm text-red-500">{{ form.errors.phone }}</p>
                </div>

                <div class="space-y-2">
                    <Label for="email">Email</Label>
                    <Input id="email" type="email" v-model="form.email" placeholder="workshop@example.com" />
                    <p v-if="form.errors.email" class="text-sm text-red-500">{{ form.errors.email }}</p>
                </div>
            </div>

            <!-- Status -->
            <div class="flex items-center space-x-2">
                <Checkbox id="is_active" v-model:checked="form.is_active" />
                <Label for="is_active" class="text-sm font-normal cursor-pointer">
                    Active
                </Label>
            </div>

            <!-- Submit Buttons -->
            <div class="flex gap-4">
                <Button type="submit" :disabled="form.processing">
                    {{ form.processing ? 'Creating...' : 'Create Workshop' }}
                </Button>
                <Button type="button" variant="outline" @click="router.visit('/admin/workshops')">
                    Cancel
                </Button>
            </div>
        </form>
    </div>
    </AppLayout>
</template>
