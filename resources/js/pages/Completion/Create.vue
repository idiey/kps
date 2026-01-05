<script setup lang="ts">
import { Alert, AlertDescription } from '@/components/ui/alert';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
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
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { Textarea } from '@/components/ui/textarea';
import AppLayout from '@/layouts/AppLayout.vue';
import { store } from '@/routes/completion';
import { show as showJob } from '@/routes/jobs';
import { index as photoIndex } from '@/routes/jobs/photos';
import { Head, Link, useForm } from '@inertiajs/vue3';
import {
    AlertCircle,
    ArrowLeft,
    CheckCircle,
    Image as ImageIcon,
    Plus,
    Save,
    Trash2,
    XCircle,
} from 'lucide-vue-next';
import { computed, ref } from 'vue';

interface Asset {
    id: number;
    asset_tag: string;
    asset_name: string;
}

interface User {
    id: number;
    name: string;
}

interface WorkshopJob {
    id: number;
    job_reference: string;
    kew_pa_10?: never;
    asset?: Asset;
    assigned_user?: User;
}
interface PhotoRequirements {
    [key: string]: {
        count: number;
        minimum_required: number;
        has_minimum: boolean;
    };
}

interface QualityRating {
    value: number;
    label: string;
}

interface Props {
    job: WorkshopJob;
    photoRequirements: PhotoRequirements;
    qualityRatings: QualityRating[];
}

const props = defineProps<Props>();

// Parts management
interface Part {
    name: string;
    quantity: number;
    cost: number;
}

const parts = ref<Part[]>([]);
const showAddPartDialog = ref(false);

const partForm = useForm({
    name: '',
    quantity: 1,
    cost: 0,
});

// Main completion form
const form = useForm({
    work_completed: true,
    time_spent_hours: 0,
    work_description: '',
    issues_encountered: '',
    recommendations: '',
    quality_rating: 3,
    parts_used: [] as Part[],
});

// Add part
const addPart = () => {
    if (partForm.name && partForm.quantity > 0 && partForm.cost >= 0) {
        parts.value.push({
            name: partForm.name,
            quantity: partForm.quantity,
            cost: partForm.cost,
        });
        form.parts_used = parts.value;
        partForm.reset();
        showAddPartDialog.value = false;
    }
};

// Remove part
const removePart = (index: number) => {
    parts.value.splice(index, 1);
    form.parts_used = parts.value;
};

// Calculate total cost
const totalCost = computed(() => {
    return parts.value.reduce(
        (sum, part) => sum + part.quantity * part.cost,
        0,
    );
});

// Check photo requirements
const hasAllRequiredPhotos = computed(() => {
    return Object.values(props.photoRequirements).every(
        (req) => req.has_minimum,
    );
});

const missingPhotoStages = computed(() => {
    return Object.entries(props.photoRequirements)
        .filter(([, req]) => !req.has_minimum)
        .map(([stage]) => stage);
});

// Submit form
const submit = () => {
    form.post(store.url(props.job.id));
};

// Format currency
const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('en-MY', {
        style: 'currency',
        currency: 'MYR',
    }).format(amount);
};
</script>

