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
import type { KpsDebt, KpsSite, KpsSiteRole } from '@/types';

interface PaginatedDebts {
    data: KpsDebt[];
    links: any[];
    meta: any;
}

const props = defineProps<{
    site: KpsSite;
    siteRole?: KpsSiteRole | null;
    debts: PaginatedDebts;
}>();
</script>

<template>
    <Head :title="`Hutang - ${site.name}`" />

    <KpsShellLayout :site="site" :site-role="siteRole">
        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold">Hutang</h1>
                    <p class="text-muted-foreground">Manage debts for {{ site.name }}</p>
                </div>
                <Button as-child>
                    <Link :href="`/kps/sites/${site.id}/hutang/create`">Add Hutang</Link>
                </Button>
            </div>

            <div class="rounded-md border">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Peneroka</TableHead>
                            <TableHead>Priority</TableHead>
                            <TableHead>Balance</TableHead>
                            <TableHead>Due Date</TableHead>
                            <TableHead class="text-right">Actions</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-if="debts.data.length === 0">
                            <TableCell colspan="5" class="text-center text-muted-foreground">
                                No debts found.
                            </TableCell>
                        </TableRow>
                        <TableRow v-for="debt in debts.data" :key="debt.id">
                            <TableCell class="font-medium">{{ debt.peneroka?.name }}</TableCell>
                            <TableCell>{{ debt.priority }}</TableCell>
                            <TableCell>{{ debt.balance }}</TableCell>
                            <TableCell>{{ debt.due_date || '-' }}</TableCell>
                            <TableCell class="text-right">
                                <Button variant="ghost" size="sm" as-child>
                                    <Link :href="`/kps/sites/${site.id}/hutang/${debt.id}/edit`">Edit</Link>
                                </Button>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
        </div>
    </KpsShellLayout>
</template>
