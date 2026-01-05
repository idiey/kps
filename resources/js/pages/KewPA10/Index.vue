<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
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
import AppLayout from '@/layouts/AppLayout.vue';
import { show as showJob } from '@/routes/jobs';
import { create, index } from '@/routes/kew-pa-10';
import { Head, Link, router } from '@inertiajs/vue3';
import { CheckCircle, Filter, Plus, Search, XCircle } from 'lucide-vue-next';
import { computed, ref } from 'vue';

interface GovernmentDepartment {
    id: number;
    name: string;
}

interface Asset {
    id: number;
    asset_tag: string;
    asset_name: string;
    asset_type: string;
}

interface WorkshopJob {
    id: number;
    job_reference: string;
    status: string;
}

interface KewPA10 {
    id: number;
    kew_pa_10_number: string;
    government_department: GovernmentDepartment;
    asset: Asset;
    description: string;
    priority: string;
    form_completeness_verified: boolean;
    signatures_verified: boolean;
    received_date: string;
    workshop_job?: WorkshopJob;
}

interface PaginatedData<T> {
    data: T[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    links: {
        url: string | null;
        label: string;
        active: boolean;
    }[];
}

interface Filters {
    search?: string;
    government_department_id?: string;
    priority?: string;
    verified?: string;
    date_from?: string;
    date_to?: string;
}

interface Props {
    kewPA10s: PaginatedData<KewPA10>;
    filters: Filters;
    governmentDepartments: GovernmentDepartment[];
    priorities: { value: string; label: string }[];
}

const props = defineProps<Props>();

// Filter state
const search = ref(props.filters.search || '');
const selectedDepartment = ref(props.filters.government_department_id || '');
const selectedPriority = ref(props.filters.priority || '');
const selectedVerified = ref(props.filters.verified || '');
const dateFrom = ref(props.filters.date_from || '');
const dateTo = ref(props.filters.date_to || '');

const showFilters = ref(false);

// Apply filters
const applyFilters = () => {
    router.get(
        index.url(),
        {
            search: search.value,
            government_department_id: selectedDepartment.value,
            priority: selectedPriority.value,
            verified: selectedVerified.value,
            date_from: dateFrom.value,
            date_to: dateTo.value,
        },
        {
            preserveState: true,
            preserveScroll: true,
        },
    );
};

// Clear filters
const clearFilters = () => {
    search.value = '';
    selectedDepartment.value = '';
    selectedPriority.value = '';
    selectedVerified.value = '';
    dateFrom.value = '';
    dateTo.value = '';
    applyFilters();
};

// Check if filters are active
const hasActiveFilters = computed(() => {
    return !!(
        search.value ||
        selectedDepartment.value ||
        selectedPriority.value ||
        selectedVerified.value ||
        dateFrom.value ||
        dateTo.value
    );
});

// Priority badge variant
const getPriorityVariant = (
    priority: string,
): 'default' | 'secondary' | 'destructive' => {
    switch (priority.toLowerCase()) {
        case 'urgent':
            return 'destructive';
        case 'high':
            return 'default';
        default:
            return 'secondary';
    }
};

// Verification status
const isVerified = (form: KewPA10) => {
    return form.form_completeness_verified && form.signatures_verified;
};

// Format date
const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('en-MY', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    });
};
</script>

