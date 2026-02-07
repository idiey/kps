<script setup lang="ts">
import { Head, router, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { ref, computed } from 'vue';
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
import { Plus, Search, Edit, Trash2, Eye } from 'lucide-vue-next';

interface Asset {
    id: number;
    asset_tag: string;
    asset_type: string;
    asset_name: string;
    location?: string;
    current_condition: string;
    last_maintenance_date?: string;
    government_department: {
        id: number;
        name: string;
    };
    created_at: string;
}

interface Department {
    id: number;
    name: string;
}

interface PaginatedAssets {
    data: Asset[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
}

const props = defineProps<{
    assets: PaginatedAssets;
    departments: Department[];
    filters: {
        search?: string;
        asset_type?: string;
        condition?: string;
        department_id?: number;
    };
}>();

const search = ref(props.filters.search || '');
const selectedType = ref(props.filters.asset_type || 'all');
const selectedCondition = ref(props.filters.condition || 'all');
const selectedDepartment = ref(props.filters.department_id?.toString() || 'all');

const applyFilters = () => {
    router.get(
        '/admin/assets',
        {
            search: search.value || undefined,
            asset_type: selectedType.value === 'all' ? undefined : selectedType.value,
            condition: selectedCondition.value === 'all' ? undefined : selectedCondition.value,
            department_id: selectedDepartment.value === 'all' ? undefined : selectedDepartment.value,
        },
        {
            preserveState: true,
            preserveScroll: true,
        },
    );
};

const clearFilters = () => {
    search.value = '';
    selectedType.value = 'all';
    selectedCondition.value = 'all';
    selectedDepartment.value = 'all';
    router.get('/admin/assets');
};

const deleteAsset = (asset: Asset) => {
    if (confirm(`Are you sure you want to delete asset "${asset.asset_tag}"? This action cannot be undone.`)) {
        router.delete(`/admin/assets/${asset.id}`);
    }
};

const getConditionColor = (condition: string) => {
    const colors: Record<string, string> = {
        operational: 'bg-green-100 text-green-800',
        maintenance_required: 'bg-yellow-100 text-yellow-800',
        under_repair: 'bg-orange-100 text-orange-800',
        decommissioned: 'bg-red-100 text-red-800',
    };
    return colors[condition] || 'bg-gray-100 text-gray-800';
};

const getConditionLabel = (condition: string) => {
    const labels: Record<string, string> = {
        operational: 'Operational',
        maintenance_required: 'Maintenance Required',
        under_repair: 'Under Repair',
        decommissioned: 'Decommissioned',
    };
    return labels[condition] || condition;
};


const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Assets', href: '/admin/assets' },
];
</script>

<template>
    <Head title="Asset Management" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold tracking-tight">Asset Management</h2>
                <p class="text-muted-foreground">Manage government department assets and equipment</p>
            </div>
            <Button as-child>
                <Link href="/admin/assets/create">
                    <Plus class="mr-2 h-4 w-4" />
                    Add Asset
                </Link>
            </Button>
        </div>

        <!-- Filters -->
        <div class="flex gap-4">
            <div class="flex-1">
                <Input
                    v-model="search"
                    placeholder="Search by tag, name, type, or location..."
                    @keyup.enter="applyFilters"
                >
                    <template #prefix>
                        <Search class="h-4 w-4 text-muted-foreground" />
                    </template>
                </Input>
            </div>
            <Select v-model="selectedType" @update:model-value="applyFilters">
                <SelectTrigger class="w-[180px]">
                    <SelectValue placeholder="Asset Type" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem value="all">All Types</SelectItem>
                    <SelectItem value="vehicle">Vehicle</SelectItem>
                    <SelectItem value="equipment">Equipment</SelectItem>
                    <SelectItem value="tool">Tool</SelectItem>
                    <SelectItem value="machinery">Machinery</SelectItem>
                </SelectContent>
            </Select>
            <Select v-model="selectedCondition" @update:model-value="applyFilters">
                <SelectTrigger class="w-[200px]">
                    <SelectValue placeholder="Condition" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem value="all">All Conditions</SelectItem>
                    <SelectItem value="operational">Operational</SelectItem>
                    <SelectItem value="maintenance_required">Maintenance Required</SelectItem>
                    <SelectItem value="under_repair">Under Repair</SelectItem>
                    <SelectItem value="decommissioned">Decommissioned</SelectItem>
                </SelectContent>
            </Select>
            <Select v-model="selectedDepartment" @update:model-value="applyFilters">
                <SelectTrigger class="w-[200px]">
                    <SelectValue placeholder="Department" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem value="all">All Departments</SelectItem>
                    <SelectItem v-for="dept in departments" :key="dept.id" :value="dept.id.toString()">
                        {{ dept.name }}
                    </SelectItem>
                </SelectContent>
            </Select>
            <Button variant="outline" @click="clearFilters">Clear</Button>
        </div>

        <!-- Assets Table -->
        <div class="rounded-md border">
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Asset Tag</TableHead>
                        <TableHead>Name</TableHead>
                        <TableHead>Type</TableHead>
                        <TableHead>Department</TableHead>
                        <TableHead>Location</TableHead>
                        <TableHead>Condition</TableHead>
                        <TableHead>Last Maintenance</TableHead>
                        <TableHead class="text-right">Actions</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-if="assets.data.length === 0">
                        <TableCell colspan="8" class="text-center text-muted-foreground">
                            No assets found
                        </TableCell>
                    </TableRow>
                    <TableRow v-for="asset in assets.data" :key="asset.id">
                        <TableCell class="font-medium">{{ asset.asset_tag }}</TableCell>
                        <TableCell>{{ asset.asset_name }}</TableCell>
                        <TableCell>{{ asset.asset_type }}</TableCell>
                        <TableCell>{{ asset.government_department.name }}</TableCell>
                        <TableCell>{{ asset.location || '-' }}</TableCell>
                        <TableCell>
                            <Badge :class="getConditionColor(asset.current_condition)">
                                {{ getConditionLabel(asset.current_condition) }}
                            </Badge>
                        </TableCell>
                        <TableCell>
                            {{ asset.last_maintenance_date || 'Never' }}
                        </TableCell>
                        <TableCell class="text-right">
                            <div class="flex justify-end gap-2">
                                <Button variant="ghost" size="sm" as-child>
                                    <Link :href="`/admin/assets/${asset.id}`">
                                        <Eye class="h-4 w-4" />
                                    </Link>
                                </Button>
                                <Button variant="ghost" size="sm" as-child>
                                    <Link :href="`/admin/assets/${asset.id}/edit`">
                                        <Edit class="h-4 w-4" />
                                    </Link>
                                </Button>
                                <Button
                                    variant="ghost"
                                    size="sm"
                                    @click="deleteAsset(asset)"
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
        <div v-if="assets.last_page > 1" class="flex items-center justify-between">
            <p class="text-sm text-muted-foreground">
                Showing {{ (assets.current_page - 1) * assets.per_page + 1 }} to
                {{ Math.min(assets.current_page * assets.per_page, assets.total) }} of
                {{ assets.total }} assets
            </p>
            <div class="flex gap-2">
                <Button
                    variant="outline"
                    size="sm"
                    :disabled="assets.current_page === 1"
                    @click="router.get(`/admin/assets?page=${assets.current_page - 1}`)"
                >
                    Previous
                </Button>
                <Button
                    variant="outline"
                    size="sm"
                    :disabled="assets.current_page === assets.last_page"
                    @click="router.get(`/admin/assets?page=${assets.current_page + 1}`)"
                >
                    Next
                </Button>
            </div>
        </div>
    </div>
    </AppLayout>
</template>
