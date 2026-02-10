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
    month: '',
    entries: [
        {
            peneroka_id: '',
            amount: '',
        },
    ],
});

const addRow = () => {
    form.entries.push({ peneroka_id: '', amount: '' });
};

const removeRow = (index: number) => {
    if (form.entries.length <= 1) return;
    form.entries.splice(index, 1);
};

const submit = () => {
    form.post(`/kps/sites/${props.site.id}/potongan/bulk`);
};
</script>

<template>
    <Head title="Bulk Potongan" />

    <KpsShellLayout :site="site" :site-role="siteRole">
        <div class="space-y-6">
            <div>
                <h1 class="text-2xl font-semibold">Bulk Potongan</h1>
                <p class="text-muted-foreground">Enter multiple deductions for {{ site.name }}.</p>
            </div>

            <form class="space-y-4" @submit.prevent="submit">
                <div class="grid gap-2 max-w-sm">
                    <Label for="month">Month</Label>
                    <Input id="month" type="month" v-model="form.month" />
                </div>

                <div class="space-y-3">
                    <div v-for="(entry, index) in form.entries" :key="index" class="grid gap-3 md:grid-cols-3">
                        <div class="md:col-span-2">
                            <Label>Peneroka</Label>
                            <Select v-model="entry.peneroka_id">
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
                        <div>
                            <Label>Amount</Label>
                            <Input type="number" v-model="entry.amount" min="0" step="0.01" />
                        </div>
                        <div class="md:col-span-3">
                            <Button variant="ghost" type="button" @click="removeRow(index)">Remove</Button>
                        </div>
                    </div>
                </div>

                <Button type="button" variant="outline" @click="addRow">Add Row</Button>

                <div class="flex gap-2">
                    <Button type="submit" :disabled="form.processing">Save Bulk</Button>
                    <Button variant="outline" as-child>
                        <Link :href="`/kps/sites/${site.id}/potongan`">Cancel</Link>
                    </Button>
                </div>
            </form>
        </div>
    </KpsShellLayout>
</template>