<template>
    <AppLayout>
        <Head title="KEW.PA-10 Forms" />

        <div class="space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight">
                        KEW.PA-10 Forms
                    </h1>
                    <p class="text-muted-foreground">
                        Manage government procurement and repair authorization
                        forms
                    </p>
                </div>
                <Link :href="create.url()">
                    <Button>
                        <Plus class="mr-2 h-4 w-4" />
                        Register New Form
                    </Button>
                </Link>
            </div>

            <!-- Search and Filters -->
            <Card>
                <CardHeader>
                    <div class="flex items-center justify-between">
                        <div class="mr-4 flex-1">
                            <div class="relative">
                                <Search
                                    class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-muted-foreground"
                                />
                                <Input
                                    v-model="search"
                                    type="text"
                                    placeholder="Search by KEW.PA-10 number or description..."
                                    class="pl-10"
                                    @keyup.enter="applyFilters"
                                />
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <Button
                                variant="outline"
                                @click="showFilters = !showFilters"
                            >
                                <Filter class="mr-2 h-4 w-4" />
                                Filters
                                <Badge
                                    v-if="hasActiveFilters"
                                    variant="default"
                                    class="ml-2"
                                >
                                    {{
                                        Object.values(filters).filter(Boolean)
                                            .length
                                    }}
                                </Badge>
                            </Button>
                            <Button @click="applyFilters"> Search </Button>
                        </div>
                    </div>
                </CardHeader>

                <!-- Advanced Filters -->
                <CardContent v-if="showFilters" class="border-t pt-6">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                        <!-- Department Filter -->
                        <div class="space-y-2">
                            <Label>Government Department</Label>
                            <Select v-model="selectedDepartment">
                                <SelectTrigger>
                                    <SelectValue
                                        placeholder="All Departments"
                                    />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value=""
                                        >All Departments</SelectItem
                                    >
                                    <SelectItem
                                        v-for="dept in governmentDepartments"
                                        :key="dept.id"
                                        :value="dept.id.toString()"
                                    >
                                        {{ dept.name }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </div>

                        <!-- Priority Filter -->
                        <div class="space-y-2">
                            <Label>Priority</Label>
                            <Select v-model="selectedPriority">
                                <SelectTrigger>
                                    <SelectValue placeholder="All Priorities" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value=""
                                        >All Priorities</SelectItem
                                    >
                                    <SelectItem
                                        v-for="priority in priorities"
                                        :key="priority.value"
                                        :value="priority.value"
                                    >
                                        {{ priority.label }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </div>

                        <!-- Verified Filter -->
                        <div class="space-y-2">
                            <Label>Verification Status</Label>
                            <Select v-model="selectedVerified">
                                <SelectTrigger>
                                    <SelectValue placeholder="All Forms" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="">All Forms</SelectItem>
                                    <SelectItem value="true"
                                        >Verified</SelectItem
                                    >
                                    <SelectItem value="false"
                                        >Not Verified</SelectItem
                                    >
                                </SelectContent>
                            </Select>
                        </div>

                        <!-- Date From -->
                        <div class="space-y-2">
                            <Label>Received From</Label>
                            <Input v-model="dateFrom" type="date" />
                        </div>

                        <!-- Date To -->
                        <div class="space-y-2">
                            <Label>Received To</Label>
                            <Input v-model="dateTo" type="date" />
                        </div>

                        <!-- Filter Actions -->
                        <div class="flex items-end gap-2">
                            <Button
                                variant="outline"
                                @click="clearFilters"
                                class="flex-1"
                            >
                                Clear
                            </Button>
                            <Button @click="applyFilters" class="flex-1">
                                Apply
                            </Button>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Results Table -->
            <Card>
                <CardHeader>
                    <CardTitle>Forms ({{ kewPA10s.total }})</CardTitle>
                    <CardDescription>
                        Showing {{ kewPA10s.data.length }} of
                        {{ kewPA10s.total }} forms
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>KEW.PA-10 No.</TableHead>
                                <TableHead>Department</TableHead>
                                <TableHead>Asset</TableHead>
                                <TableHead>Description</TableHead>
                                <TableHead>Priority</TableHead>
                                <TableHead>Verified</TableHead>
                                <TableHead>Received</TableHead>
                                <TableHead>Job</TableHead>
                                <TableHead class="text-right"
                                    >Actions</TableHead
                                >
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-if="kewPA10s.data.length === 0">
                                <TableCell
                                    colspan="9"
                                    class="py-8 text-center text-muted-foreground"
                                >
                                    No KEW.PA-10 forms found
                                </TableCell>
                            </TableRow>
                            <TableRow
                                v-for="form in kewPA10s.data"
                                :key="form.id"
                            >
                                <TableCell class="font-medium">
                                    <Link
                                        :href="`/kew-pa-10/${form.id}`"
                                        class="text-primary hover:underline"
                                    >
                                        {{ form.kew_pa_10_number }}
                                    </Link>
                                </TableCell>
                                <TableCell>
                                    {{ form.government_department.name }}
                                </TableCell>
                                <TableCell>
                                    <div class="text-sm">
                                        <div class="font-medium">
                                            {{ form.asset.asset_name }}
                                        </div>
                                        <div class="text-muted-foreground">
                                            {{ form.asset.asset_tag }}
                                        </div>
                                    </div>
                                </TableCell>
                                <TableCell>
                                    <div
                                        class="max-w-xs truncate"
                                        :title="form.description"
                                    >
                                        {{ form.description }}
                                    </div>
                                </TableCell>
                                <TableCell>
                                    <Badge
                                        :variant="
                                            getPriorityVariant(form.priority)
                                        "
                                    >
                                        {{ form.priority }}
                                    </Badge>
                                </TableCell>
                                <TableCell>
                                    <div class="flex items-center gap-2">
                                        <CheckCircle
                                            v-if="isVerified(form)"
                                            class="h-5 w-5 text-green-600"
                                            title="Verified"
                                        />
                                        <XCircle
                                            v-else
                                            class="h-5 w-5 text-red-600"
                                            title="Not Verified"
                                        />
                                    </div>
                                </TableCell>
                                <TableCell>
                                    {{ formatDate(form.received_date) }}
                                </TableCell>
                                <TableCell>
                                    <Link
                                        v-if="form.workshop_job"
                                        :href="
                                            showJob.url(form.workshop_job.id)
                                        "
                                        class="text-sm text-primary hover:underline"
                                    >
                                        {{ form.workshop_job.job_reference }}
                                    </Link>
                                    <span
                                        v-else
                                        class="text-sm text-muted-foreground"
                                    >
                                        No job created
                                    </span>
                                </TableCell>
                                <TableCell class="text-right">
                                    <Link :href="`/kew-pa-10/${form.id}`">
                                        <Button variant="ghost" size="sm">
                                            View
                                        </Button>
                                    </Link>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>

                    <!-- Pagination -->
                    <div
                        v-if="kewPA10s.last_page > 1"
                        class="mt-6 flex items-center justify-between"
                    >
                        <div class="text-sm text-muted-foreground">
                            Showing
                            {{
                                (kewPA10s.current_page - 1) *
                                    kewPA10s.per_page +
                                1
                            }}
                            to
                            {{
                                Math.min(
                                    kewPA10s.current_page * kewPA10s.per_page,
                                    kewPA10s.total,
                                )
                            }}
                            of {{ kewPA10s.total }} results
                        </div>
                        <div class="flex gap-2">
                            <Link
                                v-for="(link, index) in kewPA10s.links"
                                :key="index"
                                :href="link.url || '#'"
                                :class="[
                                    'rounded border px-3 py-1',
                                    link.active
                                        ? 'border-primary bg-primary text-primary-foreground'
                                        : 'hover:bg-muted',
                                    !link.url
                                        ? 'pointer-events-none opacity-50'
                                        : '',
                                ]"
                            >
                                <span v-html="link.label" />
                            </Link>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
