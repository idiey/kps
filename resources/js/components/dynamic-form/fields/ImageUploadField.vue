<template>
    <div>
        <input
            :id="id"
            ref="fileInput"
            type="file"
            accept="image/*"
            :disabled="field.is_readonly"
            @change="handleFileChange"
            class="block w-full text-sm text-gray-500 file:mr-4 file:rounded-md file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-blue-700 hover:file:bg-blue-100"
        />

        <div v-if="previewUrl" class="mt-3">
            <img
                :src="previewUrl"
                alt="Preview"
                class="max-w-xs rounded-lg border border-gray-300"
            />
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
    id: String,
    field: Object,
    modelValue: [File, Object, String],
    errors: Array,
});

const emit = defineEmits(['update:modelValue']);

const fileInput = ref(null);
const previewUrl = ref(null);

const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        emit('update:modelValue', file);

        // Create preview
        const reader = new FileReader();
        reader.onload = (e) => {
            previewUrl.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

// Watch for existing image URLs
watch(
    () => props.modelValue,
    (newValue) => {
        if (typeof newValue === 'string') {
            previewUrl.value = newValue;
        }
    },
    { immediate: true },
);
</script>
