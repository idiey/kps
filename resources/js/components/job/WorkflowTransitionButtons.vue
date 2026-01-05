<template>
    <div class="workflow-transitions">
        <h4 class="mb-3 text-sm font-medium text-gray-900">
            Available Actions
        </h4>

        <div class="flex flex-wrap gap-2">
            <button
                v-for="transition in availableTransitions"
                :key="transition.id"
                @click="handleTransition(transition)"
                :class="[
                    'inline-flex items-center rounded-md border px-4 py-2 text-sm font-medium shadow-sm focus:ring-2 focus:ring-offset-2 focus:outline-none',
                    getButtonClasses(transition),
                ]"
            >
                {{ transition.button_label || transition.name }}
            </button>
        </div>

        <p
            v-if="availableTransitions.length === 0"
            class="text-sm text-gray-500"
        >
            No actions available for the current status.
        </p>

        <!-- Transition Modal -->
        <TransitionModal
            v-if="showModal"
            :transition="selectedTransition"
            :job="job"
            @close="showModal = false"
            @confirm="executeTransition"
        />
    </div>
</template>

<script setup>
import { ref } from 'vue';
import TransitionModal from './TransitionModal.vue';

const props = defineProps({
    job: {
        type: Object,
        required: true,
    },
    availableTransitions: {
        type: Array,
        default: () => [],
    },
});

const emit = defineEmits(['transition']);

const showModal = ref(false);
const selectedTransition = ref(null);

const handleTransition = (transition) => {
    selectedTransition.value = transition;

    // Show modal if confirmation or comment is required
    if (transition.confirmation_message || transition.requires_comment) {
        showModal.value = true;
    } else {
        // Execute directly
        executeTransition({});
    }
};

const executeTransition = (data) => {
    emit('transition', {
        transition: selectedTransition.value,
        data,
    });
    showModal.value = false;
};

const getButtonClasses = (transition) => {
    const color = transition.button_color || 'blue';

    const colorClasses = {
        blue: 'border-transparent text-white bg-blue-600 hover:bg-blue-700 focus:ring-blue-500',
        green: 'border-transparent text-white bg-green-600 hover:bg-green-700 focus:ring-green-500',
        red: 'border-transparent text-white bg-red-600 hover:bg-red-700 focus:ring-red-500',
        yellow: 'border-transparent text-gray-900 bg-yellow-400 hover:bg-yellow-500 focus:ring-yellow-500',
        gray: 'border-gray-300 text-gray-700 bg-white hover:bg-gray-50 focus:ring-blue-500',
    };

    return colorClasses[color] || colorClasses.blue;
};
</script>

<style scoped>
.workflow-transitions {
    background: white;
    padding: 1rem;
    border-radius: 0.5rem;
    border: 1px solid #e5e7eb;
}
</style>
