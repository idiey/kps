<template>
    <teleport to="body">
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div
                class="flex min-h-screen items-end justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0"
            >
                <!-- Background overlay -->
                <div
                    class="bg-opacity-75 fixed inset-0 bg-gray-500 transition-opacity"
                    @click="$emit('close')"
                />

                <!-- Modal panel -->
                <div
                    class="inline-block transform overflow-hidden rounded-lg bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:align-middle"
                >
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                :class="[
                                    'mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full sm:mx-0 sm:h-10 sm:w-10',
                                    getIconBgClass(),
                                ]"
                            >
                                <svg
                                    :class="['h-6 w-6', getIconColorClass()]"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                            </div>
                            <div
                                class="mt-3 flex-1 text-center sm:mt-0 sm:ml-4 sm:text-left"
                            >
                                <h3
                                    class="text-lg leading-6 font-medium text-gray-900"
                                >
                                    {{ transition.name }}
                                </h3>
                                <div class="mt-2">
                                    <p
                                        v-if="transition.confirmation_message"
                                        class="text-sm text-gray-500"
                                    >
                                        {{ transition.confirmation_message }}
                                    </p>

                                    <!-- Notes field (if required) -->
                                    <div
                                        v-if="transition.requires_comment"
                                        class="mt-4"
                                    >
                                        <label
                                            for="notes"
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            Notes
                                            <span class="text-red-500">*</span>
                                        </label>
                                        <textarea
                                            id="notes"
                                            v-model="notes"
                                            rows="3"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                            placeholder="Enter notes for this transition..."
                                        />
                                        <p
                                            v-if="errors.notes"
                                            class="mt-1 text-sm text-red-600"
                                        >
                                            {{ errors.notes }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6"
                    >
                        <button
                            type="button"
                            @click="handleConfirm"
                            :class="[
                                'inline-flex w-full justify-center rounded-md border border-transparent px-4 py-2 text-base font-medium text-white shadow-sm sm:ml-3 sm:w-auto sm:text-sm',
                                getConfirmButtonClass(),
                            ]"
                        >
                            Confirm
                        </button>
                        <button
                            type="button"
                            @click="$emit('close')"
                            class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-none sm:mt-0 sm:w-auto sm:text-sm"
                        >
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </teleport>
</template>

<script setup>
import { reactive, ref } from 'vue';

const props = defineProps({
    transition: {
        type: Object,
        required: true,
    },
    job: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(['close', 'confirm']);

const notes = ref('');
const errors = reactive({});

const handleConfirm = () => {
    errors.notes = '';

    // Validate notes if required
    if (props.transition.requires_comment && !notes.value.trim()) {
        errors.notes = 'Notes are required for this transition';
        return;
    }

    emit('confirm', {
        notes: notes.value,
    });
};

const getIconBgClass = () => {
    const color = props.transition.button_color || 'blue';
    return (
        {
            blue: 'bg-blue-100',
            green: 'bg-green-100',
            red: 'bg-red-100',
            yellow: 'bg-yellow-100',
            gray: 'bg-gray-100',
        }[color] || 'bg-blue-100'
    );
};

const getIconColorClass = () => {
    const color = props.transition.button_color || 'blue';
    return (
        {
            blue: 'text-blue-600',
            green: 'text-green-600',
            red: 'text-red-600',
            yellow: 'text-yellow-600',
            gray: 'text-gray-600',
        }[color] || 'text-blue-600'
    );
};

const getConfirmButtonClass = () => {
    const color = props.transition.button_color || 'blue';
    return (
        {
            blue: 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500',
            green: 'bg-green-600 hover:bg-green-700 focus:ring-green-500',
            red: 'bg-red-600 hover:bg-red-700 focus:ring-red-500',
            yellow: 'bg-yellow-600 hover:bg-yellow-700 focus:ring-yellow-500',
            gray: 'bg-gray-600 hover:bg-gray-700 focus:ring-gray-500',
        }[color] || 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500'
    );
};
</script>
