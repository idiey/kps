import { reactive, readonly } from 'vue';

export type ToastVariant = 'success' | 'error' | 'warning' | 'info';

export interface Toast {
  id: string;
  title: string;
  description?: string;
  variant: ToastVariant;
  duration?: number;
}

interface ToastState {
  toasts: Toast[];
}

const state = reactive<ToastState>({
  toasts: [],
});

let toastCounter = 0;

const generateId = (): string => {
  return `toast-${Date.now()}-${toastCounter++}`;
};

export const useToast = () => {
  const addToast = (toast: Omit<Toast, 'id'>): string => {
    const id = generateId();
    const newToast: Toast = {
      id,
      duration: toast.duration ?? 5000,
      ...toast,
    };

    state.toasts.push(newToast);

    // Auto-remove toast after duration
    if (newToast.duration > 0) {
      setTimeout(() => {
        removeToast(id);
      }, newToast.duration);
    }

    return id;
  };

  const removeToast = (id: string): void => {
    const index = state.toasts.findIndex((t) => t.id === id);
    if (index > -1) {
      state.toasts.splice(index, 1);
    }
  };

  const success = (title: string, description?: string): string => {
    return addToast({ title, description, variant: 'success' });
  };

  const error = (title: string, description?: string): string => {
    return addToast({ title, description, variant: 'error' });
  };

  const warning = (title: string, description?: string): string => {
    return addToast({ title, description, variant: 'warning' });
  };

  const info = (title: string, description?: string): string => {
    return addToast({ title, description, variant: 'info' });
  };

  const clear = (): void => {
    state.toasts = [];
  };

  return {
    toasts: readonly(state.toasts),
    addToast,
    removeToast,
    success,
    error,
    warning,
    info,
    clear,
  };
};
