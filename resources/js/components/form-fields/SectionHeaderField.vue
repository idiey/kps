<script setup lang="ts">
defineProps<{
    field: {
        name: string;
        code: string;
        description?: string;
    };
}>();
</script>

<template>
    <div class="section-header py-4">
        <h3 class="text-lg font-semibold text-primary border-b-2 border-primary pb-2">
            {{ field.name }}
        </h3>
        <p v-if="field.description" class="text-sm text-muted-foreground mt-1">
            {{ field.description }}
        </p>
    </div>
</template>
