<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import KpsShellLayout from '@/layouts/kps/KpsShellLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import type { KpsPeneroka, KpsSite, KpsSiteRole } from '@/types';

const props = defineProps<{
    site: KpsSite;
    siteRole?: KpsSiteRole | null;
    peneroka: KpsPeneroka;
}>();

const form = useForm({
    site_id: props.site.id,
    name: props.peneroka.name,
    ic_number: props.peneroka.ic_number || '',
    phone: props.peneroka.phone || '',
    address: props.peneroka.address || '',
});

const submit = () => {
    form.put(`/kps/sites/${props.site.id}/peneroka/${props.peneroka.id}`);
};
</script>

<template>
    <Head title="Edit Peneroka" />

    <KpsShellLayout :site="site" :site-role="siteRole">
        <div class="space-y-6 max-w-3xl">
            <div>
                <h1 class="text-2xl font-semibold">Edit Peneroka</h1>
                <p class="text-muted-foreground">Update peneroka information.</p>
            </div>

            <form class="space-y-4" @submit.prevent="submit">
                <div class="grid gap-2">
                    <Label for="name">Name</Label>
                    <Input id="name" v-model="form.name" />
                </div>
                <div class="grid gap-2">
                    <Label for="ic_number">IC Number</Label>
                    <Input id="ic_number" v-model="form.ic_number" />
                </div>
                <div class="grid gap-2">
                    <Label for="phone">Phone</Label>
                    <Input id="phone" v-model="form.phone" />
                </div>
                <div class="grid gap-2">
                    <Label for="address">Address</Label>
                    <Textarea id="address" v-model="form.address" />
                </div>

                <div class="flex gap-2">
                    <Button type="submit" :disabled="form.processing">Update</Button>
                    <Button variant="outline" as-child>
                        <Link :href="`/kps/sites/${site.id}/peneroka`">Cancel</Link>
                    </Button>
                </div>
            </form>
        </div>
    </KpsShellLayout>
</template>
