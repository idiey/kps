<script setup lang="ts">
import type { SelectLabelProps } from "reka-ui"
import type { HTMLAttributes } from "vue"
import { SelectLabel, useForwardProps } from "reka-ui"
import { cn } from "@/lib/utils"

const props = defineProps<SelectLabelProps & { class?: HTMLAttributes["class"] }>()

const delegatedProps = computed(() => {
  const { class: _, ...delegated } = props
  return delegated
})

const forwardedProps = useForwardProps(delegatedProps)
</script>

<template>
  <SelectLabel
    v-bind="forwardedProps"
    :class="cn('py-1.5 pl-8 pr-2 text-sm font-semibold', props.class)"
  >
    <slot />
  </SelectLabel>
</template>
