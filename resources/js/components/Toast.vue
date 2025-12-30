<script setup lang="ts">
import { computed } from 'vue';
import { X, CheckCircle, XCircle, AlertTriangle, Info } from 'lucide-vue-next';
import type { ToastVariant } from '@/composables/useToast';

interface Props {
  title: string;
  description?: string;
  variant?: ToastVariant;
}

const props = withDefaults(defineProps<Props>(), {
  variant: 'info',
});

const emit = defineEmits<{
  close: [];
}>();

const variantClasses = computed(() => {
  const baseClasses = 'flex w-full gap-3 rounded-lg border p-4 shadow-lg transition-all';

  switch (props.variant) {
    case 'success':
      return `${baseClasses} border-green-200 bg-green-50 text-green-900 dark:border-green-800 dark:bg-green-950 dark:text-green-100`;
    case 'error':
      return `${baseClasses} border-red-200 bg-red-50 text-red-900 dark:border-red-800 dark:bg-red-950 dark:text-red-100`;
    case 'warning':
      return `${baseClasses} border-yellow-200 bg-yellow-50 text-yellow-900 dark:border-yellow-800 dark:bg-yellow-950 dark:text-yellow-100`;
    case 'info':
    default:
      return `${baseClasses} border-blue-200 bg-blue-50 text-blue-900 dark:border-blue-800 dark:bg-blue-950 dark:text-blue-100`;
  }
});

const Icon = computed(() => {
  switch (props.variant) {
    case 'success':
      return CheckCircle;
    case 'error':
      return XCircle;
    case 'warning':
      return AlertTriangle;
    case 'info':
    default:
      return Info;
  }
});

const iconClasses = computed(() => {
  switch (props.variant) {
    case 'success':
      return 'text-green-600 dark:text-green-400';
    case 'error':
      return 'text-red-600 dark:text-red-400';
    case 'warning':
      return 'text-yellow-600 dark:text-yellow-400';
    case 'info':
    default:
      return 'text-blue-600 dark:text-blue-400';
  }
});
</script>

<template>
  <div
    :class="variantClasses"
    role="alert"
    aria-live="assertive"
    aria-atomic="true"
  >
    <component :is="Icon" :class="iconClasses" class="h-5 w-5 shrink-0 mt-0.5" />

    <div class="flex-1 space-y-1">
      <h3 class="font-semibold text-sm">{{ title }}</h3>
      <p v-if="description" class="text-sm opacity-90">{{ description }}</p>
    </div>

    <button
      type="button"
      @click="emit('close')"
      class="shrink-0 rounded-md p-1 hover:bg-black/10 dark:hover:bg-white/10 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2"
      aria-label="Close notification"
    >
      <X class="h-4 w-4" />
    </button>
  </div>
</template>
