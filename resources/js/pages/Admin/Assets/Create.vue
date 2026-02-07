<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { ArrowLeft } from 'lucide-vue-next';

interface Department {
    id: number;
    name: string;
}

const props = defineProps<{
    departments: Department[];
}>();

const form = useForm({
    asset_tag: '',
    asset_type: 'equipment',
    asset_name: '',
    description: '',
    location: '',
    current_condition: 'operational',
    last_maintenance_date: '',
    government_department_id: '',
});

const submit = () => {
    form.post('/admin/assets');
};

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Assets', href: '/admin/assets' },
    { title: 'Create Asset', href: '/admin/assets/create' },
];
</script>

<template>
    <Head title="Create Asset" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6">
        <div class="flex items-center gap-4">
            <Button variant="ghost" size="icon" @click="router.visit('/admin/assets')">
                <ArrowLeft class="h-5 w-5" />
            </Button>
            <div>
                <h2 class="text-2xl font-bold tracking-tight">Create Asset</h2>
                <p class="text-muted-foreground">Add a new asset to the system</p>
            </div>
        </div>

        <form @submit.prevent="submit" class="max-w-2xl space-y-6">
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <Label for="asset_tag">Asset Tag *</Label>
                    <Input id="asset_tag" v-model="form.asset_tag" required />
                    <p v-if="form.errors.asset_tag" class="text-sm text-red-500">{{ form.errors.asset_tag }}</p>
                </div>

                <div class="space-y-2">
                    <Label for="asset_type">Asset Type *</Label>
                    <Select v-model="form.asset_type">
                        <SelectTrigger><SelectValue /></SelectTrigger>
                        <SelectContent>
                            <SelectItem value="vehicle">Vehicle</SelectItem>
                            <SelectItem value="equipment">Equipment</SelectItem>
                            <SelectItem value="tool">Tool</SelectItem>
                            <SelectItem value="machinery">Machinery</SelectItem>
                        </SelectContent>
                    </Select>
                </div>

                <div class="col-span-2 space-y-2">
                    <Label for="asset_name">Asset Name *</Label>
                    <Input id="asset_name" v-model="form.asset_name" required />
                    <p v-if="form.errors.asset_name" class="text-sm text-red-500">{{ form.errors.asset_name }}</p>
                </div>

                <div class="col-span-2 space-y-2">
                    <Label for="description">Description</Label>
                    <Textarea id="description" v-model="form.description" rows="3" />
                </div>

                <div class="space-y-2">
                    <Label for="location">Location</Label>
                    <Input id="location" v-model="form.location" />
                </div>

                <div class="space-y-2">
                    <Label for="condition">Condition *</Label>
                    <Select v-model="form.current_condition">
                        <SelectTrigger><SelectValue /></SelectTrigger>
                        <SelectContent>
                            <SelectItem value="operational">Operational</SelectItem>
                            <SelectItem value="maintenance_required">Maintenance Required</SelectItem>
                            <SelectItem value="under_repair">Under Repair</SelectItem>
                            <SelectItem value="decommissioned">Decommissioned</SelectItem>
                        </SelectContent>
                    </Select>
                </div>

                <div class="space-y-2">
                    <Label for="maintenance_date">Last Maintenance Date</Label>
                    <Input id="maintenance_date" type="date" v-model="form.last_maintenance_date" />
                </div>

                <div class="space-y-2">
                    <Label for="department">Department *</Label>
                    <Select v-model="form.government_department_id">
                        <SelectTrigger><SelectValue placeholder="Select department" /></SelectTrigger>
                        <SelectContent>
                            <SelectItem v-for="dept in departments" :key="dept.id" :value="dept.id.toString()">
                                {{ dept.name }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                    <p v-if="form.errors.government_department_id" class="text-sm text-red-500">{{ form.errors.government_department_id }}</p>
                </div>
            </div>

            <div class="flex gap-4">
                <Button type="submit" :disabled="form.processing">
                    {{ form.processing ? 'Creating...' : 'Create Asset' }}
                </Button>
                <Button type="button" variant="outline" @click="router.visit('/admin/assets')">
                    Cancel
                </Button>
            </div>
        </form>
    </div>
    </AppLayout>
</template>
