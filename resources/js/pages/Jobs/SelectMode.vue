<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/layouts/AppLayout.vue';
import JobController from '@/actions/App/Http/Controllers/JobController';

interface Props {
  canCreateKew?: boolean;
  canCreateNormal?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  canCreateKew: true,
  canCreateNormal: true,
});
</script>

<template>
  <AuthenticatedLayout>
    <Head title="Select Job Type" />

    <div class="py-12">
      <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
          <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
            Create New Job
          </h1>
          <p class="text-lg text-gray-600 dark:text-gray-400">
            Select the type of job you want to create
          </p>
        </div>

        <!-- Job Mode Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          <!-- KEW.PA-10 Card -->
          <Link
            v-if="canCreateKew"
            :href="JobController.createKew().url"
            class="job-mode-card group"
          >
            <div class="card-glow card-glow-kew"></div>
            
            <div class="card-content">
              <!-- Icon -->
              <div class="icon-wrapper mb-6">
                <svg 
                  class="w-16 h-16 text-blue-600 dark:text-blue-400"
                  fill="none" 
                  stroke="currentColor" 
                  viewBox="0 0 24 24"
                >
                  <path 
                    stroke-linecap="round" 
                    stroke-linejoin="round" 
                    stroke-width="2" 
                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" 
                  />
                </svg>
              </div>

              <!-- Title & Badge -->
              <div class="mb-4">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                  KEW.PA-10
                </h2>
                <span class="badge badge-blue">
                  Government Inspection
                </span>
              </div>

              <!-- Description -->
              <p class="text-gray-600 dark:text-gray-400 mb-6">
                For government vehicle inspections requiring KEW.PA-10 compliance forms, 
                approval workflows, and detailed inspection reports.
              </p>

              <!-- Features -->
              <ul class="feature-list mb-6">
                <li class="feature-item">
                  <svg class="feature-icon" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                  </svg>
                  <span>Supervisor approval required</span>
                </li>
                <li class="feature-item">
                  <svg class="feature-icon" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                  </svg>
                  <span>Inspection tracking</span>
                </li>
                <li class="feature-item">
                  <svg class="feature-icon" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                  </svg>
                  <span>Compliance reporting</span>
                </li>
              </ul>

              <!-- CTA Button -->
              <div class="cta-button">
                <span class="cta-text">Create KEW.PA-10 Job</span>
                <svg class="cta-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
              </div>
            </div>
          </Link>

          <!-- Normal Job Card -->
          <Link
            v-if="canCreateNormal"
            :href="JobController.createNormal().url"
            class="job-mode-card group"
          >
            <div class="card-glow card-glow-normal"></div>
            
            <div class="card-content">
              <!-- Icon -->
              <div class="icon-wrapper mb-6">
                <svg 
                  class="w-16 h-16 text-emerald-600 dark:text-emerald-400"
                  fill="none" 
                  stroke="currentColor" 
                  viewBox="0 0 24 24"
                >
                  <path 
                    stroke-linecap="round" 
                    stroke-linejoin="round" 
                    stroke-width="2" 
                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" 
                  />
                  <path 
                    stroke-linecap="round" 
                    stroke-linejoin="round" 
                    stroke-width="2" 
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" 
                  />
                </svg>
              </div>

              <!-- Title & Badge -->
              <div class="mb-4">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                  Normal Job
                </h2>
                <span class="badge badge-green">
                  Standard Workflow
                </span>
              </div>

              <!-- Description -->
              <p class="text-gray-600 dark:text-gray-400 mb-6">
                For standard repair, maintenance, and service jobs with simplified 
                workflow and faster processing.
              </p>

              <!-- Features -->
              <ul class="feature-list mb-6">
                <li class="feature-item">
                  <svg class="feature-icon" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                  </svg>
                  <span>Quick setup</span>
                </li>
                <li class="feature-item">
                  <svg class="feature-icon" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                  </svg>
                  <span>Flexible scheduling</span>
                </li>
                <li class="feature-item">
                  <svg class="feature-icon" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                  </svg>
                  <span>Cost estimation</span>
                </li>
              </ul>

              <!-- CTA Button -->
              <div class="cta-button">
                <span class="cta-text">Create Normal Job</span>
                <svg class="cta-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
              </div>
            </div>
          </Link>
        </div>

        <!-- Help Text -->
        <div class="mt-12 text-center">
          <p class="text-sm text-gray-500 dark:text-gray-400">
            Not sure which type to choose? 
            <a href="#" class="text-blue-600 dark:text-blue-400 hover:underline font-medium">
              Learn more about job types
            </a>
          </p>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
