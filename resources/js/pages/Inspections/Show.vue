<script setup lang="ts">
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import AppLayout from '@/layouts/AppLayout.vue';
import {
    approve,
    approveConditions,
    edit,
    index,
    reject,
    update,
} from '@/routes/inspections';
import { show as showJob } from '@/routes/jobs';
import { Head, Link, useForm } from '@inertiajs/vue3';
import {
    AlertCircle,
    ArrowLeft,
    Calendar,
    CheckCircle,
    Edit,
    Image as ImageIcon,
    User,
    XCircle,
} from 'lucide-vue-next';
import { ref } from 'vue';

interface Asset {
    id: number;
    asset_tag: string;
    asset_name: string;
    asset_type: string;
}

interface GovernmentDepartment {
    id: number;
    name: string;
}

interface WorkshopJob {
    id: number;
    job_reference: string;
    asset?: Asset;
    government_department?: GovernmentDepartment;
}

interface Inspector {
    id: number;
    name: string;
    email: string;
}

interface PhotoUser {
    id: number;
    name: string;
}

interface Photo {
    id: number;
    file_path: string;
    photo_stage: string;
    description?: string;
    created_at: string;
    user: PhotoUser;
}

interface InspectionReport {
    id: number;
    workshop_job: WorkshopJob;
    inspector: Inspector;
    asset_condition_current?: string;
    visual_damage_assessment?: string;
    functional_testing_results?: string;
    safety_hazards_identified?: string;
    additional_issues_discovered?: string;
    recommended_repairs?: string;
    approval_status: string;
    approval_notes?: string;
    rejection_reason?: string;
    digital_signature?: string;
    created_at: string;
    photos?: Photo[];
}

interface PhotoStage {
    value: string;
    label: string;
}

interface Props {
    inspection: InspectionReport;
    photoStages: PhotoStage[];
    canApprove: boolean;
    canReject: boolean;
}

const props = defineProps<Props>();

// Dialog states
const showApproveDialog = ref(false);
const showApproveWithConditionsDialog = ref(false);
const showRejectDialog = ref(false);
const showEditDialog = ref(false);

// Approve form
const approveForm = useForm({
    notes: '',
    digital_signature: '',
});

// Approve with conditions form
const approveConditionsForm = useForm({
    conditions: '',
    digital_signature: '',
});

// Reject form
const rejectForm = useForm({
    reason: '',
    digital_signature: '',
});

// Edit form
const editForm = useForm({
    asset_condition_current: props.inspection.asset_condition_current || '',
    visual_damage_assessment: props.inspection.visual_damage_assessment || '',
    functional_testing_results:
        props.inspection.functional_testing_results || '',
    safety_hazards_identified: props.inspection.safety_hazards_identified || '',
    additional_issues_discovered:
        props.inspection.additional_issues_discovered || '',
    recommended_repairs: props.inspection.recommended_repairs || '',
});

// Submit approval
const submitApproval = () => {
    approveForm.post(approve.url(props.inspection.id), {
        onSuccess: () => {
            showApproveDialog.value = false;
        },
    });
};

// Submit approval with conditions
const submitApprovalWithConditions = () => {
    approveConditionsForm.post(approveConditions.url(props.inspection.id), {
        onSuccess: () => {
            showApproveWithConditionsDialog.value = false;
        },
    });
};

// Submit rejection
const rejectInspection = () => {
    rejectForm.post(reject.url(props.inspection.id), {
        onSuccess: () => {
            showRejectDialog.value = false;
        },
    });
};

// Submit edit
const submitEdit = () => {
    editForm.put(update.url(props.inspection.id), {
        onSuccess: () => {
            showEditDialog.value = false;
        },
    });
};

// Get status badge variant
const getStatusBadgeVariant = (
    status: string,
): 'default' | 'secondary' | 'destructive' => {
    switch (status.toLowerCase()) {
        case 'approved':
            return 'default';
        case 'rejected':
            return 'destructive';
        default:
            return 'secondary';
    }
};

