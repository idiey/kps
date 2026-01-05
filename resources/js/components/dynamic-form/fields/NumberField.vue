<template>
    <input
        :id="id"
        type="number"
        :value="modelValue"
        :placeholder="field.placeholder"
        :disabled="field.is_readonly"
        :min="field.validation_rules?.min"
        :max="field.validation_rules?.max"
        :step="field.validation_rules?.step || 'any'"
        @input="
            $emit('update:modelValue', parseFloat($event.target.value) || null)
        "
        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
    />
</template>

<script setup>
defineProps({
    id: String,
    field: Object,
    modelValue: Number,
    errors: Array,
});

defineEmits(['update:modelValue']);
</script>