<template>
    <AppLayout>
        <Head :title="`Completion Report - ${job.job_reference}`" />

        <div class="max-w-5xl space-y-6">
            <!-- Header -->
            <div class="flex items-center gap-4">
                <Button variant="ghost" size="icon" as-child>
                    <Link :href="showJob.url(job.id)">
                        <ArrowLeft class="h-4 w-4" />
                    </Link>
                </Button>
                <div>
                    <h1 class="text-3xl font-bold tracking-tight">
                        Create Completion Report
                    </h1>
                    <p class="text-muted-foreground">
                        {{ job.job_reference }}
                    </p>
                </div>
            </div>

            <!-- Photo Requirements Alert -->
            <Alert
                v-if="!hasAllRequiredPhotos"
                variant="default"
                class="border-yellow-500 bg-yellow-50"
            >
                <AlertCircle class="h-4 w-4 text-yellow-600" />
                <AlertDescription>
                    <strong>Missing required photos:</strong>
                    You need to upload photos for the following stages before
                    submitting:
                    {{ missingPhotoStages.join(', ') }}
                    <Link :href="photoIndex.url(job.id)" class="ml-2 underline">
                        Upload Photos
                    </Link>
                </AlertDescription>
            </Alert>

            <Alert v-else class="border-green-500 bg-green-50">
                <CheckCircle class="h-4 w-4 text-green-600" />
                <AlertDescription>
                    All required photos have been uploaded. You can proceed with
                    the completion report.
                </AlertDescription>
            </Alert>

            <!-- Job Info -->
            <Card>
                <CardHeader>
                    <CardTitle>Job Information</CardTitle>
                </CardHeader>
                <CardContent class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div v-if="job.asset">
                        <Label class="text-muted-foreground">Asset</Label>
                        <p class="font-medium">{{ job.asset.asset_name }}</p>
                        <p class="text-sm text-muted-foreground">
                            {{ job.asset.asset_tag }}
                        </p>
                    </div>
                    <div v-if="job.assigned_user">
                        <Label class="text-muted-foreground"
                            >Assigned Technician</Label
                        >
                        <p class="font-medium">{{ job.assigned_user.name }}</p>
                    </div>
                </CardContent>
            </Card>

            <!-- Completion Form -->
            <form @submit.prevent="submit">
                <!-- Work Details -->
                <Card class="mb-6">
                    <CardHeader>
                        <CardTitle>Work Details</CardTitle>
                        <CardDescription>
                            Provide comprehensive details about the repair work
                            completed
                        </CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-6">
                        <!-- Error Alert -->
                        <Alert v-if="form.hasErrors" variant="destructive">
                            <AlertCircle class="h-4 w-4" />
                            <AlertDescription>
                                Please correct the errors below before
                                submitting.
                            </AlertDescription>
                        </Alert>

                        <!-- Work Completed -->
                        <div class="flex items-center space-x-2">
                            <input
                                id="work_completed"
                                v-model="form.work_completed"
                                type="checkbox"
                                class="rounded"
                            />
                            <Label for="work_completed" class="cursor-pointer">
                                All repair work has been completed
                            </Label>
                        </div>

                        <!-- Time Spent -->
                        <div class="space-y-2">
                            <Label for="time_spent_hours">
                                Time Spent (Hours)
                                <span class="text-destructive">*</span>
                            </Label>
                            <Input
                                id="time_spent_hours"
                                v-model.number="form.time_spent_hours"
                                type="number"
                                step="0.5"
                                min="0.1"
                                placeholder="e.g., 8.5"
                                :class="{
                                    'border-destructive':
                                        form.errors.time_spent_hours,
                                }"
                            />
                            <p
                                v-if="form.errors.time_spent_hours"
                                class="text-sm text-destructive"
                            >
                                {{ form.errors.time_spent_hours }}
                            </p>
                        </div>

                        <!-- Work Description -->
                        <div class="space-y-2">
                            <Label for="work_description">
                                Work Description
                                <span class="text-destructive">*</span>
                            </Label>
                            <Textarea
                                id="work_description"
                                v-model="form.work_description"
                                placeholder="Describe in detail the work that was performed..."
                                rows="5"
                                :class="{
                                    'border-destructive':
                                        form.errors.work_description,
                                }"
                            />
                            <p
                                v-if="form.errors.work_description"
                                class="text-sm text-destructive"
                            >
                                {{ form.errors.work_description }}
                            </p>
                            <p class="text-sm text-muted-foreground">
                                Include specific repairs, procedures followed,
                                and any relevant technical details
                            </p>
                        </div>

                        <!-- Issues Encountered -->
                        <div class="space-y-2">
                            <Label for="issues_encountered"
                                >Issues Encountered (Optional)</Label
                            >
                            <Textarea
                                id="issues_encountered"
                                v-model="form.issues_encountered"
                                placeholder="Describe any challenges or unexpected issues encountered..."
                                rows="3"
                            />
                            <p class="text-sm text-muted-foreground">
                                Document any problems that arose during the
                                repair process
                            </p>
                        </div>

                        <!-- Recommendations -->
                        <div class="space-y-2">
                            <Label for="recommendations"
                                >Recommendations (Optional)</Label
                            >
                            <Textarea
                                id="recommendations"
                                v-model="form.recommendations"
                                placeholder="Provide recommendations for future maintenance or improvements..."
                                rows="3"
                            />
                        </div>

                        <!-- Quality Rating -->
                        <div class="space-y-2">
                            <Label for="quality_rating">
                                Quality Rating
                                <span class="text-destructive">*</span>
                            </Label>
                            <Select v-model="form.quality_rating">
                                <SelectTrigger id="quality_rating">
                                    <SelectValue
                                        placeholder="Select quality rating"
                                    />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem
                                        v-for="rating in qualityRatings"
                                        :key="rating.value"
                                        :value="rating.value.toString()"
                                    >
                                        {{ rating.label }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                            <p
                                v-if="form.errors.quality_rating"
                                class="text-sm text-destructive"
                            >
                                {{ form.errors.quality_rating }}
                            </p>
                            <p class="text-sm text-muted-foreground">
                                Rate the overall quality of the repair work
                                completed
                            </p>
                        </div>
                    </CardContent>
                </Card>

                <!-- Parts Used -->
                <Card class="mb-6">
                    <CardHeader>
                        <div class="flex items-center justify-between">
                            <div>
                                <CardTitle>Parts Used</CardTitle>
                                <CardDescription>
                                    Track all parts and materials used during
                                    the repair
                                </CardDescription>
                            </div>
                            <Button
                                type="button"
                                variant="outline"
                                @click="showAddPartDialog = true"
                            >
                                <Plus class="mr-2 h-4 w-4" />
                                Add Part
                            </Button>
                        </div>
                    </CardHeader>
                    <CardContent>
                        <Alert
                            v-if="parts.length === 0"
                            variant="default"
                            class="mb-4"
                        >
                            <AlertCircle class="h-4 w-4" />
                            <AlertDescription>
                                At least one part must be added. Click "Add
                                Part" to begin.
                            </AlertDescription>
                        </Alert>

                        <div v-if="parts.length > 0">
                            <Table>
                                <TableHeader>
                                    <TableRow>
                                        <TableHead>Part Name</TableHead>
                                        <TableHead class="text-right"
                                            >Quantity</TableHead
                                        >
                                        <TableHead class="text-right"
                                            >Unit Cost</TableHead
                                        >
                                        <TableHead class="text-right"
                                            >Total</TableHead
                                        >
                                        <TableHead class="text-right"
                                            >Actions</TableHead
                                        >
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    <TableRow
                                        v-for="(part, index) in parts"
                                        :key="index"
                                    >
                                        <TableCell class="font-medium">{{
                                            part.name
                                        }}</TableCell>
                                        <TableCell class="text-right">{{
                                            part.quantity
                                        }}</TableCell>
                                        <TableCell class="text-right">{{
                                            formatCurrency(part.cost)
                                        }}</TableCell>
                                        <TableCell class="text-right">
                                            {{
                                                formatCurrency(
                                                    part.quantity * part.cost,
                                                )
                                            }}
                                        </TableCell>
                                        <TableCell class="text-right">
                                            <Button
                                                type="button"
                                                variant="ghost"
                                                size="sm"
                                                @click="removePart(index)"
                                            >
                                                <Trash2
                                                    class="h-4 w-4 text-destructive"
                                                />
                                            </Button>
                                        </TableCell>
                                    </TableRow>
                                    <TableRow>
                                        <TableCell
                                            colspan="3"
                                            class="text-right font-bold"
                                            >Total Cost:</TableCell
                                        >
                                        <TableCell class="text-right font-bold">
                                            {{ formatCurrency(totalCost) }}
                                        </TableCell>
                                        <TableCell></TableCell>
                                    </TableRow>
                                </TableBody>
                            </Table>
                        </div>

                        <p
                            v-if="form.errors.parts_used"
                            class="mt-2 text-sm text-destructive"
                        >
                            {{ form.errors.parts_used }}
                        </p>
                    </CardContent>
                </Card>

                <!-- Photo Summary -->
                <Card class="mb-6">
                    <CardHeader>
                        <CardTitle class="flex items-center gap-2">
                            <ImageIcon class="h-5 w-5" />
                            Photo Summary
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-2">
                            <div
                                v-for="(req, stage) in photoRequirements"
                                :key="stage"
                                class="flex items-center justify-between"
                            >
                                <span class="text-sm">{{ stage }}</span>
                                <div class="flex items-center gap-2">
                                    <Badge variant="secondary">
                                        {{ req.count }} /
                                        {{ req.minimum_required }} photos
                                    </Badge>
                                    <CheckCircle
                                        v-if="req.has_minimum"
                                        class="h-5 w-5 text-green-600"
                                    />
                                    <XCircle
                                        v-else
                                        class="h-5 w-5 text-red-600"
                                    />
                                </div>
                            </div>
                        </div>

                        <Button variant="outline" size="sm" as-child>
                            <Link
                                :href="photoIndex.url(job.id)"
                                target="_blank"
                                class="mt-4 flex items-center gap-2"
                            >
                                <ImageIcon class="h-4 w-4" />
                                View Photos
                            </Link>
                        </Button>
                    </CardContent>
                </Card>

                <!-- Form Actions -->
                <div class="flex items-center justify-end gap-4">
                    <Button variant="outline" type="button" as-child>
                        <Link :href="showJob.url(job.id)"> Cancel </Link>
                    </Button>
                    <Button
                        type="submit"
                        :disabled="form.processing || parts.length === 0"
                    >
                        <Save class="mr-2 h-4 w-4" />
                        {{
                            form.processing
                                ? 'Saving...'
                                : 'Save Completion Report'
                        }}
                    </Button>
                </div>
            </form>
        </div>

        <!-- Add Part Dialog -->
        <Dialog v-model:open="showAddPartDialog">
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Add Part</DialogTitle>
                    <DialogDescription>
                        Add a part or material used during the repair
                    </DialogDescription>
                </DialogHeader>

                <div class="space-y-4 py-4">
                    <div class="space-y-2">
                        <Label for="part_name">
                            Part Name <span class="text-destructive">*</span>
                        </Label>
                        <Input
                            id="part_name"
                            v-model="partForm.name"
                            type="text"
                            placeholder="e.g., Brake Pad, Oil Filter"
                        />
                    </div>

                    <div class="space-y-2">
                        <Label for="part_quantity">
                            Quantity <span class="text-destructive">*</span>
                        </Label>
                        <Input
                            id="part_quantity"
                            v-model.number="partForm.quantity"
                            type="number"
                            min="1"
                            placeholder="1"
                        />
                    </div>

                    <div class="space-y-2">
                        <Label for="part_cost">
                            Unit Cost (MYR)
                            <span class="text-destructive">*</span>
                        </Label>
                        <Input
                            id="part_cost"
                            v-model.number="partForm.cost"
                            type="number"
                            step="0.01"
                            min="0"
                            placeholder="0.00"
                        />
                    </div>

                    <div
                        v-if="partForm.quantity > 0 && partForm.cost > 0"
                        class="border-t pt-2"
                    >
                        <div class="flex items-center justify-between">
                            <span class="font-medium">Total Cost:</span>
                            <span class="text-lg font-bold">
                                {{
                                    formatCurrency(
                                        partForm.quantity * partForm.cost,
                                    )
                                }}
                            </span>
                        </div>
                    </div>
                </div>

                <DialogFooter>
                    <Button
                        type="button"
                        variant="outline"
                        @click="showAddPartDialog = false"
                    >
                        Cancel
                    </Button>
                    <Button
                        @click="addPart"
                        :disabled="
                            !partForm.name ||
                            partForm.quantity <= 0 ||
                            partForm.cost < 0
                        "
                    >
                        Add Part
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
