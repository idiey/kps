<script setup lang="ts">
import { ref } from 'vue'
import { Head, Link, router, useForm } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Badge } from '@/components/ui/badge'
import { Label } from '@/components/ui/label'
import { Textarea } from '@/components/ui/textarea'
import { Checkbox } from '@/components/ui/checkbox'
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog'
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert'
import {
  ArrowLeft,
  Edit,
  CheckCircle,
  XCircle,
  FileText,
  Briefcase,
  Building2,
  Calendar,
  AlertCircle,
  ClipboardCheck,
} from 'lucide-vue-next'

interface GovernmentDepartment {
  id: number
  name: string
  department_code: string
}

interface Asset {
  id: number
  asset_tag: string
  asset_name: string
  asset_type: string
}

interface User {
  id: number
  name: string
}

interface StatusHistory {
  id: number
  status: string
  notes?: string
  created_at: string
  user: User
}

interface WorkshopJob {
  id: number
  job_reference: string
  status: string
  status_histories: StatusHistory[]
}

interface KewPA10 {
  id: number
  kew_pa_10_number: string
  government_department: GovernmentDepartment
  asset: Asset
  description: string
  priority: string
  budget_allocation_reference?: string
  received_date: string
  form_completeness_verified: boolean
  signatures_verified: boolean
  verification_notes?: string
  workshop_job?: WorkshopJob
}

interface Props {
  kewPA10: KewPA10
  canVerify: boolean
  canCreateJob: boolean
}

const props = defineProps<Props>()

// Dialog states
const showVerifyDialog = ref(false)
const showCreateJobDialog = ref(false)

// Verification form
const verifyForm = useForm({
  form_completeness_verified: props.kewPA10.form_completeness_verified,
  signatures_verified: props.kewPA10.signatures_verified,
  verification_notes: props.kewPA10.verification_notes || '',
})

// Submit verification
const submitVerification = () => {
  verifyForm.post(route('kew-pa-10.verify', props.kewPA10.id), {
    onSuccess: () => {
      showVerifyDialog.value = false
    },
  })
}

// Create job
const createJob = () => {
  router.post(route('kew-pa-10.create-job', props.kewPA10.id), {}, {
    onSuccess: () => {
      showCreateJobDialog.value = false
    },
  })
}

// Priority badge variant
const getPriorityVariant = (priority: string): 'default' | 'secondary' | 'destructive' => {
  switch (priority.toLowerCase()) {
    case 'urgent':
      return 'destructive'
    case 'high':
      return 'default'
    default:
      return 'secondary'
  }
}

// Check if fully verified
const isFullyVerified = () => {
  return props.kewPA10.form_completeness_verified && props.kewPA10.signatures_verified
}

// Format date
const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('en-MY', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  })
}

const formatDateTime = (dateString: string) => {
  return new Date(dateString).toLocaleString('en-MY', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  })
}
</script>

