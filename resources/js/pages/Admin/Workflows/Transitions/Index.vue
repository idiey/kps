<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { builder } from '@/routes/admin/workflows';
import { create, destroy, edit } from '@/routes/admin/workflows/transitions';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { ArrowRight } from 'lucide-vue-next';

const props = defineProps<{
    workflow: {
        id: number;
        name: string;
    };
    transitions: Array<{
        id: number;
        name: string;
        from_status: { id: number; name: string; color: string };
        to_status: { id: number; name: string; color: string };
        is_active: boolean;
        display_order: number;
    }>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin', href: '#' },
    { title: 'Workflows', href: '/admin/workflows' },
    { title: props.workflow.name, href: builder.url(props.workflow.id) },
    { title: 'Transitions', href: '#' },
];

const deleteTransition = (transition: any) => {
    if (confirm('Are you sure you want to delete this transition?')) {
        router.delete(
            destroy.url({
                workflow: props.workflow.id,
                transition: transition.id,
            }),
            {
                preserveScroll: true,
            },
        );
    }
};
</script>

<template>
    <Head title="Workflow Transitions" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight">
                        Transitions
                    </h2>
                    <p class="text-muted-foreground">
                        Manage transitions between statuses for
                        {{ workflow.name }}.
                    </p>
                </div>
                <div class="flex gap-2">
                    <Button variant="outline" as-child>
                        <Link :href="builder.url(workflow.id)"
                            >Back to Builder</Link
                        >
                    </Button>
                    <Button as-child>
                        <Link :href="create.url(workflow.id)">
                            Create Transition
                        </Link>
                    </Button>
                </div>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>All Transitions</CardTitle>
                    <CardDescription>
                        Ordered list of transitions in this workflow.
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Order</TableHead>
                                <TableHead>Name</TableHead>
                                <TableHead>Flow</TableHead>
                                <TableHead>Status</TableHead>
                                <TableHead class="text-right"
                                    >Actions</TableHead
                                >
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow
                                v-for="transition in transitions"
                                :key="transition.id"
                            >
                                <TableCell>{{
                                    transition.display_order
                                }}</TableCell>
                                <TableCell class="font-medium">{{
                                    transition.name || 'Untitled'
                                }}</TableCell>
                                <TableCell>
                                    <div class="flex items-center gap-2">
                                        <Badge
                                            variant="outline"
                                            :style="{
                                                borderColor:
                                                    transition.from_status
                                                        .color,
                                            }"
                                        >
                                            {{ transition.from_status.name }}
                                        </Badge>
                                        <ArrowRight
                                            class="h-4 w-4 text-muted-foreground"
                                        />
                                        <Badge
                                            variant="outline"
                                            :style="{
                                                borderColor:
                                                    transition.to_status.color,
                                            }"
                                        >
                                            {{ transition.to_status.name }}
                                        </Badge>
                                    </div>
                                </TableCell>
                                <TableCell>
                                    <Badge
                                        :variant="
                                            transition.is_active
                                                ? 'default'
                                                : 'secondary'
                                        "
                                    >
                                        {{
                                            transition.is_active
                                                ? 'Active'
                                                : 'Inactive'
                                        }}
                                    </Badge>
                                </TableCell>
                                <TableCell class="text-right">
                                    <div class="flex justify-end gap-2">
                                        <Button
                                            variant="ghost"
                                            size="sm"
                                            as-child
                                        >
                                            <Link
                                                :href="
                                                    edit.url({
                                                        workflow: workflow.id,
                                                        transition:
                                                            transition.id,
                                                    })
                                                "
                                            >
                                                Edit
                                            </Link>
                                        </Button>
                                        <Button
                                            variant="ghost"
                                            size="sm"
                                            @click="
                                                deleteTransition(transition)
                                            "
                                            class="text-red-600 hover:bg-red-50 hover:text-red-600"
                                        >
                                            Delete
                                        </Button>
                                    </div>
                                </TableCell>
                            </TableRow>
                            <TableRow v-if="transitions.length === 0">
                                <TableCell
                                    colspan="5"
                                    class="py-8 text-center text-muted-foreground"
                                >
                                    No transitions found.
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
