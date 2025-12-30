<script setup lang="ts">
import { onMounted, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { useToast } from '@/composables/useToast';
import Toast from '@/components/Toast.vue';

const { toasts, removeToast, success, error, warning, info } = useToast();
const page = usePage();

// Handle Inertia flash messages
onMounted(() => {
  const flash = page.props.flash as any;

  if (flash?.success) {
    success('Success', flash.success);
  }
  if (flash?.error) {
    error('Error', flash.error);
  }
  if (flash?.warning) {
    warning('Warning', flash.warning);
  }
  if (flash?.info) {
    info('Info', flash.info);
  }
});

// Watch for flash message changes
watch(
  () => page.props.flash,
  (flash: any) => {
    if (flash?.success) {
      success('Success', flash.success);
    }
    if (flash?.error) {
      error('Error', flash.error);
    }
    if (flash?.warning) {
      warning('Warning', flash.warning);
    }
    if (flash?.info) {
      info('Info', flash.info);
    }
  },
  { deep: true }
);
</script>

<template>
  <div
    aria-label="Notifications"
    class="pointer-events-none fixed top-0 right-0 z-50 flex max-h-screen w-full flex-col-reverse gap-2 p-4 sm:flex-col md:max-w-md"
  >
    <TransitionGroup
      name="toast"
      tag="div"
      class="flex flex-col gap-2"
    >
      <div
        v-for="toast in toasts"
        :key="toast.id"
        class="pointer-events-auto"
      >
        <Toast
          :title="toast.title"
          :description="toast.description"
          :variant="toast.variant"
          @close="removeToast(toast.id)"
        />
      </div>
    </TransitionGroup>
  </div>
</template>

<style scoped>
.toast-enter-active,
.toast-leave-active {
  transition: all 0.3s ease;
}

.toast-enter-from {
  opacity: 0;
  transform: translateX(100%);
}

.toast-leave-to {
  opacity: 0;
  transform: translateX(100%);
}
</style>
