<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
  Card,
  CardContent,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle,
} from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Checkbox } from '@/components/ui/checkbox';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm, Link } from '@inertiajs/vue3';
import admin from '@/routes/admin';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin', href: '#' },
    { title: 'Templates', href: admin.templates.index.url() },
    { title: 'Create', href: '#' },
];

const form = useForm({
    name: '',
    code: '',
    description: '',
    is_active: true,
    is_default: false,
});

const submit = () => {
    form.post(admin.templates.store.url());
};
</script>

<template>
    <Head title="Create Template" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4 max-w-2xl mx-auto w-full">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight">Create Template</h2>
                    <p class="text-muted-foreground">
                        Define a new form template structure.
                    </p>
                </div>
            </div>

            <form @submit.prevent="submit">
                <Card>
                    <CardHeader>
                        <CardTitle>Template Details</CardTitle>
                        <CardDescription>
                            Basic information about the form template.
                        </CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="grid gap-2">
                            <Label for="name">Name</Label>
                            <Input
                                id="name"
                                v-model="form.name"
                                placeholder="e.g., Vehicle Inspection Form"
                                required
                            />
                            <p v-if="form.errors.name" class="text-sm text-destructive">
                                {{ form.errors.name }}
                            </p>
                        </div>

                        <div class="grid gap-2">
                            <Label for="code">Code</Label>
                            <Input
                                id="code"
                                v-model="form.code"
                                placeholder="e.g., VEHICLE_INSPECTION_V1"
                                required
                            />
                            <p class="text-xs text-muted-foreground">
                                Unique identifier for this template (uppercase, no spaces).
                            </p>
                            <p v-if="form.errors.code" class="text-sm text-destructive">
                                {{ form.errors.code }}
                            </p>
                        </div>

                        <div class="grid gap-2">
                            <Label for="description">Description</Label>
                            <Textarea
                                id="description"
                                v-model="form.description"
                                placeholder="Purpose of this form..."
                            />
                        </div>

                        <div class="flex items-center space-x-2">
                            <Checkbox id="is_active" v-model:checked="form.is_active" />
                            <Label for="is_active">Active</Label>
                        </div>

                        <div class="flex items-center space-x-2">
                            <Checkbox id="is_default" v-model:checked="form.is_default" />
                            <Label for="is_default">Default Template</Label>
                        </div>
                    </CardContent>
                    <CardFooter class="flex justify-between">
                        <Button variant="outline" as-child>
                            <Link :href="admin.templates.index.url()">Cancel</Link>
                        </Button>
                        <Button type="submit" :disabled="form.processing">
                            {{ form.processing ? 'Creating...' : 'Create & Continue' }}
                        </Button>
                    </CardFooter>
                </Card>
            </form>
        </div>
    </AppLayout>
</template>
