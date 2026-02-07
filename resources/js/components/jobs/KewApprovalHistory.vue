<script setup lang="ts">
import { computed } from 'vue'
import type { JobStatusHistory } from '@/types'

interface Props {
  history: JobStatusHistory[]
  showEmpty?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  showEmpty: true
})

const sortedHistory = computed(() => {
  return [...props.history].sort((a, b) => 
    new Date(b.changed_at).getTime() - new Date(a.changed_at).getTime()
  )
})

const getStatusIcon = (status: string) => {
  const icons: Record<string, string> = {
    'inspection_approved': '✅',
    'inspection_rejected': '❌',
    'inspection_in_progress': '⏳',
    'in_progress': '🔄'
  }
  return icons[status] || '📝'
}

const getStatusColor = (status: string) => {
  const colors: Record<string, { bg: string, text: string, ring: string }> = {
    'inspection_approved': {
      bg: 'bg-green-50 dark:bg-green-900/20',
      text: 'text-green-800 dark:text-green-200',
      ring: 'ring-green-200 dark:ring-green-800'
    },
    'inspection_rejected': {
      bg: 'bg-red-50 dark:bg-red-900/20',
      text: 'text-red-800 dark:text-red-200',
      ring: 'ring-red-200 dark:ring-red-800'
    },
    'inspection_in_progress': {
      bg: 'bg-yellow-50 dark:bg-yellow-900/20',
      text: 'text-yellow-800 dark:text-yellow-200',
      ring: 'ring-yellow-200 dark:ring-yellow-800'
    }
  }
  return colors[status] || {
    bg: 'bg-gray-50 dark:bg-gray-900/20',
    text: 'text-gray-800 dark:text-gray-200',
    ring: 'ring-gray-200 dark:ring-gray-800'
  }
}

const getStatusLabel = (status: string) => {
  const labels: Record<string, string> = {
    'inspection_approved': 'Approved',
    'inspection_rejected': 'Rejected',
    'inspection_in_progress': 'Inspection In Progress',
    'in_progress': 'In Progress'
  }
  return labels[status] || status.replace(/_/g, ' ')
}

const formatDate = (dateString: string) => {
  const date = new Date(dateString)
  const now = new Date()
  const diffInHours = (now.getTime() - date.getTime()) / (1000 * 60 * 60)
  
  if (diffInHours < 24) {
    return date.toLocaleTimeString('en-MY', { 
      hour: '2-digit', 
      minute: '2-digit' 
    })
  }
  
  return date.toLocaleDateString('en-MY', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const getRelativeTime = (dateString: string) => {
  const date = new Date(dateString)
  const now = new Date()
  const diffInMinutes = Math.floor((now.getTime() - date.getTime()) / (1000 * 60))
  
  if (diffInMinutes < 1) return 'just now'
  if (diffInMinutes < 60) return `${diffInMinutes}m ago`
  
  const diffInHours = Math.floor(diffInMinutes / 60)
  if (diffInHours < 24) return `${diffInHours}h ago`
  
  const diffInDays = Math.floor(diffInHours / 24)
  if (diffInDays < 7) return `${diffInDays}d ago`
  
  const diffInWeeks = Math.floor(diffInDays / 7)
  return `${diffInWeeks}w ago`
}
</script>

<template>
  <div class="space-y-4">
    <!-- Header -->
    <div class="flex items-center justify-between">
      <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
        📜 Approval History
      </h3>
      <span class="text-sm text-gray-500 dark:text-gray-400">
        {{ history.length }} {{ history.length === 1 ? 'record' : 'records' }}
      </span>
    </div>

    <!-- Empty State -->
    <div 
      v-if="history.length === 0 && showEmpty"
      class="text-center py-12 px-4 bg-gray-50 dark:bg-gray-900/20 rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-700"
    >
      <div class="text-4xl mb-3">📋</div>
      <p class="text-gray-600 dark:text-gray-400 font-medium">No approval history yet</p>
      <p class="text-sm text-gray-500 dark:text-gray-500 mt-1">
        Approval records will appear here once actions are taken
      </p>
    </div>

    <!-- Timeline -->
    <div v-else class="space-y-4">
      <div
        v-for="(record, index) in sortedHistory"
        :key="record.id"
        class="relative"
      >
        <!-- Timeline connector -->
        <div
          v-if="index < sortedHistory.length - 1"
          class="absolute left-6 top-12 bottom-0 w-0.5 bg-gray-200 dark:bg-gray-700"
          aria-hidden="true"
        />

        <!-- Record Card -->
        <div 
          :class="[
            getStatusColor(record.to_status).bg,
            getStatusColor(record.to_status).ring,
            'relative flex gap-4 p-4 rounded-lg ring-1 transition-all duration-200 hover:shadow-md'
          ]"
        >
          <!-- Icon -->
          <div class="shrink-0">
            <div 
              :class="[
                getStatusColor(record.to_status).text,
                'w-12 h-12 flex items-center justify-center text-2xl bg-white dark:bg-gray-800 rounded-full ring-2',
                getStatusColor(record.to_status).ring
              ]"
            >
              {{ getStatusIcon(record.to_status) }}
            </div>
          </div>

          <!-- Content -->
          <div class="flex-1 min-w-0">
            <!-- Status and Time -->
            <div class="flex items-start justify-between gap-4 mb-2">
              <div>
                <h4 :class="[getStatusColor(record.to_status).text, 'font-semibold text-base']">
                  {{ getStatusLabel(record.to_status) }}
                </h4>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-0.5">
                  by <span class="font-medium">{{ record.user?.name || 'Unknown' }}</span>
                </p>
              </div>
              <div class="text-right shrink-0">
                <p class="text-xs text-gray-500 dark:text-gray-400">
                  {{ getRelativeTime(record.changed_at) }}
                </p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">
                  {{ formatDate(record.changed_at) }}
                </p>
              </div>
            </div>

            <!-- Status Transition -->
            <div 
              v-if="record.from_status"
              class="flex items-center gap-2 text-xs text-gray-600 dark:text-gray-400 mb-2"
            >
              <span class="px-2 py-0.5 bg-gray-200 dark:bg-gray-700 rounded">
                {{ getStatusLabel(record.from_status) }}
              </span>
              <span>→</span>
              <span class="px-2 py-0.5 bg-gray-200 dark:bg-gray-700 rounded font-medium">
                {{ getStatusLabel(record.to_status) }}
              </span>
            </div>

            <!-- Notes/Reason -->
            <div 
              v-if="record.notes"
              class="mt-3 p-3 bg-white/50 dark:bg-gray-800/50 rounded border border-gray-200 dark:border-gray-700"
            >
              <p class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">
                {{ record.to_status === 'inspection_rejected' ? '❗ Rejection Reason:' : '💬 Notes:' }}
              </p>
              <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap">
                {{ record.notes }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Smooth timeline animations */
.relative {
  animation: fadeInUp 0.3s ease-out backwards;
}

.relative:nth-child(1) { animation-delay: 0.05s; }
.relative:nth-child(2) { animation-delay: 0.1s; }
.relative:nth-child(3) { animation-delay: 0.15s; }

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>
