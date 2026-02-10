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

const props = defineProps<{
    site: KpsSite;
    siteRole?: KpsSiteRole | null;
    penerokas: KpsPeneroka[];
}>();
</script>

<template>
    <Head :title="`Reports - ${site.name}`" />

    <KpsShellLayout :site="site" :site-role="siteRole">
        <div class="space-y-6">
            <div>
                <h1 class="text-2xl font-semibold">Reports</h1>
                <p class="text-muted-foreground">Statements by peneroka</p>
            </div>

            <div class="rounded-md border">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Name</TableHead>
                            <TableHead>IC Number</TableHead>
                            <TableHead class="text-right">Statement</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-if="penerokas.length === 0">
                            <TableCell colspan="3" class="text-center text-muted-foreground">
                                No peneroka found.
                            </TableCell>
                        </TableRow>
                        <TableRow v-for="p in penerokas" :key="p.id">
                            <TableCell class="font-medium">{{ p.name }}</TableCell>
                            <TableCell>{{ p.ic_number || '-' }}</TableCell>
                            <TableCell class="text-right">
                                <Button variant="outline" size="sm" as-child>
                                    <Link :href="`/kps/sites/${site.id}/reports/peneroka/${p.id}`">View Statement</Link>
                                </Button>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
        </div>
    </KpsShellLayout>
</template>
