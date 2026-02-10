<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import KpsShellLayout from '@/layouts/kps/KpsShellLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import type { KpsSite, KpsSiteRole, KpsPeneroka } from '@/types';

const props = defineProps<{
    site: KpsSite;
    siteRole?: KpsSiteRole | null;
    penerokas: KpsPeneroka[];
}>();

const form = useForm({
    site_id: props.site.id,
    peneroka_id: '',
    month: '',
    amount: '',
});

const submit = () => {
    form.post(`/kps/sites/${props.site.id}/potongan`);
};
</script>

<template>
    <Head title="Create Potongan" />

    <KpsShellLayout :site="site" :site-role="siteRole">
        <div class="space-y-6 max-w-3xl">
            <div>
                <h1 class="text-2xl font-semibold">Create Potongan</h1>
                <p class="text-muted-foreground">Enter monthly deduction for {{ site.name }}.</p>
            </div>

            <form class="space-y-4" @submit.prevent="submit">
                <div class="grid gap-2">
                    <Label>Peneroka</Label>
                    <Select v-model="form.peneroka_id">
                        <SelectTrigger>
                            <SelectValue placeholder="Select peneroka" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem v-for="p in penerokas" :key="p.id" :value="p.id">
                                {{ p.name }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                </div>
                <div class="grid gap-2">
                    <Label for="month">Month</Label>
                    <Input id="month" type="month" v-model="form.month" />
                </div>
                <div class="grid gap-2">
                    <Label for="amount">Amount</Label>
                    <Input id="amount" type="number" v-model="form.amount" min="0" step="0.01" />
                </div>

                <div class="flex gap-2">
                    <Button type="submit" :disabled="form.processing">Save</Button>
                    <Button variant="outline" as-child>
                        <Link :href="`/kps/sites/${site.id}/potongan`">Cancel</Link>
                    </Button>
                </div>
            </form>
        </div>
    </KpsShellLayout>
</template>
