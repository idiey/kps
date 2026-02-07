<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { debounce } from 'lodash';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Badge } from '@/components/ui/badge';
import { Plus, Search, Eye, Edit, Trash2, Power } from 'lucide-vue-next';

interface Workshop {
    id: number;
    name: string;
    code: string;
    phone?: string;
    email?: string;
    is_active: boolean;
    jobs_count: number;
    customers_count: number;
    company?: { name: string };
}

interface Company {
    id: number;
    name: string;
}

interface PaginatedWorkshops {
    data: Workshop[];
    from: number;
    to: number;
    total: number;
    links: Array<{ label: string; url: string | null; active: boolean }>;
}

const props = defineProps<{
    workshops: PaginatedWorkshops;
    companies: Company[];
    filters: {
        search?: string;
        status?: string;
        company_id?: string;
    };
}>();

const search = ref(props.filters.search || '');
const status = ref(props.filters.status || 'all');
const companyId = ref(props.filters.company_id || 'all');

const searchWorkshops = debounce(() => {
    router.get('/admin/workshops', {
        search: search.value,
        status: status.value === 'all' ? '' : status.value,
        company_id: companyId.value === 'all' ? '' : companyId.value,
    }, {
        preserveState: true,
        preserveScroll: true,
    });
}, 300);

const deleteWorkshop = (workshop: Workshop) => {
    if (confirm(`Are you sure you want to delete ${workshop.name}?`)) {
        router.delete(`/admin/workshops/${workshop.id}`, {
            preserveScroll: true,
        });
    }
};

const toggleStatus = (workshop: Workshop) => {
    router.post(`/admin/workshops/${workshop.id}/toggle-status`, {}, {
        preserveScroll: true,
    });
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Workshops',
        href: '/admin/workshops',
    },
];
</script>

<template>
    <Head title="Workshops" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold tracking-tight">Workshop Management</h2>
                <p class="text-muted-foreground">Manage workshops and their locations</p>
            </div>
            <Button as-child>
                <Link href="/admin/workshops/create">
                    <Plus class="mr-2 h-4 w-4" />
                    Add Workshop
                </Link>
            </Button>
        </div>

        <!-- Filters -->
        <div class="flex gap-4">
            <div class="flex-1">
                <Input
                    v-model="search"
                    placeholder="Search by name, code, email..."
                    @input="searchWorkshops"
                >
                    <template #prefix>
                        <Search class="h-4 w-4 text-muted-foreground" />
                    </template>
                </Input>
            </div>
            <Select v-model="status" @update:model-value="searchWorkshops">
                <SelectTrigger class="w-[180px]">
                    <SelectValue placeholder="Filter by status" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem value="all">All Status</SelectItem>
                    <SelectItem value="active">Active</SelectItem>
                    <SelectItem value="inactive">Inactive</SelectItem>
                </SelectContent>
            </Select>
            <Select v-model="companyId" @update:model-value="searchWorkshops">
                <SelectTrigger class="w-[200px]">
                    <SelectValue placeholder="Filter by company" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem value="all">All Companies</SelectItem>
                    <SelectItem v-for="company in companies" :key="company.id" :value="company.id.toString()">
                        {{ company.name }}
                    </SelectItem>
                </SelectContent>
            </Select>
        </div>

        <!-- Workshops Table -->
        <div class="rounded-md border">
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Workshop</TableHead>
                        <TableHead>Code</TableHead>
                        <TableHead>Company</TableHead>
                        <TableHead>Contact</TableHead>
                        <TableHead class="text-center">Jobs</TableHead>
                        <TableHead class="text-center">Customers</TableHead>
                        <TableHead class="text-center">Status</TableHead>
                        <TableHead class="text-right">Actions</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-if="workshops.data.length === 0">
                        <TableCell colspan="8" class="text-center text-muted-foreground">
                            No workshops found
                        </TableCell>
                    </TableRow>
                    <TableRow v-for="workshop in workshops.data" :key="workshop.id">
                        <TableCell class="font-medium">{{ workshop.name }}</TableCell>
                        <TableCell>
                            <code class="px-2 py-1 text-xs bg-muted rounded">{{ workshop.code }}</code>
                        </TableCell>
                        <TableCell>{{ workshop.company?.name || 'N/A' }}</TableCell>
                        <TableCell>
                            <div class="text-xs space-y-1">
                                <div v-if="workshop.phone">📞 {{ workshop.phone }}</div>
                                <div v-if="workshop.email">✉️ {{ workshop.email }}</div>
                                <div v-if="!workshop.phone && !workshop.email" class="text-muted-foreground">-</div>
                            </div>
                        </TableCell>
                        <TableCell class="text-center">
                            <Badge variant="secondary">{{ workshop.jobs_count }}</Badge>
                        </TableCell>
                        <TableCell class="text-center">
                            <Badge variant="secondary">{{ workshop.customers_count }}</Badge>
                        </TableCell>
                        <TableCell class="text-center">
                            <Badge :variant="workshop.is_active ? 'default' : 'outline'">
                                {{ workshop.is_active ? 'Active' : 'Inactive' }}
                            </Badge>
                        </TableCell>
                        <TableCell class="text-right">
                            <div class="flex justify-end gap-2">
                                <Button variant="ghost" size="sm" as-child>
                                    <Link :href="`/admin/workshops/${workshop.id}`">
                                        <Eye class="h-4 w-4" />
                                    </Link>
                                </Button>
                                <Button variant="ghost" size="sm" as-child>
                                    <Link :href="`/admin/workshops/${workshop.id}/edit`">
                                        <Edit class="h-4 w-4" />
                                    </Link>
                                </Button>
                                <Button variant="ghost" size="sm" @click="toggleStatus(workshop)">
                                    <Power class="h-4 w-4" :class="workshop.is_active ? 'text-yellow-600' : 'text-green-600'" />
                                </Button>
                                <Button variant="ghost" size="sm" @click="deleteWorkshop(workshop)">
                                    <Trash2 class="h-4 w-4 text-red-600" />
                                </Button>
                            </div>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>

        <!-- Pagination -->
        <div v-if="workshops.links.length > 3" class="flex items-center justify-between">
            <p class="text-sm text-muted-foreground">
                Showing {{ workshops.from }} to {{ workshops.to }} of {{ workshops.total }} workshops
            </p>
            <div class="flex gap-2">
                <Link
                    v-for="link in workshops.links"
                    :key="link.label"
                    :href="link.url"
                    :class="{
                        'pointer-events-none opacity-50': !link.url,
                    }"
                >
                    <Button
                        :variant="link.active ? 'default' : 'outline'"
                        size="sm"
                        :disabled="!link.url"
                        v-html="link.label"
                    />
                </Link>
            </div>
        </div>
    </div>
    </AppLayout>
</template>
