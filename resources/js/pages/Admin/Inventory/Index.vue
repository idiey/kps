<script setup lang="ts">
import { Head, router, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { ref } from 'vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Plus, Search, Edit, Trash2, AlertCircle } from 'lucide-vue-next';

interface Part {
    id: number;
    part_number: string;
    name: string;
    category: string;
    quantity_in_stock: number;
    minimum_stock_level: number;
    unit_price: string;
    location?: string;
}

const props = defineProps<{
    parts: {
        data: Part[];
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
    };
    filters: {
        search?: string;
        category?: string;
        low_stock?: boolean;
    };
}>();

const search = ref(props.filters.search || '');
const selectedCategory = ref(props.filters.category || 'all');
const showLowStock = ref(props.filters.low_stock || false);

const applyFilters = () => {
    router.get('/admin/inventory', {
        search: search.value || undefined,
        category: selectedCategory.value === 'all' ? undefined : selectedCategory.value,
        low_stock: showLowStock.value || undefined,
    }, { preserveState: true, preserveScroll: true });
};

const clearFilters = () => {
    search.value = '';
    selectedCategory.value = 'all';
    showLowStock.value = false;
    router.get('/admin/inventory');
};

const deletePart = (part: Part) => {
    if (confirm(`Delete part "${part.name}"? This cannot be undone.`)) {
        router.delete(`/admin/inventory/${part.id}`);
    }
};

const isLowStock = (part: Part) => part.quantity_in_stock <= part.minimum_stock_level;

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Inventory', href: '/admin/inventory' },
];
</script>

<template>
    <Head title="Inventory Management" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold tracking-tight">Inventory Management</h2>
                <p class="text-muted-foreground">Manage parts and stock levels</p>
            </div>
            <Button as-child>
                <Link href="/admin/inventory/create">
                    <Plus class="mr-2 h-4 w-4" />
                    Add Part
                </Link>
            </Button>
        </div>

        <!-- Filters -->
        <div class="flex gap-4">
            <div class="flex-1">
                <Input v-model="search" placeholder="Search parts..." @keyup.enter="applyFilters">
                    <template #prefix>
                        <Search class="h-4 w-4 text-muted-foreground" />
                    </template>
                </Input>
            </div>
            <Select v-model="selectedCategory" @update:model-value="applyFilters">
                <SelectTrigger class="w-[180px]">
                    <SelectValue placeholder="Category" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem value="all">All Categories</SelectItem>
                    <SelectItem value="engine">Engine Parts</SelectItem>
                    <SelectItem value="electrical">Electrical</SelectItem>
                    <SelectItem value="body">Body Parts</SelectItem>
                    <SelectItem value="tools">Tools</SelectItem>
                </SelectContent>
            </Select>
            <Button :variant="showLowStock ? 'default' : 'outline'" @click="showLowStock = !showLowStock; applyFilters()">
                <AlertCircle class="mr-2 h-4 w-4" />
                Low Stock Only
            </Button>
            <Button variant="outline" @click="clearFilters">Clear</Button>
        </div>

        <!-- Parts Table -->
        <div class="rounded-md border">
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Part Number</TableHead>
                        <TableHead>Name</TableHead>
                        <TableHead>Category</TableHead>
                        <TableHead>Stock</TableHead>
                        <TableHead>Min Level</TableHead>
                        <TableHead>Unit Price</TableHead>
                        <TableHead>Location</TableHead>
                        <TableHead class="text-right">Actions</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-if="parts.data.length === 0">
                        <TableCell colspan="8" class="text-center text-muted-foreground">
                            No parts found
                        </TableCell>
                    </TableRow>
                    <TableRow v-for="part in parts.data" :key="part.id">
                        <TableCell class="font-medium">{{ part.part_number }}</TableCell>
                        <TableCell>{{ part.name }}</TableCell>
                        <TableCell>{{ part.category }}</TableCell>
                        <TableCell>
                            <Badge :class="isLowStock(part) ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'">
                                {{ part.quantity_in_stock }}
                                <AlertCircle v-if="isLowStock(part)" class="ml-1 h-3 w-3" />
                            </Badge>
                        </TableCell>
                        <TableCell>{{ part.minimum_stock_level }}</TableCell>
                        <TableCell>RM {{ parseFloat(part.unit_price).toFixed(2) }}</TableCell>
                        <TableCell>{{ part.location || '-' }}</TableCell>
                        <TableCell class="text-right">
                            <div class="flex justify-end gap-2">
                                <Button variant="ghost" size="sm" as-child>
                                    <Link :href="`/admin/inventory/${part.id}/edit`">
                                        <Edit class="h-4 w-4" />
                                    </Link>
                                </Button>
                                <Button variant="ghost" size="sm" @click="deletePart(part)">
                                    <Trash2 class="h-4 w-4 text-red-600" />
                                </Button>
                            </div>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>

        <!-- Pagination -->
        <div v-if="parts.last_page > 1" class="flex items-center justify-between">
            <p class="text-sm text-muted-foreground">
                Showing {{ (parts.current_page - 1) * parts.per_page + 1 }} to
                {{ Math.min(parts.current_page * parts.per_page, parts.total) }} of {{ parts.total }} parts
            </p>
            <div class="flex gap-2">
                <Button variant="outline" size="sm"
                    :disabled="parts.current_page === 1"
                    @click="router.get(`/admin/inventory?page=${parts.current_page - 1}`)">
                    Previous
                </Button>
                <Button variant="outline" size="sm"
                    :disabled="parts.current_page === parts.last_page"
                    @click="router.get(`/admin/inventory?page=${parts.current_page + 1}`)">
                    Next
                </Button>
            </div>
        </div>
    </div>
    </AppLayout>
</template>
