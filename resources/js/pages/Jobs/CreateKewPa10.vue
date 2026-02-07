<script setup lang="ts">
import { Head, useForm, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/layouts/AppLayout.vue';
import { ref } from 'vue';
import JobController from '@/actions/App/Http/Controllers/JobController';

interface Props {
  workshop?: any;
  customers?: any[];
}

const props = defineProps<Props>();

const form = useForm({
  job_mode: 'KEW_PA_10',
  workshop_id: props.workshop?.id || null,
  title: '',
  description: '',
  
  // KEW.PA-10 specific fields
  kew_vehicle_registration: '',
  kew_asset_tag: '',
  kew_department_name: '',
  kew_inspection_date: '',
  kew_inspector_name: '',
  kew_inspector_ic: '',
  kew_findings: '',
  kew_recommendations: '',
});

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

// Helper to check if field has error
const hasError = (field: string) => {
  return !!form.errors[field];
};

// Format IC number (auto-format as user types)
const formatIC = () => {
  let ic = form.kew_inspector_ic.replace(/\D/g, ''); // Remove non-digits
  if (ic.length > 12) {
    ic = ic.substring(0, 12);
  }
  if (ic.length >= 6) {
    ic = ic.substring(0, 6) + '-' + ic.substring(6, 8) + '-' + ic.substring(8);
  }
  form.kew_inspector_ic = ic;
};
</script>

<template>
  <AuthenticatedLayout>
    <Head title="Create KEW.PA-10 Job" />

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
            <div class="icon-badge bg-blue-600 dark:bg-blue-500">
              <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" 
                />
              </svg>
            </div>
            <div class="ml-4">
              <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                KEW.PA-10 Government Inspection
              </h1>
              <p class="text-gray-600 dark:text-gray-400 mt-1">
                Complete all required fields for government vehicle inspection compliance
              </p>
            </div>
          </div>
        </div>

        <!-- Form Card -->
        <form @submit.prevent="submit" class="form-card">
          <!-- Basic Information Section -->
          <section class="form-section">
            <div class="section-header">
              <h2 class="section-title">Basic Information</h2>
              <p class="section-description">General job details and description</p>
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
                  placeholder="e.g., KEW.PA-10 Inspection - Vehicle WA1234A"
                  required
                />
                <p v-if="hasError('title')" class="error-message">
                  {{ form.errors.title }}
                </p>
              </div>

              <!-- Description -->
              <div class="form-group col-span-2">
                <label for="description" class="form-label">Description</label>
                <textarea
                  id="description"
                  v-model="form.description"
                  class="form-textarea"
                  :class="{ 'input-error': hasError('description') }"
                  rows="3"
                  placeholder="Additional context or special notes for this inspection..."
                ></textarea>
              </div>
            </div>
          </section>

          <!-- Vehicle/Asset Information Section -->
          <section class="form-section">
            <div class="section-header">
              <h2 class="section-title">Vehicle/Asset Information</h2>
              <p class="section-description">Details about the vehicle or asset being inspected</p>
            </div>

            <div class="form-grid">
              <!-- Vehicle Registration -->
              <div class="form-group">
                <label for="vehicle-reg" class="form-label required">Vehicle Registration Number</label>
                <input
                  id="vehicle-reg"
                  v-model="form.kew_vehicle_registration"
                  type="text"
                  class="form-input font-mono"
                  :class="{ 'input-error': hasError('kew_vehicle_registration') }"
                  placeholder="e.g., WA1234A"
                  required
                />
                <p v-if="hasError('kew_vehicle_registration')" class="error-message">
                  {{ form.errors.kew_vehicle_registration }}
                </p>
                <p class="help-text">Enter the vehicle plate number</p>
              </div>

              <!-- Asset Tag -->
              <div class="form-group">
                <label for="asset-tag" class="form-label required">Asset Tag Number</label>
                <input
                  id="asset-tag"
                  v-model="form.kew_asset_tag"
                  type="text"
                  class="form-input font-mono"
                  :class="{ 'input-error': hasError('kew_asset_tag') }"
                  placeholder="e.g., ASSET-2024-001"
                  required
                />
                <p v-if="hasError('kew_asset_tag')" class="error-message">
                  {{ form.errors.kew_asset_tag }}
                </p>
                <p class="help-text">Internal asset tracking number</p>
              </div>

              <!-- Department Name -->
              <div class="form-group col-span-2">
                <label for="department" class="form-label required">Department Name</label>
                <input
                  id="department"
                  v-model="form.kew_department_name"
                  type="text"
                  class="form-input"
                  :class="{ 'input-error': hasError('kew_department_name') }"
                  placeholder="e.g., Fleet Management Division"
                  required
                />
                <p v-if="hasError('kew_department_name')" class="error-message">
                  {{ form.errors.kew_department_name }}
                </p>
              </div>
            </div>
          </section>

          <!-- Inspection Details Section -->
          <section class="form-section">
            <div class="section-header">
              <h2 class="section-title">Inspection Details</h2>
              <p class="section-description">Information about the inspector and inspection date</p>
            </div>

            <div class="form-grid">
              <!-- Inspection Date -->
              <div class="form-group">
                <label for="inspection-date" class="form-label required">Inspection Date</label>
                <input
                  id="inspection-date"
                  v-model="form.kew_inspection_date"
                  type="date"
                  class="form-input"
                  :class="{ 'input-error': hasError('kew_inspection_date') }"
                  required
                />
                <p v-if="hasError('kew_inspection_date')" class="error-message">
                  {{ form.errors.kew_inspection_date }}
                </p>
              </div>

              <!-- Inspector Name -->
              <div class="form-group">
                <label for="inspector-name" class="form-label required">Inspector Name</label>
                <input
                  id="inspector-name"
                  v-model="form.kew_inspector_name"
                  type="text"
                  class="form-input"
                  :class="{ 'input-error': hasError('kew_inspector_name') }"
                  placeholder="Full name of inspector"
                  required
                />
                <p v-if="hasError('kew_inspector_name')" class="error-message">
                  {{ form.errors.kew_inspector_name }}
                </p>
              </div>

              <!-- Inspector IC -->
              <div class="form-group col-span-2">
                <label for="inspector-ic" class="form-label required">Inspector IC Number</label>
                <input
                  id="inspector-ic"
                  v-model="form.kew_inspector_ic"
                  @input="formatIC"
                  type="text"
                  class="form-input font-mono"
                  :class="{ 'input-error': hasError('kew_inspector_ic') }"
                  placeholder="YYMMDD-PB-###G (e.g., 900101-01-1234)"
                  maxlength="14"
                  required
                />
                <p v-if="hasError('kew_inspector_ic')" class="error-message">
                  {{ form.errors.kew_inspector_ic }}
                </p>
                <p class="help-text">Format: YYMMDD-PB-###G (12 digits)</p>
              </div>
            </div>
          </section>

          <!-- Findings & Recommendations Section -->
          <section class="form-section">
            <div class="section-header">
              <h2 class="section-title">Findings & Recommendations</h2>
              <p class="section-description">Detailed inspection findings and recommended actions</p>
            </div>

            <div class="form-grid">
              <!-- Findings -->
              <div class="form-group col-span-2">
                <label for="findings" class="form-label required">Inspection Findings</label>
                <textarea
                  id="findings"
                  v-model="form.kew_findings"
                  class="form-textarea"
                  :class="{ 'input-error': hasError('kew_findings') }"
                  rows="6"
                  placeholder="Describe all findings from the inspection in detail..."
                  required
                ></textarea>
                <p v-if="hasError('kew_findings')" class="error-message">
                  {{ form.errors.kew_findings }}
                </p>
                <p class="help-text">
                  Be thorough and specific. Include any defects, concerns, or compliance issues.
                </p>
              </div>

              <!-- Recommendations -->
              <div class="form-group col-span-2">
                <label for="recommendations" class="form-label required">Recommendations</label>
                <textarea
                  id="recommendations"
                  v-model="form.kew_recommendations"
                  class="form-textarea"
                  :class="{ 'input-error': hasError('kew_recommendations') }"
                  rows="6"
                  placeholder="Provide detailed recommendations for addressing the findings..."
                  required
                ></textarea>
                <p v-if="hasError('kew_recommendations')" class="error-message">
                  {{ form.errors.kew_recommendations }}
                </p>
                <p class="help-text">
                  Suggest corrective actions, repairs needed, or preventive measures.
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
              <span v-if="form.processing">Creating KEW.PA-10 Job...</span>
              <span v-else>Create KEW.PA-10 Job</span>
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
  @apply w-16 h-16 rounded-2xl;
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
.form-textarea {
  @apply block w-full;
  @apply px-4 py-3;
  @apply bg-white dark:bg-gray-900;
  @apply border border-gray-300 dark:border-gray-600;
  @apply rounded-lg;
  @apply text-gray-900 dark:text-white;
  @apply placeholder-gray-400 dark:placeholder-gray-500;
  @apply transition-all duration-200;
  @apply focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent;
}

.form-input:hover,
.form-textarea:hover {
  @apply border-gray-400 dark:border-gray-500;
}

.input-error {
  @apply border-red-500 dark:border-red-500;
  @apply focus:ring-red-500;
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
  @apply bg-linear-to-r from-blue-600 to-blue-700;
  @apply text-white;
  @apply hover:from-blue-700 hover:to-blue-800;
  @apply focus:ring-blue-500/50;
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
