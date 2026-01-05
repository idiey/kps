<template>
    <div class="relative">
        <input
            :id="id"
            type="text"
            :value="displayValue"
            disabled
            class="block w-full rounded-md border-gray-300 bg-gray-50 text-gray-700 shadow-sm sm:text-sm"
        />
        <div
            class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3"
        >
            <svg
                class="h-5 w-5 text-gray-400"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"
                />
            </svg>
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

const displayValue = computed(() => {
    if (props.modelValue === null || props.modelValue === undefined) {
        return '';
    }

    // Format number if it's a calculated field
    if (typeof props.modelValue === 'number') {
        return props.modelValue.toFixed(2);
    }

    return props.modelValue;
});
</script>
