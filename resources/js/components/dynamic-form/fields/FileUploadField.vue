<template>
    <div>
        <input
            :id="id"
            ref="fileInput"
            type="file"
            :accept="acceptedTypes"
            :disabled="field.is_readonly"
            @change="handleFileChange"
            class="block w-full text-sm text-gray-500 file:mr-4 file:rounded-md file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-blue-700 hover:file:bg-blue-100"
        />

        <div v-if="currentFile" class="mt-2 text-sm text-gray-600">
            Selected: {{ currentFile.name }} ({{
                formatFileSize(currentFile.size)
            }})
        </div>
    </div>
</template>

<script setup>
import { computed, ref } from 'vue';

const props = defineProps({
    id: String,
    field: Object,
    modelValue: [File, Object, String],
    errors: Array,
});

const emit = defineEmits(['update:modelValue']);

const fileInput = ref(null);
const currentFile = ref(null);

const acceptedTypes = computed(() => {
    return props.field.validation_rules?.allowed_types?.join(',') || '*';
});

const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        currentFile.value = file;
        emit('update:modelValue', file);
    }
};

const formatFileSize = (bytes) => {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round((bytes / Math.pow(k, i)) * 100) / 100 + ' ' + sizes[i];
};
</script>
