<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import KpsShellLayout from '@/layouts/kps/KpsShellLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import type { KpsDebt, KpsSite, KpsSiteRole } from '@/types';

const props = defineProps<{
    site: KpsSite;
    siteRole?: KpsSiteRole | null;
    debt: KpsDebt;
}>();

const form = useForm({
    priority: props.debt.priority,
    balance: props.debt.balance,
    due_date: props.debt.due_date || '',
    description: props.debt.description || '',
});

const submit = () => {
    form.put(`/kps/sites/${props.site.id}/hutang/${props.debt.id}`);
};
</script>

<template>
    <Head title="Edit Hutang" />

    <KpsShellLayout :site="site" :site-role="siteRole">
        <div class="space-y-6 max-w-3xl">
            <div>
                <h1 class="text-2xl font-semibold">Edit Hutang</h1>
                <p class="text-muted-foreground">Update debt information.</p>
            </div>

            <form class="space-y-4" @submit.prevent="submit">
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
                    <Button type="submit" :disabled="form.processing">Update</Button>
                    <Button variant="outline" as-child>
                        <Link :href="`/kps/sites/${site.id}/hutang`">Cancel</Link>
                    </Button>
                </div>
            </form>
        </div>
    </KpsShellLayout>
</template>
