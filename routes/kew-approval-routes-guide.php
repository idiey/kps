<?php

// ============================================================================
// KEW.PA-10 Approval Routes
// ============================================================================
// Add these routes to routes/web.php
//
// These routes handle the KEW.PA-10 inspection approval workflow
// Only supervisors and admins can access these routes
// ============================================================================

use App\Http\Controllers\KewApprovalController;

Route::prefix('jobs/kew')
    ->middleware(['auth'])
    ->name('jobs.kew.')
    ->group(function () {
        
        // List pending KEW.PA-10 approvals (supervisor dashboard)
        Route::get('/pending', [KewApprovalController::class, 'index'])
            ->middleware('role:supervisor|admin|super_admin')
            ->name('pending');
        
        // Approve a KEW.PA-10 inspection
        Route::post('/{job}/approve', [KewApprovalController::class, 'approve'])
            ->middleware('role:supervisor|admin|super_admin')
            ->name('approve');
        
        // Reject a KEW.PA-10 inspection (with reason)
        Route::post('/{job}/reject', [KewApprovalController::class, 'reject'])
            ->middleware('role:supervisor|admin|super_admin')
            ->name('reject');
        
        // View approval/rejection history for a job
        Route::get('/{job}/history', [KewApprovalController::class, 'history'])
            ->name('history');
    });

// ============================================================================
// Example API Usage
// ============================================================================

// 1. Get pending approvals (Inertia.js - Vue)
// Route: /jobs/kew/pending
// Returns: { pendingApprovals: [...], statistics: {...} }

// 2. Approve a job
// POST /jobs/kew/123/approve
// Headers: X-CSRF-TOKEN
// Response: Redirect with success message

// 3. Reject a job
// POST /jobs/kew/123/reject
// Headers: X-CSRF-TOKEN
// Body: { reason: "Inspection findings incomplete..." }
// Response: Redirect with success message

// 4. View history
// GET /jobs/kew/123/history
// Returns: { job: {...}, history: [...] }

// ============================================================================
// Frontend Integration (Vue/Inertia)
// ============================================================================

/*
// Example: Approve button in Vue component
<template>
  <button @click="approveJob" class="btn-approve">
    Approve Inspection
  </button>
</template>

<script setup>
import { router } from '@inertiajs/vue3'

const props = defineProps({
  job: Object
})

const approveJob = () => {
  if (confirm('Approve this KEW.PA-10 inspection?')) {
    router.post(route('jobs.kew.approve', props.job.id))
  }
}
</script>
*/

/*
// Example: Reject button with reason modal
<template>
  <button @click="showRejectModal = true" class="btn-reject">
    Reject Inspection
  </button>
  
  <Modal v-model="showRejectModal">
    <form @submit.prevent="rejectJob">
      <label>Rejection Reason:</label>
      <textarea v-model="form.reason" required minlength="10"></textarea>
      <button type="submit">Submit Rejection</button>
    </form>
  </Modal>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const showRejectModal = ref(false)
const form = ref({ reason: '' })

const rejectJob = () => {
  router.post(route('jobs.kew.reject', props.job.id), form.value, {
    onSuccess: () => {
      showRejectModal.value = false
      form.value.reason = ''
    }
  })
}
</script>
*/

// ============================================================================
// Testing Routes
// ============================================================================

/*
// Test with Artisan Tinker or HTTP client

// 1. Get pending approvals
GET http://localhost/jobs/kew/pending
Authorization: Bearer {token}

// 2. Approve job
POST http://localhost/jobs/kew/1/approve
Authorization: Bearer {token}
X-CSRF-TOKEN: {token}

// 3. Reject job
POST http://localhost/jobs/kew/1/reject
Authorization: Bearer {token}
X-CSRF-TOKEN: {token}
Content-Type: application/json

{
  "reason": "Inspection findings are incomplete. Please provide detailed brake measurements."
}

// 4. View history
GET http://localhost/jobs/kew/1/history
Authorization: Bearer {token}
*/
