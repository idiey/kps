<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { useConfirmDialog } from '@/composables/useConfirmDialog';

const { state, handleConfirm, handleCancel } = useConfirmDialog();
</script>

<template>
    <Dialog :open="state.isOpen" @update:open="handleCancel">
        <DialogContent class="sm:max-w-md">
            <DialogHeader>
                <DialogTitle>{{ state.options.title }}</DialogTitle>
                <DialogDescription v-if="state.options.description">
                    {{ state.options.description }}
                </DialogDescription>
            </DialogHeader>

            <DialogFooter class="gap-2 sm:gap-0">
                <Button type="button" variant="outline" @click="handleCancel">
                    {{ state.options.cancelText }}
                </Button>
                <Button
                    type="button"
                    :variant="
                        state.options.variant === 'destructive'
                            ? 'destructive'
                            : 'default'
                    "
                    @click="handleConfirm"
                >
                    {{ state.options.confirmText }}
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
