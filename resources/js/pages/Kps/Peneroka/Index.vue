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
import type { KpsPeneroka, KpsSite, KpsSiteRole } from '@/types';

interface PaginatedPeneroka {
    data: KpsPeneroka[];
    links: any[];
    meta: any;
}

const props = defineProps<{
    site: KpsSite;
    siteRole?: KpsSiteRole | null;
    penerokas: PaginatedPeneroka;
}>();
</script>

<template>
    <Head :title="`Peneroka - ${site.name}`" />

    <KpsShellLayout :site="site" :site-role="siteRole">
        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold">Peneroka</h1>
                    <p class="text-muted-foreground">Manage peneroka for {{ site.name }}</p>
                </div>
                <Button as-child>
                    <Link :href="`/kps/sites/${site.id}/peneroka/create`">Add Peneroka</Link>
                </Button>
            </div>

            <div class="rounded-md border">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Name</TableHead>
                            <TableHead>IC Number</TableHead>
                            <TableHead>Phone</TableHead>
                            <TableHead class="text-right">Actions</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-if="penerokas.data.length === 0">
                            <TableCell colspan="4" class="text-center text-muted-foreground">
                                No peneroka found.
                            </TableCell>
                        </TableRow>
                        <TableRow v-for="p in penerokas.data" :key="p.id">
                            <TableCell class="font-medium">{{ p.name }}</TableCell>
                            <TableCell>{{ p.ic_number || '-' }}</TableCell>
                            <TableCell>{{ p.phone || '-' }}</TableCell>
                            <TableCell class="text-right">
                                <div class="flex justify-end gap-2">
                                    <Button variant="ghost" size="sm" as-child>
                                        <Link :href="`/kps/sites/${site.id}/peneroka/${p.id}/edit`">Edit</Link>
                                    </Button>
                                    <Button variant="outline" size="sm" as-child>
                                        <Link :href="`/kps/sites/${site.id}/reports/peneroka/${p.id}`">Statement</Link>
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
