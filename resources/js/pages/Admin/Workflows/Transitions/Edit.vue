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
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Switch } from '@/components/ui/switch';
import AppLayout from '@/layouts/AppLayout.vue';
import { index, update } from '@/routes/admin/workflows/transitions';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    workflow: {
        id: number;
        name: string;
    };
    transition: {
        id: number;
        from_status_id: number;
        to_status_id: number;
        name: string;
        description: string;
        is_active: boolean;
        display_order: number;
    };
    statuses: Array<{
        id: number;
        name: string;
    }>;
    roles: Array<{
        id: number;
        name: string;
    }>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin', href: '#' },
    { title: 'Workflows', href: '/admin/workflows' },
    {
        title: props.workflow.name,
        href: `/admin/workflows/${props.workflow.id}/builder`,
    },
    { title: 'Transitions', href: index.url(props.workflow.id) },
    { title: 'Edit', href: '#' },
];

const form = useForm({
    from_status_id: props.transition.from_status_id.toString(),
    to_status_id: props.transition.to_status_id.toString(),
    name: props.transition.name,
    description: props.transition.description,
    is_active: Boolean(props.transition.is_active),
    display_order: props.transition.display_order,
});

const submit = () => {
    form.put(
        update.url({
            workflow: props.workflow.id,
            transition: props.transition.id,
        }),
    );
};
</script>

<template>
    <Head title="Edit Transition" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="mx-auto flex h-full w-full max-w-2xl flex-1 flex-col gap-4 p-4"
        >
            <Card>
                <CardHeader>
                    <CardTitle>Edit Transition</CardTitle>
                    <CardDescription>
                        Edit transition details.
                    </CardDescription>
                </CardHeader>
                <form @submit.prevent="submit">
                    <CardContent class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <Label>From Status</Label>
                                <Select v-model="form.from_status_id">
                                    <SelectTrigger>
                                        <SelectValue
                                            placeholder="Select status"
                                        />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem
                                            v-for="status in statuses"
                                            :key="status.id"
                                            :value="status.id.toString()"
                                        >
                                            {{ status.name }}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                                <p
                                    v-if="form.errors.from_status_id"
                                    class="text-sm text-red-500"
                                >
                                    {{ form.errors.from_status_id }}
                                </p>
                            </div>

                            <div class="space-y-2">
                                <Label>To Status</Label>
                                <Select v-model="form.to_status_id">
                                    <SelectTrigger>
                                        <SelectValue
                                            placeholder="Select status"
                                        />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem
                                            v-for="status in statuses"
                                            :key="status.id"
                                            :value="status.id.toString()"
                                        >
                                            {{ status.name }}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                                <p
                                    v-if="form.errors.to_status_id"
                                    class="text-sm text-red-500"
                                >
                                    {{ form.errors.to_status_id }}
                                </p>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <Label for="name">Transition Name</Label>
                            <Input
                                id="name"
                                v-model="form.name"
                                placeholder="e.g. Approve"
                            />
                            <p
                                v-if="form.errors.name"
                                class="text-sm text-red-500"
                            >
                                {{ form.errors.name }}
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

                        <div
                            class="flex items-center justify-between rounded-lg border p-4"
                        >
                            <div class="space-y-0.5">
                                <Label class="text-base">Active</Label>
                                <div class="text-sm text-muted-foreground">
                                    Is this transition currently available?
                                </div>
                            </div>
                            <Switch v-model:checked="form.is_active" />
                        </div>
                    </CardContent>
                    <CardFooter class="flex justify-between">
                        <Button variant="outline" as-child>
                            <Link :href="index.url(workflow.id)">Cancel</Link>
                        </Button>
                        <Button type="submit" :disabled="form.processing"
                            >Save Changes</Button
                        >
                    </CardFooter>
                </form>
            </Card>
        </div>
    </AppLayout>
</template>
