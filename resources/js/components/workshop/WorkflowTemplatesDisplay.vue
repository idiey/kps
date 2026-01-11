<script setup lang="ts">
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { computed } from 'vue';

interface TemplateField {
    id: number;
    code: string;
    name: string;
    type: string;
    value: any;
    help_text?: string;
    options?: any;
    is_required: boolean;
    validation_rules?: any;
}

interface WorkflowTemplate {
    status_id: number;
    status_name: string;
    status_code: string;
    template_id: number;
    template_name: string;
    template_code: string;
    template_description?: string;
    is_current_status: boolean;
    fields_by_section: Record<string, TemplateField[]>;
}

interface Props {
    templates: WorkflowTemplate[];
}

const props = defineProps<Props>();

/**
 * Format field value based on field type
 */
const formatFieldValue = (field: TemplateField, value: any): string => {
    if (value === null || value === undefined || value === '') {
        return '-';
    }

    switch (field.type) {
        case 'date':
            return new Date(value).toLocaleDateString('en-MY', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
            });
        case 'datetime':
            return new Date(value).toLocaleString('en-MY', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
            });
        case 'number':
        case 'calculated':
            return typeof value === 'number' ? value.toFixed(2) : value;
        case 'checkbox':
            return value ? 'Yes' : 'No';
        case 'multiselect':
            return Array.isArray(value) ? value.join(', ') : value;
        default:
            return String(value);
    }
};

/**
 * Check if there are any templates to display
 */
const hasTemplates = computed(() => {
    return props.templates && props.templates.length > 0;
});
</script>

<template>
    <div v-if="hasTemplates" class="space-y-6">
        <div
            v-for="templateData in templates"
            :key="templateData.template_id"
            class="transition-shadow hover:shadow-md"
        >
            <Card>
                <CardHeader>
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <CardTitle class="text-lg">
                                {{ templateData.template_name }}
                            </CardTitle>
                            <div class="mt-1 flex items-center gap-2">
                                <p class="text-sm text-muted-foreground">
                                    Workflow Status:
                                    <span class="font-medium">{{
                                        templateData.status_name
                                    }}</span>
                                </p>
                                <Badge
                                    v-if="templateData.is_current_status"
                                    variant="default"
                                    class="ml-2"
                                >
                                    Current
                                </Badge>
                            </div>
                        </div>
                    </div>

                    <p
                        v-if="templateData.template_description"
                        class="mt-2 text-sm text-muted-foreground"
                    >
                        {{ templateData.template_description }}
                    </p>
                </CardHeader>

                <CardContent>
                    <!-- Fields organized by section -->
                    <div
                        v-for="(fields, sectionName) in templateData.fields_by_section"
                        :key="sectionName"
                        class="mb-6 last:mb-0"
                    >
                        <h4
                            v-if="sectionName !== 'default'"
                            class="mb-3 border-b pb-2 text-sm font-semibold text-foreground"
                        >
                            {{ sectionName }}
                        </h4>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div
                                v-for="field in fields"
                                :key="field.code"
                                class="space-y-1"
                            >
                                <label
                                    class="text-sm font-medium text-muted-foreground"
                                >
                                    {{ field.name }}
                                    <span
                                        v-if="field.is_required"
                                        class="text-destructive"
                                        >*</span
                                    >
                                </label>
                                <p class="text-sm text-foreground">
                                    {{ formatFieldValue(field, field.value) }}
                                </p>
                                <p
                                    v-if="field.help_text"
                                    class="text-xs text-muted-foreground"
                                >
                                    {{ field.help_text }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Empty state for sections -->
                    <div
                        v-if="
                            Object.keys(templateData.fields_by_section)
                                .length === 0
                        "
                        class="py-8 text-center text-sm text-muted-foreground"
                    >
                        No fields defined for this template
                    </div>
                </CardContent>
            </Card>
        </div>
    </div>

    <!-- Empty state when no templates -->
    <Card v-else>
        <CardContent class="py-8 text-center text-sm text-muted-foreground">
            No workflow forms attached to workflow statuses
        </CardContent>
    </Card>
</template>
