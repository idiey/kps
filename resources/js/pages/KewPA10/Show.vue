<script setup lang="ts">
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import AppLayout from '@/layouts/AppLayout.vue';
import {
    createJob as createJobRoute,
    edit,
    index,
    verify,
} from '@/routes/kew-pa-10';
import { Head, Link, useForm } from '@inertiajs/vue3';
import {
    AlertCircle,
    ArrowLeft,
    Briefcase,
    Building2,
    Calendar,
    CheckCircle,
    ClipboardCheck,
    Edit,
    FileText,
    XCircle,
} from 'lucide-vue-next';
import { computed, ref } from 'vue';

interface GovernmentDepartment {
    id: number;
    name: string;
    department_code: string;
}

interface Asset {
    id: number;
    asset_tag: string;
    asset_name: string;
    asset_type: string;
}

interface User {
    id: number;
    name: string;
}

interface StatusHistory {
    id: number;
    status: string;
    notes?: string;
    created_at: string;
    user: User;
}

interface WorkshopJob {
    id: number;
    job_reference: string;
    status: string;
    status_histories: StatusHistory[];
}

interface KewPA10 {
    id: number;
    kew_pa_10_number: string;
    government_department: GovernmentDepartment;
    asset: Asset;
    description: string;
    priority: string;
    budget_allocation_reference?: string;
    received_date: string;
    form_completeness_verified: boolean;
    signatures_verified: boolean;
    verification_notes?: string;
    workshop_job?: WorkshopJob;
}

interface Props {
    kewPA10: KewPA10;
    canVerify: boolean;
    canCreateJob: boolean;
}

const props = defineProps<Props>();

// Dialog states
const showVerifyDialog = ref(false);
const showCreateJobDialog = ref(false);
const verifyError = ref('');
const verifySuccess = ref(false);

// Separate reactive refs for checkboxes (needed for proper reactivity with reka-ui Checkbox)
const formCompletenessVerified = ref<boolean | 'indeterminate'>(
    props.kewPA10?.form_completeness_verified ?? false,
);
const signaturesVerified = ref<boolean | 'indeterminate'>(
    props.kewPA10?.signatures_verified ?? false,
);

// Verification form
const verifyForm = useForm({
    form_completeness_verified:
        props.kewPA10?.form_completeness_verified ?? false,
    signatures_verified: props.kewPA10?.signatures_verified ?? false,
    verification_notes: props.kewPA10?.verification_notes ?? '',
});

const canSubmitVerification = computed(() => {
    // Both checkboxes must be checked (truthy values)
    const isFormComplete =
        !!formCompletenessVerified.value &&
        formCompletenessVerified.value !== 'indeterminate';
    const isSignaturesVerified =
        !!signaturesVerified.value &&
        signaturesVerified.value !== 'indeterminate';
    const isNotProcessing = !verifyForm.processing;

    return isFormComplete && isSignaturesVerified && isNotProcessing;
});

// Submit verification
function submitVerification() {
    // Get the ID - try multiple sources
    const kewPA10Id = props.kewPA10?.id;

    console.log('submitVerification called');
    console.log('props.kewPA10:', props.kewPA10);
    console.log('kewPA10Id:', kewPA10Id);

    if (!kewPA10Id) {
        verifyError.value = 'Cannot save verification: Record ID not found.';
        console.error('No kewPA10 id found in props:', props.kewPA10);
        return;
    }

    verifyError.value = '';
    verifySuccess.value = false;

    // Sync checkbox values to form before submitting
    verifyForm.form_completeness_verified =
        !!formCompletenessVerified.value &&
        formCompletenessVerified.value !== 'indeterminate';
    verifyForm.signatures_verified =
        !!signaturesVerified.value &&
        signaturesVerified.value !== 'indeterminate';

    // Use the ID directly to construct the URL
    const url = verify.url(kewPA10Id);
    console.log('Posting to:', url);

    verifyForm.post(url, {
        preserveScroll: true,
        onSuccess: (page) => {
            console.log('Success! New page props:', page.props);
            showVerifyDialog.value = false;
            verifySuccess.value = true;
        },
        onError: (errors) => {
            console.log('Error:', errors);
            verifyError.value = 'Please check required fields and try again.';
        },
    });
}

const createJobForm = useForm({
    inspection_required: true,
});

const createJob = () => {
    if (!props.kewPA10?.id) return;
    createJobForm.post(createJobRoute.url(props.kewPA10), {
        onSuccess: () => {
            showCreateJobDialog.value = false;
        },
    });
};

// Priority badge variant
const getPriorityVariant = (
    priority?: string,
): 'default' | 'secondary' | 'destructive' => {
    const p = (priority ?? '').toLowerCase();
    switch (p) {
        case 'urgent':
            return 'destructive';
        case 'high':
            return 'default';
        default:
            return 'secondary';
    }
};

