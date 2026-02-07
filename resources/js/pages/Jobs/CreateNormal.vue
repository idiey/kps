<script setup lang="ts">
import { Head, useForm, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/layouts/AppLayout.vue';
import JobController from '@/actions/App/Http/Controllers/JobController';
import { ref, reactive } from 'vue';
import axios from 'axios';
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';

interface Props {
  workshop?: any;
  customers?: any[];
}

const props = defineProps<Props>();

const localCustomers = ref(props.customers || []);
const isCustomerModalOpen = ref(false);
const isCreatingCustomer = ref(false);

const newCustomer = reactive({
  name: '',
  email: '',
  phone: '',
  department: '',
  government_entity: '',
  address: '',
});

const customerErrors = reactive<Record<string, string>>({});

const form = useForm({
  job_mode: 'NORMAL',
  workshop_id: props.workshop?.id || null,
  customer_id: '',
  title: '',
  description: '',
  priority: 'medium',
  estimated_cost: null,
});

const priorities = [
  { value: 'low', label: 'Low', color: 'text-gray-600', bgColor: 'bg-gray-100 dark:bg-gray-800' },
  { value: 'medium', label: 'Medium', color: 'text-blue-600', bgColor: 'bg-blue-100 dark:bg-blue-900/30' },
  { value: 'high', label: 'High', color:'text-orange-600', bgColor: 'bg-orange-100 dark:bg-orange-900/30' },
  { value: 'urgent', label: 'Urgent', color: 'text-red-600', bgColor: 'bg-red-100 dark:bg-red-900/30' },
];

const submit = () => {
  form.post(JobController.store().url, {
    onSuccess: () => {
      // Success handled by Inertia
    },
    onError: (errors) => {
      console.error('Validation errors:', errors);
    },
  });
};

const createCustomer = async () => {
  isCreatingCustomer.value = true;
  Object.keys(customerErrors).forEach(key => delete customerErrors[key]);

  try {
    const response = await axios.post('/customers', newCustomer, {
      headers: { 'Accept': 'application/json' }
    });
    
    // Add new customer to list and select it
    const createdCustomer = response.data;
    localCustomers.value.push(createdCustomer);
    form.customer_id = createdCustomer.id;
    
    // Close modal and reset form
    isCustomerModalOpen.value = false;
    Object.assign(newCustomer, {
      name: '',
      email: '',
      phone: '',
      department: '',
      government_entity: '',
      address: '',
    });
  } catch (error: any) {
    if (error.response?.data?.errors) {
      Object.assign(customerErrors, error.response.data.errors);
    } else {
      console.error('Error creating customer:', error);
    }
  } finally {
    isCreatingCustomer.value = false;
  }
};

const hasError = (field: string) => {
  return !!form.errors[field];
};

// Format currency input
const formatCurrency = (event: Event) => {
  const input = event.target as HTMLInputElement;
  let value = input.value.replace(/[^\d.]/g, '');
  
  // Ensure only one decimal point
  const parts = value.split('.');
  if (parts.length > 2) {
    value = parts[0] + '.' + parts.slice(1).join('');
  }
  
  // Update form value
  form.estimated_cost = value ? parseFloat(value) : null;
};
</script>

<template>
  <AuthenticatedLayout>
    <Head title="Create Normal Job" />

    <div class="py-12">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
          <div class="flex items-center mb-4">
            <Link :href="JobController.selectMode().url" class="back-button">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
              </svg>
              <span>Back</span>
            </Link>
          </div>

          <div class="flex items-center">
            <div class="icon-badge bg-emerald-600 dark:bg-emerald-500">
              <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" 
                />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
            </div>

            <div class="ml-4">
              <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                Create Workshop Job
              </h1>
              <p class="text-gray-600 dark:text-gray-400 mt-1">
                Standard repair, maintenance, or service job
              </p>
            </div>
          </div>
        </div>

        <!-- Form Card -->
        <form @submit.prevent="submit" class="form-card">
          <!-- Customer Information Section -->
          <section class="form-section">
            <div class="section-header flex justify-between items-start">
              <div>
                <h2 class="section-title">Customer Information</h2>
                <p class="section-description">Select the customer for this job</p>
              </div>
            </div>

            <div class="form-grid">
              <!-- Customer Selection -->
              <div class="form-group col-span-2">
                <label for="customer" class="form-label required">Customer</label>
                <div class="flex gap-2">
                  <select
                    id="customer"
                    v-model="form.customer_id"
                    class="form-select flex-1"
                    :class="{ 'input-error': hasError('customer_id') }"
                    required
                  >
                    <option value="">Select a customer...</option>
                    <option 
                      v-for="customer in localCustomers" 
                      :key="customer.id" 
                      :value="customer.id"
                    >
                      {{ customer.name }} 
                      <span v-if="customer.phone">- {{ customer.phone }}</span>
                    </option>
                  </select>
                  
                  <Dialog v-model:open="isCustomerModalOpen">
                    <DialogTrigger as-child>
                      <Button type="button" variant="outline" class="whitespace-nowrap">
                        New Customer
                      </Button>
                    </DialogTrigger>
                    <DialogContent class="sm:max-w-[425px]">
                      <DialogHeader>
                        <DialogTitle>Create New Customer</DialogTitle>
                        <DialogDescription>
                          Add a new customer to the system. Click save when you're done.
                        </DialogDescription>
                      </DialogHeader>
                      <div class="grid gap-4 py-4">
                        <div class="grid grid-cols-4 items-center gap-4">
                          <Label for="name" class="text-right required">Name</Label>
                          <div class="col-span-3">
                            <Input
                              id="name"
                              v-model="newCustomer.name"
                              class="col-span-3"
                            />
                            <p v-if="customerErrors.name" class="text-red-500 text-xs mt-1">{{ customerErrors.name[0] }}</p>
                          </div>
                        </div>
                        <div class="grid grid-cols-4 items-center gap-4">
                          <Label for="phone" class="text-right required">Phone</Label>
                          <div class="col-span-3">
                            <Input
                              id="phone"
                              v-model="newCustomer.phone"
                              class="col-span-3"
                            />
                            <p v-if="customerErrors.phone" class="text-red-500 text-xs mt-1">{{ customerErrors.phone[0] }}</p>
                          </div>
                        </div>
                        <div class="grid grid-cols-4 items-center gap-4">
                          <Label for="email" class="text-right">Email</Label>
                          <div class="col-span-3">
                            <Input
                              id="email"
                              type="email"
                              v-model="newCustomer.email"
                              class="col-span-3"
                            />
                            <p v-if="customerErrors.email" class="text-red-500 text-xs mt-1">{{ customerErrors.email[0] }}</p>
                          </div>
                        </div>
                        <div class="grid grid-cols-4 items-center gap-4">
                          <Label for="dept" class="text-right">Department</Label>
                           <div class="col-span-3">
                            <Input
                              id="dept"
                              v-model="newCustomer.department"
                              class="col-span-3"
                            />
                             <p v-if="customerErrors.department" class="text-red-500 text-xs mt-1">{{ customerErrors.department[0] }}</p>
                           </div>
                        </div>
                         <div class="grid grid-cols-4 items-center gap-4">
                          <Label for="gov" class="text-right">Gov Entity</Label>
                           <div class="col-span-3">
                            <Input
                              id="gov"
                              v-model="newCustomer.government_entity"
                              class="col-span-3"
                              placeholder="Ministry/Agency"
                            />
                             <p v-if="customerErrors.government_entity" class="text-red-500 text-xs mt-1">{{ customerErrors.government_entity[0] }}</p>
                           </div>
                        </div>
                        <div class="grid grid-cols-4 items-center gap-4">
                          <Label for="address" class="text-right">Address</Label>
                           <div class="col-span-3">
                            <Textarea
                              id="address"
                              v-model="newCustomer.address"
                              class="col-span-3"
                            />
                             <p v-if="customerErrors.address" class="text-red-500 text-xs mt-1">{{ customerErrors.address[0] }}</p>
                           </div>
                        </div>
                      </div>
                      <DialogFooter>
                        <Button type="button" @click="createCustomer" :disabled="isCreatingCustomer">
                          {{ isCreatingCustomer ? 'Saving...' : 'Save Customer' }}
                        </Button>
                      </DialogFooter>
                    </DialogContent>
                  </Dialog>
                </div>
                <p v-if="hasError('customer_id')" class="error-message">
                  {{ form.errors.customer_id }}
                </p>
              </div>
            </div>
          </section>

          <!-- Job Details Section -->
          <section class="form-section">
            <div class="section-header">
              <h2 class="section-title">Job Details</h2>
              <p class="section-description">Describe the work to be performed</p>
            </div>

            <div class="form-grid">
              <!-- Job Title -->
              <div class="form-group col-span-2">
                <label for="title" class="form-label required">Job Title</label>
                <input
                  id="title"
                  v-model="form.title"
                  type="text"
                  class="form-input"
                  :class="{ 'input-error': hasError('title') }"
                  placeholder="e.g., Engine Oil Change - Toyota Camry"
                  required
                />
                <p v-if="hasError('title')" class="error-message">
                  {{ form.errors.title }}
                </p>
                <p class="help-text">Brief, descriptive title for this job</p>
              </div>

              <!-- Description -->
              <div class="form-group col-span-2">
                <label for="description" class="form-label">Description</label>
                <textarea
                  id="description"
                  v-model="form.description"
                  class="form-textarea"
                  :class="{ 'input-error': hasError('description') }"
                  rows="5"
                  placeholder="Provide detailed information about the work required, any specific requests from the customer, or additional context..."
                ></textarea>
                <p v-if="hasError('description')" class="error-message">
                  {{ form.errors.description }}
                </p>
              </div>
            </div>
          </section>

          <!-- Priority & Estimates Section -->
          <section class="form-section">
            <div class="section-header">
              <h2 class="section-title">Priority & Cost Estimate</h2>
              <p class="section-description">Set job priority and estimated costs</p>
            </div>

            <div class="form-grid">
              <!-- Priority -->
              <div class="form-group col-span-2">
                <label for="priority" class="form-label required">Priority Level</label>
                <div class="priority-selector">
                  <button
                    v-for="priority in priorities"
                    :key="priority.value"
                    type="button"
                    @click="form.priority = priority.value"
                    class="priority-option"
                    :class="[
                      form.priority === priority.value ? 'priority-selected' : '',
                      priority.bgColor,
                      priority.color,
                    ]"
                  >
                    <span class="priority-dot" :class="priority.color"></span>
                    <span class="font-medium">{{ priority.label }}</span>
                    <svg 
                      v-if="form.priority === priority.value"
                      class="w-5 h-5 ml-auto"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                    >
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                  </button>
                </div>
                <p v-if="hasError('priority')" class="error-message">
                  {{ form.errors.priority }}
                </p>
                <p class="help-text">
                  Priority affects scheduling and resource allocation
                </p>
              </div>

              <!-- Estimated Cost -->
              <div class="form-group col-span-2">
                <label for="estimated-cost" class="form-label">Estimated Cost (RM)</label>
                <div class="relative">
                  <span class="currency-symbol">RM</span>
                  <input
                    id="estimated-cost"
                    :value="form.estimated_cost"
                    @input="formatCurrency"
                    type="text"
                    class="form-input pl-14"
                    :class="{ 'input-error': hasError('estimated_cost') }"
                    placeholder="0.00"
                    inputmode="decimal"
                  />
                </div>
                <p v-if="hasError('estimated_cost')" class="error-message">
                  {{ form.errors.estimated_cost }}
                </p>
                <p class="help-text">
                  Preliminary cost estimate. Can be updated later.
                </p>
              </div>
            </div>
          </section>

          <!-- Form Actions -->
          <div class="form-actions">
            <Link :href="JobController.index().url" class="btn btn-secondary">
              Cancel
            </Link>
            <button 
              type="submit" 
              class="btn btn-primary"
              :disabled="form.processing"
            >
              <svg v-if="form.processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <span v-if="form.processing">Creating Job...</span>
              <span v-else>Create Job</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
