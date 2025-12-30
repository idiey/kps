<script setup lang="ts">
import { computed } from 'vue';
import { Badge } from '@/components/ui/badge';
import { useJobStatus } from '@/composables/useJobStatus';
import type { JobStatus } from '@/types';

interface Props {
  status: JobStatus;
}

const props = defineProps<Props>();

const { getStatusConfig } = useJobStatus();

const config = computed(() => getStatusConfig(props.status));
</script>

<template>
  <Badge :class="[config.bgColor, config.textColor]">
    {{ config.label }}
  </Badge>
</template>
