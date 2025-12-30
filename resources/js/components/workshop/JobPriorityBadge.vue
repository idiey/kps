<script setup lang="ts">
import { computed } from 'vue';
import { Badge } from '@/components/ui/badge';
import { useJobStatus } from '@/composables/useJobStatus';
import type { JobPriority } from '@/types';
import { ArrowUp, ArrowDown, Minus } from 'lucide-vue-next';

interface Props {
  priority: JobPriority;
  showIcon?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  showIcon: false,
});

const { getPriorityConfig } = useJobStatus();

const config = computed(() => getPriorityConfig(props.priority));

const Icon = computed(() => {
  switch (props.priority) {
    case 'urgent':
    case 'high':
      return ArrowUp;
    case 'low':
      return ArrowDown;
    default:
      return Minus;
  }
});
</script>

<template>
  <Badge :class="[config.bgColor, config.textColor]" class="inline-flex items-center gap-1">
    <component v-if="showIcon" :is="Icon" class="h-3 w-3" />
    {{ config.label }}
  </Badge>
</template>
