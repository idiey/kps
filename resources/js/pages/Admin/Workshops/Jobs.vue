<script setup lang="ts">
/**
 * Site Jobs Page
 * 
 * Displays jobs filtered by the current site/workshop.
 * Uses the dual sidebar layout.
 */
import { Head, Link, router } from '@inertiajs/vue3';
import SiteLayout from '@/layouts/app/SiteLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem, type Workshop, type SiteRole, type WorkshopJob, type PaginatedResponse } from '@/types';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Badge } from '@/components/ui/badge';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Eye, Plus, Search } from 'lucide-vue-next';
import { ref, computed, watch } from 'vue';
import { useDebounceFn } from '@vueuse/core';

interface Props {
    workshop: {
        id: string;
        name: string;
        code: string;
    };
    jobs: PaginatedResponse<WorkshopJob>;
    filters: {
        search?: string;
        status?: string;
    };
    site: Workshop;
    siteRole?: SiteRole;
}

const props = defineProps<Props>();

const searchQuery = ref(props.filters.search || '');

const breadcrumbs = computed<BreadcrumbItem[]>(() => [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Sites', href: '/admin/workshops' },
    { title: props.workshop.name, href: `/admin/workshops/${props.workshop.id}` },
    { title: 'Jobs', href: `/admin/workshops/${props.workshop.id}/jobs` },
]);

const debouncedSearch = useDebounceFn(() => {
    router.get(
        `/admin/workshops/${props.workshop.id}/jobs`,
        { search: searchQuery.value || undefined },
        { preserveState: true, preserveScroll: true }
    );
}, 300);

watch(searchQuery, () => {
    debouncedSearch();
});

function getStatusColor(status: string): string {
    const statusColors: Record<string, string> = {
        new: 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300',
        in_progress: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300',
        pending_inspection: 'bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-300',
        completed: 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300',
        cancelled: 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300',
    };
    return statusColors[status] || 'bg-gray-100 text-gray-800 dark:bg-gray-900/50 dark:text-gray-300';
}
</script>

<template>
    <Head :title="`Jobs - ${workshop.name}`" />

    <SiteLayout :breadcrumbs="breadcrumbs" :site="site" :site-role="siteRole">
        <div class="space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight">Site Jobs</h2>
                    <p class="text-muted-foreground">
                        Manage jobs for {{ workshop.name }}
                    </p>
                </div>
                <Button as-child>
                    <Link href="/jobs/select-mode">
                        <Plus class="mr-2 h-4 w-4" />
                        New Job
                    </Link>
                </Button>
            </div>

            <!-- Search and Filters -->
            <Card>
                <CardContent class="pt-6">
                    <div class="flex gap-4">
                        <div class="relative flex-1 max-w-sm">
                            <Search class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                            <Input
                                v-model="searchQuery"
                                placeholder="Search jobs..."
                                class="pl-10"
                            />
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Jobs Table -->
            <Card>
                <CardHeader>
                    <CardTitle>Jobs</CardTitle>
                    <CardDescription>
                        {{ jobs.meta.total }} jobs in this site
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <Table v-if="jobs.data.length > 0">
                        <TableHeader>
                            <TableRow>
                                <TableHead>Job #</TableHead>
                                <TableHead>Customer</TableHead>
                                <TableHead>Mode</TableHead>
                                <TableHead>Status</TableHead>
                                <TableHead>Assigned To</TableHead>
                                <TableHead class="text-right">Actions</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="job in jobs.data" :key="job.id">
                                <TableCell class="font-medium">
                                    {{ job.job_number }}
                                </TableCell>
                                <TableCell>
                                    {{ job.customer?.name || 'N/A' }}
                                </TableCell>
                                <TableCell>
                                    <Badge variant="outline">
                                        {{ job.job_mode }}
                                    </Badge>
                                </TableCell>
                                <TableCell>
                                    <Badge :class="getStatusColor(job.status)">
                                        {{ job.status.replace(/_/g, ' ') }}
                                    </Badge>
                                </TableCell>
                                <TableCell>
                                    {{ job.assigned_user?.name || 'Unassigned' }}
                                </TableCell>
                                <TableCell class="text-right">
                                    <Button variant="ghost" size="icon" as-child>
                                        <Link :href="`/jobs/${job.id}`">
                                            <Eye class="h-4 w-4" />
                                        </Link>
                                    </Button>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>

                    <!-- Empty State -->
                    <div 
                        v-else 
                        class="flex flex-col items-center justify-center py-12 text-center"
                    >
                        <p class="text-muted-foreground mb-4">
                            No jobs found for this site.
                        </p>
                        <Button as-child>
                            <Link href="/jobs/select-mode">
                                <Plus class="mr-2 h-4 w-4" />
                                Create First Job
                            </Link>
                        </Button>
                    </div>

                    <!-- Pagination -->
                    <div 
                        v-if="jobs.meta.last_page > 1" 
                        class="flex items-center justify-between pt-4 border-t mt-4"
                    >
                        <p class="text-sm text-muted-foreground">
                            Showing {{ jobs.meta.from }} to {{ jobs.meta.to }} of {{ jobs.meta.total }} results
                        </p>
                        <div class="flex gap-2">
                            <Button
                                v-if="jobs.links.prev"
                                variant="outline"
                                size="sm"
                                as-child
                            >
                                <Link :href="jobs.links.prev">Previous</Link>
                            </Button>
                            <Button
                                v-if="jobs.links.next"
                                variant="outline"
                                size="sm"
                                as-child
                            >
                                <Link :href="jobs.links.next">Next</Link>
                            </Button>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </SiteLayout>
</template>
