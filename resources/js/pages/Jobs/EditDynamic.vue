<template>
    <AppLayout :title="`Edit Job #${job.id}`">
        <template #header>
            <div class="flex items-center gap-3">
                <Link
                    :href="show.url(job.id)"
                    class="text-gray-600 hover:text-gray-900"
                >
                    <svg
                        class="h-5 w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M15 19l-7-7 7-7"
                        />
                    </svg>
                </Link>
                <h2 class="text-xl leading-tight font-semibold text-gray-800">
                    Edit Job #{{ job.id }}
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <DynamicFormRenderer
                            v-if="formSchema"
                            :schema="formSchema"
                            :initial-data="job.field_values"
                            :job-id="job.id"
                            submit-label="Update Job"
                            @submit="handleSubmit"
                            @cancel="handleCancel"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import DynamicFormRenderer from '@/components/dynamic-form/DynamicFormRenderer.vue';
import { show, update } from '@/routes/jobs';
import { Link, router, useForm } from '@inertiajs/vue3';

const props = defineProps({
    job: {
        type: Object,
        required: true,
    },
    formSchema: {
        type: Object,
        required: true,
    },
});

const handleSubmit = (formData) => {
    const form = useForm({
        field_data: formData,
    });

    form.put(update.url(props.job.id), {
        onSuccess: () => {
            // Redirect handled by controller
        },
        onError: (errors) => {
            console.error('Validation errors:', errors);
        },
    });
};

const handleCancel = () => {
    router.visit(show.url(props.job.id));
};
</script>
