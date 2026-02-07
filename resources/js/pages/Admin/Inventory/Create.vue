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

const form = useForm({
    part_number: '',
    name: '',
    description: '',
    category: 'engine',
    quantity_in_stock: 0,
    minimum_stock_level: 10,
    unit_price: '',
    unit_of_measurement: 'piece',
    location: '',
});

const submit = () => {
    form.post('/admin/inventory');
};

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Inventory', href: '/admin/inventory' },
    { title: 'Add Part', href: '/admin/inventory/create' },
];
</script>

<template>
    <Head title="Add Part" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6">
        <div class="flex items-center gap-4">
            <Button variant="ghost" size="icon" @click="router.visit('/admin/inventory')">
                <ArrowLeft class="h-5 w-5" />
            </Button>
            <div>
                <h2 class="text-2xl font-bold tracking-tight">Add Part</h2>
                <p class="text-muted-foreground">Add a new part to inventory</p>
            </div>
        </div>

        <form @submit.prevent="submit" class="max-w-2xl space-y-6">
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <Label for="part_number">Part Number *</Label>
                    <Input id="part_number" v-model="form.part_number" required />
                    <p v-if="form.errors.part_number" class="text-sm text-red-500">{{ form.errors.part_number }}</p>
                </div>

                <div class="space-y-2">
                    <Label for="category">Category *</Label>
                    <Select v-model="form.category">
                        <SelectTrigger><SelectValue /></SelectTrigger>
                        <SelectContent>
                            <SelectItem value="engine">Engine Parts</SelectItem>
                            <SelectItem value="electrical">Electrical</SelectItem>
                            <SelectItem value="body">Body Parts</SelectItem>
                            <SelectItem value="tools">Tools</SelectItem>
                        </SelectContent>
                    </Select>
                </div>

                <div class="col-span-2 space-y-2">
                    <Label for="name">Part Name *</Label>
                    <Input id="name" v-model="form.name" required />
                    <p v-if="form.errors.name" class="text-sm text-red-500">{{ form.errors.name }}</p>
                </div>

                <div class="col-span-2 space-y-2">
                    <Label for="description">Description</Label>
                    <Textarea id="description" v-model="form.description" rows="3" />
                </div>

                <div class="space-y-2">
                    <Label for="quantity">Initial Quantity *</Label>
                    <Input id="quantity" type="number" v-model.number="form.quantity_in_stock" required />
                </div>

                <div class="space-y-2">
                    <Label for="min_level">Minimum Stock Level *</Label>
                    <Input id="min_level" type="number" v-model.number="form.minimum_stock_level" required />
                </div>

                <div class="space-y-2">
                    <Label for="unit_price">Unit Price (RM) *</Label>
                    <Input id="unit_price" type="number" step="0.01" v-model="form.unit_price" required />
                    <p v-if="form.errors.unit_price" class="text-sm text-red-500">{{ form.errors.unit_price }}</p>
                </div>

                <div class="space-y-2">
                    <Label for="unit">Unit of Measurement *</Label>
                    <Select v-model="form.unit_of_measurement">
                        <SelectTrigger><SelectValue /></SelectTrigger>
                        <SelectContent>
                            <SelectItem value="piece">Piece</SelectItem>
                            <SelectItem value="set">Set</SelectItem>
                            <SelectItem value="meter">Meter</SelectItem>
                            <SelectItem value="liter">Liter</SelectItem>
                            <SelectItem value="kg">Kilogram</SelectItem>
                        </SelectContent>
                    </Select>
                </div>

                <div class="col-span-2 space-y-2">
                    <Label for="location">Storage Location</Label>
                    <Input id="location" v-model="form.location" placeholder="e.g., Shelf A-1" />
                </div>
            </div>

            <div class="flex gap-4">
                <Button type="submit" :disabled="form.processing">
                    {{ form.processing ? 'Adding...' : 'Add Part' }}
                </Button>
                <Button type="button" variant="outline" @click="router.visit('/admin/inventory')">Cancel</Button>
            </div>
        </form>
    </div>
    </AppLayout>
</template>
