<template>
    <AppLayout :title="`Job #${job.id}`">
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <Link
                        :href="index.url()"
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
                    <h2
                        class="text-xl leading-tight font-semibold text-gray-800"
                    >
                        Job #{{ job.id }} - {{ job.title || 'Workshop Job' }}
                    </h2>
                </div>
                <div class="flex items-center gap-2">
                    <Link
                        v-if="canEdit"
                        :href="edit.url(job.id)"
                        class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                    >
                        Edit
                    </Link>
                    <button
                        v-if="canDelete"
                        @click="handleDelete"
                        class="rounded-md border border-red-300 bg-white px-4 py-2 text-sm font-medium text-red-700 hover:bg-red-50"
                    >
                        Delete
                    </button>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                    <!-- Main Content -->
                    <div class="space-y-6 lg:col-span-2">
                        <!-- Dynamic Fields Display - Show all workflow templates -->
                        <div
                            v-for="templateData in job.workflow_templates"
                            :key="templateData.template_id"
                            class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                        >
                            <div class="p-6">
                                <div class="mb-4 flex items-center justify-between">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">
                                            {{ templateData.template_name }}
                                        </h3>
                                        <p class="text-sm text-gray-500">
                                            Workflow Status: {{ templateData.status_name }}
                                            <span
                                                v-if="templateData.is_current_status"
                                                class="ml-2 inline-flex items-center rounded-full bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-800"
                                            >
                                                Current
                                            </span>
                                        </p>
                                    </div>
                                </div>

                                <p
                                    v-if="templateData.template_description"
                                    class="mb-4 text-sm text-gray-600"
                                >
                                    {{ templateData.template_description }}
                                </p>

                                <!-- Sections within this template -->
                                <div
                                    v-for="(
                                        fields, sectionName
                                    ) in templateData.fields_by_section"
                                    :key="sectionName"
                                    class="mb-6 last:mb-0"
                                >
                                    <h4
                                        v-if="sectionName !== 'default'"
                                        class="text-md mb-3 border-b pb-2 font-medium text-gray-700"
                                    >
                                        {{ sectionName }}
                                    </h4>

                                    <div
                                        class="grid grid-cols-1 gap-4 md:grid-cols-2"
                                    >
                                        <div
                                            v-for="field in fields"
                                            :key="field.code"
                                        >
                                            <label
                                                class="block text-sm font-medium text-gray-500"
                                            >
                                                {{ field.name }}
                                            </label>
                                            <p
                                                class="mt-1 text-sm text-gray-900"
                                            >
                                                {{
                                                    formatFieldValue(
                                                        field,
                                                        field.value,
                                                    )
                                                }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Show message if no templates found -->
                        <div
                            v-if="!job.workflow_templates || job.workflow_templates.length === 0"
                            class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                        >
                            <div class="p-6">
                                <p class="text-sm text-gray-500">
                                    No form templates attached to workflow statuses.
                                </p>
                            </div>
                        </div>

                        <!-- Notes Section -->
                        <div
                            class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                        >
                            <div class="p-6">
                                <h3
                                    class="mb-4 text-lg font-semibold text-gray-900"
                                >
                                    Notes
                                </h3>
                                <!-- Notes component would go here -->
                                <p class="text-sm text-gray-500">
                                    Notes feature coming soon...
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="space-y-6">
                        <!-- Workflow Progress (if using dynamic workflow) -->
                        <WorkflowStatusTimeline
                            v-if="job.workflow"
                            :job="job"
                            :statuses="job.workflow.statuses"
                        />

                        <!-- Workflow Actions -->
                        <WorkflowTransitionButtons
                            v-if="
                                job.workflow && availableTransitions.length > 0
                            "
                            :job="job"
                            :available-transitions="availableTransitions"
                            :required-template-id="job.current_workflow_status?.required_template_id"
                            @transition="handleTransition"
                        />

                        <!-- Job Meta Information -->
                        <div
                            class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                        >
                            <div class="p-6">
                                <h3
                                    class="mb-4 text-lg font-semibold text-gray-900"
                                >
                                    Information
                                </h3>

                                <dl class="space-y-3">
                                    <div>
                                        <dt
                                            class="text-sm font-medium text-gray-500"
                                        >
                                            Status
                                        </dt>
                                        <dd class="mt-1">
                                            <span
                                                v-if="
                                                    job.current_workflow_status
                                                "
                                                :class="[
                                                    'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium',
                                                    `bg-${job.current_workflow_status.color}-100 text-${job.current_workflow_status.color}-800`,
                                                ]"
                                            >
                                                {{
                                                    job.current_workflow_status
                                                        .name
                                                }}
                                            </span>
                                        </dd>
                                    </div>

                                    <div v-if="job.assigned_to_user">
                                        <dt
                                            class="text-sm font-medium text-gray-500"
                                        >
                                            Assigned To
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            {{ job.assigned_to_user.name }}
                                        </dd>
                                    </div>

                                    <div>
                                        <dt
                                            class="text-sm font-medium text-gray-500"
                                        >
                                            Created
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            {{ formatDateTime(job.created_at) }}
                                        </dd>
                                    </div>

                                    <div>
                                        <dt
                                            class="text-sm font-medium text-gray-500"
                                        >
                                            Last Updated
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            {{ formatDateTime(job.updated_at) }}
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import WorkflowStatusTimeline from '@/components/job/WorkflowStatusTimeline.vue';
import WorkflowTransitionButtons from '@/components/job/WorkflowTransitionButtons.vue';
import { destroy, edit, executeTransition, index } from '@/routes/jobs';
import { Link, router, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    job: {
        type: Object,
        required: true,
    },
    availableTransitions: {
        type: Array,
        default: () => [],
    },
    canEdit: {
        type: Boolean,
        default: false,
    },
    canDelete: {
        type: Boolean,
        default: false,
    },
});

// Group fields by section
const groupedFields = computed(() => {
    if (!props.job.template || !props.job.template.fields) {
        return [];
    }

    const sections = {};

    props.job.template.fields.forEach((field) => {
        const sectionName = field.section || 'default';

        if (!sections[sectionName]) {
            sections[sectionName] = {
                name: sectionName,
                fields: [],
            };
        }

        sections[sectionName].fields.push(field);
    });

    return Object.values(sections);
});

// Format field value for display
const formatFieldValue = (field, value) => {
    if (value === null || value === undefined || value === '') {
        return '-';
    }

    switch (field.field_type) {
        case 'date':
            return new Date(value).toLocaleDateString();
        case 'datetime':
            return new Date(value).toLocaleString();
        case 'number':
        case 'calculated':
            return typeof value === 'number' ? value.toFixed(2) : value;
        case 'checkbox':
            return value ? 'Yes' : 'No';
        case 'multiselect':
            return Array.isArray(value) ? value.join(', ') : value;
        default:
            return value;
    }
};

const formatDateTime = (dateString) => {
    if (!dateString) return '-';
    return new Date(dateString).toLocaleString('en-MY', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};

const handleTransition = ({ transition, data }) => {
    const form = useForm({
        notes: data.notes || null,
        field_data: data.field_data || {},
        metadata: data.metadata || {},
    });

    form.post(
        executeTransition.url({
            job: props.job.id,
            transition: transition.id,
        }),
        {
            onSuccess: () => {
                // Page will reload with updated job data
            },
            onError: (errors) => {
                console.error('Transition error:', errors);
            },
        },
    );
};

const handleDelete = () => {
    if (
        confirm(
            'Are you sure you want to delete this job? This action cannot be undone.',
        )
    ) {
        router.delete(destroy.url(props.job.id));
    }
};
</script>
