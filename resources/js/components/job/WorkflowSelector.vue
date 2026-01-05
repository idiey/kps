<template>
    <div class="workflow-selector">
        <h3 class="mb-4 text-lg font-semibold text-gray-900">
            Choose Workflow
        </h3>

        <p v-if="template" class="mb-4 text-sm text-gray-600">
            Select the workflow for your
            <strong>{{ template.name }}</strong> job.
        </p>

        <div class="space-y-3">
            <div
                v-for="workflow in workflows"
                :key="workflow.id"
                @click="selectWorkflow(workflow)"
                :class="[
                    'relative flex cursor-pointer rounded-lg border p-4 shadow-sm focus:outline-none',
                    modelValue?.id === workflow.id
                        ? 'border-blue-600 ring-2 ring-blue-600'
                        : 'border-gray-300 hover:border-gray-400',
                ]"
            >
                <div class="flex flex-1">
                    <div class="flex flex-1 flex-col">
                        <div class="flex items-center justify-between">
                            <span
                                class="block text-sm font-medium text-gray-900"
                            >
                                {{ workflow.name }}
                                <span
                                    v-if="workflow.is_default"
                                    class="ml-2 inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800"
                                >
                                    Default
                                </span>
                            </span>
                            <svg
                                v-if="modelValue?.id === workflow.id"
                                class="h-5 w-5 text-blue-600"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </div>
                        <span
                            v-if="workflow.description"
                            class="mt-1 block text-sm text-gray-500"
                        >
                            {{ workflow.description }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div
            v-if="workflows.length === 0"
            class="py-8 text-center text-gray-500"
        >
            No workflows available for this template.
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    workflows: {
        type: Array,
        required: true,
    },
    template: {
        type: Object,
        default: null,
    },
    modelValue: {
        type: Object,
        default: null,
    },
});

const emit = defineEmits(['update:modelValue']);

const selectWorkflow = (workflow) => {
    emit('update:modelValue', workflow);
};
</script>

<style scoped>
.workflow-selector {
    background: white;
    padding: 1.5rem;
    border-radius: 0.5rem;
    border: 1px solid #e5e7eb;
}
</style>
