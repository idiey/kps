<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import KpsShellLayout from '@/layouts/kps/KpsShellLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import type { KpsMonthlyDeduction, KpsSite, KpsSiteRole } from '@/types';

const props = defineProps<{
    site: KpsSite;
    siteRole?: KpsSiteRole | null;
    deduction: KpsMonthlyDeduction;
}>();

const reallocate = () => {
    router.post(`/kps/sites/${props.site.id}/allocations/${props.deduction.id}/reallocate`);
};
</script>

<template>
    <Head title="Allocation Details" />

    <KpsShellLayout :site="site" :site-role="siteRole">
        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold">Allocation Details</h1>
                    <p class="text-muted-foreground">{{ deduction.peneroka?.name }} - {{ deduction.month }}</p>
                </div>
                <div class="flex gap-2">
                    <Button variant="outline" as-child>
                        <Link :href="`/kps/sites/${site.id}/allocations`">Back</Link>
                    </Button>
                    <Button :disabled="deduction.is_closed" @click="reallocate">Recalculate</Button>
                </div>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>Summary</CardTitle>
                </CardHeader>
                <CardContent class="space-y-2">
                    <div>Amount: {{ deduction.amount }}</div>
                    <div>Unallocated: {{ deduction.unallocated_amount }}</div>
                    <div>Status: {{ deduction.is_closed ? 'Closed' : 'Open' }}</div>
                </CardContent>
            </Card>

            <div class="rounded-md border">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Debt</TableHead>
                            <TableHead>Priority</TableHead>
                            <TableHead>Allocated</TableHead>
                            <TableHead>Remaining Balance</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-if="!deduction.allocations || deduction.allocations.length === 0">
                            <TableCell colspan="4" class="text-center text-muted-foreground">
                                No allocations found.
                            </TableCell>
                        </TableRow>
                        <TableRow v-for="allocation in deduction.allocations" :key="allocation.id">
                            <TableCell>{{ allocation.debt?.description || allocation.debt_id }}</TableCell>
                            <TableCell>{{ allocation.debt?.priority }}</TableCell>
                            <TableCell>{{ allocation.amount }}</TableCell>
                            <TableCell>{{ allocation.debt?.balance }}</TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
        </div>
    </KpsShellLayout>
</template>
