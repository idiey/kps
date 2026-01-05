<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { edit, index } from '@/routes/admin/workflows';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps<{
    workflow: {
        id: number;
        name: string;
        code: string;
        description: string;
        is_active: boolean;
        is_default: boolean;
        statuses: any[];
        transitions: any[];
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin', href: '#' },
    { title: 'Workflows', href: index.url() },
    { title: props.workflow.name, href: '#' },
];
</script>

<template>
    <Head :title="`Workflow: ${workflow.name}`" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight">
                        {{ workflow.name }}
                    </h2>
                    <p class="text-muted-foreground">
                        Workflow details and configuration.
                    </p>
                </div>
                <Button as-child>
                    <Link :href="edit.url(workflow.id)">Edit Workflow</Link>
                </Button>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <Card>
                    <CardHeader>
                        <CardTitle>Details</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <div
                                    class="text-sm font-medium text-muted-foreground"
                                >
                                    Code
                                </div>
                                <div>{{ workflow.code }}</div>
                            </div>
                            <div>
                                <div
                                    class="text-sm font-medium text-muted-foreground"
                                >
                                    Status
                                </div>
                                <div>
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
                                </div>
                            </div>
                            <div>
                                <div
                                    class="text-sm font-medium text-muted-foreground"
                                >
                                    Default
                                </div>
                                <div>
                                    <Badge
                                        variant="outline"
                                        v-if="workflow.is_default"
                                        >Yes</Badge
                                    >
                                    <span v-else>No</span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div
                                class="text-sm font-medium text-muted-foreground"
                            >
                                Description
                            </div>
                            <div class="mt-1">
                                {{
                                    workflow.description ||
                                    'No description provided.'
                                }}
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Statistics</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <div
                                    class="text-sm font-medium text-muted-foreground"
                                >
                                    Statuses
                                </div>
                                <div class="text-2xl font-bold">
                                    {{ workflow.statuses.length }}
                                </div>
                            </div>
                            <div>
                                <div
                                    class="text-sm font-medium text-muted-foreground"
                                >
                                    Transitions
                                </div>
                                <div class="text-2xl font-bold">
                                    {{ workflow.transitions.length }}
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