@import "tailwindcss";
/* Back Button */
.back-button {
  @apply inline-flex items-center gap-2;
  @apply text-gray-600 dark:text-gray-400;
  @apply hover:text-gray-900 dark:hover:text-white;
  @apply transition-colors duration-200;
  @apply font-medium text-sm;
}

/* Icon Badge */
.icon-badge {
  @apply w-12 h-12 rounded-2xl;
  @apply flex items-center justify-center;
  @apply shadow-lg;
}

/* Form Card */
.form-card {
  @apply bg-white dark:bg-gray-800;
  @apply rounded-2xl shadow-xl;
  @apply border border-gray-200 dark:border-gray-700;
  @apply overflow-hidden;
}

/* Form Section */
.form-section {
  @apply p-8 border-b border-gray-200 dark:border-gray-700;
}

.form-section:last-of-type {
  @apply border-b-0;
}

/* Section Header */
.section-header {
  @apply mb-6;
}

.section-title {
  @apply text-xl font-bold text-gray-900 dark:text-white mb-1;
}

.section-description {
  @apply text-sm text-gray-600 dark:text-gray-400;
}

/* Form Grid */
.form-grid {
  @apply grid grid-cols-1 md:grid-cols-2 gap-6;
}

/* Form Group */
.form-group {
  @apply space-y-2;
}