// Format date
const formatDateTime = (dateString: string) => {
    return new Date(dateString).toLocaleString('en-MY', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};

// Get photo URL
const getPhotoUrl = (path: string) => {
    return `/storage/${path}`;
};
</script>

<template>
    <AppLayout>
        <Head
            :title="`Inspection - ${inspection.workshop_job.job_reference}`"
        />

        <div class="max-w-6xl space-y-6">
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
                            Inspection Report
                        </h1>
                        <p class="text-muted-foreground">
                            {{ inspection.workshop_job.job_reference }}
                        </p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <Link
                        v-if="inspection.approval_status === 'pending'"
                        :href="edit.url(inspection.id)"
                    >
                        <Button variant="outline">
                            <Edit class="mr-2 h-4 w-4" />
                            Edit Report
                        </Button>
                    </Link>
                    <Button
                        v-if="canApprove"
                        @click="showApproveDialog = true"
                        variant="default"
                    >
                        <CheckCircle class="mr-2 h-4 w-4" />
                        Approve
                    </Button>
                    <Button
                        v-if="canApprove"
                        @click="showApproveWithConditionsDialog = true"
                        variant="outline"
                    >
                        Approve with Conditions
                    </Button>
                    <Button
                        v-if="canReject"
                        @click="showRejectDialog = true"
                        variant="destructive"
                    >
                        <XCircle class="mr-2 h-4 w-4" />
                        Reject
                    </Button>
                </div>
            </div>

            <!-- Status Alert -->
            <Alert
                v-if="inspection.approval_status === 'approved'"
                class="border-green-500 bg-green-50"
            >
                <CheckCircle class="h-4 w-4 text-green-600" />
                <AlertTitle>Inspection Approved</AlertTitle>
                <AlertDescription v-if="inspection.approval_notes">
                    {{ inspection.approval_notes }}
                </AlertDescription>
            </Alert>

            <Alert
                v-else-if="inspection.approval_status === 'rejected'"
                variant="destructive"
            >
                <XCircle class="h-4 w-4" />
                <AlertTitle>Inspection Rejected</AlertTitle>
                <AlertDescription v-if="inspection.rejection_reason">
                    {{ inspection.rejection_reason }}
                </AlertDescription>
            </Alert>

            <Alert
                v-else
                variant="default"
                class="border-yellow-500 bg-yellow-50"
            >
                <AlertCircle class="h-4 w-4 text-yellow-600" />
                <AlertTitle>Pending Review</AlertTitle>
                <AlertDescription>
                    This inspection is awaiting approval or rejection.
                </AlertDescription>
            </Alert>

            <!-- Main Content -->
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <!-- Inspection Details -->
                <div class="space-y-6 lg:col-span-2">
                    <!-- Job Information -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Job Information</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <Label class="text-muted-foreground"
                                        >Job Reference</Label
                                    >
                                    <Link
                                        :href="
                                            showJob.url(
                                                inspection.workshop_job.id,
                                            )
                                        "
                                        class="font-medium text-primary hover:underline"
                                    >
                                        {{
                                            inspection.workshop_job
                                                .job_reference
                                        }}
                                    </Link>
                                </div>
                            </div>

                            <div v-if="inspection.workshop_job.asset">
                                <Label class="text-muted-foreground"
                                    >Asset</Label
                                >
                                <p class="font-medium">
                                    {{
                                        inspection.workshop_job.asset.asset_name
                                    }}
                                </p>
                                <p class="text-sm text-muted-foreground">
                                    {{
                                        inspection.workshop_job.asset.asset_tag
                                    }}
                                    -
                                    {{
                                        inspection.workshop_job.asset.asset_type
                                    }}
                                </p>
                            </div>

                            <div
                                v-if="
                                    inspection.workshop_job
                                        .government_department
                                "
                            >
                                <Label class="text-muted-foreground"
                                    >Government Department</Label
                                >
                                <p class="font-medium">
                                    {{
                                        inspection.workshop_job
                                            .government_department.name
                                    }}
                                </p>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Assessment Details -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Assessment Details</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div v-if="inspection.asset_condition_current">
                                <Label class="text-muted-foreground"
                                    >Current Asset Condition</Label
                                >
                                <p class="mt-1">
                                    {{ inspection.asset_condition_current }}
                                </p>
                            </div>

                            <div v-if="inspection.visual_damage_assessment">
                                <Label class="text-muted-foreground"
                                    >Visual Damage Assessment</Label
                                >
                                <p class="mt-1">
                                    {{ inspection.visual_damage_assessment }}
                                </p>
                            </div>

                            <div v-if="inspection.functional_testing_results">
                                <Label class="text-muted-foreground"
                                    >Functional Testing Results</Label
                                >
                                <p class="mt-1">
                                    {{ inspection.functional_testing_results }}
                                </p>
                            </div>

                            <div v-if="inspection.safety_hazards_identified">
                                <Label class="text-muted-foreground"
                                    >Safety Hazards Identified</Label
                                >
                                <p class="mt-1">
                                    {{ inspection.safety_hazards_identified }}
                                </p>
                            </div>

                            <div v-if="inspection.additional_issues_discovered">
                                <Label class="text-muted-foreground"
                                    >Additional Issues Discovered</Label
                                >
                                <p class="mt-1">
                                    {{
                                        inspection.additional_issues_discovered
                                    }}
                                </p>
                            </div>

                            <div v-if="inspection.recommended_repairs">
                                <Label class="text-muted-foreground"
                                    >Recommended Repairs</Label
                                >
                                <p class="mt-1">
                                    {{ inspection.recommended_repairs }}
                                </p>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Inspection Photos -->
                    <Card
                        v-if="inspection.photos && inspection.photos.length > 0"
                    >
                        <CardHeader>
                            <CardTitle class="flex items-center gap-2">
                                <ImageIcon class="h-5 w-5" />
                                Inspection Photos ({{
                                    inspection.photos.length
                                }})
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="grid grid-cols-3 gap-4 md:grid-cols-4">
                                <div
                                    v-for="photo in inspection.photos"
                                    :key="photo.id"
                                    class="aspect-square overflow-hidden rounded-lg bg-muted"
                                >
                                    <img
                                        :src="getPhotoUrl(photo.file_path)"
                                        :alt="
                                            photo.description ||
                                            'Inspection photo'
                                        "
                                        class="h-full w-full object-cover"
                                    />
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Status Card -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Status</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <Badge
                                :variant="
                                    getStatusBadgeVariant(
                                        inspection.approval_status,
                                    )
                                "
                                class="text-base"
                            >
                                {{ inspection.approval_status }}
                            </Badge>
                        </CardContent>
                    </Card>

                    <!-- Inspector Info -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Inspector</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-2">
                            <div class="flex items-center gap-2">
                                <User class="h-4 w-4 text-muted-foreground" />
                                <span class="font-medium">{{
                                    inspection.inspector.name
                                }}</span>
                            </div>
                            <p class="text-sm text-muted-foreground">
                                {{ inspection.inspector.email }}
                            </p>
                        </CardContent>
                    </Card>

                    <!-- Timeline -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Timeline</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-3">
                            <div class="flex items-start gap-2">
                                <Calendar
                                    class="mt-0.5 h-4 w-4 text-muted-foreground"
                                />
                                <div>
                                    <p class="text-sm font-medium">Created</p>
                                    <p class="text-sm text-muted-foreground">
                                        {{
                                            formatDateTime(
                                                inspection.created_at,
                                            )
                                        }}
                                    </p>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Digital Signature -->
                    <Card v-if="inspection.digital_signature">
                        <CardHeader>
                            <CardTitle>Digital Signature</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="rounded-lg bg-muted p-4">
                                <p class="font-mono text-sm break-all">
                                    {{ inspection.digital_signature }}
                                </p>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>

        <!-- Approve Dialog -->
        <Dialog v-model:open="showApproveDialog">
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Approve Inspection</DialogTitle>
                    <DialogDescription>
                        Approve this inspection report to proceed with repairs
                    </DialogDescription>
                </DialogHeader>

                <div class="space-y-4 py-4">
                    <Alert>
                        <CheckCircle class="h-4 w-4" />
                        <AlertDescription>
                            By approving, you confirm that the inspection
                            findings are accurate and the recommended repairs
                            are appropriate.
                        </AlertDescription>
                    </Alert>

                    <div class="space-y-2">
                        <Label for="approve_notes"
                            >Approval Notes (Optional)</Label
                        >
                        <Textarea
                            id="approve_notes"
                            v-model="approveForm.notes"
                            placeholder="Add any approval notes..."
                            rows="3"
                        />
                    </div>

                    <div class="space-y-2">
                        <Label for="approve_signature">
                            Digital Signature
                            <span class="text-destructive">*</span>
                        </Label>
                        <Input
                            id="approve_signature"
                            v-model="approveForm.digital_signature"
                            type="text"
                            placeholder="Enter your full name to sign"
                        />
                    </div>
                </div>

                <DialogFooter>
                    <Button
                        type="button"
                        variant="outline"
                        @click="showApproveDialog = false"
                        :disabled="approveForm.processing"
                    >
                        Cancel
                    </Button>
                    <Button
                        @click="submitApproval"
                        :disabled="
                            approveForm.processing ||
                            !approveForm.digital_signature
                        "
                    >
                        {{
                            approveForm.processing
                                ? 'Approving...'
                                : 'Approve Inspection'
                        }}
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>

        <!-- Approve with Conditions Dialog -->
        <Dialog v-model:open="showApproveWithConditionsDialog">
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Approve with Conditions</DialogTitle>
                    <DialogDescription>
                        Approve with additional conditions or requirements
                    </DialogDescription>
                </DialogHeader>

                <div class="space-y-4 py-4">
                    <div class="space-y-2">
                        <Label for="conditions">
                            Conditions <span class="text-destructive">*</span>
                        </Label>
                        <Textarea
                            id="conditions"
                            v-model="approveConditionsForm.conditions"
                            placeholder="Specify conditions that must be met..."
                            rows="4"
                        />
                    </div>

                    <div class="space-y-2">
                        <Label for="conditions_signature">
                            Digital Signature
                            <span class="text-destructive">*</span>
                        </Label>
                        <Input
                            id="conditions_signature"
                            v-model="approveConditionsForm.digital_signature"
                            type="text"
                            placeholder="Enter your full name to sign"
                        />
                    </div>
                </div>

                <DialogFooter>
                    <Button
                        type="button"
                        variant="outline"
                        @click="showApproveWithConditionsDialog = false"
                        :disabled="approveConditionsForm.processing"
                    >
                        Cancel
                    </Button>
                    <Button
                        @click="submitApprovalWithConditions"
                        :disabled="
                            approveConditionsForm.processing ||
                            !approveConditionsForm.conditions ||
                            !approveConditionsForm.digital_signature
                        "
                    >
                        {{
                            approveConditionsForm.processing
                                ? 'Approving...'
                                : 'Approve with Conditions'
                        }}
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>

        <!-- Reject Dialog -->
        <Dialog v-model:open="showRejectDialog">
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Reject Inspection</DialogTitle>
                    <DialogDescription>
                        Reject this inspection and provide a reason
                    </DialogDescription>
                </DialogHeader>

                <div class="space-y-4 py-4">
                    <Alert variant="destructive">
                        <XCircle class="h-4 w-4" />
                        <AlertDescription>
                            Rejecting will require a new inspection to be
                            performed. Please provide a clear reason for
                            rejection.
                        </AlertDescription>
                    </Alert>

                    <div class="space-y-2">
                        <Label for="reject_reason">
                            Rejection Reason
                            <span class="text-destructive">*</span>
                        </Label>
                        <Textarea
                            id="reject_reason"
                            v-model="rejectForm.reason"
                            placeholder="Explain why this inspection is being rejected..."
                            rows="4"
                        />
                    </div>

                    <div class="space-y-2">
                        <Label for="reject_signature">
                            Digital Signature
                            <span class="text-destructive">*</span>
                        </Label>
                        <Input
                            id="reject_signature"
                            v-model="rejectForm.digital_signature"
                            type="text"
                            placeholder="Enter your full name to sign"
                        />
                    </div>
                </div>

                <DialogFooter>
                    <Button
                        type="button"
                        variant="outline"
                        @click="showRejectDialog = false"
                        :disabled="rejectForm.processing"
                    >
                        Cancel
                    </Button>
                    <Button
                        variant="destructive"
                        @click="submitRejection"
                        :disabled="
                            rejectForm.processing ||
                            !rejectForm.reason ||
                            !rejectForm.digital_signature
                        "
                    >
                        {{
                            rejectForm.processing
                                ? 'Rejecting...'
                                : 'Reject Inspection'
                        }}
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
