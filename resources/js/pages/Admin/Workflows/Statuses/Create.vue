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
import { Switch } from '@/components/ui/switch';
import { Textarea } from '@/components/ui/textarea';
import AppLayout from '@/layouts/AppLayout.vue';
import { index, store } from '@/routes/admin/workflows/statuses';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    workflow: {
        id: number;
        name: string;
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin', href: '#' },
    { title: 'Workflows', href: '/admin/workflows' },
    {
        title: props.workflow.name,
        href: `/admin/workflows/${props.workflow.id}/builder`,
    },
    { title: 'Statuses', href: index.url(props.workflow.id) },
    { title: 'Create', href: '#' },
];

const form = useForm({
    name: '',
    code: '',
    description: '',
    color: '#ffffff',
    icon: '',
    is_initial: false,
    is_final: false,
    display_order: 0,
});

const submit = () => {
    form.post(store.url(props.workflow.id));
};
</script>

<template>
    <Head title="Create Status" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="mx-auto flex h-full w-full max-w-2xl flex-1 flex-col gap-4 p-4"
        >
            <Card>
                <CardHeader>
                    <CardTitle>Create Status</CardTitle>
                    <CardDescription>
                        Add a new status to {{ workflow.name }}.
                    </CardDescription>
                </CardHeader>
                <form @submit.prevent="submit">
                    <CardContent class="space-y-4">
                        <div class="space-y-2">
                            <Label for="name">Name</Label>
                            <Input
                                id="name"
                                v-model="form.name"
                                placeholder="e.g. In Progress"
                                required
                            />
                            <p
                                v-if="form.errors.name"
                                class="text-sm text-red-500"
                            >
                                {{ form.errors.name }}
                            </p>
                        </div>

                        <div class="space-y-2">
                            <Label for="code">Code</Label>
                            <Input
                                id="code"
                                v-model="form.code"
                                placeholder="e.g. in_progress"
                                required
                            />
                            <p
                                v-if="form.errors.code"
                                class="text-sm text-red-500"
                            >
                                {{ form.errors.code }}
                            </p>
                        </div>

                        <div class="space-y-2">
                            <Label for="description">Description</Label>
                            <Textarea
                                id="description"
                                v-model="form.description"
                                placeholder="Optional description"
                            />
                            <p
                                v-if="form.errors.description"
                                class="text-sm text-red-500"
                            >
                                {{ form.errors.description }}
                            </p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <Label for="color">Color</Label>
                                <div class="flex gap-2">
                                    <Input
                                        id="color"
                                        type="color"
                                        v-model="form.color"
                                        class="h-10 w-12 p-1"
                                    />
                                    <Input
                                        v-model="form.color"
                                        placeholder="#ffffff"
                                    />
                                </div>
                                <p
                                    v-if="form.errors.color"
                                    class="text-sm text-red-500"
                                >
                                    {{ form.errors.color }}
                                </p>
                            </div>
                            <div class="space-y-2">
                                <Label for="display_order">Display Order</Label>
                                <Input
                                    id="display_order"
                                    type="number"
                                    v-model="form.display_order"
                                    min="0"
                                />
                                <p
                                    v-if="form.errors.display_order"
                                    class="text-sm text-red-500"
                                >
                                    {{ form.errors.display_order }}
                                </p>
                            </div>
                        </div>

                        <div class="flex flex-col gap-4 pt-2">
                            <div
                                class="flex items-center justify-between rounded-lg border p-4"
                            >
                                <div class="space-y-0.5">
                                    <Label class="text-base"
                                        >Initial Status</Label
                                    >
                                    <div class="text-sm text-muted-foreground">
                                        Is this the starting status for new
                                        workflows?
                                    </div>
                                </div>
                                <Switch v-model:checked="form.is_initial" />
                            </div>

                            <div
                                class="flex items-center justify-between rounded-lg border p-4"
                            >
                                <div class="space-y-0.5">
                                    <Label class="text-base"
                                        >Final Status</Label
                                    >
                                    <div class="text-sm text-muted-foreground">
                                        Is this a completed/closed status?
                                    </div>
                                </div>
                                <Switch v-model:checked="form.is_final" />
                            </div>
                        </div>
                    </CardContent>
                    <CardFooter class="flex justify-between">
                        <Button variant="outline" as-child>
                            <Link :href="index.url(workflow.id)">Cancel</Link>
                        </Button>
                        <Button type="submit" :disabled="form.processing"
                            >Create Status</Button
                        >
                    </CardFooter>
                </form>
            </Card>
        </div>
    </AppLayout>
</template>
