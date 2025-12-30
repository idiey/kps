# Quick Start Guide - Frontend

## Component Overview

### 14 Workshop Components Created
1. **JobStatusBadge** - Status display with colors
2. **JobPriorityBadge** - Priority display with icons
3. **JobCard** - Compact job summary card
4. **StatCard** - Dashboard statistics card
5. **WorkloadCard** - Technician workload display
6. **JobFilterBar** - Advanced job filtering
7. **JobStatusTransition** - Change job status
8. **JobNoteForm** - Add/edit job notes
9. **JobNotesList** - Display job notes
10. **TechnicianSelect** - Assign technicians
11. **CustomerAutocomplete** - Search customers
12. **AssignmentHistory** - Assignment timeline
13. **TimelineView** - Complete job timeline
14. **JobsTable** - Jobs data table

### 4 Jobs Pages
1. **Index** - Jobs listing with filters
2. **Show** - Job details
3. **Create** - Create new job
4. **Edit** - Edit existing job

### 4 Customer Pages
1. **Index** - Customer listing
2. **Show** - Customer details
3. **Create** - Create customer
4. **Edit** - Edit customer

### 2 Dashboard Pages
1. **Workload** - Admin workload view
2. **MyJobs** - Technician personal jobs

## Common Usage Patterns

### Show a Toast Notification
```typescript
import { useToast } from '@/composables/useToast';

const { success, error } = useToast();

// Success message
success('Job Created', 'Workshop job has been created successfully.');

// Error message
error('Failed', 'Please check the form for errors.');
```

### Ask for Confirmation
```typescript
import { useConfirmDialog } from '@/composables/useConfirmDialog';

const { confirm } = useConfirmDialog();

const confirmed = await confirm({
  title: 'Delete Job',
  description: 'Are you sure? This cannot be undone.',
  confirmText: 'Delete',
  cancelText: 'Cancel',
  variant: 'destructive'
});

if (confirmed) {
  // Delete the job
}
```

### Format Dates
```typescript
import { formatDate, formatDateTime, formatRelative } from '@/utils/date';

formatDate(job.created_at)                 // "30 Dec 2025"
formatDateTime(job.created_at)             // "30 Dec 2025, 14:30"
formatRelative(job.created_at)             // "2 hours ago"
formatDateForInput(job.expected_date)      // "2025-12-30" for <input type="date">
```

### Format Currency
```typescript
import { formatCurrency } from '@/utils/currency';

formatCurrency(1500.50)        // "RM 1,500.50"
formatCurrency(0)              // "RM 0.00"
formatCompactCurrency(1500)    // "RM 1.5K"
```

### Get Job Status Info
```typescript
import { useJobStatus } from '@/composables/useJobStatus';

const { getStatusConfig, getPriorityConfig, getAllStatuses, canTransitionTo } = useJobStatus();

const config = getStatusConfig('in_progress');
// { label: 'In Progress', color: 'blue', bgColor: '...', textColor: '...' }

const canChange = canTransitionTo('pending', 'in_progress');  // true
const canComplete = canTransitionTo('pending', 'completed');  // false
```

### Use Job Filters
```typescript
import { useJobFilters } from '@/composables/useJobFilters';

const { filters, applyFilters, updateFilter, clearFilters } = useJobFilters({
  search: 'JOB-001',
  status: 'in_progress'
});

updateFilter('priority', 'urgent');
applyFilters();  // Navigates with new filters
```

## Component Props Examples

### JobCard
```vue
<JobCard :job="job" />
```

### JobStatusBadge
```vue
<JobStatusBadge :status="job.status" />
```

### JobPriorityBadge
```vue
<JobPriorityBadge :priority="job.priority" show-icon />
```

### StatCard
```vue
<StatCard
  title="Total Jobs"
  :value="statistics.total_jobs"
  :icon="Briefcase"
  description="All jobs in the system"
/>
```

### EmptyState
```vue
<EmptyState
  title="No jobs yet"
  description="Create your first job to get started"
  :icon="Plus"
  action-text="Create Job"
  :action-href="route('jobs.create')"
/>
```

## Route Names

