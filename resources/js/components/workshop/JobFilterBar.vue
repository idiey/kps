<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import type { JobFilters } from '@/composables/useJobFilters';
import { useJobStatus } from '@/composables/useJobStatus';
import type { User } from '@/types';
import { Filter, Search, X } from 'lucide-vue-next';
import { computed } from 'vue';

interface Props {
    filters: JobFilters;
    technicians?: User[];
    loading?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    loading: false,
});

const emit = defineEmits<{
    'update:filters': [filters: JobFilters];
    apply: [];
    clear: [];
}>();

const { getAllStatuses, getAllPriorities } = useJobStatus();

const hasActiveFilters = computed(() => {
    return Object.values(props.filters).some(
        (value) => value !== undefined && value !== null && value !== '',
    );
});

const updateFilter = <K extends keyof JobFilters>(
    key: K,
    value: JobFilters[K],
) => {
    emit('update:filters', { ...props.filters, [key]: value });
};
</script>

<template>
    <div class="space-y-4 rounded-lg border bg-card p-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <Filter class="h-4 w-4 text-muted-foreground" />
                <h3 class="font-semibold">Filters</h3>
            </div>
            <Button
                v-if="hasActiveFilters"
                variant="ghost"
                size="sm"
                @click="emit('clear')"
                :disabled="loading"
            >
                <X class="mr-1 h-4 w-4" />
                Clear
            </Button>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            <div class="space-y-2">
                <Label for="search">Search</Label>
                <div class="relative">
                    <Search
                        class="absolute top-2.5 left-2.5 h-4 w-4 text-muted-foreground"
                    />
                    <Input
                        id="search"
                        type="text"
                        placeholder="Job number, description..."
                        class="pl-8"
                        :model-value="filters.search"
                        @update:model-value="updateFilter('search', $event)"
                        @keyup.enter="emit('apply')"
                    />
                </div>
            </div>

            <div class="space-y-2">
                <Label for="status">Status</Label>
                <Select
                    :model-value="filters.status"
                    @update:model-value="updateFilter('status', $event)"
                >
                    <SelectTrigger id="status">
                        <SelectValue placeholder="All statuses" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem :value="undefined">All statuses</SelectItem>
                        <SelectItem
                            v-for="status in getAllStatuses()"
                            :key="status.value"
                            :value="status.value"
                        >
                            {{ status.label }}
                        </SelectItem>
                    </SelectContent>
                </Select>
            </div>

            <div class="space-y-2">
                <Label for="priority">Priority</Label>
                <Select
                    :model-value="filters.priority"
                    @update:model-value="updateFilter('priority', $event)"
                >
                    <SelectTrigger id="priority">
                        <SelectValue placeholder="All priorities" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem :value="undefined"
                            >All priorities</SelectItem
                        >
                        <SelectItem
                            v-for="priority in getAllPriorities()"
                            :key="priority.value"
                            :value="priority.value"
                        >
                            {{ priority.label }}
                        </SelectItem>
                    </SelectContent>
                </Select>
            </div>

            <div v-if="technicians" class="space-y-2">
                <Label for="assigned_to">Assigned To</Label>
                <Select
                    :model-value="filters.assigned_to?.toString()"
                    @update:model-value="
                        updateFilter(
                            'assigned_to',
                            $event ? parseInt($event) : undefined,
                        )
                    "
                >
                    <SelectTrigger id="assigned_to">
                        <SelectValue placeholder="All technicians" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem :value="undefined"
                            >All technicians</SelectItem
                        >
                        <SelectItem
                            v-for="tech in technicians"
                            :key="tech.id"
                            :value="tech.id.toString()"
                        >
                            {{ tech.name }}
                        </SelectItem>
                    </SelectContent>
                </Select>
            </div>

            <div class="space-y-2">
                <Label for="date_from">From Date</Label>
                <Input
                    id="date_from"
                    type="date"
                    :model-value="filters.date_from"
                    @update:model-value="updateFilter('date_from', $event)"
                />
            </div>

            <div class="space-y-2">
                <Label for="date_to">To Date</Label>
                <Input
                    id="date_to"
                    type="date"
                    :model-value="filters.date_to"
                    @update:model-value="updateFilter('date_to', $event)"
                />
            </div>
        </div>

        <div class="flex justify-end">
            <Button @click="emit('apply')" :disabled="loading">
                Apply Filters
            </Button>
        </div>
    </div>
</template>
