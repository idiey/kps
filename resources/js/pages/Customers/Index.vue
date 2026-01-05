<script setup lang="ts">
import EmptyState from '@/components/EmptyState.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { create, edit, index, show } from '@/routes/customers';
import type { Customer, PaginatedResponse } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { Edit, Eye, Mail, Phone, Plus, Search } from 'lucide-vue-next';
import { ref } from 'vue';

interface Props {
    customers: PaginatedResponse<Customer>;
    filters?: {
        search?: string;
    };
    canCreate?: boolean;
    canEdit?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    filters: () => ({}),
    canCreate: false,
    canEdit: false,
});

const searchQuery = ref(props.filters?.search || '');

const performSearch = () => {
    router.get(
        index.url(),
        { search: searchQuery.value },
        { preserveState: true, preserveScroll: true },
    );
};
</script>

<template>
    <AppLayout>
        <Head title="Customers" />

        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight">Customers</h1>
                    <p class="text-muted-foreground">
                        Manage customer information
                    </p>
                </div>

                <Button v-if="canCreate" as-child>
                    <Link :href="create.url()">
                        <Plus class="mr-2 h-4 w-4" />
                        Add Customer
                    </Link>
                </Button>
            </div>

            <div class="flex items-center gap-2">
                <div class="relative max-w-sm flex-1">
                    <Search
                        class="absolute top-2.5 left-2.5 h-4 w-4 text-muted-foreground"
                    />
                    <Input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Search customers..."
                        class="pl-8"
                        @keyup.enter="performSearch"
                    />
                </div>
                <Button @click="performSearch"> Search </Button>
            </div>

            <div v-if="customers.data.length === 0 && !searchQuery">
                <EmptyState
                    title="No customers yet"
                    description="Get started by adding your first customer"
                    :icon="Plus"
                    action-text="Add Customer"
                    :action-href="canCreate ? create.url() : undefined"
                />
            </div>

            <div v-else-if="customers.data.length === 0">
                <EmptyState
                    title="No customers found"
                    description="Try adjusting your search criteria"
                />
            </div>

            <div v-else class="space-y-4">
                <div class="rounded-md border">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Name</TableHead>
                                <TableHead>Email</TableHead>
                                <TableHead>Phone</TableHead>
                                <TableHead>Address</TableHead>
                                <TableHead class="text-right"
                                    >Actions</TableHead
                                >
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow
                                v-for="customer in customers.data"
                                :key="customer.id"
                                class="hover:bg-muted/50"
                            >
                                <TableCell class="font-medium">
                                    <Link
                                        :href="show.url(customer.id)"
                                        class="hover:underline"
                                    >
                                        {{ customer.name }}
                                    </Link>
                                </TableCell>
                                <TableCell>
                                    <div class="flex items-center gap-2">
                                        <Mail
                                            class="h-4 w-4 text-muted-foreground"
                                        />
                                        <a
                                            :href="`mailto:${customer.email}`"
                                            class="hover:underline"
                                        >
                                            {{ customer.email }}
                                        </a>
                                    </div>
                                </TableCell>
                                <TableCell>
                                    <div class="flex items-center gap-2">
                                        <Phone
                                            class="h-4 w-4 text-muted-foreground"
                                        />
                                        <a
                                            :href="`tel:${customer.phone}`"
                                            class="hover:underline"
                                        >
                                            {{ customer.phone }}
                                        </a>
                                    </div>
                                </TableCell>
                                <TableCell class="max-w-xs truncate">
                                    {{ customer.address || '-' }}
                                </TableCell>
                                <TableCell class="text-right">
                                    <div class="flex justify-end gap-1">
                                        <Button
                                            variant="ghost"
                                            size="sm"
                                            as-child
                                        >
                                            <Link :href="show.url(customer.id)">
                                                <Eye class="h-4 w-4" />
                                            </Link>
                                        </Button>
                                        <Button
                                            v-if="canEdit"
                                            variant="ghost"
                                            size="sm"
                                            as-child
                                        >
                                            <Link :href="edit.url(customer.id)">
                                                <Edit class="h-4 w-4" />
                                            </Link>
                                        </Button>
                                    </div>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>

                <div class="flex items-center justify-between">
                    <div class="text-sm text-muted-foreground">
                        Showing {{ customers.from }} to {{ customers.to }} of
                        {{ customers.total }} customers
                    </div>

                    <div
                        v-if="customers.links.length > 3"
                        class="flex items-center gap-2"
                    >
                        <Button
                            v-for="link in customers.links"
                            :key="link.label"
                            variant="outline"
                            size="sm"
                            :disabled="!link.url || link.active"
                            @click="link.url && router.visit(link.url)"
                        >
                            <span v-html="link.label" />
                        </Button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
