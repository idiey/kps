<script setup lang="ts">
import { Checkbox } from '@/components/ui/checkbox';
import { Label } from '@/components/ui/label';
import { computed, ref, watch } from 'vue';

const props = defineProps<{
    field: {
        name: string;
        code: string;
        options?: {
            rows?: string[];
            columns?: string[];
        };
    };
    modelValue?: Record<string, string[]>;
    disabled?: boolean;
}>();

const emit = defineEmits<{
    (e: 'update:modelValue', value: Record<string, string[]>): void;
}>();

const rows = computed(() => props.field.options?.rows || []);
const columns = computed(() => props.field.options?.columns || []);

const localData = ref<Record<string, string[]>>({});

watch(() => props.modelValue, (val) => {
    if (val) {
        localData.value = { ...val };
    }
}, { immediate: true });

const isChecked = (row: string, col: string) => {
    return localData.value[row]?.includes(col) || false;
};

const toggleCell = (row: string, col: string) => {
    if (props.disabled) return;
    
    if (!localData.value[row]) {
        localData.value[row] = [];
    }
    
    const index = localData.value[row].indexOf(col);
    if (index > -1) {
        localData.value[row].splice(index, 1);
    } else {
        localData.value[row].push(col);
    }
    
    emit('update:modelValue', { ...localData.value });
};
</script>

<template>
    <div class="checkbox-grid">
        <Label class="mb-2 block font-medium">{{ field.name }}</Label>
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr>
                        <th class="border p-2 bg-muted text-left"></th>
                        <th
                            v-for="col in columns"
                            :key="col"
                            class="border p-2 bg-muted text-center text-sm font-medium"
                        >
                            {{ col }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="row in rows" :key="row">
                        <td class="border p-2 font-medium text-sm">{{ row }}</td>
                        <td
                            v-for="col in columns"
                            :key="col"
                            class="border p-2 text-center"
                        >
                            <Checkbox
                                :checked="isChecked(row, col)"
                                @update:checked="toggleCell(row, col)"
                                :disabled="disabled"
                            />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
