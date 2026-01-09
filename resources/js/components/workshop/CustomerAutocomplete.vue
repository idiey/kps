<script setup lang="ts">
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import type { Customer } from '@/types';
import { router } from '@inertiajs/vue3';
import { Search, X } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';

interface Props {
    modelValue?: number | null;
    label?: string;
    placeholder?: string;
    error?: string;
    required?: boolean;
    disabled?: boolean;
    customers?: Customer[]; // Pre-loaded customers list
}

const props = withDefaults(defineProps<Props>(), {
    modelValue: null,
    label: 'Customer',
    placeholder: 'Search or select customer...',
    required: false,
    disabled: false,
    customers: () => [],
});

const emit = defineEmits<{
    'update:modelValue': [value: number | null];
}>();

const searchQuery = ref('');
const isSearching = ref(false);
const showResults = ref(false);
const fetchedCustomers = ref<Customer[]>([]);
const selectedCustomer = ref<Customer | null>(null);

// Combine prop customers with fetched ones, filter by search
const displayCustomers = computed(() => {
    const allCustomers = props.customers.length > 0 ? props.customers : fetchedCustomers.value;
    
    if (!searchQuery.value || searchQuery.value.length < 2) {
        return allCustomers;
    }
    
    const query = searchQuery.value.toLowerCase();
    return allCustomers.filter(c => 
        c.name.toLowerCase().includes(query) ||
        c.email?.toLowerCase().includes(query) ||
        c.phone?.toLowerCase().includes(query)
    );
});

const searchCustomers = () => {
    // If we have pre-loaded customers, just filter locally
    if (props.customers.length > 0) {
        showResults.value = true;
        return;
    }
    
    // Otherwise fetch from server
    if (searchQuery.value.length < 2) {
        fetchedCustomers.value = [];
        showResults.value = false;
        return;
    }

    isSearching.value = true;

    router.get(
        '/customers/search/autocomplete',
        { search: searchQuery.value },
        {
            preserveState: true,
            preserveScroll: true,
            only: ['customers'],
            onSuccess: (page: any) => {
                fetchedCustomers.value = page.props.customers?.data || page.props.customers || [];
                showResults.value = true;
                isSearching.value = false;
            },
            onError: () => {
                isSearching.value = false;
            },
        },
    );
};

const selectCustomer = (customer: Customer) => {
    selectedCustomer.value = customer;
    searchQuery.value = customer.name;
    showResults.value = false;
    emit('update:modelValue', customer.id);
};

const clearSelection = () => {
    selectedCustomer.value = null;
    searchQuery.value = '';
    fetchedCustomers.value = [];
    showResults.value = false;
    emit('update:modelValue', null);
};

const handleFocus = () => {
    // Show dropdown on focus if we have customers
    if (props.customers.length > 0 || fetchedCustomers.value.length > 0) {
        showResults.value = true;
    }
};

watch(searchQuery, () => {
    if (!selectedCustomer.value) {
        const timeoutId = setTimeout(searchCustomers, 300);
        return () => clearTimeout(timeoutId);
    }
});

const hasSelection = computed(() => selectedCustomer.value !== null);
</script>

<template>
    <div class="space-y-2">
        <Label v-if="label" :for="`customer-search-${$.uid}`">
            {{ label }}
            <span v-if="required" class="text-destructive">*</span>
        </Label>

        <div class="relative">
            <div class="relative">
                <Search
                    class="absolute top-2.5 left-2.5 h-4 w-4 text-muted-foreground"
                />
                <Input
                    :id="`customer-search-${$.uid}`"
                    v-model="searchQuery"
                    type="text"
                    :placeholder="placeholder"
                    class="pr-8 pl-8"
                    :disabled="disabled"
                    @focus="handleFocus"
                    @blur="setTimeout(() => (showResults = false), 200)"
                />
                <button
                    v-if="hasSelection"
                    type="button"
                    @click="clearSelection"
                    class="absolute top-2.5 right-2.5 text-muted-foreground hover:text-foreground"
                    :disabled="disabled"
                >
                    <X class="h-4 w-4" />
                </button>
            </div>

            <div
                v-if="showResults && displayCustomers.length > 0"
                class="absolute z-50 mt-1 w-full rounded-md border bg-popover shadow-md"
            >
                <div class="max-h-60 overflow-y-auto p-1">
                    <button
                        v-for="customer in displayCustomers"
                        :key="customer.id"
                        type="button"
                        @mousedown.prevent="selectCustomer(customer)"
                        class="w-full rounded-sm px-3 py-2 text-left text-sm transition-colors hover:bg-accent"
                    >
                        <div class="font-medium">{{ customer.name }}</div>
                        <div class="text-xs text-muted-foreground">
                            {{ customer.email }} | {{ customer.phone }}
                        </div>
                    </button>
                </div>
            </div>

            <div
                v-if="
                    showResults &&
                    !isSearching &&
                    displayCustomers.length === 0 &&
                    searchQuery.length >= 2
                "
                class="absolute z-50 mt-1 w-full rounded-md border bg-popover p-4 text-center text-sm text-muted-foreground shadow-md"
            >
                No customers found
            </div>
        </div>

        <p v-if="error" class="text-sm text-destructive">
            {{ error }}
        </p>
    </div>
</template>
