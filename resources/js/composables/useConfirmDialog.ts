import { readonly, ref } from 'vue';

export interface ConfirmOptions {
    title: string;
    description?: string;
    confirmText?: string;
    cancelText?: string;
    variant?: 'default' | 'destructive';
}

interface ConfirmState {
    isOpen: boolean;
    options: ConfirmOptions;
    resolve: ((value: boolean) => void) | null;
}

const state = ref<ConfirmState>({
    isOpen: false,
    options: {
        title: '',
        confirmText: 'Confirm',
        cancelText: 'Cancel',
        variant: 'default',
    },
    resolve: null,
});

export const useConfirmDialog = () => {
    const confirm = (options: ConfirmOptions): Promise<boolean> => {
        return new Promise((resolve) => {
            state.value = {
                isOpen: true,
                options: {
                    confirmText: 'Confirm',
                    cancelText: 'Cancel',
                    variant: 'default',
                    ...options,
                },
                resolve,
            };
        });
    };

    const handleConfirm = (): void => {
        if (state.value.resolve) {
            state.value.resolve(true);
        }
        state.value.isOpen = false;
        state.value.resolve = null;
    };

    const handleCancel = (): void => {
        if (state.value.resolve) {
            state.value.resolve(false);
        }
        state.value.isOpen = false;
        state.value.resolve = null;
    };

    return {
        state: readonly(state),
        confirm,
        handleConfirm,
        handleCancel,
    };
};
