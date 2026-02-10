<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import KpsShellLayout from '@/layouts/kps/KpsShellLayout.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';

interface Props {
    stats: {
        total_outstanding: number;
        total_deductions_this_month: number;
        total_unallocated_this_month: number;
    };
    month: string;
}

defineProps<Props>();
</script>

<template>
    <Head title="KPS Analytics" />

    <KpsShellLayout>
        <div class="space-y-6">
            <div>
                <h1 class="text-2xl font-semibold">KPS Analytics</h1>
                <p class="text-muted-foreground">Month: {{ month }}</p>
            </div>

            <div class="grid gap-4 md:grid-cols-3">
                <Card>
                    <CardHeader>
                        <CardTitle>Total Outstanding</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="text-3xl font-semibold">{{ stats.total_outstanding }}</div>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader>
                        <CardTitle>Deductions This Month</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="text-3xl font-semibold">{{ stats.total_deductions_this_month }}</div>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader>
                        <CardTitle>Unallocated This Month</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="text-3xl font-semibold">{{ stats.total_unallocated_this_month }}</div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </KpsShellLayout>
</template>
