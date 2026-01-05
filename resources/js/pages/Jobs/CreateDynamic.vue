<template>
    <AppLayout title="Create Job">
        <template #header>
            <h2 class="text-xl leading-tight font-semibold text-gray-800">
                Create Job - {{ template.name }}
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="space-y-6">
                    <!-- Workflow Selection (if multiple workflows available) -->
                    <div
                        v-if="workflows.length > 1"
                        class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                    >
                        <div class="p-6">
                            <WorkflowSelector
                                :workflows="workflows"
                                :template="template"
                                v-model="selectedWorkflow"
                            />
                        </div>
                    </div>

                    <!-- Dynamic Form -->
                    <div
                        class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                    >
                        <div class="p-6">
                            <DynamicFormRenderer
                                v-if="formSchema && selectedWorkflow"
                                :schema="formSchema"
                                :initial-data="initialData"
                                submit-label="Create Job"
                                @submit="handleSubmit"
                                @cancel="handleCancel"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import DynamicFormRenderer from '@/components/dynamic-form/DynamicFormRenderer.vue';
import WorkflowSelector from '@/components/job/WorkflowSelector.vue';
import { router, useForm } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

const props = defineProps({
    template: {
        type: Object,
        required: true,
    },
    workflows: {
        type: Array,
        required: true,
    },
    selectedWorkflow: {
        type: Object,
        default: null,
    },
    formSchema: {
        type: Object,
        required: true,
    },
});

const selectedWorkflow = ref(props.selectedWorkflow);
const initialData = ref({});

// Auto-select workflow if only one or if default is set
onMounted(() => {
    if (!selectedWorkflow.value && props.workflows.length === 1) {
        selectedWorkflow.value = props.workflows[0];
    } else if (!selectedWorkflow.value) {
        const defaultWorkflow = props.workflows.find((w) => w.is_default);
        if (defaultWorkflow) {
            selectedWorkflow.value = defaultWorkflow;
        }
    }
});

const handleSubmit = (formData) => {
    const form = useForm({
        template_id: props.template.id,
        workflow_id: selectedWorkflow.value.id,
        field_data: formData,
    });

    form.post(store.url(), {
        onSuccess: () => {
            // Redirect handled by controller
        },
        onError: (errors) => {
            console.error('Validation errors:', errors);
        },
    });
};

const handleCancel = () => {
    router.visit(index.url());
};
</script>
