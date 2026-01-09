<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Switch } from '@/components/ui/switch';
import { useForm } from '@inertiajs/vue3';
import admin from '@/routes/admin';
import {
  GripVertical,
  Plus,
  Settings,
  Trash2,
  Type,
  List,
  LayoutGrid,
  PenTool,
  SeparatorHorizontal,
  Images,
  CheckSquare,
  Hash,
  Calendar,
  Image as ImageIcon,
} from 'lucide-vue-next';
import { ref, computed } from 'vue';

const props = defineProps<{
    template: {
        id: number;
        fields: Array<any>;
    };
}>();

const fields = ref([...props.template.fields]);
const selectedField = ref<any>(null);

const fieldTypes = [
    { type: 'text', label: 'Text Input', icon: Type },
    { type: 'textarea', label: 'Long Text', icon: List },
    { type: 'number', label: 'Number', icon: Hash },
    { type: 'select', label: 'Dropdown', icon: List },
    { type: 'checkbox', label: 'Checkbox', icon: CheckSquare },
    { type: 'date', label: 'Date', icon: Calendar },
    { type: 'file', label: 'File Upload', icon: ImageIcon },
    // Inspection form types
    { type: 'section_header', label: 'Section Header', icon: SeparatorHorizontal },
    { type: 'inspection_grid', label: 'Inspection Grid', icon: LayoutGrid },
    { type: 'checkbox_grid', label: 'Checkbox Grid', icon: LayoutGrid },
    { type: 'signature', label: 'Signature', icon: PenTool },
    { type: 'image_gallery', label: 'Image Gallery', icon: Images },
];

const addField = (type: string) => {
    const newField = {
        id: null, // New field
        template_id: props.template.id,
        // Match backend field_type_id mapping (simplified for UI, actual ID needed on save)
        // Using a temporary mapping or default ID 1 for now if needed, 
        // ideally we fetch types from backend.
        field_type_id: 1, // Placeholder
        type_code: type, 
        name: 'New ' + type,
        code: type.toUpperCase() + '_' + Date.now(),
        is_required: false,
        display_order: fields.value.length + 1,
    };
    fields.value.push(newField);
    selectedField.value = newField;
};

const removeField = (index: number) => {
    if (confirm('Delete this field?')) {
        const field = fields.value[index];
        if (field.id) {
            // Call backend destroy
            useForm({}).delete(admin.templates.fields.destroy.url({
                template: props.template.id,
                field: field.id
            }), {
                onSuccess: () => {
                    fields.value.splice(index, 1);
                    selectedField.value = null;
                }
            });
        } else {
            fields.value.splice(index, 1);
            selectedField.value = null;
        }
    }
};

const saveField = () => {
    if (!selectedField.value) return;

    const form = useForm({
        ...selectedField.value,
        // Ensure mapping to backend expectations
        field_type_id: getFieldTypeId(selectedField.value.type_code), 
    });

    if (selectedField.value.id) {
        form.put(admin.templates.fields.update.url({
            template: props.template.id,
            field: selectedField.value.id
        }), {
            onSuccess: () => {
                // Refresh list logic handled by Inertia reload usually
            }
        });
    } else {
        form.post(admin.templates.fields.store.url(props.template.id), {
            onSuccess: (page: any) => {
                 // ideally update fields.value with new ID
            }
        });
    }
};

// Hardcoded map for demo - in real app, fetch from `template_field_types`
const getFieldTypeId = (code: string) => {
    const map: Record<string, number> = {
        'text': 1, 'textarea': 2, 'number': 3, 'select': 4,
        'checkbox': 5, 'radio': 6, 'date': 7, 'datetime': 8, 'file': 9,
        'image': 10, 'calculated': 11, 'section_header': 12,
        'inspection_grid': 13, 'checkbox_grid': 14, 'signature': 15, 'image_gallery': 16
    };
    return map[code] || 1;
};

</script>

<template>
    <div class="flex h-full">
        <!-- Sidebar: Palette -->
        <div class="w-64 border-r bg-muted/10 p-4">
            <h3 class="mb-4 text-sm font-semibold text-muted-foreground uppercase tracking-wider">
                Tools
            </h3>
            <div class="grid gap-2">
                <Button
                    v-for="type in fieldTypes"
                    :key="type.type"
                    variant="outline"
                    class="justify-start"
                    @click="addField(type.type)"
                >
                    <component :is="type.icon" class="mr-2 h-4 w-4" />
                    {{ type.label }}
                </Button>
            </div>
        </div>

        <!-- Main Canvas -->
        <div class="flex-1 overflow-auto bg-muted/20 p-8">
            <div class="mx-auto max-w-2xl space-y-4">
                <div v-if="fields.length === 0" class="flex h-64 items-center justify-center rounded-lg border-2 border-dashed">
                    <p class="text-muted-foreground">Select a tool to add fields</p>
                </div>

                <Card
                    v-for="(field, index) in fields"
                    :key="index"
                    class="cursor-pointer transition-all hover:border-primary"
                    :class="{ 'ring-2 ring-primary': selectedField === field }"
                    @click="selectedField = field"
                >
                    <div class="flex items-center gap-4 p-4">
                        <GripVertical class="h-5 w-5 text-muted-foreground cursor-move" />
                        <div class="flex-1">
                            <Label>{{ field.name }} <span v-if="field.is_required" class="text-red-500">*</span></Label>
                            <Input v-if="['text', 'number', 'date'].includes(field.type_code || 'text')" disabled :placeholder="field.placeholder" />
                            <div v-else-if="field.type_code === 'textarea'" class="h-20 w-full rounded border bg-muted/10"></div>
                             <div v-else-if="field.type_code === 'select'" class="h-10 w-full rounded border bg-muted/10 flex items-center px-3 text-muted-foreground">Select...</div>
                             <div v-else-if="field.type_code === 'checkbox'" class="flex items-center"><div class="h-4 w-4 rounded border mr-2"></div> Option</div>
                        </div>
                        <Button variant="ghost" size="icon" class="text-destructive hover:bg-destructive/10" @click.stop="removeField(index)">
                            <Trash2 class="h-4 w-4" />
                        </Button>
                    </div>
                </Card>
            </div>
        </div>

        <!-- Right Sidebar: Properties -->
        <div class="w-80 border-l bg-background p-4 overflow-y-auto" v-if="selectedField">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="font-semibold">Properties</h3>
                <Button size="sm" @click="saveField">Save</Button>
            </div>

            <div class="space-y-4">
                <div class="grid gap-2">
                    <Label>Label</Label>
                    <Input v-model="selectedField.name" />
                </div>

                <div class="grid gap-2">
                    <Label>Field Code</Label>
                    <Input v-model="selectedField.code" />
                </div>

                <div class="grid gap-2">
                    <Label>Placeholder</Label>
                    <Input v-model="selectedField.placeholder" />
                </div>

                <div class="flex items-center justify-between rounded border p-3">
                    <Label>Required</Label>
                    <Switch v-model:checked="selectedField.is_required" />
                </div>

                <!-- Conditional Options for Select -->
                <div v-if="['select', 'radio', 'checkbox'].includes(selectedField.type_code)">
                    <Label class="mb-2 block">Options (JSON)</Label>
                    <textarea class="w-full rounded border p-2 text-xs font-mono" rows="5" v-model="selectedField.options"></textarea>
                    <p class="text-[10px] text-muted-foreground">Format: [{"label":"Yes","value":"yes"}]</p>
                </div>
            </div>
        </div>
    </div>
</template>
