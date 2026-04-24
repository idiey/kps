<script setup lang="ts">
import type { Component } from "vue"
import type { SidebarMenuButtonProps } from "./SidebarMenuButtonChild.vue"
import { reactiveOmit } from "@vueuse/core"
import { TooltipRoot, TooltipTrigger, TooltipPortal, TooltipContent } from 'reka-ui'
import SidebarMenuButtonChild from "./SidebarMenuButtonChild.vue"

defineOptions({
  inheritAttrs: false,
})

const props = withDefaults(defineProps<SidebarMenuButtonProps & {
  tooltip?: string | Component
}>(), {
  as: "button",
  variant: "default",
  size: "default",
})

const delegatedProps = reactiveOmit(props, "tooltip")
</script>

<template>
  <SidebarMenuButtonChild v-if="!tooltip" v-bind="{ ...delegatedProps, ...$attrs }">
    <slot />
  </SidebarMenuButtonChild>

  <TooltipRoot v-else :delay-duration="100">
    <TooltipTrigger as-child>
      <SidebarMenuButtonChild v-bind="{ ...delegatedProps, ...$attrs }">
        <slot />
      </SidebarMenuButtonChild>
    </TooltipTrigger>
    <TooltipPortal>
      <TooltipContent
        side="right"
        align="center"
        :side-offset="8"
        class="z-50 ml-1 rounded-lg bg-[#171717] px-3 py-1.5 text-[11px] font-semibold uppercase tracking-[0.18em] text-white shadow-[0_8px_24px_rgba(0,0,0,0.22)] animate-in fade-in-0 zoom-in-95"
      >
        <template v-if="typeof tooltip === 'string'">
          {{ tooltip }}
        </template>
        <component :is="tooltip" v-else />
      </TooltipContent>
    </TooltipPortal>
  </TooltipRoot>
</template>
