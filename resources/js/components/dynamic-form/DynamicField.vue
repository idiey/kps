<template>
    <div v-show="isVisible" :class="fieldClasses">
        <label
            :for="field.code"
            class="mb-1 block text-sm font-medium text-gray-700"
        >
            {{ field.name }}
            <span v-if="isRequired" class="text-red-500">*</span>
        </label>

        <p v-if="field.description" class="mb-2 text-sm text-gray-500">
            {{ field.description }}
        </p>

        <!-- Render appropriate field type -->
        <component
            :is="fieldComponent"
            :id="field.code"
            :field="field"
            :model-value="modelValue"
            :errors="errors"
            @update:model-value="$emit('update:modelValue', $event)"
        />

        <!-- Help text -->
        <p v-if="field.help_text" class="mt-1 text-sm text-gray-500">
            {{ field.help_text }}
        </p>

        <!-- Error messages -->
        <div v-if="errors && errors.length > 0" class="mt-1">
            <p
                v-for="(error, index) in errors"
                :key="index"
                class="text-sm text-red-600"
            >
                {{ error }}
            </p>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import CalculatedField from './fields/CalculatedField.vue';
import CheckboxField from './fields/CheckboxField.vue';
import DateField from './fields/DateField.vue';
import DateTimeField from './fields/DateTimeField.vue';
import DropdownField from './fields/DropdownField.vue';
import FileUploadField from './fields/FileUploadField.vue';
import ImageUploadField from './fields/ImageUploadField.vue';
import MultiSelectField from './fields/MultiSelectField.vue';
import NumberField from './fields/NumberField.vue';
import RadioField from './fields/RadioField.vue';
import TextareaField from './fields/TextareaField.vue';
import TextField from './fields/TextField.vue';

const props = defineProps({
    field: {
        type: Object,
        required: true,
    },
    modelValue: {
        type: [String, Number, Boolean, Array, Object, Date],
        default: null,
    },
    errors: {
        type: Array,
        default: () => [],
    },
    allValues: {
        type: Object,
        default: () => ({}),
    },
    visibilityRules: {
        type: Object,
        default: () => ({}),
    },
    requirementRules: {
        type: Array,
        default: () => [],
    },
});

defineEmits(['update:modelValue']);

// Field component mapping
const fieldComponents = {
    text: TextField,
    number: NumberField,
    textarea: TextareaField,
    date: DateField,
    datetime: DateTimeField,
    dropdown: DropdownField,
    radio: RadioField,
    checkbox: CheckboxField,
    multiselect: MultiSelectField,
    file: FileUploadField,
    image: ImageUploadField,
    calculated: CalculatedField,
};

const fieldComponent = computed(() => {
    return fieldComponents[props.field.field_type] || TextField;
});

const isVisible = computed(() => {
    // Check visibility rules from server
    if (props.visibilityRules[props.field.code] !== undefined) {
        return props.visibilityRules[props.field.code];
    }

    // Evaluate conditional rules
    if (
        props.field.conditional_rules &&
        props.field.conditional_rules.length > 0
    ) {
        return props.field.conditional_rules.every((rule) => {
            const fieldValue = props.allValues[rule.field_code];
            return evaluateCondition(fieldValue, rule.operator, rule.value);
        });
    }

    return true;
});

const isRequired = computed(() => {
    return (
        props.field.is_required ||
        props.requirementRules.includes(props.field.code)
    );
});

const fieldClasses = computed(() => {
    const span = props.field.grid_column_span || 12;
    return `col-span-12 md:col-span-${span}`;
});

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
</script>
