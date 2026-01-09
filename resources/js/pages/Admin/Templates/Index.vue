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
import admin from '@/routes/admin';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Edit, FileText, Plus, Trash2 } from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps<{
    templates: {
        data: Array<{
            id: number;
            name: string;
            code: string;
            description: string;
            is_active: boolean;
            is_default: boolean;
            jobs_count: number;
        }>;
        links: any[];
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin', href: '#' },
    { title: 'Templates', href: admin.templates.index.url() },
];

const form = useForm({});

const deleteTemplate = (id: number) => {
    if (confirm('Are you sure you want to delete this template?')) {
        form.delete(admin.templates.destroy.url(id));
    }
};
</script>

<template>
    <Head title="Job Templates" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight">Job Templates</h2>
                    <p class="text-muted-foreground">
                        Manage form templates for various job workflows.
                    </p>
                </div>
                <Button as-child>
                    <Link :href="admin.templates.create.url()">
                        <Plus class="mr-2 h-4 w-4" />
                        Create Template
                    </Link>
                </Button>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>Templates</CardTitle>
                    <CardDescription>
                        List of all available form templates.
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Name</TableHead>
                                <TableHead>Code</TableHead>
                                <TableHead>Usage</TableHead>
                                <TableHead>Status</TableHead>
                                <TableHead class="text-right">Actions</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="template in templates.data" :key="template.id">
                                <TableCell class="font-medium">
                                    <div class="flex items-center gap-2">
                                        <FileText class="h-4 w-4 text-muted-foreground" />
                                        <span>{{ template.name }}</span>
                                        <Badge v-if="template.is_default" variant="secondary" class="text-xs">
                                            Default
                                        </Badge>
                                    </div>
                                    <div class="text-xs text-muted-foreground mt-1">
                                        {{ template.description }}
                                    </div>
                                </TableCell>
                                <TableCell>{{ template.code }}</TableCell>
                                <TableCell>
                                    <Badge variant="outline">{{ template.jobs_count }} Jobs</Badge>
                                </TableCell>
                                <TableCell>
                                    <Badge :variant="template.is_active ? 'default' : 'destructive'">
                                        {{ template.is_active ? 'Active' : 'Inactive' }}
                                    </Badge>
                                </TableCell>
                                <TableCell class="text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <Button variant="ghost" size="icon" as-child>
                                            <Link :href="admin.templates.edit.url(template.id)">
                                                <Edit class="h-4 w-4" />
                                            </Link>
                                        </Button>
                                        <Button
                                            variant="ghost"
                                            size="icon"
                                            class="text-destructive hover:text-destructive"
                                            @click="deleteTemplate(template.id)"
                                            :disabled="template.jobs_count > 0"
                                        >
                                            <Trash2 class="h-4 w-4" />
                                        </Button>
                                    </div>
                                </TableCell>
                            </TableRow>
                            <TableRow v-if="templates.data.length === 0">
                                <TableCell colspan="5" class="h-24 text-center">
                                    No templates found.
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
