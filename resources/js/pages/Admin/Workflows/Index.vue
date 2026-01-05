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
import {
    builder,
    create,
    destroy,
    edit,
    index as indexRoute,
} from '@/routes/admin/workflows';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps<{
    workflows: {
        data: Array<{
            id: number;
            name: string;
            code: string;
            description: string;
            is_active: boolean;
            is_default: boolean;
            statuses_count: number;
            transitions_count: number;
            created_at: string;
        }>;
        links: any[];
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Admin',
        href: '#',
    },
    {
        title: 'Workflows',
        href: indexRoute.url(),
    },
];

const deleteWorkflow = (workflow: any) => {
    if (
        confirm(
            'Are you sure you want to delete this workflow? This action cannot be undone.',
        )
    ) {
        router.delete(destroy.url(workflow.id), {
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <Head title="Workflows" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight">Workflows</h2>
                    <p class="text-muted-foreground">
                        Manage system workflows, statuses, and transitions.
                    </p>
                </div>
                <Button as-child>
                    <Link :href="create.url()"> Create Workflow </Link>
                </Button>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>All Workflows</CardTitle>
                    <CardDescription>
                        A list of all registered workflows in the system.
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Name</TableHead>
                                <TableHead>Code</TableHead>
                                <TableHead>Status</TableHead>
                                <TableHead>Stats</TableHead>
                                <TableHead class="text-right"
                                    >Actions</TableHead
                                >
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow
                                v-for="workflow in workflows.data"
                                :key="workflow.id"
                            >
                                <TableCell class="font-medium">
                                    <div class="flex flex-col">
                                        <span>{{ workflow.name }}</span>
                                        <span
                                            class="text-xs text-muted-foreground"
                                            >{{ workflow.description }}</span
                                        >
                                    </div>
                                </TableCell>
                                <TableCell>
                                    <Badge variant="outline">{{
                                        workflow.code
                                    }}</Badge>
                                    <Badge
                                        v-if="workflow.is_default"
                                        class="ml-2"
                                        variant="secondary"
                                        >Default</Badge
                                    >
                                </TableCell>
                                <TableCell>
                                    <Badge
                                        :variant="
                                            workflow.is_active
                                                ? 'default'
                                                : 'destructive'
                                        "
                                    >
                                        {{
                                            workflow.is_active
                                                ? 'Active'
                                                : 'Inactive'
                                        }}
                                    </Badge>
                                </TableCell>
                                <TableCell>
                                    <div class="text-sm text-muted-foreground">
                                        {{ workflow.statuses_count }} Statuses •
                                        {{ workflow.transitions_count }}
                                        Transitions
                                    </div>
                                </TableCell>
                                <TableCell class="text-right">
                                    <div class="flex justify-end gap-2">
                                        <Button
                                            variant="ghost"
                                            size="sm"
                                            as-child
                                        >
                                            <Link
                                                :href="builder.url(workflow.id)"
                                            >
                                                Builder
                                            </Link>
                                        </Button>
                                        <Button
                                            variant="ghost"
                                            size="sm"
                                            as-child
                                        >
                                            <Link :href="edit.url(workflow.id)">
                                                Edit
                                            </Link>
                                        </Button>
                                        <Button
                                            variant="ghost"
                                            size="sm"
                                            @click="deleteWorkflow(workflow)"
                                            class="text-red-600 hover:bg-red-50 hover:text-red-600"
                                        >
                                            Delete
                                        </Button>
                                    </div>
                                </TableCell>
                            </TableRow>
                            <TableRow v-if="workflows.data.length === 0">
                                <TableCell
                                    colspan="5"
                                    class="py-8 text-center text-muted-foreground"
                                >
                                    No workflows found. Create one to get
                                    started.
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
