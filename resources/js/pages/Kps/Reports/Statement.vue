<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import KpsShellLayout from '@/layouts/kps/KpsShellLayout.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import type { KpsMonthlyDeduction, KpsPeneroka, KpsSite, KpsSiteRole, KpsDebt } from '@/types';

const props = defineProps<{
    site: KpsSite;
    siteRole?: KpsSiteRole | null;
    peneroka: KpsPeneroka & { debts?: KpsDebt[] };
    deductions: KpsMonthlyDeduction[];
}>();
</script>

<template>
    <Head :title="`Statement - ${peneroka.name}`" />

    <KpsShellLayout :site="site" :site-role="siteRole">
        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold">Statement</h1>
                    <p class="text-muted-foreground">{{ peneroka.name }}</p>
                </div>
                <Button variant="outline" as-child>
                    <Link :href="`/kps/sites/${site.id}/reports`">Back to Reports</Link>
                </Button>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>Peneroka Details</CardTitle>
                </CardHeader>
                <CardContent class="space-y-2">
                    <div>IC: {{ peneroka.ic_number || '-' }}</div>
                    <div>Phone: {{ peneroka.phone || '-' }}</div>
                    <div>Address: {{ peneroka.address || '-' }}</div>
                </CardContent>
            </Card>

            <Card>
                <CardHeader>
                    <CardTitle>Hutang</CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="rounded-md border">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>Description</TableHead>
                                    <TableHead>Priority</TableHead>
                                    <TableHead>Balance</TableHead>
                                    <TableHead>Due Date</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-if="!peneroka.debts || peneroka.debts.length === 0">
                                    <TableCell colspan="4" class="text-center text-muted-foreground">
                                        No debts found.
                                    </TableCell>
                                </TableRow>
                                <TableRow v-for="debt in peneroka.debts" :key="debt.id">
                                    <TableCell>{{ debt.description || debt.id }}</TableCell>
                                    <TableCell>{{ debt.priority }}</TableCell>
                                    <TableCell>{{ debt.balance }}</TableCell>
                                    <TableCell>{{ debt.due_date || '-' }}</TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>
                </CardContent>
            </Card>

            <Card>
                <CardHeader>
                    <CardTitle>Monthly Deductions</CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="space-y-4" v-if="deductions.length">
                        <div v-for="deduction in deductions" :key="deduction.id" class="rounded-md border p-4">
                            <div class="flex items-center justify-between">
                                <div class="font-medium">{{ deduction.month }}</div>
                                <div class="text-sm text-muted-foreground">Amount: {{ deduction.amount }} | Unallocated: {{ deduction.unallocated_amount }}</div>
                            </div>
                            <div class="mt-3 rounded-md border">
                                <Table>
                                    <TableHeader>
                                        <TableRow>
                                            <TableHead>Debt</TableHead>
                                            <TableHead>Allocated</TableHead>
                                        </TableRow>
                                    </TableHeader>
                                    <TableBody>
                                        <TableRow v-if="!deduction.allocations || deduction.allocations.length === 0">
                                            <TableCell colspan="2" class="text-center text-muted-foreground">
                                                No allocations.
                                            </TableCell>
                                        </TableRow>
                                        <TableRow v-for="allocation in deduction.allocations" :key="allocation.id">
                                            <TableCell>{{ allocation.debt?.description || allocation.debt_id }}</TableCell>
                                            <TableCell>{{ allocation.amount }}</TableCell>
                                        </TableRow>
                                    </TableBody>
                                </Table>
                            </div>
                        </div>
                    </div>
                    <div v-else class="text-center text-muted-foreground">No deductions found.</div>
                </CardContent>
            </Card>
        </div>
    </KpsShellLayout>
</template>