.form-group.col-span-2 {
  @apply md:col-span-2;
}

/* Form Label */
.form-label {
  @apply block text-sm font-semibold text-gray-700 dark:text-gray-300;
}

.form-label.required::after {
  content: ' *';
  @apply text-red-500;
}

/* Form Input */
.form-input,
.form-select,
.form-textarea {
  @apply block w-full;
  @apply px-4 py-3;
  @apply bg-white dark:bg-gray-900;
  @apply border border-gray-300 dark:border-gray-600;
  @apply rounded-lg;
  @apply text-gray-900 dark:text-white;
  @apply placeholder-gray-400 dark:placeholder-gray-500;
  @apply transition-all duration-200;
  @apply focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent;
}

.form-input:hover,
.form-select:hover,
.form-textarea:hover {
  @apply border-gray-400 dark:border-gray-500;
}

.input-error {
  @apply border-red-500 dark:border-red-500;
  @apply focus:ring-red-500;
}

/* Currency Symbol */
.currency-symbol {
  @apply absolute left-4 top-1/2 transform -translate-y-1/2;
  @apply text-gray-600 dark:text-gray-400;
  @apply font-semibold;
  @apply pointer-events-none;
}

/* Priority Selector */
.priority-selector {
  @apply grid grid-cols-2 md:grid-cols-4 gap-3;
}

