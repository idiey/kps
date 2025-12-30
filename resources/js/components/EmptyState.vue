<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Link } from '@inertiajs/vue3';
import type { Component } from 'vue';

interface Props {
  title: string;
  description?: string;
  icon?: Component;
  actionText?: string;
  actionHref?: string;
}

defineProps<Props>();

const emit = defineEmits<{
  action: [];
}>();
</script>

<template>
  <div class="flex flex-col items-center justify-center py-12 px-4 text-center">
    <component
      v-if="icon"
      :is="icon"
      class="h-16 w-16 text-muted-foreground mb-4 opacity-50"
    />

    <h3 class="text-lg font-semibold mb-2">{{ title }}</h3>

    <p v-if="description" class="text-muted-foreground mb-6 max-w-md">
      {{ description }}
    </p>

    <Button
      v-if="actionText && actionHref"
      as-child
    >
      <Link :href="actionHref">
        {{ actionText }}
      </Link>
    </Button>

    <Button
      v-else-if="actionText"
      @click="emit('action')"
    >
      {{ actionText }}
    </Button>
  </div>
</template>
