<script setup lang="ts">
import { Head, useForm, Link } from '@inertiajs/vue3';
import KpsShellLayout from '@/layouts/kps/KpsShellLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Checkbox } from '@/components/ui/checkbox';

const form = useForm({
    name: '',
    code: '',
    address: '',
    phone: '',
    email: '',
    is_active: true,
});

const submit = () => {
    form.post('/kps/sites');
};
</script>

<template>
    <Head title="Create Site" />

    <KpsShellLayout>
        <div class="space-y-6 max-w-3xl">
            <div>
                <h1 class="text-2xl font-semibold">Create Site</h1>
                <p class="text-muted-foreground">Add a new KPS site.</p>
            </div>

            <form class="space-y-4" @submit.prevent="submit">
                <div class="grid gap-2">
                    <Label for="name">Name</Label>
                    <Input id="name" v-model="form.name" />
                </div>
                <div class="grid gap-2">
                    <Label for="code">Code</Label>
                    <Input id="code" v-model="form.code" />
                </div>
                <div class="grid gap-2">
                    <Label for="address">Address</Label>
                    <Textarea id="address" v-model="form.address" />
                </div>
                <div class="grid gap-2">
                    <Label for="phone">Phone</Label>
                    <Input id="phone" v-model="form.phone" />
                </div>
                <div class="grid gap-2">
                    <Label for="email">Email</Label>
                    <Input id="email" v-model="form.email" type="email" />
                </div>
                <div class="flex items-center gap-2">
                    <Checkbox id="is_active" v-model:checked="form.is_active" />
                    <Label for="is_active">Active</Label>
                </div>

                <div class="flex gap-2">
                    <Button type="submit" :disabled="form.processing">Create</Button>
                    <Button variant="outline" as-child>
                        <Link href="/kps/sites">Cancel</Link>
                    </Button>
                </div>
            </form>
        </div>
    </KpsShellLayout>
</template>
