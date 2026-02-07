<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { ref } from 'vue';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { FileText, Download, Calendar } from 'lucide-vue-next';

// Job Report Form
const jobReportForm = useForm({
    type: 'monthly',
    start_date: '',
    end_date: '',
    status: 'all',
    job_mode: 'all',
    format: 'pdf',
});

// Customer Report Form
const customerReportForm = useForm({
    start_date: '',
    end_date: '',
    customer_type: 'all',
    format: 'pdf',
});

// Performance Report Form
const performanceReportForm = useForm({
    start_date: '',
    end_date: '',
    format: 'pdf',
});

const generateJobReport = () => {
    jobReportForm.post('/admin/reports/jobs', {
        onSuccess: () => {
            // File download will be triggered automatically
        },
    });
};

const generateCustomerReport = () => {
    customerReportForm.post('/admin/reports/customers', {
        onSuccess: () => {
            // File download will be triggered automatically
        },
    });
};

const generatePerformanceReport = () => {
    performanceReportForm.post('/admin/reports/performance', {
        onSuccess: () => {
            // File download will be triggered automatically
        },
    });
};

// Set default dates (last 30 days)
const today = new Date().toISOString().split('T')[0];
const thirtyDaysAgo = new Date(Date.now() - 30 * 24 * 60 * 60 * 1000).toISOString().split('T')[0];

jobReportForm.start_date = thirtyDaysAgo;
jobReportForm.end_date = today;
customerReportForm.start_date = thirtyDaysAgo;
customerReportForm.end_date = today;
performanceReportForm.start_date = thirtyDaysAgo;
performanceReportForm.end_date = today;

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Reports', href: '/admin/reports' },
];
</script>

