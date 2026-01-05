<template>
    <div class="dynamic-form">
        <form @submit.prevent="handleSubmit">
            <!-- Render sections -->
            <div
                v-for="(section, index) in schema.sections"
                :key="index"
                class="form-section mb-6"
            >
                <h3
                    v-if="section.name !== 'default'"
                    class="mb-4 text-lg font-semibold text-gray-900"
                >
                    {{ section.name }}
                </h3>

                <div class="grid grid-cols-12 gap-4">
                    <DynamicField
                        v-for="field in section.fields"
                        :key="field.code"
                        :field="field"
                        :model-value="formData[field.code]"
                        :errors="errors[field.code]"
                        :all-values="formData"
                        :visibility-rules="visibilityRules"
                        :requirement-rules="requirementRules"
                        @update:model-value="
                            updateFieldValue(field.code, $event)
                        "
                    />
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 border-t pt-4">
                <button
                    type="button"
                    @click="$emit('cancel')"
                    class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                >
                    Cancel
                </button>
                <button
                    type="submit"
                    :disabled="isSubmitting"
                    class="rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 disabled:opacity-50"
                >
                    {{ isSubmitting ? 'Saving...' : submitLabel }}
                </button>
            </div>
        </form>
    </div>
</template>

<script setup>
import axios from 'axios';
import { onMounted, reactive, ref, watch } from 'vue';
import DynamicField from './DynamicField.vue';

const props = defineProps({
    schema: {
        type: Object,
        required: true,
    },
    initialData: {
        type: Object,
        default: () => ({}),
    },
    jobId: {
        type: Number,
        default: null,
    },
    submitLabel: {
        type: String,
        default: 'Save',
    },
});

const emit = defineEmits(['submit', 'cancel']);

const formData = reactive({ ...props.initialData });
const errors = reactive({});
const isSubmitting = ref(false);
const visibilityRules = ref({});
const requirementRules = ref([]);

// Initialize form data with default values
onMounted(() => {
    props.schema.sections.forEach((section) => {
        section.fields.forEach((field) => {
            if (
                field.value !== undefined &&
                formData[field.code] === undefined
            ) {
                formData[field.code] = field.value;
            } else if (
                field.default_value !== undefined &&
                formData[field.code] === undefined
            ) {
                formData[field.code] = field.default_value;
            }
        });
    });

    // Load field rules if editing existing job
    if (props.jobId) {
        loadFieldRules();
    }
});

// Update field value and recalculate dependencies
const updateFieldValue = (fieldCode, value) => {
    formData[fieldCode] = value;
    errors[fieldCode] = [];

    // Recalculate calculated fields
    recalculateFields();

    // Re-evaluate visibility rules
    evaluateVisibilityRules();
};

// Recalculate calculated fields
const recalculateFields = () => {
    props.schema.sections.forEach((section) => {
        section.fields.forEach((field) => {
            if (field.field_type === 'calculated' && field.formula) {
                try {
                    const result = evaluateFormula(field.formula, formData);
                    formData[field.code] = result;
                } catch (error) {
                    console.error(
                        `Error calculating field ${field.code}:`,
                        error,
                    );
                }
            }
        });
    });
};

// Evaluate formula (simple JavaScript expression evaluation)
const evaluateFormula = (formula, values) => {
    try {
        // Create a safe evaluation context
        const func = new Function(...Object.keys(values), `return ${formula}`);
        return func(...Object.values(values));
    } catch (error) {
        console.error('Formula evaluation error:', error);
        return 0;
    }
};

// Evaluate conditional visibility rules
const evaluateVisibilityRules = () => {
    props.schema.sections.forEach((section) => {
        section.fields.forEach((field) => {
            if (field.conditional_rules && field.conditional_rules.length > 0) {
                const isVisible = field.conditional_rules.every((rule) => {
                    const fieldValue = formData[rule.field_code];
                    return evaluateCondition(
                        fieldValue,
                        rule.operator,
                        rule.value,
                    );
                });

                visibilityRules.value[field.code] = isVisible;
            } else {
                visibilityRules.value[field.code] = true;
            }
        });
    });
};

// Evaluate condition
const evaluateCondition = (actual, operator, expected) => {
    switch (operator) {
        case '=':
        case '==':
            return actual == expected;
        case '!=':
            return actual != expected;
        case '>':
            return Number(actual) > Number(expected);
        case '>=':
            return Number(actual) >= Number(expected);
        case '<':
            return Number(actual) < Number(expected);
        case '<=':
            return Number(actual) <= Number(expected);
        case 'contains':
            return String(actual).includes(String(expected));
        case 'not_contains':
            return !String(actual).includes(String(expected));
        default:
            return true;
    }
};

// Load field rules from server (for existing jobs)
const loadFieldRules = async () => {
    try {
        const response = await axios.get(
            `/api/jobs/${props.jobId}/field-rules`,
        );
        visibilityRules.value = response.data.visibilityRules || {};
        requirementRules.value = response.data.requirementRules || [];
    } catch (error) {
        console.error('Error loading field rules:', error);
    }
};

// Validate form
const validateForm = () => {
    const newErrors = {};
    let hasErrors = false;

    props.schema.sections.forEach((section) => {
        section.fields.forEach((field) => {
            // Skip hidden fields
            if (visibilityRules.value[field.code] === false) {
                return;
            }

            const value = formData[field.code];
            const fieldErrors = [];

            // Check required
            const isRequired =
                field.is_required ||
                requirementRules.value.includes(field.code);
            if (
                isRequired &&
                (value === null || value === undefined || value === '')
            ) {
                fieldErrors.push(`${field.name} is required`);
            }

            // Validation rules
            if (field.validation_rules && value) {
                const rules = field.validation_rules;

                if (
                    rules.min !== undefined &&
                    String(value).length < rules.min
                ) {
                    fieldErrors.push(
                        `${field.name} must be at least ${rules.min} characters`,
                    );
                }

                if (
                    rules.max !== undefined &&
                    String(value).length > rules.max
                ) {
                    fieldErrors.push(
                        `${field.name} must not exceed ${rules.max} characters`,
                    );
                }

                if (rules.pattern && !new RegExp(rules.pattern).test(value)) {
                    fieldErrors.push(`${field.name} format is invalid`);
                }
            }

            if (fieldErrors.length > 0) {
                newErrors[field.code] = fieldErrors;
                hasErrors = true;
            }
        });
    });

    Object.assign(errors, newErrors);
    return !hasErrors;
};

// Handle form submission
const handleSubmit = async () => {
    if (!validateForm()) {
        return;
    }

    isSubmitting.value = true;

    try {
        emit('submit', { ...formData });
    } finally {
        isSubmitting.value = false;
    }
};

// Watch for initial data changes
watch(
    () => props.initialData,
    (newData) => {
        Object.assign(formData, newData);
        recalculateFields();
        evaluateVisibilityRules();
    },
    { deep: true },
);
</script>

<style scoped>
.form-section {
    background: white;
    padding: 1.5rem;
    border-radius: 0.5rem;
    border: 1px solid #e5e7eb;
}
</style>