@import "tailwindcss";
/* Card Container */
.job-mode-card {
  @apply relative overflow-hidden;
  @apply bg-white dark:bg-gray-800;
  @apply rounded-2xl shadow-lg;
  @apply transition-all duration-300 ease-out;
  @apply border border-gray-200 dark:border-gray-700;
  @apply cursor-pointer;
  @apply hover:shadow-2xl hover:scale-105;
  @apply hover:-translate-y-1;
  @apply focus:outline-none focus:ring-4 focus:ring-blue-500/20;
}

/* Animated Glow Effect */
.card-glow {
  @apply absolute inset-0 opacity-0;
  @apply transition-opacity duration-300;
  @apply pointer-events-none;
  background: radial-gradient(
    circle at 50% 0%,
    rgba(59, 130, 246, 0.15),
    transparent 70%
  );
}

.card-glow-kew {
  background: radial-gradient(
    circle at 50% 0%,
    rgba(59, 130, 246, 0.15),
    transparent 70%
  );
}

.card-glow-normal {
  background: radial-gradient(
    circle at 50% 0%,
    rgba(16, 185, 129, 0.15),
    transparent 70%
  );
}

.job-mode-card:hover .card-glow {
  @apply opacity-100;
}

/* Card Content */
.card-content {
  @apply relative z-10 p-8;
}

/* Icon Wrapper */
.icon-wrapper {
  @apply transform transition-transform duration-300;
}

.group:hover .icon-wrapper {
  @apply scale-110 rotate-3;
}

/* Badge Styles */
.badge {
  @apply inline-block px-3 py-1 rounded-full text-xs font-semibold;
}

.badge-blue {
  @apply bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300;
}

.badge-green {
  @apply bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300;
}

/* Feature List */
.feature-list {
  @apply space-y-3;
}

.feature-item {
  @apply flex items-center text-sm text-gray-600 dark:text-gray-400;
  @apply transition-colors duration-200;
}

.group:hover .feature-item {
  @apply text-gray-900 dark:text-gray-200;
}

.feature-icon {
  @apply w-5 h-5 mr-3 text-green-500 dark:text-green-400;
  @apply shrink-0;
}

/* CTA Button */
.cta-button {
  @apply flex items-center justify-between;
  @apply px-6 py-3 rounded-lg;
  @apply bg-linear-to-r from-blue-600 to-blue-700 dark:from-blue-500 dark:to-blue-600;
  @apply text-white font-medium;
  @apply transition-all duration-300;
  @apply shadow-md;
}

.group:hover .cta-button {
  @apply shadow-xl;
  @apply from-blue-700 to-blue-800 dark:from-blue-600 dark:to-blue-700;
}

.cta-text {
  @apply text-sm;
}

.cta-arrow {
  @apply w-5 h-5;
  @apply transform transition-transform duration-300;
}

.group:hover .cta-arrow {
  @apply translate-x-1;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
  .card-content {
    @apply p-6;
  }

  .icon-wrapper svg {
    @apply w-12 h-12;
  }
}

/* Accessibility */
.job-mode-card:focus {
  @apply outline-none ring-4 ring-blue-500/50;
}

/* Dark Mode Enhancements */
.dark .job-mode-card {
  @apply bg-linear-to-br from-gray-800 to-gray-900;
  @apply border-gray-700;
}
</style>
