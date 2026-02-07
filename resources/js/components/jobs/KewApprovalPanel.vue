<script setup lang="ts">
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import type { WorkshopJob } from '@/types'

interface Props {
  job: WorkshopJob
  canApprove?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  canApprove: false
})

const showRejectModal = ref(false)
const rejectionReason = ref('')
const isProcessing = ref(false)
const errors = ref<Record<string, string>>({})

const canSubmitRejection = computed(() => {
  return rejectionReason.value.trim().length >= 10
})

const handleApprove = () => {
  if (!props.canApprove || isProcessing.value) return

  if (!confirm(`Are you sure you want to APPROVE Job #${props.job.job_number}?\n\nThis action will mark the inspection as approved and allow the job to proceed.`)) {
    return
  }

  isProcessing.value = true
  errors.value = {}

  router.post(
    `/jobs/kew/${props.job.id}/approve`,
    {},
    {
      preserveScroll: true,
      onSuccess: () => {
        isProcessing.value = false
      },
      onError: (err) => {
        isProcessing.value = false
        errors.value = err
      }
    }
  )
}

const openRejectModal = () => {
  if (!props.canApprove || isProcessing.value) return
  showRejectModal.value = true
  rejectionReason.value = ''
  errors.value = {}
}

const closeRejectModal = () => {
  if (isProcessing.value) return
  showRejectModal.value = false
  rejectionReason.value = ''
  errors.value = {}
}

const handleReject = () => {
  if (!canSubmitRejection.value || isProcessing.value) return

  isProcessing.value = true
  errors.value = {}

  router.post(
    `/jobs/kew/${props.job.id}/reject`,
    { reason: rejectionReason.value },
    {
      preserveScroll: true,
      onSuccess: () => {
        isProcessing.value = false
        closeRejectModal()
      },
      onError: (err) => {
        isProcessing.value = false
        errors.value = err
      }
    }
  )
}

const getStatusInfo = computed(() => {
  const statusMap: Record<string, { icon: string, label: string, color: string }> = {
    'inspection_in_progress': {
      icon: '⏳',
      label: 'Inspection In Progress',
      color: 'yellow'
    },
    'inspection_approved': {
      icon: '✅',
      label: 'Approved',
      color: 'green'
    },
    'inspection_rejected': {
      icon: '❌',
      label: 'Rejected',
      color: 'red'
    },
    'in_progress': {
      icon: '🔄',
      label: 'In Progress',
      color: 'blue'
    }
  }
  
  return statusMap[props.job.status] || {
    icon: '📝',
    label: props.job.status.replace(/_/g, ' '),
    color: 'gray'
  }
})
</script>

