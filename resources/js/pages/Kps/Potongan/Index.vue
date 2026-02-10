<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import KpsShellLayout from '@/layouts/kps/KpsShellLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import type { KpsMonthlyDeduction, KpsSite, KpsSiteRole } from '@/types';
import { ref } from 'vue';

interface PaginatedDeductions {
    data: KpsMonthlyDeduction[];
    links: any[];
    meta: any;
}

const props = defineProps<{
    site: KpsSite;
    siteRole?: KpsSiteRole | null;
    deductions: PaginatedDeductions;
    selectedMonth?: string | null;
}>();

const month = ref(props.selectedMonth || '');

const applyFilter = () => {
    router.get(`/kps/sites/${props.site.id}/potongan`, { month: month.value }, { preserveState: true });
};
</script>

<template>
    <Head :title="`Potongan - ${site.name}`" />

    <KpsShellLayout :site="site" :site-role="siteRole">
        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold">Potongan Bulanan</h1>
                    <p class="text-muted-foreground">Manage monthly deductions for {{ site.name }}</p>
                </div>
                <div class="flex gap-2">
                    <Button variant="outline" as-child>
                        <Link :href="`/kps/sites/${site.id}/potongan/bulk`">Bulk Entry</Link>
                    </Button>
                    <Button as-child>
                        <Link :href="`/kps/sites/${site.id}/potongan/create`">Add Potongan</Link>
                    </Button>
                </div>
            </div>

            <div class="flex items-end gap-2">
                <div class="grid gap-2">
                    <label class="text-sm text-muted-foreground">Filter by Month</label>
                    <Input type="month" v-model="month" />
                </div>
                <Button variant="outline" @click="applyFilter">Apply</Button>
            </div>

            <div class="rounded-md border">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Month</TableHead>
                            <TableHead>Peneroka</TableHead>
                            <TableHead>Amount</TableHead>
                            <TableHead>Unallocated</TableHead>
                            <TableHead>Status</TableHead>
                            <TableHead class="text-right">Actions</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-if="deductions.data.length === 0">
                            <TableCell colspan="6" class="text-center text-muted-foreground">
                                No deductions found.
                            </TableCell>
                        </TableRow>
                        <TableRow v-for="deduction in deductions.data" :key="deduction.id">
                            <TableCell>{{ deduction.month }}</TableCell>
                            <TableCell>{{ deduction.peneroka?.name }}</TableCell>
                            <TableCell>{{ deduction.amount }}</TableCell>
                            <TableCell>{{ deduction.unallocated_amount }}</TableCell>
                            <TableCell>{{ deduction.is_closed ? 'Closed' : 'Open' }}</TableCell>
                            <TableCell class="text-right">
                                <Button variant="ghost" size="sm" as-child>
                                    <Link :href="`/kps/sites/${site.id}/allocations/${deduction.id}`">View</Link>
                                </Button>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
        </div>
    </KpsShellLayout>
</template>
