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
    peneroka_id: '',
    priority: 1,
    balance: '',
    due_date: '',
    description: '',
});

const submit = () => {
    form.post(`/kps/sites/${props.site.id}/hutang`);
};
</script>

<template>
    <Head title="Create Hutang" />

    <KpsShellLayout :site="site" :site-role="siteRole">
        <div class="space-y-6 max-w-3xl">
            <div>
                <h1 class="text-2xl font-semibold">Create Hutang</h1>
                <p class="text-muted-foreground">Add a debt for {{ site.name }}.</p>
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
                    <Label for="priority">Priority</Label>
                    <Input id="priority" type="number" v-model="form.priority" min="1" />
                </div>
                <div class="grid gap-2">
                    <Label for="balance">Balance</Label>
                    <Input id="balance" type="number" v-model="form.balance" min="0" step="0.01" />
                </div>
                <div class="grid gap-2">
                    <Label for="due_date">Due Date</Label>
                    <Input id="due_date" type="date" v-model="form.due_date" />
                </div>
                <div class="grid gap-2">
                    <Label for="description">Description</Label>
                    <Input id="description" v-model="form.description" />
                </div>

                <div class="flex gap-2">
                    <Button type="submit" :disabled="form.processing">Create</Button>
                    <Button variant="outline" as-child>
                        <Link :href="`/kps/sites/${site.id}/hutang`">Cancel</Link>
                    </Button>
                </div>
            </form>
        </div>
    </KpsShellLayout>
</template>