### Jobs
- `jobs.index` - List all jobs
- `jobs.show` - View job details
- `jobs.create` - Create job form
- `jobs.edit` - Edit job form
- `jobs.store` - Save new job (POST)
- `jobs.update` - Update job (PUT)
- `jobs.destroy` - Delete job (DELETE)
- `jobs.update-status` - Change job status (PUT)
- `jobs.notes.store` - Add job note (POST)
- `jobs.notes.update` - Update job note (PUT)
- `jobs.notes.destroy` - Delete job note (DELETE)
- `jobs.assignments.store` - Assign technician (POST)

### Customers
- `customers.index` - List customers
- `customers.show` - View customer
- `customers.create` - Create customer form
- `customers.edit` - Edit customer form
- `customers.store` - Save customer (POST)
- `customers.update` - Update customer (PUT)
- `customers.destroy` - Delete customer (DELETE)

### Dashboard
- `dashboard.workload` - Admin workload view
- `dashboard.my-jobs` - Technician jobs view

## Form Handling Pattern

```vue
<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { useToast } from '@/composables/useToast';

const { success, error } = useToast();

const form = useForm({
  customer_id: null,
  description: '',
  status: 'pending',
  priority: 'normal',
});

const submitForm = () => {
  form.post(route('jobs.store'), {
    onSuccess: () => {
      success('Job Created', 'Workshop job has been created successfully.');
    },
    onError: () => {
      error('Create Failed', 'Please check the form for errors.');
    },
  });
};
</script>

<template>
  <form @submit.prevent="submitForm">
    <!-- Form fields -->
    <Button type="submit" :disabled="form.processing">
      Create Job
    </Button>
  </form>
</template>
```

## Delete Pattern with Confirmation

```vue
<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { useConfirmDialog } from '@/composables/useConfirmDialog';
import { useToast } from '@/composables/useToast';

const { confirm } = useConfirmDialog();
const { success, error } = useToast();

const deleteJob = async () => {
  const confirmed = await confirm({
    title: 'Delete Job',
    description: 'This action cannot be undone.',
    confirmText: 'Delete',
    variant: 'destructive',
  });

  if (confirmed) {
    router.delete(route('jobs.destroy', job.id), {
      onSuccess: () => success('Deleted', 'Job deleted successfully.'),
      onError: () => error('Failed', 'Could not delete job.'),
    });
  }
};
</script>
```

## Styling Guidelines

### Colors
- **Primary**: Used for main actions
- **Destructive**: Used for delete/danger actions
- **Muted**: Used for secondary text
- **Border**: Used for card borders

### Spacing
- **gap-2**: 8px - Small spacing
- **gap-4**: 16px - Medium spacing
- **gap-6**: 24px - Large spacing
- **space-y-4**: Vertical spacing between elements

### Responsive Classes
```vue
<!-- Mobile first, then larger screens -->
<div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
  <!-- Cards -->
</div>

<!-- md: 768px+, lg: 1024px+, xl: 1280px+ -->
```

## Dark Mode
All components support dark mode automatically via Tailwind's `dark:` prefix.

## Icons
Import from `lucide-vue-next`:
```typescript
import { Plus, Edit, Trash2, Eye, Search } from 'lucide-vue-next';
```

## Development Commands

```bash
# Install dependencies
npm install

# Development server with HMR
npm run dev

# Build for production
npm run build

# Type checking
npm run type-check
```

## Testing the Frontend

1. Start Laravel dev server: `php artisan serve`
2. Start Vite dev server: `npm run dev`
3. Visit: `http://localhost:8000`
4. Login with seeded credentials
5. Navigate to Jobs, Customers, Dashboard

## Common Issues & Solutions

### Build Errors
```bash
# Clear cache and rebuild
rm -rf node_modules
npm install
npm run build
```

### TypeScript Errors
- Check `resources/js/types/index.d.ts` for type definitions
- Ensure all imports use correct paths with `@/` alias

### Styles Not Applying
- Check Tailwind configuration
- Ensure component is using proper class names
- Check for conflicting CSS

### Component Not Found
- Verify component path in import
- Check file name matches import (case-sensitive)
- Ensure component is properly exported

---

**Quick Reference Complete**
All components are ready to use. Backend integration tested with successful build.