.priority-option {
  @apply flex items-center gap-2;
  @apply px-4 py-3 rounded-lg;
  @apply border-2 border-transparent;
  @apply transition-all duration-200;
  @apply hover:border-current hover:shadow-md;
}

.priority-selected {
  @apply border-current shadow-lg;
  @apply ring-2 ring-current/20;
}

.priority-dot {
  @apply w-3 h-3 rounded-full;
  @apply bg-current;
}

/* Error Message */
.error-message {
  @apply text-sm text-red-600 dark:text-red-400;
  @apply flex items-center gap-1;
}

/* Help Text */
.help-text {
  @apply text-xs text-gray-500 dark:text-gray-400;
}

/* Form Actions */
.form-actions {
  @apply flex justify-end gap-4 p-8;
  @apply bg-gray-50 dark:bg-gray-900/50;
}

/* Buttons */
.btn {
  @apply inline-flex items-center justify-center;
  @apply px-6 py-3;
  @apply rounded-lg;
  @apply font-semibold text-sm;
  @apply transition-all duration-200;
  @apply focus:outline-none focus:ring-4;
  @apply disabled:opacity-50 disabled:cursor-not-allowed;
}

.btn-secondary {
  @apply bg-white dark:bg-gray-700;
  @apply text-gray-700 dark:text-gray-300;
  @apply border border-gray-300 dark:border-gray-600;
  @apply hover:bg-gray-50 dark:hover:bg-gray-600;
  @apply focus:ring-gray-500/20;
}

.btn-primary {
  @apply bg-linear-to-r from-emerald-600 to-emerald-700;
  @apply text-white;
  @apply hover:from-emerald-700 hover:to-emerald-800;
  @apply focus:ring-emerald-500/50;
  @apply shadow-lg hover:shadow-xl;
}

/* Responsive */
@media (max-width: 768px) {
  .form-section {
    @apply p-6;
  }

  .form-actions {
    @apply flex-col;
  }

  .btn {
    @apply w-full;
  }
}
</style>
