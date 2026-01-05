<template>
    <textarea
        :id="id"
        :value="modelValue"
        :placeholder="field.placeholder"
        :disabled="field.is_readonly"
        :rows="field.options?.rows || 4"
        @input="$emit('update:modelValue', $event.target.value)"
        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
    ></textarea>
</template>

<script setup>
defineProps({
    id: String,
    field: Object,
    modelValue: String,
    errors: Array,
});

defineEmits(['update:modelValue']);
</script>
