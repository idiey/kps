<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { useToast } from '@/composables/useToast';
import AppLayout from '@/layouts/AppLayout.vue';
import { index, store } from '@/routes/customers';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowLeft, Save } from 'lucide-vue-next';

const { success, error } = useToast();

const form = useForm({
    name: '',
    email: '',
    phone: '',
    address: '',
    notes: '',
});

const submitForm = () => {
    form.post(store.url(), {
        onSuccess: () => {
            success(
                'Customer Created',
                'Customer has been created successfully.',
            );
        },
        onError: () => {
            error(
                'Create Failed',
                'Failed to create customer. Please check the form for errors.',
            );
        },
    });
};
</script>

<template>
    <AppLayout>
        <Head title="Create Customer" />

        <div class="space-y-6">
            <div class="flex items-center gap-4">
                <Button variant="outline" size="sm" as-child>
                    <Link :href="index.url()">
                        <ArrowLeft class="mr-2 h-4 w-4" />
                        Back
                    </Link>
                </Button>
                <div>
                    <h1 class="text-3xl font-bold tracking-tight">
                        Add New Customer
                    </h1>
                    <p class="text-muted-foreground">
                        Create a new customer record
                    </p>
                </div>
            </div>

            <form @submit.prevent="submitForm">
                <Card>
                    <CardHeader>
                        <CardTitle>Customer Information</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-6">
                        <div class="space-y-2">
                            <Label for="name">
                                Name
                                <span class="text-destructive">*</span>
                            </Label>
                            <Input
                                id="name"
                                v-model="form.name"
                                placeholder="Customer name"
                                :disabled="form.processing"
                            />
                            <p
                                v-if="form.errors.name"
                                class="text-sm text-destructive"
                            >
                                {{ form.errors.name }}
                            </p>
                        </div>

                        <div class="grid gap-6 sm:grid-cols-2">
                            <div class="space-y-2">
                                <Label for="email">
                                    Email
                                    <span class="text-destructive">*</span>
                                </Label>
                                <Input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    placeholder="email@example.com"
                                    :disabled="form.processing"
                                />
                                <p
                                    v-if="form.errors.email"
                                    class="text-sm text-destructive"
                                >
                                    {{ form.errors.email }}
                                </p>
                            </div>

                            <div class="space-y-2">
                                <Label for="phone">
                                    Phone
                                    <span class="text-destructive">*</span>
                                </Label>
                                <Input
                                    id="phone"
                                    v-model="form.phone"
                                    type="tel"
                                    placeholder="+60123456789"
                                    :disabled="form.processing"
                                />
                                <p
                                    v-if="form.errors.phone"
                                    class="text-sm text-destructive"
                                >
                                    {{ form.errors.phone }}
                                </p>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <Label for="address">Address</Label>
                            <Textarea
                                id="address"
                                v-model="form.address"
                                placeholder="Customer address"
                                rows="3"
                                :disabled="form.processing"
                            />
                            <p
                                v-if="form.errors.address"
                                class="text-sm text-destructive"
                            >
                                {{ form.errors.address }}
                            </p>
                        </div>

                        <div class="space-y-2">
                            <Label for="notes">Notes</Label>
                            <Textarea
                                id="notes"
                                v-model="form.notes"
                                placeholder="Additional notes about the customer"
                                rows="3"
                                :disabled="form.processing"
                            />
                            <p
                                v-if="form.errors.notes"
                                class="text-sm text-destructive"
                            >
                                {{ form.errors.notes }}
                            </p>
                        </div>

                        <div class="flex justify-end gap-2">
                            <Button
                                variant="outline"
                                type="button"
                                :disabled="form.processing"
                                as-child
                            >
                                <Link :href="index.url()"> Cancel </Link>
                            </Button>
                            <Button type="submit" :disabled="form.processing">
                                <Save class="mr-2 h-4 w-4" />
                                Create Customer
                            </Button>
                        </div>
                    </CardContent>
                </Card>
            </form>
        </div>
    </AppLayout>
</template>
