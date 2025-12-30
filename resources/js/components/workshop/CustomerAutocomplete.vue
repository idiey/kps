<script setup lang="ts">
import { ref, computed, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { Search, X, Plus } from 'lucide-vue-next';
import type { Customer } from '@/types';

interface Props {
  modelValue?: number | null;
  label?: string;
  placeholder?: string;
  error?: string;
  required?: boolean;
  disabled?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  modelValue: null,
  label: 'Customer',
  placeholder: 'Search customer...',
  required: false,
  disabled: false,
});

const emit = defineEmits<{
  'update:modelValue': [value: number | null];
}>();

const searchQuery = ref('');
const isSearching = ref(false);
const showResults = ref(false);
const customers = ref<Customer[]>([]);
const selectedCustomer = ref<Customer | null>(null);

const searchCustomers = () => {
  if (searchQuery.value.length < 2) {
    customers.value = [];
    showResults.value = false;
    return;
  }

  isSearching.value = true;

  router.get(
    route('customers.index'),
    { search: searchQuery.value },
    {
      preserveState: true,
      preserveScroll: true,
      only: ['customers'],
      onSuccess: (page: any) => {
        customers.value = page.props.customers?.data || [];
        showResults.value = true;
        isSearching.value = false;
      },
      onError: () => {
        isSearching.value = false;
      },
    }
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
  customers.value = [];
  showResults.value = false;
  emit('update:modelValue', null);
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
        <Search class="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground" />
        <Input
          :id="`customer-search-${$.uid}`"
          v-model="searchQuery"
          type="text"
          :placeholder="placeholder"
          class="pl-8 pr-8"
          :disabled="disabled"
          @focus="showResults = customers.length > 0"
          @blur="setTimeout(() => (showResults = false), 200)"
        />
        <button
          v-if="hasSelection"
          type="button"
          @click="clearSelection"
          class="absolute right-2.5 top-2.5 text-muted-foreground hover:text-foreground"
          :disabled="disabled"
        >
          <X class="h-4 w-4" />
        </button>
      </div>

      <div
        v-if="showResults && customers.length > 0"
        class="absolute z-50 mt-1 w-full rounded-md border bg-popover shadow-md"
      >
        <div class="max-h-60 overflow-y-auto p-1">
          <button
            v-for="customer in customers"
            :key="customer.id"
            type="button"
            @click="selectCustomer(customer)"
            class="w-full rounded-sm px-3 py-2 text-left text-sm hover:bg-accent transition-colors"
          >
            <div class="font-medium">{{ customer.name }}</div>
            <div class="text-xs text-muted-foreground">
              {{ customer.email }} | {{ customer.phone }}
            </div>
          </button>
        </div>
      </div>

      <div
        v-if="showResults && !isSearching && customers.length === 0 && searchQuery.length >= 2"
        class="absolute z-50 mt-1 w-full rounded-md border bg-popover shadow-md p-4 text-center text-sm text-muted-foreground"
      >
        No customers found
      </div>
    </div>

    <p v-if="error" class="text-sm text-destructive">
      {{ error }}
    </p>
  </div>
</template>
