<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import KpsShellLayout from '@/layouts/kps/KpsShellLayout.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';

interface Props {
    stats: {
        sites: number;
        peneroka: number;
        total_debt_balance: number;
        monthly_deductions: number;
    };
}

defineProps<Props>();
</script>

<template>
    <Head title="KPS Dashboard" />

    <KpsShellLayout>
        <div class="space-y-6">
            <div>
                <h1 class="text-2xl font-semibold">KPS HQ Dashboard</h1>
                <p class="text-muted-foreground">Overview across all sites.</p>
            </div>

            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <Card>
                    <CardHeader>
                        <CardTitle>Sites</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="text-3xl font-semibold">{{ stats.sites }}</div>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader>
                        <CardTitle>Peneroka</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="text-3xl font-semibold">{{ stats.peneroka }}</div>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader>
                        <CardTitle>Total Outstanding</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="text-3xl font-semibold">{{ stats.total_debt_balance }}</div>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader>
                        <CardTitle>This Month Deductions</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="text-3xl font-semibold">{{ stats.monthly_deductions }}</div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </KpsShellLayout>
</template>
