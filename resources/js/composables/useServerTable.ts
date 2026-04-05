import { router } from '@inertiajs/vue3';
import type { SortingState } from '@tanstack/vue-table';
import { ref, watch } from 'vue';

export interface ServerTableOptions {
    search?: string;
    sort_by?: string;
    sort_dir?: 'asc' | 'desc';
    [key: string]: string | undefined;
}

export function useServerTable(baseUrl: string, initial: ServerTableOptions = {}) {
    const globalFilter = ref<string>(initial.search ?? '');

    const sorting = ref<SortingState>(
        initial.sort_by
            ? [{ id: initial.sort_by, desc: initial.sort_dir === 'desc' }]
            : [],
    );

    const extraFilters = ref<Record<string, string | undefined>>(
        Object.fromEntries(
            Object.entries(initial).filter(([k]) => !['search', 'sort_by', 'sort_dir'].includes(k)),
        ) as Record<string, string | undefined>,
    );

    let debounceTimer: ReturnType<typeof setTimeout> | null = null;

    const buildParams = (page?: number): Record<string, string> => {
        const sort = sorting.value[0];
        const raw: Record<string, string | undefined> = {
            ...extraFilters.value,
            search: globalFilter.value || undefined,
            sort_by: sort?.id || undefined,
            sort_dir: sort ? (sort.desc ? 'desc' : 'asc') : undefined,
            page: page ? String(page) : undefined,
        };

        return Object.fromEntries(
            Object.entries(raw).filter(([, v]) => v !== undefined),
        ) as Record<string, string>;
    };

    const navigate = (page?: number) => {
        router.get(baseUrl, buildParams(page), { preserveState: true, preserveScroll: true });
    };

    // Debounce search input - 400 ms
    watch(globalFilter, () => {
        if (debounceTimer) clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => navigate(), 400);
    });

    watch(sorting, () => navigate(), { deep: true });

    const setFilter = (key: string, value: string | undefined) => {
        extraFilters.value = { ...extraFilters.value, [key]: value || undefined };
        navigate();
    };

    const goToPage = (page: number) => navigate(page);

    return { globalFilter, sorting, extraFilters, setFilter, goToPage };
}
