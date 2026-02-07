<script setup lang="ts">
interface Props {
  mode: 'KEW_PA_10' | 'NORMAL'
  size?: 'sm' | 'md' | 'lg'
}

const props = withDefaults(defineProps<Props>(), {
  size: 'md'
})

const styles = {
  KEW_PA_10: {
    bg: 'bg-blue-100 dark:bg-blue-900/30',
    text: 'text-blue-800 dark:text-blue-200',
    ring: 'ring-blue-600/20 dark:ring-blue-400/20',
    icon: '📋',
    label: 'KEW.PA-10'
  },
  NORMAL: {
    bg: 'bg-emerald-100 dark:bg-emerald-900/30',
    text: 'text-emerald-800 dark:text-emerald-200',
    ring: 'ring-emerald-600/20 dark:ring-emerald-400/20',
    icon: '🔧',
    label: 'Normal Job'
  }
}

const sizeClasses = {
  sm: 'text-xs px-2 py-0.5',
  md: 'text-sm px-3 py-1',
  lg: 'text-base px-4 py-1.5'
}

const currentStyle = styles[props.mode]
const sizeClass = sizeClasses[props.size]
</script>

<template>
  <span
    :class="[
      currentStyle.bg,
      currentStyle.text,
      currentStyle.ring,
      sizeClass,
      'inline-flex items-center gap-1.5 font-semibold rounded-full ring-1 transition-all duration-200'
    ]"
  >
    <span class="text-base leading-none">{{ currentStyle.icon }}</span>
    <span>{{ currentStyle.label }}</span>
  </span>
</template>

<style scoped>
/* Smooth badge animations */
span {
  will-change: transform;
}
</style>
