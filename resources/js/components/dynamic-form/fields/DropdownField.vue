<template>
    <select
        :id="id"
        :value="modelValue"
        :disabled="field.is_readonly"
        @change="$emit('update:modelValue', $event.target.value)"
        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
    >
        <option value="">{{ field.placeholder || 'Select an option' }}</option>
        <option
            v-for="option in options"
            :key="option.value"
            :value="option.value"
        >
            {{ option.label }}
        </option>
    </select>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    id: String,
    field: Object,
    modelValue: [String, Number],
    errors: Array,
});

defineEmits(['update:modelValue']);

const options = computed(() => {
    if (props.field.options && Array.isArray(props.field.options)) {
        return props.field.options.map((opt) => {
            if (typeof opt === 'string') {
                return { value: opt, label: opt };
            }
            return opt;
        });
    }
    return [];
});
</script>