// Check if fully verified - use computed to react to both props and local state after verification
const isFullyVerified = computed(() => {
    // If verification was just successful, return true immediately
    if (verifySuccess.value) {
        return true;
    }
    // Otherwise use props
    return (
        props.kewPA10?.form_completeness_verified &&
        props.kewPA10?.signatures_verified
    );
});

// Format date
const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('en-MY', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
};
</script>

<template>
    <AppLayout>
        <Head
            :title="
                kewPA10 ? `KEW.PA-10: ${kewPA10.kew_pa_10_number}` : 'KEW.PA-10'
            "
        />

        <div v-if="kewPA10" class="max-w-6xl space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Link :href="index.url()">
                        <Button variant="ghost" size="icon">
                            <ArrowLeft class="h-4 w-4" />
                        </Button>
                    </Link>
                    <div>
                        <h1 class="text-3xl font-bold tracking-tight">
                            {{ kewPA10.kew_pa_10_number }}
                        </h1>
                        <p class="text-muted-foreground">
                            KEW.PA-10 Form Details
                        </p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <Link
                        v-if="kewPA10 && kewPA10.id"
                        :href="edit.url(kewPA10.id)"
                    >
                        <Button variant="outline">
                            <Edit class="mr-2 h-4 w-4" />
                            Edit
                        </Button>
                    </Link>
                    <Button
                        v-if="canVerify"
                        @click.stop="showVerifyDialog = true"
                        variant="outline"
                    >
                        <ClipboardCheck class="mr-2 h-4 w-4" />
                        {{ showVerifyDialog ? 'Opening...' : 'Verify Form' }}
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
            <Alert
                v-if="!isFullyVerified"
                variant="default"
                class="border-yellow-500 bg-yellow-50"
            >
                <AlertCircle class="h-4 w-4 text-yellow-600" />
                <AlertTitle>Verification Pending</AlertTitle>
                <AlertDescription>
                    This form has not been verified yet. You can still create a
                    workshop job, but verification is recommended.
                </AlertDescription>
            </Alert>

            <Alert
                v-else-if="!kewPA10.workshop_job"
                class="border-green-500 bg-green-50"
            >
                <CheckCircle class="h-4 w-4 text-green-600" />
                <AlertTitle>Form Verified</AlertTitle>
                <AlertDescription>
                    This form has been verified and is ready for workshop job
                    creation.
                </AlertDescription>
            </Alert>

            <!-- Form Details -->
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <!-- Main Details -->
                <div class="space-y-6 lg:col-span-2">
                    <!-- Basic Information -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Form Information</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <Label class="text-muted-foreground"
                                        >KEW.PA-10 Number</Label
                                    >
                                    <p class="font-medium">
                                        {{ kewPA10.kew_pa_10_number }}
                                    </p>
                                </div>
                                <div>
                                    <Label class="text-muted-foreground"
                                        >Priority</Label
                                    >
                                    <div class="mt-1">
                                        <Badge
                                            :variant="
                                                getPriorityVariant(
                                                    kewPA10.priority,
                                                )
                                            "
                                        >
                                            {{ kewPA10.priority || 'Normal' }}
                                        </Badge>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <Label class="text-muted-foreground"
                                    >Description</Label
                                >
                                <p class="mt-1">{{ kewPA10.description }}</p>
                            </div>

                            <div v-if="kewPA10.budget_allocation_reference">
                                <Label class="text-muted-foreground"
                                    >Budget Allocation Reference</Label
                                >
                                <p class="mt-1 font-medium">
                                    {{ kewPA10.budget_allocation_reference }}
                                </p>
                            </div>

                            <div>
                                <Label class="text-muted-foreground"
                                    >Received Date</Label
                                >
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
                                <Label
                                    class="flex items-center gap-2 text-muted-foreground"
                                >
                                    <Building2 class="h-4 w-4" />
                                    Government Department
                                </Label>
                                <p
                                    v-if="kewPA10.government_department"
                                    class="mt-1 font-medium"
                                >
                                    {{ kewPA10.government_department.name }}
                                </p>
                                <p v-else class="mt-1 text-muted-foreground">
                                    Not provided
                                </p>
                                <p
                                    v-if="kewPA10.government_department"
                                    class="text-sm text-muted-foreground"
                                >
                                    Code:
                                    {{
                                        kewPA10.government_department
                                            .department_code
                                    }}
                                </p>
                            </div>

                            <div>
                                <Label
                                    class="flex items-center gap-2 text-muted-foreground"
                                >
                                    <FileText class="h-4 w-4" />
                                    Asset
                                </Label>
                                <div class="mt-1">
                                    <p v-if="kewPA10.asset" class="font-medium">
                                        {{ kewPA10.asset.asset_name }}
                                    </p>
                                    <p v-else class="text-muted-foreground">
                                        Not provided
                                    </p>
                                    <p
                                        v-if="kewPA10.asset"
                                        class="text-sm text-muted-foreground"
                                    >
                                        Tag: {{ kewPA10.asset.asset_tag }} |
                                        Type: {{ kewPA10.asset.asset_type }}
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

                    <!-- Quick Actions -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Quick Actions</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-2">
                            <Button
                                v-if="canVerify"
                                @click.stop="showVerifyDialog = true"
                                variant="outline"
                                class="w-full"
                            >
                                <ClipboardCheck class="mr-2 h-4 w-4" />
                                {{
                                    showVerifyDialog
                                        ? 'Opening...'
                                        : 'Verify Form'
                                }}
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

        <div v-else class="max-w-6xl space-y-6">
            <Alert variant="default" class="border-yellow-500 bg-yellow-50">
                <AlertCircle class="h-4 w-4 text-yellow-600" />
                <AlertTitle>Form Not Found</AlertTitle>
                <AlertDescription>
                    The KEW.PA-10 record could not be loaded. It may not exist
                    or you may not have access.
                </AlertDescription>
            </Alert>
            <div>
                <Link :href="index.url()">
                    <Button variant="outline">
                        <ArrowLeft class="mr-2 h-4 w-4" />
                        Back to KEW.PA-10 List
                    </Button>
                </Link>
            </div>
        </div>

        <!-- Verify Form Dialog -->
        <Dialog v-model:open="showVerifyDialog">
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Verify KEW.PA-10 Form</DialogTitle>
                    <DialogDescription>
                        Verify that the form is complete and all signatures are
                        valid
                    </DialogDescription>
                </DialogHeader>

                <form @submit.prevent="submitVerification">
                    <div class="space-y-4 py-4">
                        <Alert v-if="verifyError">
                            <AlertCircle class="h-4 w-4" />
                            <AlertDescription>
                                {{ verifyError }}
                            </AlertDescription>
                        </Alert>
                        <Alert>
                            <AlertCircle class="h-4 w-4" />
                            <AlertDescription>
                                Ensure all required fields are filled and
                                authorized signatures are present before
                                verification.
                            </AlertDescription>
                        </Alert>

                        <div class="flex items-center space-x-2">
                            <Checkbox
                                id="form_completeness"
                                v-model:checked="formCompletenessVerified"
                            />
                            <Label
                                for="form_completeness"
                                class="cursor-pointer"
                            >
                                Form completeness verified (all required fields
                                filled)
                            </Label>
                            <p
                                v-if="
                                    verifyForm.errors.form_completeness_verified
                                "
                                class="ml-2 text-sm text-destructive"
                            >
                                {{
                                    verifyForm.errors.form_completeness_verified
                                }}
                            </p>
                        </div>

                        <div class="flex items-center space-x-2">
                            <Checkbox
                                id="signatures"
                                v-model:checked="signaturesVerified"
                            />
                            <Label for="signatures" class="cursor-pointer">
                                Signatures verified (authorized signatures
                                present and valid)
                            </Label>
                            <p
                                v-if="verifyForm.errors.signatures_verified"
                                class="ml-2 text-sm text-destructive"
                            >
                                {{ verifyForm.errors.signatures_verified }}
                            </p>
                        </div>

                        <div class="space-y-2">
                            <Label for="verification_notes"
                                >Verification Notes (Optional)</Label
                            >
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
                        <button
                            type="button"
                            class="inline-flex h-10 items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium whitespace-nowrap text-primary-foreground ring-offset-background transition-colors hover:bg-primary/90 focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:pointer-events-none disabled:opacity-50"
                            :disabled="!canSubmitVerification"
                            @click.stop.prevent="submitVerification"
                        >
                            {{
                                verifyForm.processing
                                    ? 'Saving...'
                                    : 'Save Verification'
                            }}
                        </button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>

        <!-- Create Job Dialog -->
        <Dialog v-model:open="showCreateJobDialog">
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Create Workshop Job</DialogTitle>
                    <DialogDescription>
                        Create a new workshop job from this KEW.PA-10 form
                    </DialogDescription>
                </DialogHeader>

                <div class="py-4">
                    <Alert
                        v-if="isFullyVerified"
                        class="border-green-500 bg-green-50"
                    >
                        <CheckCircle class="h-4 w-4 text-green-600" />
                        <AlertTitle>Form Verified</AlertTitle>
                        <AlertDescription>
                            A unique job reference will be automatically
                            generated.
                        </AlertDescription>
                    </Alert>
                    <Alert v-else class="border-yellow-500 bg-yellow-50">
                        <AlertCircle class="h-4 w-4 text-yellow-600" />
                        <AlertTitle>Form Not Verified</AlertTitle>
                        <AlertDescription>
                            A unique job reference will still be generated.
                        </AlertDescription>
                    </Alert>

                    <div class="mt-4 space-y-2 text-sm">
                        <p>
                            <strong>Department:</strong>
                            {{
                                kewPA10.government_department
                                    ? kewPA10.government_department.name
                                    : 'Not provided'
                            }}
                        </p>
                        <p>
                            <strong>Asset:</strong>
                            {{
                                kewPA10.asset
                                    ? kewPA10.asset.asset_name
                                    : 'Not provided'
                            }}
                        </p>
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
                    <Button @click="createJob"> Create Job </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