<template>
  <div class="space-y-4">
    <!-- Status Card -->
    <div 
      :class="[
        'p-6 rounded-lg border-2',
        getStatusInfo.color === 'yellow' ? 'bg-yellow-50 dark:bg-yellow-900/20 border-yellow-300 dark:border-yellow-800' :
        getStatusInfo.color === 'green' ? 'bg-green-50 dark:bg-green-900/20 border-green-300 dark:border-green-800' :
        getStatusInfo.color === 'red' ? 'bg-red-50 dark:bg-red-900/20 border-red-300 dark:border-red-800' :
        'bg-gray-50 dark:bg-gray-900/20 border-gray-300 dark:border-gray-800'
      ]"
    >
      <div class="flex items-center gap-3 mb-4">
        <div class="text-3xl">{{ getStatusInfo.icon }}</div>
        <div>
          <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">
            Current Status
          </h3>
          <p 
            :class="[
              'text-sm font-semibold',
              getStatusInfo.color === 'yellow' ? 'text-yellow-800 dark:text-yellow-200' :
              getStatusInfo.color === 'green' ? 'text-green-800 dark:text-green-200' :
              getStatusInfo.color === 'red' ? 'text-red-800 dark:text-red-200' :
              'text-gray-800 dark:text-gray-200'
            ]"
          >
            {{ getStatusInfo.label }}
          </p>
        </div>
      </div>

      <!-- Inspection Info -->
      <div v-if="job.kew_inspection_date || job.kew_inspector_name" class="space-y-2 text-sm">
        <div v-if="job.kew_inspection_date" class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
          <span class="font-semibold">📅 Inspection Date:</span>
          <span>{{ new Date(job.kew_inspection_date).toLocaleDateString('en-MY', { 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
          }) }}</span>
        </div>
        <div v-if="job.kew_inspector_name" class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
          <span class="font-semibold">👤 Inspector:</span>
          <span>{{ job.kew_inspector_name }}</span>
        </div>
      </div>
    </div>

    <!-- Action Buttons (Only for Pending Approval) -->
    <div 
      v-if="job.status === 'inspection_in_progress' && canApprove"
      class="p-6 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm"
    >
      <h4 class="text-base font-semibold text-gray-900 dark:text-gray-100 mb-4">
        🎯 Approval Actions
      </h4>
      
      <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
        Review the inspection details above and choose an action:
      </p>

      <div class="flex flex-col sm:flex-row gap-3">
        <!-- Approve Button -->
        <button
          @click="handleApprove"
          :disabled="isProcessing"
          class="flex-1 flex items-center justify-center gap-2 px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800"
        >
          <span class="text-xl">✅</span>
          <span>{{ isProcessing ? 'Processing...' : 'Approve Inspection' }}</span>
        </button>

        <!-- Reject Button -->
        <button
          @click="openRejectModal"
          :disabled="isProcessing"
          class="flex-1 flex items-center justify-center gap-2 px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800"
        >
          <span class="text-xl">❌</span>
          <span>Reject Inspection</span>
        </button>
      </div>

      <!-- Error Display -->
      <div 
        v-if="Object.keys(errors).length > 0"
        class="mt-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg"
      >
        <p class="text-sm font-semibold text-red-800 dark:text-red-200 mb-2">⚠️ Error:</p>
        <ul class="text-sm text-red-700 dark:text-red-300 list-disc list-inside space-y-1">
          <li v-for="(error, key) in errors" :key="key">{{ error }}</li>
        </ul>
      </div>
    </div>

    <!-- Permission Denied Message -->
    <div 
      v-else-if="job.status === 'inspection_in_progress' && !canApprove"
      class="p-6 bg-yellow-50 dark:bg-yellow-900/20 border-2 border-yellow-300 dark:border-yellow-700 rounded-lg"
    >
      <div class="flex items-start gap-3">
        <div class="text-2xl">🔒</div>
        <div>
          <h4 class="font-semibold text-yellow-900 dark:text-yellow-200 mb-1">
            Supervisor Approval Required
          </h4>
          <p class="text-sm text-yellow-800 dark:text-yellow-300">
            Only supervisors and administrators can approve or reject KEW.PA-10 inspections.
          </p>
        </div>
      </div>
    </div>

    <!-- Reject Modal -->
    <Teleport to="body">
      <div
        v-if="showRejectModal"
        class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title"
        role="dialog"
        aria-modal="true"
      >
        <!-- Backdrop -->
        <div 
          class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-75 transition-opacity"
          @click="closeRejectModal"
        />

        <!-- Modal -->
        <div class="flex min-h-full items-center justify-center p-4">
          <div 
            class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-lg w-full p-6 transform transition-all"
            @click.stop
          >
            <!-- Header -->
            <div class="flex items-center justify-between mb-4">
              <h3 id="modal-title" class="text-lg font-bold text-gray-900 dark:text-gray-100">
                ❌ Reject Inspection
              </h3>
              <button
                @click="closeRejectModal"
                :disabled="isProcessing"
                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors disabled:opacity-50"
              >
                <span class="text-2xl">&times;</span>
              </button>
            </div>

            <!-- Content -->
            <div class="space-y-4">
              <p class="text-sm text-gray-600 dark:text-gray-400">
                Please provide a detailed reason for rejecting Job <strong>#{{ job.job_number }}</strong>. 
                This will help the team understand what needs to be corrected.
              </p>

              <!-- Textarea -->
              <div>
                <label 
                  for="rejection-reason" 
                  class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2"
                >
                  Rejection Reason <span class="text-red-500">*</span>
                </label>
                <textarea
                  id="rejection-reason"
                  v-model="rejectionReason"
                  :disabled="isProcessing"
                  rows="5"
                  placeholder="Describe the issues that need to be addressed... (minimum 10 characters)"
                  class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-gray-100 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                  :class="{ 'border-red-500': errors.reason }"
                />
                <div class="flex justify-between mt-2">
                  <p v-if="errors.reason" class="text-sm text-red-600 dark:text-red-400">
                    {{ errors.reason }}
                  </p>
                  <p 
                    class="text-xs ml-auto"
                    :class="rejectionReason.length >= 10 ? 'text-green-600 dark:text-green-400' : 'text-gray-500 dark:text-gray-400'"
                  >
                    {{ rejectionReason.length }} / 1000 characters
                    <span v-if="rejectionReason.length < 10">({{ 10 - rejectionReason.length }} more needed)</span>
                  </p>
                </div>
              </div>

              <!-- Actions -->
              <div class="flex gap-3 pt-2">
                <button
                  @click="closeRejectModal"
                  :disabled="isProcessing"
                  class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  Cancel
                </button>
                <button
                  @click="handleReject"
                  :disabled="!canSubmitRejection || isProcessing"
                  class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold shadow-md hover:shadow-lg transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  {{ isProcessing ? 'Rejecting...' : 'Confirm Rejection' }}
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </Teleport>
  </div>
</template>

<style scoped>
/* Modal animation */
.fixed {
  animation: fadeIn 0.2s ease-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}
</style>
