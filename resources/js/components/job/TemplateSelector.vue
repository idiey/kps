<template>
    <div class="template-selector">
        <h3 class="mb-4 text-lg font-semibold text-gray-900">
            Select Job Template
        </h3>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <div
                v-for="template in templates"
                :key="template.id"
                @click="selectTemplate(template)"
                :class="[
                    'relative flex cursor-pointer rounded-lg border p-4 shadow-sm transition-all focus:outline-none',
                    modelValue?.id === template.id
                        ? 'border-blue-600 bg-blue-50 ring-2 ring-blue-600'
                        : 'border-gray-300 hover:border-gray-400 hover:shadow-md',
                ]"
            >
                <div class="flex flex-1 flex-col">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center">
                            <div
                                v-if="template.icon"
                                :class="[
                                    'flex-shrink-0 rounded-lg p-3',
                                    modelValue?.id === template.id
                                        ? 'bg-blue-100'
                                        : 'bg-gray-100',
                                ]"
                            >
                                <svg
                                    class="h-6 w-6"
                                    :class="
                                        modelValue?.id === template.id
                                            ? 'text-blue-600'
                                            : 'text-gray-600'
                                    "
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                                    />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <span
                                    class="block text-sm font-medium text-gray-900"
                                >
                                    {{ template.name }}
                                </span>
                            </div>
                        </div>
                        <svg
                            v-if="modelValue?.id === template.id"
                            class="h-5 w-5 flex-shrink-0 text-blue-600"
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
                    <p
                        v-if="template.description"
                        class="mt-2 text-sm text-gray-500"
                    >
                        {{ template.description }}
                    </p>
                </div>
            </div>
        </div>

        <div
            v-if="templates.length === 0"
            class="py-12 text-center text-gray-500"
        >
            <svg
                class="mx-auto h-12 w-12 text-gray-400"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                />
            </svg>
            <p class="mt-2">No templates available.</p>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    templates: {
        type: Array,
        required: true,
    },
    modelValue: {
        type: Object,
        default: null,
    },
});

const emit = defineEmits(['update:modelValue']);

const selectTemplate = (template) => {
    emit('update:modelValue', template);
};
</script>

<style scoped>
.template-selector {
    background: white;
    padding: 1.5rem;
    border-radius: 0.5rem;
    border: 1px solid #e5e7eb;
}
</style>
