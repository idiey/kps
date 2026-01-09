<script setup lang="ts">
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { computed, ref, watch } from 'vue';

interface InspectionItem {
    name: string;
    status: 'ok' | 'perlu' | 'segera' | null;
    notes: string;
}

const props = defineProps<{
    field: {
        name: string;
        code: string;
        options?: {
            columns?: string[];
            items?: string[];
            has_notes?: boolean;
        };
    };
    modelValue?: Record<string, InspectionItem>;
    disabled?: boolean;
}>();

const emit = defineEmits<{
    (e: 'update:modelValue', value: Record<string, InspectionItem>): void;
}>();

const columns = computed(() => props.field.options?.columns || ['OK', 'Perlu Perhatian', 'Perhatian Segera']);
const items = computed(() => props.field.options?.items || []);
const hasNotes = computed(() => props.field.options?.has_notes !== false);

const localData = ref<Record<string, InspectionItem>>({});

// Initialize from modelValue or defaults
watch(() => props.modelValue, (val) => {
    if (val) {
        localData.value = { ...val };
    } else {
        items.value.forEach(item => {
            localData.value[item] = { name: item, status: null, notes: '' };
        });
    }
}, { immediate: true });

const updateStatus = (itemName: string, status: 'ok' | 'perlu' | 'segera') => {
    if (!localData.value[itemName]) {
        localData.value[itemName] = { name: itemName, status: null, notes: '' };
    }
    localData.value[itemName].status = status;
    emit('update:modelValue', { ...localData.value });
};

const updateNotes = (itemName: string, notes: string) => {
    if (!localData.value[itemName]) {
        localData.value[itemName] = { name: itemName, status: null, notes: '' };
    }
    localData.value[itemName].notes = notes;
    emit('update:modelValue', { ...localData.value });
};

const isChecked = (itemName: string, status: 'ok' | 'perlu' | 'segera') => {
    return localData.value[itemName]?.status === status;
};
</script>

<template>
    <div class="inspection-grid">
        <Label class="mb-2 block font-medium">{{ field.name }}</Label>
        <Table>
            <TableHeader>
                <TableRow>
                    <TableHead class="w-[200px]">Item</TableHead>
                    <TableHead v-for="col in columns" :key="col" class="text-center w-[100px]">
                        {{ col }}
                    </TableHead>
                    <TableHead v-if="hasNotes">Nota</TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                <TableRow v-for="(item, index) in items" :key="item">
                    <TableCell class="font-medium">
                        {{ index + 1 }}. {{ item }}
                    </TableCell>
                    <TableCell class="text-center">
                        <Checkbox
                            :checked="isChecked(item, 'ok')"
                            @update:checked="updateStatus(item, 'ok')"
                            :disabled="disabled"
                        />
                    </TableCell>
                    <TableCell class="text-center">
                        <Checkbox
                            :checked="isChecked(item, 'perlu')"
                            @update:checked="updateStatus(item, 'perlu')"
                            :disabled="disabled"
                        />
                    </TableCell>
                    <TableCell class="text-center">
                        <Checkbox
                            :checked="isChecked(item, 'segera')"
                            @update:checked="updateStatus(item, 'segera')"
                            :disabled="disabled"
                        />
                    </TableCell>
                    <TableCell v-if="hasNotes">
                        <Input
                            :model-value="localData[item]?.notes || ''"
                            @update:model-value="(val) => updateNotes(item, val as string)"
                            placeholder="Nota..."
                            :disabled="disabled"
                            class="h-8"
                        />
                    </TableCell>
                </TableRow>
            </TableBody>
        </Table>
    </div>
</template>
