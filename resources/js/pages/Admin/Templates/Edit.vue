<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import FormBuilder from './Partials/FormBuilder.vue';
import { ChevronLeft } from 'lucide-vue-next';
import admin from '@/routes/admin';

const props = defineProps<{
    template: {
        id: number;
        name: string;
        code: string;
        fields: Array<any>;
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin', href: '#' },
    { title: 'Templates', href: admin.templates.index.url() },
    { title: props.template.name, href: '#' },
    { title: 'Builder', href: '#' },
];
</script>

<template>
    <Head title="Edit Template" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-col">
            <!-- Header -->
            <div class="border-b bg-background px-4 py-3">
                <div class="flex items-center gap-4">
                    <Button variant="ghost" size="icon" as-child>
                        <Link :href="admin.templates.index.url()">
                            <ChevronLeft class="h-4 w-4" />
                        </Link>
                    </Button>
                    <div>
                        <h1 class="text-lg font-semibold">{{ template.name }}</h1>
                        <p class="text-xs text-muted-foreground">{{ template.code }}</p>
                    </div>
                    <div class="ml-auto flex items-center gap-2">
                        <!-- Actions like Preview or Save could go here -->
                    </div>
                </div>
            </div>

            <!-- Builder Area -->
            <div class="flex-1 overflow-hidden">
                <FormBuilder :template="template" />
            </div>
        </div>
    </AppLayout>
</template>
