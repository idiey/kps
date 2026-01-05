<template>
    <div class="space-y-2">
        <div
            v-for="option in options"
            :key="option.value"
            class="flex items-center"
        >
            <input
                :id="`${id}-${option.value}`"
                type="radio"
                :name="id"
                :value="option.value"
                :checked="modelValue === option.value"
                :disabled="field.is_readonly"
                @change="$emit('update:modelValue', option.value)"
                class="h-4 w-4 border-gray-300 text-blue-600 focus:ring-blue-500"
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
