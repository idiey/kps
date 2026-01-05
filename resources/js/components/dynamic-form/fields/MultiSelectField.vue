<template>
    <div class="space-y-2">
        <div
            v-for="option in options"
            :key="option.value"
            class="flex items-center"
        >
            <input
                :id="`${id}-${option.value}`"
                type="checkbox"
                :value="option.value"
                :checked="selectedValues.includes(option.value)"
                :disabled="field.is_readonly"
                @change="toggleOption(option.value)"
                class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
            />
            <label
                :for="`${id}-${option.value}`"
                class="ml-3 block text-sm font-medium text-gray-700"
            >
                {{ option.label }}
            </label>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    id: String,
    field: Object,
    modelValue: Array,
    errors: Array,
});

const emit = defineEmits(['update:modelValue']);

const selectedValues = computed(() => props.modelValue || []);

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

const toggleOption = (value) => {
    const current = selectedValues.value;
    const index = current.indexOf(value);

    if (index > -1) {
        emit(
            'update:modelValue',
            current.filter((v) => v !== value),
        );
    } else {
        emit('update:modelValue', [...current, value]);
    }
};
</script>
