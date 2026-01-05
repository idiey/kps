<script setup lang="ts">
import EmptyState from '@/components/EmptyState.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import JobCard from '@/components/workshop/JobCard.vue';
import { useConfirmDialog } from '@/composables/useConfirmDialog';
import { useToast } from '@/composables/useToast';
import AppLayout from '@/layouts/AppLayout.vue';
import { destroy, edit, index } from '@/routes/customers';
import { create as jobsCreate, index as jobsIndex } from '@/routes/jobs';
import type { Customer, WorkshopJob } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import {
    ArrowLeft,
    Edit,
    FileText,
    Mail,
    MapPin,
    Phone,
    Plus,
    Trash2,
} from 'lucide-vue-next';

interface Props {
    customer: Customer;
    recentJobs: WorkshopJob[];
    canEdit?: boolean;
    canDelete?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    canEdit: false,
    canDelete: false,
});

const { confirm } = useConfirmDialog();
const { success, error } = useToast();

const deleteCustomer = async () => {
    const confirmed = await confirm({
        title: 'Delete Customer',
        description:
            'Are you sure you want to delete this customer? This action cannot be undone.',
        confirmText: 'Delete',
        cancelText: 'Cancel',
        variant: 'destructive',
    });

    if (confirmed) {
        router.delete(destroy.url(props.customer.id), {
            onSuccess: () => {
                success(
                    'Customer Deleted',
                    'Customer has been deleted successfully.',
                );
            },
            onError: () => {
                error('Delete Failed', 'Failed to delete customer.');
            },
        });
    }
};
</script>

<template>
    <AppLayout>
        <Head :title="customer.name" />

        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Button variant="outline" size="sm" as-child>
                        <Link :href="index.url()">
                            <ArrowLeft class="mr-2 h-4 w-4" />
                            Back
                        </Link>
                    </Button>
                    <div>
                        <h1 class="text-3xl font-bold tracking-tight">
                            {{ customer.name }}
                        </h1>
                        <p class="text-muted-foreground">Customer Details</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <Button v-if="canEdit" variant="outline" as-child>
                        <Link :href="edit.url(customer.id)">
                            <Edit class="mr-2 h-4 w-4" />
                            Edit
                        </Link>
                    </Button>
                    <Button
                        v-if="canDelete"
                        variant="destructive"
                        @click="deleteCustomer"
                    >
                        <Trash2 class="mr-2 h-4 w-4" />
                        Delete
                    </Button>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-3">
                <Card class="md:col-span-1">
                    <CardHeader>
                        <CardTitle>Contact Information</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="flex items-start gap-3">
                            <Mail
                                class="mt-0.5 h-5 w-5 text-muted-foreground"
                            />
                            <div class="flex-1">
                                <p
                                    class="text-sm font-medium text-muted-foreground"
                                >
                                    Email
                                </p>
                                <a
                                    :href="`mailto:${customer.email}`"
                                    class="hover:underline"
                                >
                                    {{ customer.email }}
                                </a>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <Phone
                                class="mt-0.5 h-5 w-5 text-muted-foreground"
                            />
                            <div class="flex-1">
                                <p
                                    class="text-sm font-medium text-muted-foreground"
                                >
                                    Phone
                                </p>
                                <a
                                    :href="`tel:${customer.phone}`"
                                    class="hover:underline"
                                >
                                    {{ customer.phone }}
                                </a>
                            </div>
                        </div>

                        <div
                            v-if="customer.address"
                            class="flex items-start gap-3"
                        >
                            <MapPin
                                class="mt-0.5 h-5 w-5 text-muted-foreground"
                            />
                            <div class="flex-1">
                                <p
                                    class="text-sm font-medium text-muted-foreground"
                                >
                                    Address
                                </p>
                                <p class="whitespace-pre-wrap">
                                    {{ customer.address }}
                                </p>
                            </div>
                        </div>

                        <div
                            v-if="customer.notes"
                            class="flex items-start gap-3 border-t pt-4"
                        >
                            <FileText
                                class="mt-0.5 h-5 w-5 text-muted-foreground"
                            />
                            <div class="flex-1">
                                <p
                                    class="text-sm font-medium text-muted-foreground"
                                >
                                    Notes
                                </p>
                                <p class="text-sm whitespace-pre-wrap">
                                    {{ customer.notes }}
                                </p>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <div class="space-y-6 md:col-span-2">
                    <Card>
                        <CardHeader
                            class="flex flex-row items-center justify-between"
                        >
                            <CardTitle>Recent Jobs</CardTitle>
                            <Button size="sm" as-child>
                                <Link
                                    :href="
                                        jobsCreate.url({
                                            customer_id: customer.id,
                                        })
                                    "
                                >
                                    <Plus class="mr-2 h-4 w-4" />
                                    New Job
                                </Link>
                            </Button>
                        </CardHeader>
                        <CardContent>
                            <div v-if="recentJobs.length === 0">
                                <EmptyState
                                    title="No jobs yet"
                                    description="Create a new job for this customer"
                                    :icon="FileText"
                                />
                            </div>

                            <div v-else class="grid gap-4">
                                <JobCard
                                    v-for="job in recentJobs"
                                    :key="job.id"
                                    :job="job"
                                />

                                <div
                                    v-if="recentJobs.length >= 5"
                                    class="text-center"
                                >
                                    <Button variant="outline" as-child>
                                        <Link
                                            :href="
                                                jobsIndex.url({
                                                    customer_id: customer.id,
                                                })
                                            "
                                        >
                                            View All Jobs
                                        </Link>
                                    </Button>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