<template>
    <Head title="Reports" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6">
        <!-- Header -->
        <div>
            <h2 class="text-2xl font-bold tracking-tight">Reports</h2>
            <p class="text-muted-foreground">
                Generate and download reports in PDF, Excel, or CSV format
            </p>
        </div>

        <Tabs default-value="jobs" class="w-full">
            <TabsList class="grid w-full grid-cols-3">
                <TabsTrigger value="jobs">Job Reports</TabsTrigger>
                <TabsTrigger value="customers">Customer Reports</TabsTrigger>
                <TabsTrigger value="performance">Performance Reports</TabsTrigger>
            </TabsList>

            <!-- Job Reports Tab -->
            <TabsContent value="jobs">
                <Card>
                    <CardHeader>
                        <CardTitle>
                            <FileText class="inline-block mr-2 h-5 w-5" />
                            Job Reports
                        </CardTitle>
                        <CardDescription>
                            Generate reports for job activities and status
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <form @submit.prevent="generateJobReport" class="space-y-6">
                            <div class="grid grid-cols-2 gap-4">
                                <!-- Report Type -->
                                <div class="space-y-2">
                                    <Label for="job-type">Report Type</Label>
                                    <Select v-model="jobReportForm.type">
                                        <SelectTrigger id="job-type">
                                            <SelectValue />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem value="daily">Daily</SelectItem>
                                            <SelectItem value="weekly">Weekly</SelectItem>
                                            <SelectItem value="monthly">Monthly</SelectItem>
                                            <SelectItem value="annual">Annual</SelectItem>
                                        </SelectContent>
                                    </Select>
                                </div>

                                <!-- Status Filter -->
                                <div class="space-y-2">
                                    <Label for="job-status">Status (Optional)</Label>
                                    <Select v-model="jobReportForm.status">
                                        <SelectTrigger id="job-status">
                                            <SelectValue placeholder="All Statuses" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem value="all">All Statuses</SelectItem>
                                            <SelectItem value="pending">Pending</SelectItem>
                                            <SelectItem value="assigned">Assigned</SelectItem>
                                            <SelectItem value="inspected">Inspected</SelectItem>
                                            <SelectItem value="approved">Approved</SelectItem>
                                            <SelectItem value="completed">Completed</SelectItem>
                                        </SelectContent>
                                    </Select>
                                </div>

                                <!-- Start Date -->
                                <div class="space-y-2">
                                    <Label for="job-start">Start Date</Label>
                                    <Input
                                        id="job-start"
                                        type="date"
                                        v-model="jobReportForm.start_date"
                                        required
                                    />
                                </div>

                                <!-- End Date -->
                                <div class="space-y-2">
                                    <Label for="job-end">End Date</Label>
                                    <Input
                                        id="job-end"
                                        type="date"
                                        v-model="jobReportForm.end_date"
                                        required
                                    />
                                </div>

                                <!-- Job Mode -->
                                <div class="space-y-2">
                                    <Label for="job-mode">Job Mode (Optional)</Label>
                                    <Select v-model="jobReportForm.job_mode">
                                        <SelectTrigger id="job-mode">
                                            <SelectValue placeholder="All Modes" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem value="all">All Modes</SelectItem>
                                            <SelectItem value="normal">Normal</SelectItem>
                                            <SelectItem value="kew_pa_10">KEW.PA-10</SelectItem>
                                        </SelectContent>
                                    </Select>
                                </div>

                                <!-- Export Format -->
                                <div class="space-y-2">
                                    <Label for="job-format">Export Format</Label>
                                    <Select v-model="jobReportForm.format">
                                        <SelectTrigger id="job-format">
                                            <SelectValue />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem value="pdf">PDF</SelectItem>
                                            <SelectItem value="excel">Excel</SelectItem>
                                            <SelectItem value="csv">CSV</SelectItem>
                                        </SelectContent>
                                    </Select>
                                </div>
                            </div>

                            <Button type="submit" :disabled="jobReportForm.processing">
                                <Download class="mr-2 h-4 w-4" />
                                {{ jobReportForm.processing ? 'Generating...' : 'Generate Report' }}
                            </Button>
                        </form>
                    </CardContent>
                </Card>
            </TabsContent>

            <!-- Customer Reports Tab -->
            <TabsContent value="customers">
                <Card>
                    <CardHeader>
                        <CardTitle>
                            <FileText class="inline-block mr-2 h-5 w-5" />
                            Customer Reports
                        </CardTitle>
                        <CardDescription>
                            Generate reports for customer statistics and activities
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <form @submit.prevent="generateCustomerReport" class="space-y-6">
                            <div class="grid grid-cols-2 gap-4">
                                <!-- Start Date -->
                                <div class="space-y-2">
                                    <Label for="customer-start">Start Date</Label>
                                    <Input
                                        id="customer-start"
                                        type="date"
                                        v-model="customerReportForm.start_date"
                                        required
                                    />
                                </div>

                                <!-- End Date -->
                                <div class="space-y-2">
                                    <Label for="customer-end">End Date</Label>
                                    <Input
                                        id="customer-end"
                                        type="date"
                                        v-model="customerReportForm.end_date"
                                        required
                                    />
                                </div>

                                <!-- Customer Type -->
                                <div class="space-y-2">
                                    <Label for="customer-type">Customer Type (Optional)</Label>
                                    <Select v-model="customerReportForm.customer_type">
                                        <SelectTrigger id="customer-type">
                                            <SelectValue placeholder="All Types" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem value="all">All Types</SelectItem>
                                            <SelectItem value="government">Government</SelectItem>
                                            <SelectItem value="private">Private</SelectItem>
                                        </SelectContent>
                                    </Select>
                                </div>

                                <!-- Export Format -->
                                <div class="space-y-2">
                                    <Label for="customer-format">Export Format</Label>
                                    <Select v-model="customerReportForm.format">
                                        <SelectTrigger id="customer-format">
                                            <SelectValue />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem value="pdf">PDF</SelectItem>
                                            <SelectItem value="excel">Excel</SelectItem>
                                            <SelectItem value="csv">CSV</SelectItem>
                                        </SelectContent>
                                    </Select>
                                </div>
                            </div>

                            <Button type="submit" :disabled="customerReportForm.processing">
                                <Download class="mr-2 h-4 w-4" />
                                {{
                                    customerReportForm.processing
                                        ? 'Generating...'
                                        : 'Generate Report'
                                }}
                            </Button>
                        </form>
                    </CardContent>
                </Card>
            </TabsContent>

            <!-- Performance Reports Tab -->
            <TabsContent value="performance">
                <Card>
                    <CardHeader>
                        <CardTitle>
                            <FileText class="inline-block mr-2 h-5 w-5" />
                            Performance Reports
                        </CardTitle>
                        <CardDescription>
                            Generate reports for workshop performance metrics
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <form @submit.prevent="generatePerformanceReport" class="space-y-6">
                            <div class="grid grid-cols-2 gap-4">
                                <!-- Start Date -->
                                <div class="space-y-2">
                                    <Label for="performance-start">Start Date</Label>
                                    <Input
                                        id="performance-start"
                                        type="date"
                                        v-model="performanceReportForm.start_date"
                                        required
                                    />
                                </div>

                                <!-- End Date -->
                                <div class="space-y-2">
                                    <Label for="performance-end">End Date</Label>
                                    <Input
                                        id="performance-end"
                                        type="date"
                                        v-model="performanceReportForm.end_date"
                                        required
                                    />
                                </div>

                                <!-- Export Format -->
                                <div class="space-y-2">
                                    <Label for="performance-format">Export Format</Label>
                                    <Select v-model="performanceReportForm.format">
                                        <SelectTrigger id="performance-format">
                                            <SelectValue />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem value="pdf">PDF</SelectItem>
                                            <SelectItem value="excel">Excel</SelectItem>
                                            <SelectItem value="csv">CSV</SelectItem>
                                        </SelectContent>
                                    </Select>
                                </div>
                            </div>

                            <Button type="submit" :disabled="performanceReportForm.processing">
                                <Download class="mr-2 h-4 w-4" />
                                {{
                                    performanceReportForm.processing
                                        ? 'Generating...'
                                        : 'Generate Report'
                                }}
                            </Button>
                        </form>
                    </CardContent>
                </Card>
            </TabsContent>
        </Tabs>
    </div>
    </AppLayout>
</template>
