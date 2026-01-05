<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Switch } from '@/components/ui/switch';
import { Textarea } from '@/components/ui/textarea';
import AppLayout from '@/layouts/AppLayout.vue';
import { index, store } from '@/routes/admin/workflows';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin', href: '#' },
    { title: 'Workflows', href: index.url() },
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
    form.post(store.url());
};
</script>

<template>
    <Head title="Create Workflow" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight">
                        Create Workflow
                    </h2>
                    <p class="text-muted-foreground">
                        Create a new workflow definition.
                    </p>
                </div>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>Workflow Details</CardTitle>
                    <CardDescription>
                        Enter the basic details for the new workflow.
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="space-y-2">
                            <Label for="name">Name</Label>
                            <Input id="name" v-model="form.name" required />
                            <div
                                v-if="form.errors.name"
                                class="text-sm text-red-500"
                            >
                                {{ form.errors.name }}
                            </div>
                        </div>

                        <div class="space-y-2">
                            <Label for="code">Code</Label>
                            <Input id="code" v-model="form.code" required />
                            <p class="text-xs text-muted-foreground">
                                Unique identifier for the workflow (e.g.,
                                'standard-repair').
                            </p>
                            <div
                                v-if="form.errors.code"
                                class="text-sm text-red-500"
                            >
                                {{ form.errors.code }}
                            </div>
                        </div>

                        <div class="space-y-2">
                            <Label for="description">Description</Label>
                            <Textarea
                                id="description"
                                v-model="form.description"
                            />
                            <div
                                v-if="form.errors.description"
                                class="text-sm text-red-500"
                            >
                                {{ form.errors.description }}
                            </div>
                        </div>

                        <div class="flex items-center space-x-2">
                            <Switch
                                id="is_active"
                                :checked="form.is_active"
                                @update:checked="form.is_active = $event"
                            />
                            <Label for="is_active">Active</Label>
                        </div>

                        <div class="flex items-center space-x-2">
                            <Switch
                                id="is_default"
                                :checked="form.is_default"
                                @update:checked="form.is_default = $event"
                            />
                            <Label for="is_default">Default Workflow</Label>
                        </div>

                        <div class="flex justify-end gap-2">
                            <Button variant="outline" as-child>
                                <Link :href="index.url()">Cancel</Link>
                            </Button>
                            <Button type="submit" :disabled="form.processing"
                                >Create Workflow</Button
                            >
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
