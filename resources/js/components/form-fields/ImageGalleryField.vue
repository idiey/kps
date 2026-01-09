<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { ref, computed, watch } from 'vue';
import { Plus, X, Image as ImageIcon } from 'lucide-vue-next';

interface GalleryImage {
    file?: File;
    preview: string;
    caption?: string;
}

const props = defineProps<{
    field: {
        name: string;
        code: string;
        metadata?: {
            max_items?: number;
        };
    };
    modelValue?: GalleryImage[];
    disabled?: boolean;
}>();

const emit = defineEmits<{
    (e: 'update:modelValue', value: GalleryImage[]): void;
}>();

const maxItems = computed(() => props.field.metadata?.max_items || 6);
const images = ref<GalleryImage[]>(props.modelValue || []);

watch(() => props.modelValue, (val) => {
    if (val) images.value = [...val];
}, { deep: true });

const handleFileSelect = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (!target.files?.length) return;
    
    const file = target.files[0];
    if (images.value.length >= maxItems.value) {
        alert(`Maksimum ${maxItems.value} gambar sahaja`);
        return;
    }
    
    const reader = new FileReader();
    reader.onload = (e) => {
        images.value.push({
            file,
            preview: e.target?.result as string,
            caption: '',
        });
        emit('update:modelValue', [...images.value]);
    };
    reader.readAsDataURL(file);
    
    // Reset input
    target.value = '';
};

const removeImage = (index: number) => {
    images.value.splice(index, 1);
    emit('update:modelValue', [...images.value]);
};
</script>

<template>
    <div class="image-gallery">
        <Label class="mb-2 block font-medium">{{ field.name }}</Label>
        <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
            <!-- Existing images -->
            <div
                v-for="(img, index) in images"
                :key="index"
                class="relative aspect-square rounded-lg border-2 border-dashed border-gray-200 overflow-hidden group"
            >
                <img
                    :src="img.preview"
                    class="w-full h-full object-cover"
                    alt="Uploaded image"
                />
                <Button
                    v-if="!disabled"
                    type="button"
                    variant="destructive"
                    size="icon"
                    class="absolute top-1 right-1 h-6 w-6 opacity-0 group-hover:opacity-100 transition-opacity"
                    @click="removeImage(index)"
                >
                    <X class="h-4 w-4" />
                </Button>
            </div>
            
            <!-- Add image button -->
            <label
                v-if="images.length < maxItems && !disabled"
                class="aspect-square rounded-lg border-2 border-dashed border-gray-300 flex flex-col items-center justify-center cursor-pointer hover:border-primary hover:bg-primary/5 transition-colors"
            >
                <Plus class="h-8 w-8 text-gray-400" />
                <span class="text-xs text-gray-500 mt-1">Tambah</span>
                <input
                    type="file"
                    accept="image/*"
                    class="hidden"
                    @change="handleFileSelect"
                />
            </label>
        </div>
        <p class="text-xs text-muted-foreground mt-2">
            {{ images.length }} / {{ maxItems }} gambar
        </p>
    </div>
</template>
