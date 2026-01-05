<script setup lang="ts">
import { Alert, AlertDescription } from '@/components/ui/alert';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';
import AppLayout from '@/layouts/AppLayout.vue';
import { index, store } from '@/routes/kew-pa-10';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { AlertCircle, ArrowLeft, Save } from 'lucide-vue-next';

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

interface Priority {
    value: string;
    label: string;
}

interface Props {
    governmentDepartments: GovernmentDepartment[];
    assets: Asset[];
    priorities: Priority[];
}

defineProps<Props>();

const form = useForm({
    kew_pa_10_number: '',
    government_department_id: '',
    asset_id: '',
    description: '',
    priority: 'normal',
    budget_allocation_reference: '',
    received_date: new Date().toISOString().split('T')[0],
});

const submit = () => {
    form.post(store.url());
};
</script>

<template>
    <AppLayout>
        <Head title="Register KEW.PA-10 Form" />

        <div class="max-w-4xl space-y-6">
            <!-- Header -->
            <div class="flex items-center gap-4">
                <Link :href="index.url()">
                    <Button variant="ghost" size="icon">
                        <ArrowLeft class="h-4 w-4" />
                    </Button>
                </Link>
                <div>
                    <h1 class="text-3xl font-bold tracking-tight">
                        Register KEW.PA-10 Form
                    </h1>
                    <p class="text-muted-foreground">
                        Register a new government procurement/repair
                        authorization form
                    </p>
                </div>
            </div>

            <!-- Form -->
            <form @submit.prevent="submit">
                <Card>
                    <CardHeader>
                        <CardTitle>Form Details</CardTitle>
                        <CardDescription>
                            Enter the details from the received KEW.PA-10 form
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

                        <!-- KEW.PA-10 Number -->
                        <div class="space-y-2">
                            <Label for="kew_pa_10_number">
                                KEW.PA-10 Number
                                <span class="text-destructive">*</span>
                            </Label>
                            <Input
                                id="kew_pa_10_number"
                                v-model="form.kew_pa_10_number"
                                type="text"
                                placeholder="e.g., KEW.PA-10/2025/001"
                                :class="{
                                    'border-destructive':
                                        form.errors.kew_pa_10_number,
                                }"
                            />
                            <p
                                v-if="form.errors.kew_pa_10_number"
                                class="text-sm text-destructive"
                            >
                                {{ form.errors.kew_pa_10_number }}
                            </p>
                            <p class="text-sm text-muted-foreground">
                                Enter the unique KEW.PA-10 form number from the
                                document
                            </p>
                        </div>

                        <!-- Government Department -->
                        <div class="space-y-2">
                            <Label for="government_department_id">
                                Government Department
                                <span class="text-destructive">*</span>
                            </Label>
                            <Select v-model="form.government_department_id">
                                <SelectTrigger
                                    id="government_department_id"
                                    :class="{
                                        'border-destructive':
                                            form.errors
                                                .government_department_id,
                                    }"
                                >
                                    <SelectValue
                                        placeholder="Select department"
                                    />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem
                                        v-for="dept in governmentDepartments"
                                        :key="dept.id"
                                        :value="dept.id.toString()"
                                    >
                                        {{ dept.name }} ({{
                                            dept.department_code
                                        }})
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                            <p
                                v-if="form.errors.government_department_id"
                                class="text-sm text-destructive"
                            >
                                {{ form.errors.government_department_id }}
                            </p>
                        </div>

                        <!-- Asset -->
                        <div class="space-y-2">
                            <Label for="asset_id">
                                Asset <span class="text-destructive">*</span>
                            </Label>
                            <Select v-model="form.asset_id">
                                <SelectTrigger
                                    id="asset_id"
                                    :class="{
                                        'border-destructive':
                                            form.errors.asset_id,
                                    }"
                                >
                                    <SelectValue placeholder="Select asset" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem
                                        v-for="asset in assets"
                                        :key="asset.id"
                                        :value="asset.id.toString()"
                                    >
                                        {{ asset.asset_name }} -
                                        {{ asset.asset_tag }} ({{
                                            asset.asset_type
                                        }})
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                            <p
                                v-if="form.errors.asset_id"
                                class="text-sm text-destructive"
                            >
                                {{ form.errors.asset_id }}
                            </p>
                        </div>

                        <!-- Description -->
                        <div class="space-y-2">
                            <Label for="description">
                                Description
                                <span class="text-destructive">*</span>
                            </Label>
                            <Textarea
                                id="description"
                                v-model="form.description"
                                placeholder="Describe the issue or repair needed..."
                                rows="4"
                                :class="{
                                    'border-destructive':
                                        form.errors.description,
                                }"
                            />
                            <p
                                v-if="form.errors.description"
                                class="text-sm text-destructive"
                            >
                                {{ form.errors.description }}
                            </p>
                            <p class="text-sm text-muted-foreground">
                                Provide detailed description of the issue or
                                work required
                            </p>
                        </div>

                        <!-- Priority -->
                        <div class="space-y-2">
                            <Label for="priority">
                                Priority <span class="text-destructive">*</span>
                            </Label>
                            <Select v-model="form.priority">
                                <SelectTrigger
                                    id="priority"
                                    :class="{
                                        'border-destructive':
                                            form.errors.priority,
                                    }"
                                >
                                    <SelectValue
                                        placeholder="Select priority"
                                    />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem
                                        v-for="priority in priorities"
                                        :key="priority.value"
                                        :value="priority.value"
                                    >
                                        {{ priority.label }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                            <p
                                v-if="form.errors.priority"
                                class="text-sm text-destructive"
                            >
                                {{ form.errors.priority }}
                            </p>
                        </div>

                        <!-- Budget Allocation Reference -->
                        <div class="space-y-2">
                            <Label for="budget_allocation_reference">
                                Budget Allocation Reference
                            </Label>
                            <Input
                                id="budget_allocation_reference"
                                v-model="form.budget_allocation_reference"
                                type="text"
                                placeholder="e.g., BA-2025-12345"
                                :class="{
                                    'border-destructive':
                                        form.errors.budget_allocation_reference,
                                }"
                            />
                            <p
                                v-if="form.errors.budget_allocation_reference"
                                class="text-sm text-destructive"
                            >
                                {{ form.errors.budget_allocation_reference }}
                            </p>
                            <p class="text-sm text-muted-foreground">
                                Optional budget allocation reference number
                            </p>
                        </div>

                        <!-- Received Date -->
                        <div class="space-y-2">
                            <Label for="received_date"> Received Date </Label>
                            <Input
                                id="received_date"
                                v-model="form.received_date"
                                type="date"
                                :class="{
                                    'border-destructive':
                                        form.errors.received_date,
                                }"
                            />
                            <p
                                v-if="form.errors.received_date"
                                class="text-sm text-destructive"
                            >
                                {{ form.errors.received_date }}
                            </p>
                            <p class="text-sm text-muted-foreground">
                                Date the KEW.PA-10 form was received by the
                                workshop
                            </p>
                        </div>

                        <!-- Form Actions -->
                        <div
                            class="flex items-center justify-end gap-4 border-t pt-4"
                        >
                            <Link :href="index.url()">
                                <Button type="button" variant="outline">
                                    Cancel
                                </Button>
                            </Link>
                            <Button type="submit" :disabled="form.processing">
                                <Save class="mr-2 h-4 w-4" />
                                {{
                                    form.processing
                                        ? 'Saving...'
                                        : 'Register Form'
                                }}
                            </Button>
                        </div>
                    </CardContent>
                </Card>
            </form>

            <!-- Help Card -->
            <Card>
                <CardHeader>
                    <CardTitle>Registration Guidelines</CardTitle>
                </CardHeader>
                <CardContent class="space-y-2 text-sm text-muted-foreground">
                    <p>
                        <strong>1. Verify Form Completeness:</strong> Ensure the
                        KEW.PA-10 form has all required signatures and stamps
                        before registration.
                    </p>
                    <p>
                        <strong>2. Accurate Asset Identification:</strong>
                        Select the correct asset from the dropdown. If not
                        found, create the asset first.
                    </p>
                    <p>
                        <strong>3. Priority Assignment:</strong> Assign priority
                        based on urgency and impact:
                    </p>
                    <ul class="ml-4 list-inside list-disc">
                        <li>
                            <strong>Urgent:</strong> Critical safety issues,
                            complete failure
                        </li>
                        <li>
                            <strong>High:</strong> Significant operational
                            impact
                        </li>
                        <li>
                            <strong>Normal:</strong> Regular maintenance or
                            minor repairs
                        </li>
                        <li>
                            <strong>Low:</strong> Cosmetic or non-urgent
                            improvements
                        </li>
                    </ul>
                    <p>
                        <strong>4. Next Steps:</strong> After registration,
                        verify the form details and signatures before creating a
                        workshop job.
                    </p>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
