<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { ref, onMounted, watch } from 'vue';
import { Eraser, Check } from 'lucide-vue-next';

const props = defineProps<{
    field: {
        name: string;
        code: string;
        metadata?: {
            width?: number;
            height?: number;
        };
    };
    modelValue?: string;
    disabled?: boolean;
}>();

const emit = defineEmits<{
    (e: 'update:modelValue', value: string): void;
}>();

const canvasRef = ref<HTMLCanvasElement | null>(null);
const isDrawing = ref(false);
const ctx = ref<CanvasRenderingContext2D | null>(null);

const width = props.field.metadata?.width || 400;
const height = props.field.metadata?.height || 150;

onMounted(() => {
    if (canvasRef.value) {
        ctx.value = canvasRef.value.getContext('2d');
        if (ctx.value) {
            ctx.value.strokeStyle = '#1e40af';
            ctx.value.lineWidth = 2;
            ctx.value.lineCap = 'round';
            ctx.value.lineJoin = 'round';
        }
        // Load existing signature if provided
        if (props.modelValue) {
            const img = new Image();
            img.onload = () => {
                ctx.value?.drawImage(img, 0, 0);
            };
            img.src = props.modelValue;
        }
    }
});

const startDrawing = (e: MouseEvent | TouchEvent) => {
    if (props.disabled) return;
    isDrawing.value = true;
    const pos = getPosition(e);
    ctx.value?.beginPath();
    ctx.value?.moveTo(pos.x, pos.y);
};

const draw = (e: MouseEvent | TouchEvent) => {
    if (!isDrawing.value || props.disabled) return;
    const pos = getPosition(e);
    ctx.value?.lineTo(pos.x, pos.y);
    ctx.value?.stroke();
};

const stopDrawing = () => {
    if (isDrawing.value) {
        isDrawing.value = false;
        saveSignature();
    }
};

const getPosition = (e: MouseEvent | TouchEvent) => {
    const rect = canvasRef.value?.getBoundingClientRect();
    if (!rect) return { x: 0, y: 0 };
    
    if ('touches' in e) {
        return {
            x: e.touches[0].clientX - rect.left,
            y: e.touches[0].clientY - rect.top,
        };
    }
    return {
        x: e.clientX - rect.left,
        y: e.clientY - rect.top,
    };
};

const clearSignature = () => {
    if (ctx.value && canvasRef.value) {
        ctx.value.clearRect(0, 0, width, height);
        emit('update:modelValue', '');
    }
};

const saveSignature = () => {
    if (canvasRef.value) {
        const dataUrl = canvasRef.value.toDataURL('image/png');
        emit('update:modelValue', dataUrl);
    }
};
</script>

<template>
    <div class="signature-field">
        <Label class="mb-2 block font-medium">{{ field.name }}</Label>
        <div class="border rounded-lg bg-white p-2">
            <canvas
                ref="canvasRef"
                :width="width"
                :height="height"
                class="border border-dashed border-gray-300 rounded cursor-crosshair touch-none"
                :class="{ 'opacity-50 cursor-not-allowed': disabled }"
                @mousedown="startDrawing"
                @mousemove="draw"
                @mouseup="stopDrawing"
                @mouseleave="stopDrawing"
                @touchstart.prevent="startDrawing"
                @touchmove.prevent="draw"
                @touchend="stopDrawing"
            />
            <div class="flex gap-2 mt-2">
                <Button 
                    type="button" 
                    variant="outline" 
                    size="sm" 
                    @click="clearSignature"
                    :disabled="disabled"
                >
                    <Eraser class="h-4 w-4 mr-1" />
                    Padam
                </Button>
            </div>
        </div>
    </div>
</template>
