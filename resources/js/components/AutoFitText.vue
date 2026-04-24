<script setup lang="ts">
import { computed, nextTick, onBeforeUnmount, onMounted, ref, watch } from 'vue';

const props = withDefaults(
    defineProps<{
        text: string | number;
        minSize?: number;
        maxSize?: number;
        weightClass?: string;
        textClass?: string;
    }>(),
    {
        minSize: 20,
        maxSize: 40,
        weightClass: 'font-black',
        textClass: 'text-[#1b1b1b]',
    },
);

const hostRef = ref<HTMLElement | null>(null);
const textRef = ref<HTMLElement | null>(null);
const currentSize = ref(props.maxSize);

let resizeObserver: ResizeObserver | null = null;

const displayValue = computed(() => String(props.text ?? ''));

const fitToWidth = async () => {
    await nextTick();
    const host = hostRef.value;
    const text = textRef.value;
    if (!host || !text) return;
    
    let size = props.maxSize;
    text.style.fontSize = `${size}px`;

    while (text.scrollWidth > host.clientWidth && size > props.minSize) {
        size -= 1;
        text.style.fontSize = `${size}px`;
    }

    currentSize.value = size;
};

watch(
    () => [displayValue.value, props.minSize, props.maxSize],
    () => {
        void fitToWidth();
    },
);

onMounted(() => {
    resizeObserver = new ResizeObserver(() => {
        void fitToWidth();
    });
    if (hostRef.value) resizeObserver.observe(hostRef.value);
    void fitToWidth();
});

onBeforeUnmount(() => {
    if (resizeObserver) resizeObserver.disconnect();
    resizeObserver = null;
});
</script>

<template>
    <div ref="hostRef" class="min-w-0 w-full overflow-hidden">
        <p
            ref="textRef"
            :class="`${weightClass} ${textClass} w-full whitespace-nowrap leading-[1.02]`"
            :style="{ fontSize: `${currentSize}px`, fontFamily: 'Manrope, Inter, sans-serif' }"
        >
            {{ displayValue }}
        </p>
    </div>
</template>
