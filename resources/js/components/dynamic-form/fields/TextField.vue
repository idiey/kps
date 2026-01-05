<template>
    <input
        :id="id"
        type="text"
        :value="modelValue"
        :placeholder="field.placeholder"
        :disabled="field.is_readonly"
        @input="$emit('update:modelValue', $event.target.value)"
        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
    />
</template>

<script setup>
defineProps({
    id: String,
    field: Object,
    modelValue: [String, Number],
    errors: Array,
});

defineEmits(['update:modelValue']);
</script>
