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
import { create, destroy, edit } from '@/routes/admin/workflows/statuses';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';

const props = defineProps<{
    workflow: {
        id: number;
        name: string;
        code: string;
    };
    statuses: Array<{
        id: number;
        name: string;
        code: string;
        description: string;
        color: string;
        is_initial: boolean;
        is_final: boolean;
        display_order: number;
    }>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin', href: '#' },
    { title: 'Workflows', href: '/admin/workflows' },
    { title: props.workflow.name, href: builder.url(props.workflow.id) },
    { title: 'Statuses', href: '#' },
];

const deleteStatus = (status: any) => {
    if (confirm('Are you sure you want to delete this status?')) {
        router.delete(
            destroy.url({ workflow: props.workflow.id, status: status.id }),
            {
                preserveScroll: true,
            },
        );
    }
};
</script>

<template>
    <Head title="Workflow Statuses" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight">Statuses</h2>
                    <p class="text-muted-foreground">
                        Manage statuses for {{ workflow.name }}.
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
                            Create Status
                        </Link>
                    </Button>
                </div>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>All Statuses</CardTitle>
                    <CardDescription>
                        Ordered list of statuses in this workflow.
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Order</TableHead>
                                <TableHead>Name</TableHead>
                                <TableHead>Code</TableHead>
                                <TableHead>Type</TableHead>
                                <TableHead>Color</TableHead>
                                <TableHead class="text-right"
                                    >Actions</TableHead
                                >
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow
                                v-for="status in statuses"
                                :key="status.id"
                            >
                                <TableCell>{{
                                    status.display_order
                                }}</TableCell>
                                <TableCell>
                                    <div class="flex flex-col">
                                        <span class="font-medium">{{
                                            status.name
                                        }}</span>
                                        <span
                                            class="text-xs text-muted-foreground"
                                            >{{ status.description }}</span
                                        >
                                    </div>
                                </TableCell>
                                <TableCell>
                                    <Badge variant="outline">{{
                                        status.code
                                    }}</Badge>
                                </TableCell>
                                <TableCell>
                                    <Badge v-if="status.is_initial" class="mr-1"
                                        >Initial</Badge
                                    >
                                    <Badge
                                        v-if="status.is_final"
                                        variant="secondary"
                                        >Final</Badge
                                    >
                                </TableCell>
                                <TableCell>
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="h-4 w-4 rounded-full border"
                                            :style="{
                                                backgroundColor:
                                                    status.color || '#e5e7eb',
                                            }"
                                        ></div>
                                        <span class="text-xs">{{
                                            status.color
                                        }}</span>
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
                                                :href="
                                                    edit.url({
                                                        workflow: workflow.id,
                                                        status: status.id,
                                                    })
                                                "
                                            >
                                                Edit
                                            </Link>
                                        </Button>
                                        <Button
                                            variant="ghost"
                                            size="sm"
                                            @click="deleteStatus(status)"
                                            class="text-red-600 hover:bg-red-50 hover:text-red-600"
                                        >
                                            Delete
                                        </Button>
                                    </div>
                                </TableCell>
                            </TableRow>
                            <TableRow v-if="statuses.length === 0">
                                <TableCell
                                    colspan="6"
                                    class="py-8 text-center text-muted-foreground"
                                >
                                    No statuses found.
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
