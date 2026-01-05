<template>
    <div class="workflow-timeline">
        <h3 class="mb-4 text-lg font-semibold text-gray-900">
            Workflow Progress
        </h3>

        <div class="flow-root">
            <ul role="list" class="-mb-8">
                <li
                    v-for="(status, index) in statuses"
                    :key="status.id"
                    class="relative"
                >
                    <!-- Connector line -->
                    <span
                        v-if="index !== statuses.length - 1"
                        class="absolute top-5 left-5 -ml-px h-full w-0.5"
                        :class="
                            isStatusCompleted(index)
                                ? 'bg-blue-600'
                                : 'bg-gray-300'
                        "
                        aria-hidden="true"
                    />

                    <div class="group relative flex items-start">
                        <!-- Status icon -->
                        <div class="flex items-center">
                            <div
                                :class="[
                                    'relative flex h-10 w-10 items-center justify-center rounded-full',
                                    isCurrentStatus(status)
                                        ? 'bg-blue-600 ring-8 ring-blue-100'
                                        : isStatusCompleted(index)
                                          ? 'bg-blue-600'
                                          : 'bg-gray-300',
                                ]"
                            >
                                <svg
                                    v-if="isStatusCompleted(index)"
                                    class="h-5 w-5 text-white"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                                <span
                                    v-else
                                    class="h-2.5 w-2.5 rounded-full"
                                    :class="
                                        isCurrentStatus(status)
                                            ? 'bg-white'
                                            : 'bg-white'
                                    "
                                />
                            </div>
                        </div>

                        <!-- Status details -->
                        <div class="ml-4 min-w-0 flex-1">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p
                                        :class="[
                                            'text-sm font-medium',
                                            isCurrentStatus(status)
                                                ? 'text-blue-600'
                                                : 'text-gray-900',
                                        ]"
                                    >
                                        {{ status.name }}
                                        <span
                                            v-if="isCurrentStatus(status)"
                                            class="ml-2 inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800"
                                        >
                                            Current
                                        </span>
                                    </p>
                                    <p
                                        v-if="status.description"
                                        class="mt-0.5 text-sm text-gray-500"
                                    >
                                        {{ status.description }}
                                    </p>
                                </div>
                                <div
                                    v-if="getStatusHistory(status)"
                                    class="text-sm text-gray-500"
                                >
                                    {{
                                        formatDate(
                                            getStatusHistory(status).changed_at,
                                        )
                                    }}
                                </div>
                            </div>

                            <!-- Status history note -->
                            <div
                                v-if="getStatusHistory(status)?.notes"
                                class="mt-2 rounded bg-gray-50 p-2 text-sm text-gray-600"
                            >
                                <p class="font-medium text-gray-700">Notes:</p>
                                <p>{{ getStatusHistory(status).notes }}</p>
                                <p class="mt-1 text-xs text-gray-500">
                                    by {{ getStatusHistory(status).user?.name }}
                                </p>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    job: {
        type: Object,
        required: true,
    },
    statuses: {
        type: Array,
        default: () => [],
    },
});

const currentStatusIndex = computed(() => {
    return props.statuses.findIndex(
        (s) => s.id === props.job.current_workflow_status_id,
    );
});

const isCurrentStatus = (status) => {
    return status.id === props.job.current_workflow_status_id;
};

const isStatusCompleted = (index) => {
    return index < currentStatusIndex.value;
};

const getStatusHistory = (status) => {
    if (!props.job.status_histories) return null;

    return props.job.status_histories.find(
        (h) => h.workflow_status_id === status.id,
    );
};

const formatDate = (date) => {
    if (!date) return '';
    return new Date(date).toLocaleDateString('en-MY', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};
</script>

<style scoped>
.workflow-timeline {
    background: white;
    padding: 1.5rem;
    border-radius: 0.5rem;
    border: 1px solid #e5e7eb;
}
</style>
