<script setup lang="ts">
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Checkbox } from '@/components/ui/checkbox';
import { computed } from 'vue';

interface Field {
    id: number;
    code: string;
    name: string;
    type: string;
    value: any;
    help_text?: string;
    options?: string[] | null;
    is_required: boolean;
}

const props = defineProps<{
    fields: Field[];
    modelValue: Record<string, any>;
    readonly?: boolean;
}>();

const emit = defineEmits<{
    'update:modelValue': [value: Record<string, any>];
}>();

const updateValue = (code: string, value: any) => {
    emit('update:modelValue', {
        ...props.modelValue,
        [code]: value,
    });
};
</script>

<template>
    <div class="grid gap-6">
        <div v-for="field in fields" :key="field.code" class="space-y-2">
            <Label :for="field.code">
                {{ field.name }}
                <span v-if="field.is_required" class="text-red-500">*</span>
            </Label>

            <!-- Text Input -->
            <Input
                v-if="field.type === 'text'"
                :id="field.code"
                :model-value="modelValue[field.code]"
                @update:model-value="(v) => updateValue(field.code, v)"
                :disabled="readonly"
                :required="field.is_required"
            />

            <!-- Textarea -->
            <Textarea
                v-else-if="field.type === 'textarea'"
                :id="field.code"
                :model-value="modelValue[field.code]"
                @update:model-value="(v) => updateValue(field.code, v)"
                :disabled="readonly"
                :required="field.is_required"
            />

            <!-- Number Input -->
            <Input
                v-else-if="field.type === 'number'"
                type="number"
                :id="field.code"
                :model-value="modelValue[field.code]"
                @update:model-value="(v) => updateValue(field.code, v)"
                :disabled="readonly"
                :required="field.is_required"
            />

            <!-- Select -->
            <Select
                v-else-if="field.type === 'select'"
                :model-value="modelValue[field.code]"
                @update:model-value="(v) => updateValue(field.code, v)"
                :disabled="readonly"
            >
                <SelectTrigger :id="field.code">
                    <SelectValue :placeholder="`Select ${field.name}`" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem
                        v-for="option in field.options || []"
                        :key="option"
                        :value="option"
                    >
                        {{ option }}
                    </SelectItem>
                </SelectContent>
            </Select>

            <!-- Checkbox -->
            <div v-else-if="field.type === 'checkbox'" class="flex items-center space-x-2">
                <Checkbox
                    :id="field.code"
                    :checked="!!modelValue[field.code]"
                    @update:checked="(v) => updateValue(field.code, v)"
                    :disabled="readonly"
                />
                <label
                    :for="field.code"
                    class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                >
                    {{ field.help_text || field.name }}
                </label>
            </div>

             <!-- Date Input -->
            <Input
                v-else-if="field.type === 'date'"
                type="date"
                :id="field.code"
                :model-value="modelValue[field.code]"
                @update:model-value="(v) => updateValue(field.code, v)"
                :disabled="readonly"
                :required="field.is_required"
            />

             <!-- File Input (Just a text placeholder for now as generic implementation) -->
             <div v-else-if="field.type === 'file'" class="text-sm text-muted-foreground">
                Attachment upload not yet implemented.
             </div>

            <p v-if="field.help_text && field.type !== 'checkbox'" class="text-sm text-muted-foreground">
                {{ field.help_text }}
            </p>
        </div>
    </div>
</template>
