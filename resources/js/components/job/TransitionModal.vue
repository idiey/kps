<template>
    <teleport to="body">
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div
                class="flex min-h-screen items-end justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0"
            >
                <!-- Background overlay -->
                <div
                    class="bg-opacity-75 fixed inset-0 bg-gray-500 transition-opacity"
                    @click="$emit('close')"
                />

                <!-- Modal panel -->
                <div
                    :class="[
                        'inline-block transform overflow-hidden rounded-lg bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:align-middle',
                        hasRequiredForm ? 'sm:w-full sm:max-w-2xl' : 'sm:w-full sm:max-w-lg',
                    ]"
                >
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                :class="[
                                    'mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full sm:mx-0 sm:h-10 sm:w-10',
                                    getIconBgClass(),
                                ]"
                            >
                                <svg
                                    :class="['h-6 w-6', getIconColorClass()]"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                            </div>
                            <div
                                class="mt-3 flex-1 text-center sm:mt-0 sm:ml-4 sm:text-left"
                            >
                                <h3
                                    class="text-lg leading-6 font-medium text-gray-900"
                                >
                                    {{ transition.name }}
                                </h3>
                                <div class="mt-2">
                                    <p
                                        v-if="transition.confirmation_message"
                                        class="text-sm text-gray-500"
                                    >
                                        {{ transition.confirmation_message }}
                                    </p>

                                    <!-- Loading state for form schema -->
                                    <div
                                        v-if="hasRequiredForm && isLoadingSchema"
                                        class="mt-4 flex items-center justify-center py-8"
                                    >
                                        <svg
                                            class="h-8 w-8 animate-spin text-blue-600"
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                        >
                                            <circle
                                                class="opacity-25"
                                                cx="12"
                                                cy="12"
                                                r="10"
                                                stroke="currentColor"
                                                stroke-width="4"
                                            />
                                            <path
                                                class="opacity-75"
                                                fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                            />
                                        </svg>
                                        <span class="ml-2 text-gray-500"
                                            >Loading form...</span
                                        >
                                    </div>

                                    <!-- Dynamic Form -->
                                    <div
                                        v-else-if="hasRequiredForm && formSchema"
                                        class="mt-4 max-h-96 overflow-y-auto"
                                    >
                                        <div class="mb-4 rounded-md bg-blue-50 p-3">
                                            <p class="text-sm text-blue-700">
                                                Please complete the required form
                                                before proceeding with this
                                                transition.
                                            </p>
                                        </div>
                                        <DynamicFormRenderer
                                            :schema="formSchema"
                                            :initial-data="{}"
                                            :job-id="job.id"
                                            submit-label="Continue"
                                            @submit="handleFormSubmit"
                                            @cancel="$emit('close')"
                                        />
                                    </div>

                                    <!-- Notes field (if required and no form) -->
                                    <div
                                        v-else-if="transition.requires_comment"
                                        class="mt-4"
                                    >
                                        <label
                                            for="notes"
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            Notes
                                            <span class="text-red-500">*</span>
                                        </label>
                                        <textarea
                                            id="notes"
                                            v-model="notes"
                                            rows="3"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                            placeholder="Enter notes for this transition..."
                                        />
                                        <p
                                            v-if="errors.notes"
                                            class="mt-1 text-sm text-red-600"
                                        >
                                            {{ errors.notes }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer buttons (only shown when no dynamic form is rendered) -->
                    <div
                        v-if="!hasRequiredForm || !formSchema"
                        class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6"
                    >
                        <button
                            type="button"
                            @click="handleConfirm"
                            :class="[
                                'inline-flex w-full justify-center rounded-md border border-transparent px-4 py-2 text-base font-medium text-white shadow-sm sm:ml-3 sm:w-auto sm:text-sm',
                                getConfirmButtonClass(),
                            ]"
                        >
                            Confirm
                        </button>
                        <button
                            type="button"
                            @click="$emit('close')"
                            class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-none sm:mt-0 sm:w-auto sm:text-sm"
                        >
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </teleport>
</template>

<script setup>
import axios from 'axios';
import { computed, onMounted, reactive, ref } from 'vue';
import DynamicFormRenderer from '../dynamic-form/DynamicFormRenderer.vue';

const props = defineProps({
    transition: {
        type: Object,
        required: true,
    },
    job: {
        type: Object,
        required: true,
    },
    requiredTemplateId: {
        type: Number,
        default: null,
    },
});

const emit = defineEmits(['close', 'confirm']);

const notes = ref('');
const errors = reactive({});
const formSchema = ref(null);
const isLoadingSchema = ref(false);

// Check if a form is required for this transition
const hasRequiredForm = computed(() => props.requiredTemplateId !== null);

// Load form schema on mount if template is required
onMounted(async () => {
    if (hasRequiredForm.value) {
        await loadFormSchema();
    }
});

/**
 * Load the form schema for the required template
 */
const loadFormSchema = async () => {
    isLoadingSchema.value = true;
    try {
        const response = await axios.get(
            `/api/templates/${props.requiredTemplateId}/schema`,
            {
                params: { job_id: props.job.id },
            },
        );
        formSchema.value = response.data;
    } catch (error) {
        console.error('Error loading form schema:', error);
        // If schema load fails, allow transition without form
        formSchema.value = null;
    } finally {
        isLoadingSchema.value = false;
    }
};

/**
 * Handle form submission from DynamicFormRenderer
 */
const handleFormSubmit = (formData) => {
    emit('confirm', {
        notes: notes.value,
        field_data: formData,
    });
};

/**
 * Handle confirm button click (for transitions without form)
 */
const handleConfirm = () => {
    errors.notes = '';

    // Validate notes if required
    if (props.transition.requires_comment && !notes.value.trim()) {
        errors.notes = 'Notes are required for this transition';
        return;
    }

    emit('confirm', {
        notes: notes.value,
        field_data: {},
    });
};

const getIconBgClass = () => {
    const color = props.transition.button_color || 'blue';
    return (
        {
            blue: 'bg-blue-100',
            green: 'bg-green-100',
            red: 'bg-red-100',
            yellow: 'bg-yellow-100',
            gray: 'bg-gray-100',
        }[color] || 'bg-blue-100'
    );
};

const getIconColorClass = () => {
    const color = props.transition.button_color || 'blue';
    return (
        {
            blue: 'text-blue-600',
            green: 'text-green-600',
            red: 'text-red-600',
            yellow: 'text-yellow-600',
            gray: 'text-gray-600',
        }[color] || 'text-blue-600'
    );
};

const getConfirmButtonClass = () => {
    const color = props.transition.button_color || 'blue';
    return (
        {
            blue: 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500',
            green: 'bg-green-600 hover:bg-green-700 focus:ring-green-500',
            red: 'bg-red-600 hover:bg-red-700 focus:ring-red-500',
            yellow: 'bg-yellow-600 hover:bg-yellow-700 focus:ring-yellow-500',
            gray: 'bg-gray-600 hover:bg-gray-700 focus:ring-gray-500',
        }[color] || 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500'
    );
};
</script>
