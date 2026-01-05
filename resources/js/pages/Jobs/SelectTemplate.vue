<template>
    <AppLayout title="Create Job - Select Template">
        <template #header>
            <h2 class="text-xl leading-tight font-semibold text-gray-800">
                Create Job - Select Template
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <TemplateSelector
                            :templates="templates"
                            v-model="selectedTemplate"
                        />

                        <div class="mt-6 flex items-center justify-end gap-3">
                            <Link
                                :href="index.url()"
                                class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                            >
                                Cancel
                            </Link>
                            <button
                                @click="continueToForm"
                                :disabled="!selectedTemplate"
                                class="rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50"
                            >
                                Continue
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import TemplateSelector from '@/components/job/TemplateSelector.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    templates: {
        type: Array,
        required: true,
    },
});

const selectedTemplate = ref(null);

const continueToForm = () => {
    if (selectedTemplate.value) {
        router.visit(
            createDynamic.url({ template: selectedTemplate.value.id }),
        );
    }
};
</script>