<template>
  <AppLayout>
    <Head :title="`KEW.PA-10: ${kewPA10.kew_pa_10_number}`" />

    <div class="max-w-6xl space-y-6">
      <!-- Header -->
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
          <Link :href="route('kew-pa-10.index')">
            <Button variant="ghost" size="icon">
              <ArrowLeft class="h-4 w-4" />
            </Button>
          </Link>
          <div>
            <h1 class="text-3xl font-bold tracking-tight">{{ kewPA10.kew_pa_10_number }}</h1>
            <p class="text-muted-foreground">
              KEW.PA-10 Form Details
            </p>
          </div>
        </div>
        <div class="flex gap-2">
          <Link v-if="!kewPA10.workshop_job" :href="route('kew-pa-10.edit', kewPA10.id)">
            <Button variant="outline">
              <Edit class="mr-2 h-4 w-4" />
              Edit
            </Button>
          </Link>
          <Button
            v-if="canVerify"
            @click="showVerifyDialog = true"
            variant="outline"
          >
            <ClipboardCheck class="mr-2 h-4 w-4" />
            Verify Form
          </Button>
          <Button
            v-if="canCreateJob"
            @click="showCreateJobDialog = true"
          >
            <Briefcase class="mr-2 h-4 w-4" />
            Create Workshop Job
          </Button>
        </div>
      </div>

      <!-- Verification Status Alert -->
      <Alert v-if="!isFullyVerified()" variant="default" class="border-yellow-500 bg-yellow-50">
        <AlertCircle class="h-4 w-4 text-yellow-600" />
        <AlertTitle>Verification Required</AlertTitle>
        <AlertDescription>
          This form needs to be verified before a workshop job can be created.
          Please verify form completeness and signatures.
        </AlertDescription>
      </Alert>

      <Alert v-else-if="!kewPA10.workshop_job" class="border-green-500 bg-green-50">
        <CheckCircle class="h-4 w-4 text-green-600" />
        <AlertTitle>Form Verified</AlertTitle>
        <AlertDescription>
          This form has been verified and is ready for workshop job creation.
        </AlertDescription>
      </Alert>

      <!-- Form Details -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Details -->
        <div class="lg:col-span-2 space-y-6">
          <!-- Basic Information -->
          <Card>
            <CardHeader>
              <CardTitle>Form Information</CardTitle>
            </CardHeader>
            <CardContent class="space-y-4">
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <Label class="text-muted-foreground">KEW.PA-10 Number</Label>
                  <p class="font-medium">{{ kewPA10.kew_pa_10_number }}</p>
                </div>
                <div>
                  <Label class="text-muted-foreground">Priority</Label>
                  <div class="mt-1">
                    <Badge :variant="getPriorityVariant(kewPA10.priority)">
                      {{ kewPA10.priority }}
                    </Badge>
                  </div>
                </div>
              </div>

              <div>
                <Label class="text-muted-foreground">Description</Label>
                <p class="mt-1">{{ kewPA10.description }}</p>
              </div>

              <div v-if="kewPA10.budget_allocation_reference">
                <Label class="text-muted-foreground">Budget Allocation Reference</Label>
                <p class="mt-1 font-medium">{{ kewPA10.budget_allocation_reference }}</p>
              </div>

              <div>
                <Label class="text-muted-foreground">Received Date</Label>
                <p class="mt-1 flex items-center gap-2">
                  <Calendar class="h-4 w-4" />
                  {{ formatDate(kewPA10.received_date) }}
                </p>
              </div>
            </CardContent>
          </Card>

          <!-- Department & Asset -->
          <Card>
            <CardHeader>
              <CardTitle>Department & Asset</CardTitle>
            </CardHeader>
            <CardContent class="space-y-4">
              <div>
                <Label class="text-muted-foreground flex items-center gap-2">
                  <Building2 class="h-4 w-4" />
                  Government Department
                </Label>
                <p class="mt-1 font-medium">{{ kewPA10.government_department.name }}</p>
                <p class="text-sm text-muted-foreground">
                  Code: {{ kewPA10.government_department.department_code }}
                </p>
              </div>

              <div>
                <Label class="text-muted-foreground flex items-center gap-2">
                  <FileText class="h-4 w-4" />
                  Asset
                </Label>
                <div class="mt-1">
                  <p class="font-medium">{{ kewPA10.asset.asset_name }}</p>
                  <p class="text-sm text-muted-foreground">
                    Tag: {{ kewPA10.asset.asset_tag }} | Type: {{ kewPA10.asset.asset_type }}
                  </p>
                </div>
              </div>
            </CardContent>
          </Card>

          <!-- Verification Notes -->
          <Card v-if="kewPA10.verification_notes">
            <CardHeader>
              <CardTitle>Verification Notes</CardTitle>
            </CardHeader>
            <CardContent>
              <p>{{ kewPA10.verification_notes }}</p>
            </CardContent>
          </Card>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
          <!-- Verification Status -->
          <Card>
            <CardHeader>
              <CardTitle>Verification Status</CardTitle>
            </CardHeader>
            <CardContent class="space-y-3">
              <div class="flex items-center justify-between">
                <span class="text-sm">Form Completeness</span>
                <CheckCircle
                  v-if="kewPA10.form_completeness_verified"
                  class="h-5 w-5 text-green-600"
                />
                <XCircle v-else class="h-5 w-5 text-red-600" />
              </div>
              <div class="flex items-center justify-between">
                <span class="text-sm">Signatures Verified</span>
                <CheckCircle
                  v-if="kewPA10.signatures_verified"
                  class="h-5 w-5 text-green-600"
                />
                <XCircle v-else class="h-5 w-5 text-red-600" />
              </div>
            </CardContent>
          </Card>

          <!-- Workshop Job -->
          <Card v-if="kewPA10.workshop_job">
            <CardHeader>
              <CardTitle>Workshop Job</CardTitle>
              <CardDescription>Associated repair job</CardDescription>
            </CardHeader>
            <CardContent>
              <Link :href="route('jobs.show', kewPA10.workshop_job.id)">
                <Button variant="outline" class="w-full">
                  <Briefcase class="mr-2 h-4 w-4" />
                  {{ kewPA10.workshop_job.job_reference }}
                </Button>
              </Link>
              <div class="mt-4">
                <Label class="text-muted-foreground">Current Status</Label>
                <Badge class="mt-1">{{ kewPA10.workshop_job.status }}</Badge>
              </div>
            </CardContent>
          </Card>

          <!-- Quick Actions -->
          <Card v-else>
            <CardHeader>
              <CardTitle>Quick Actions</CardTitle>
            </CardHeader>
            <CardContent class="space-y-2">
              <Button
                v-if="canVerify"
                @click="showVerifyDialog = true"
                variant="outline"
                class="w-full"
              >
                <ClipboardCheck class="mr-2 h-4 w-4" />
                Verify Form
              </Button>
              <Button
                v-if="canCreateJob"
                @click="showCreateJobDialog = true"
                class="w-full"
              >
                <Briefcase class="mr-2 h-4 w-4" />
                Create Job
              </Button>
            </CardContent>
          </Card>
        </div>
      </div>
    </div>

    <!-- Verify Form Dialog -->
    <Dialog v-model:open="showVerifyDialog">
      <DialogContent>
        <DialogHeader>
          <DialogTitle>Verify KEW.PA-10 Form</DialogTitle>
          <DialogDescription>
            Verify that the form is complete and all signatures are valid
          </DialogDescription>
        </DialogHeader>

        <div class="space-y-4 py-4">
          <Alert>
            <AlertCircle class="h-4 w-4" />
            <AlertDescription>
              Ensure all required fields are filled and authorized signatures are present
              before verification.
            </AlertDescription>
          </Alert>

          <div class="flex items-center space-x-2">
            <Checkbox
              id="form_completeness"
              v-model:checked="verifyForm.form_completeness_verified"
            />
            <Label for="form_completeness" class="cursor-pointer">
              Form completeness verified (all required fields filled)
            </Label>
          </div>

          <div class="flex items-center space-x-2">
            <Checkbox
              id="signatures"
              v-model:checked="verifyForm.signatures_verified"
            />
            <Label for="signatures" class="cursor-pointer">
              Signatures verified (authorized signatures present and valid)
            </Label>
          </div>

          <div class="space-y-2">
            <Label for="verification_notes">Verification Notes (Optional)</Label>
            <Textarea
              id="verification_notes"
              v-model="verifyForm.verification_notes"
              placeholder="Add any notes about the verification..."
              rows="3"
            />
          </div>
        </div>

        <DialogFooter>
          <Button
            type="button"
            variant="outline"
            @click="showVerifyDialog = false"
            :disabled="verifyForm.processing"
          >
            Cancel
          </Button>
          <Button
            @click="submitVerification"
            :disabled="verifyForm.processing"
          >
            {{ verifyForm.processing ? 'Saving...' : 'Save Verification' }}
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>

    <!-- Create Job Dialog -->
    <Dialog v-model:open="showCreateJobDialog">
      <DialogContent>
        <DialogHeader>
          <DialogTitle>Create Workshop Job</DialogTitle>
          <DialogDescription>
            Create a new workshop job from this verified KEW.PA-10 form
          </DialogDescription>
        </DialogHeader>

        <div class="py-4">
          <Alert>
            <CheckCircle class="h-4 w-4" />
            <AlertTitle>Form Verified</AlertTitle>
            <AlertDescription>
              This form has been verified and is ready for job creation.
              A unique job reference will be automatically generated.
            </AlertDescription>
          </Alert>

          <div class="mt-4 space-y-2 text-sm">
            <p><strong>Department:</strong> {{ kewPA10.government_department.name }}</p>
            <p><strong>Asset:</strong> {{ kewPA10.asset.asset_name }}</p>
            <p><strong>Priority:</strong> {{ kewPA10.priority }}</p>
          </div>
        </div>

        <DialogFooter>
          <Button
            type="button"
            variant="outline"
            @click="showCreateJobDialog = false"
          >
            Cancel
          </Button>
          <Button @click="createJob">
            Create Job
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  </AppLayout>
</template>
