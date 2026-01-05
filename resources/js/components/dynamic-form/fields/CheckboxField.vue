<template>
    <div class="flex items-center">
        <input
            :id="id"
            type="checkbox"
            :checked="modelValue"
            :disabled="field.is_readonly"
            @change="$emit('update:modelValue', $event.target.checked)"
            class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
        />
        <label
            v-if="field.options?.label"
            :for="id"
            class="ml-3 block text-sm font-medium text-gray-700"
        >
            {{ field.options.label }}
        </label>
    </div>
</template>

<script setup>
defineProps({
    id: String,
    field: Object,
    modelValue: Boolean,
    errors: Array,
});

defineEmits(['update:modelValue']);
</script>
