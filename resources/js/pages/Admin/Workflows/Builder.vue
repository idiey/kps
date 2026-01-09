<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { index as statusesIndex } from '@/routes/admin/workflows/statuses';
import { index as transitionsIndex } from '@/routes/admin/workflows/transitions';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowRight, Plus, Settings, Copy } from 'lucide-vue-next';
import { ref } from 'vue';
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from '@/components/ui/dialog';
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select';
import { Label } from '@/components/ui/label';

import { importMethod } from '@/routes/admin/workflows';

const props = defineProps<{
    workflow: {
        id: number;
        name: string;
        statuses: Array<{
            id: number;
            name: string;
            color: string;
            is_initial: boolean;
            is_final: boolean;
            display_order: number;
        }>;
        transitions: Array<{
            id: number;
            from_status_id: number;
            to_status_id: number;
            name: string;
            from_status: { name: string };
            to_status: { name: string };
        }>;
    };
    availableWorkflows: Array<{
        id: number;
        name: string;
    }>;
}>();

const isImportModalOpen = ref(false);

const importForm = useForm({
    source_workflow_id: '' as string,
    mode: 'replace' as 'replace' | 'append',
});

const handleImport = () => {
    importForm.post(importMethod.url(props.workflow.id), {
        onSuccess: () => {
            isImportModalOpen.value = false;
            importForm.reset();
        },
    });
};

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin', href: '#' },
    { title: 'Workflows', href: '/admin/workflows' },
    { title: props.workflow.name, href: '#' },
    { title: 'Builder', href: '#' },
];

const getTransitionsForStatus = (statusId: number) => {
    return props.workflow.transitions.filter(
        (t) => t.from_status_id === statusId,
    );
};
</script>

