<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import KpsShellLayout from '@/layouts/kps/KpsShellLayout.vue';
import { Button } from '@/components/ui/button';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import type { KpsSite } from '@/types';

interface PaginatedSites {
    data: KpsSite[];
    links: any[];
    meta: any;
}

interface Props {
    sites: PaginatedSites;
}

defineProps<Props>();
</script>

<template>
    <Head title="KPS Sites" />

    <KpsShellLayout>
        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold">Sites</h1>
                    <p class="text-muted-foreground">Manage KPS sites</p>
                </div>
                <Button as-child>
                    <Link href="/kps/sites/create">Add Site</Link>
                </Button>
            </div>

            <div class="rounded-md border">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Name</TableHead>
                            <TableHead>Code</TableHead>
                            <TableHead>Status</TableHead>
                            <TableHead class="text-right">Actions</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-if="sites.data.length === 0">
                            <TableCell colspan="4" class="text-center text-muted-foreground">
                                No sites found.
                            </TableCell>
                        </TableRow>
                        <TableRow v-for="site in sites.data" :key="site.id">
                            <TableCell class="font-medium">{{ site.name }}</TableCell>
                            <TableCell>{{ site.code }}</TableCell>
                            <TableCell>{{ site.is_active ? 'Active' : 'Inactive' }}</TableCell>
                            <TableCell class="text-right">
                                <div class="flex justify-end gap-2">
                                    <Button variant="outline" size="sm" as-child>
                                        <Link :href="`/kps/sites/${site.id}`">View</Link>
                                    </Button>
                                    <Button variant="ghost" size="sm" as-child>
                                        <Link :href="`/kps/sites/${site.id}/edit`">Edit</Link>
                                    </Button>
                                </div>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
        </div>
    </KpsShellLayout>
</template>
