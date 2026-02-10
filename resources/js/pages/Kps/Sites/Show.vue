<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import KpsShellLayout from '@/layouts/kps/KpsShellLayout.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import type { KpsSite, KpsSiteRole } from '@/types';

interface Props {
    site: KpsSite;
    siteRole?: KpsSiteRole | null;
    stats: {
        peneroka: number;
        debts: number;
        total_debt_balance: number;
        monthly_deductions: number;
    };
}

const props = defineProps<Props>();
</script>

<template>
    <Head :title="`Site: ${site.name}`" />

    <KpsShellLayout :site="site" :site-role="siteRole">
        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold">{{ site.name }}</h1>
                    <p class="text-muted-foreground">Site dashboard</p>
                </div>
                <Button variant="outline" as-child>
                    <Link href="/kps/sites">Back to Sites</Link>
                </Button>
            </div>

            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
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
                        <CardTitle>Hutang</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="text-3xl font-semibold">{{ stats.debts }}</div>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader>
                        <CardTitle>Outstanding</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="text-3xl font-semibold">{{ stats.total_debt_balance }}</div>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader>
                        <CardTitle>Potongan (Month)</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="text-3xl font-semibold">{{ stats.monthly_deductions }}</div>
                    </CardContent>
                </Card>
            </div>

            <div class="flex flex-wrap gap-2">
                <Button variant="outline" as-child>
                    <Link :href="`/kps/sites/${site.id}/peneroka`">Manage Peneroka</Link>
                </Button>
                <Button variant="outline" as-child>
                    <Link :href="`/kps/sites/${site.id}/hutang`">Manage Hutang</Link>
                </Button>
                <Button variant="outline" as-child>
                    <Link :href="`/kps/sites/${site.id}/potongan`">Potongan Bulanan</Link>
                </Button>
                <Button variant="outline" as-child>
                    <Link :href="`/kps/sites/${site.id}/allocations`">Allocation Review</Link>
                </Button>
            </div>
        </div>
    </KpsShellLayout>
</template>