<template>
    <Head title="Workflow Builder" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight">
                        Workflow Builder: {{ workflow.name }}
                    </h2>
                    <p class="text-muted-foreground">
                        Visualize and manage the workflow process.
                    </p>
                </div>
                <div class="flex gap-2">
                    <Dialog v-model:open="isImportModalOpen">
                        <DialogTrigger as-child>
                            <Button variant="outline">
                                <Copy class="mr-2 h-4 w-4" />
                                Import Template
                            </Button>
                        </DialogTrigger>
                        <DialogContent>
                            <DialogHeader>
                                <DialogTitle>Import Workflow</DialogTitle>
                                <DialogDescription>
                                    Copy statuses and transitions from another workflow.
                                </DialogDescription>
                            </DialogHeader>

                            <div class="grid gap-4 py-4">
                                <div class="grid gap-2">
                                    <Label for="source-workflow">Source Workflow</Label>
                                    <Select v-model="importForm.source_workflow_id">
                                        <SelectTrigger id="source-workflow">
                                            <SelectValue placeholder="Select a workflow" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem
                                                v-for="wf in availableWorkflows"
                                                :key="wf.id"
                                                :value="wf.id.toString()"
                                            >
                                                {{ wf.name }}
                                            </SelectItem>
                                        </SelectContent>
                                    </Select>
                                    <p v-if="importForm.errors.source_workflow_id" class="text-sm text-red-500">
                                        {{ importForm.errors.source_workflow_id }}
                                    </p>
                                </div>

                                <div class="grid gap-2">
                                    <Label>Import Mode</Label>
                                    <div class="flex flex-col space-y-2">
                                        <div class="flex items-center space-x-2">
                                            <input
                                                type="radio"
                                                id="replace"
                                                value="replace"
                                                v-model="importForm.mode"
                                                class="h-4 w-4 border-gray-300 text-primary focus:ring-primary"
                                            />
                                            <Label for="replace" class="font-normal cursor-pointer">
                                                Replace Existing (Clear current workflow)
                                            </Label>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <input
                                                type="radio"
                                                id="append"
                                                value="append"
                                                v-model="importForm.mode"
                                                class="h-4 w-4 border-gray-300 text-primary focus:ring-primary"
                                            />
                                            <Label for="append" class="font-normal cursor-pointer">
                                                Append (Keep existing items)
                                            </Label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <DialogFooter>
                                <Button variant="outline" @click="isImportModalOpen = false">
                                    Cancel
                                </Button>
                                <Button @click="handleImport" :disabled="importForm.processing">
                                    {{ importForm.processing ? 'Importing...' : 'Import' }}
                                </Button>
                            </DialogFooter>
                        </DialogContent>
                    </Dialog>

                    <Button variant="outline" as-child>
                        <Link :href="statusesIndex.url(workflow.id)">
                            <Settings class="mr-2 h-4 w-4" />
                            Manage Statuses
                        </Link>
                    </Button>
                    <Button variant="outline" as-child>
                        <Link :href="transitionsIndex.url(workflow.id)">
                            <Settings class="mr-2 h-4 w-4" />
                            Manage Transitions
                        </Link>
                    </Button>
                </div>
            </div>

            <div
                class="flex min-h-[500px] flex-col gap-8 rounded-lg border bg-muted/20 p-4"
            >
                <div
                    v-if="workflow.statuses.length === 0"
                    class="flex h-full flex-col items-center justify-center py-12"
                >
                    <p class="mb-4 text-muted-foreground">
                        No statuses defined yet.
                    </p>
                    <Button as-child>
                        <Link :href="statusesIndex.url(workflow.id)">
                            <Plus class="mr-2 h-4 w-4" />
                            Add Status
                        </Link>
                    </Button>
                </div>

                <div v-else class="flex flex-wrap items-start gap-4">
                    <div
                        v-for="(status, index) in workflow.statuses"
                        :key="status.id"
                        class="flex flex-col items-center gap-4"
                    >
                        <Card
                            class="relative w-64 overflow-hidden transition-all hover:shadow-md"
                        >
                            <div
                                class="absolute top-0 left-0 h-full w-1"
                                :style="{
                                    backgroundColor: status.color || '#e5e7eb',
                                }"
                            ></div>
                            <CardHeader class="pb-2">
                                <div class="flex items-start justify-between">
                                    <CardTitle class="text-lg">{{
                                        status.name
                                    }}</CardTitle>
                                    <div class="flex gap-1">
                                        <Badge
                                            v-if="status.is_initial"
                                            variant="secondary"
                                            class="h-5 px-1 text-[10px]"
                                            >Start</Badge
                                        >
                                        <Badge
                                            v-if="status.is_final"
                                            variant="secondary"
                                            class="h-5 px-1 text-[10px]"
                                            >End</Badge
                                        >
                                    </div>
                                </div>
                            </CardHeader>
                            <CardContent>
                                <div class="mb-4 text-xs text-muted-foreground">
                                    Order: {{ status.display_order }}
                                </div>

                                <div
                                    v-if="
                                        getTransitionsForStatus(status.id)
                                            .length > 0
                                    "
                                    class="space-y-2"
                                >
                                    <p
                                        class="text-xs font-semibold text-muted-foreground uppercase"
                                    >
                                        Transitions To:
                                    </p>
                                    <div
                                        v-for="transition in getTransitionsForStatus(
                                            status.id,
                                        )"
                                        :key="transition.id"
                                        class="flex items-center gap-2 rounded bg-muted/50 p-2 text-sm"
                                    >
                                        <ArrowRight
                                            class="h-3 w-3 text-muted-foreground"
                                        />
                                        <span>{{
                                            transition.to_status.name
                                        }}</span>
                                        <span
                                            v-if="transition.name"
                                            class="text-xs text-muted-foreground"
                                            >({{ transition.name }})</span
                                        >
                                    </div>
                                </div>
                                <div
                                    v-else
                                    class="text-xs text-muted-foreground italic"
                                >
                                    No outgoing transitions
                                </div>
                            </CardContent>
                        </Card>

                        <!-- Visual Arrow for flow (simplified) -->
                        <div
                            v-if="index < workflow.statuses.length - 1"
                            class="my-2 hidden h-8 w-0 border-l border-dashed border-gray-300 md:flex"
                        ></div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
