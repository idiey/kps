# Coding Standards & Best Practices

> **Last Updated**: 2026-02-02  
> **Version**: 2.0.0-rework  
> **Applies To**: Backend (Laravel), Frontend (Vue), Mobile (React Native)  

---

## Table of Contents

1. [PHP / Laravel Standards](#php--laravel-standards)
2. [JavaScript / Vue Standards](#javascript--vue-standards)
3. [TypeScript / React Native Standards](#typescript--react-native-standards)
4. [Database Conventions](#database-conventions)
5. [Git Workflow](#git-workflow)
6. [Code Review Guidelines](#code-review-guidelines)

---

## PHP / Laravel Standards

### PSR-12 Compliance

Follow [PSR-12](https://www.php-fig.org/psr/psr-12/) coding standard.

```bash
# Auto-fix with Laravel Pint
./vendor/bin/pint
```

### Naming Conventions

```php
// Classes: PascalCase
class QuotationController extends Controller {}

// Methods: camelCase
public function createQuotation() {}

// Variables: camelCase
$quotationNumber =  'QT-2024-001';

// Constants: UPPER_SNAKE_CASE
const MAX_UPLOAD_SIZE = 5242880;

// Database tables: snake_case plural
Schema::create('quotations', function() {});

// Model properties: snake_case
protected $fillable = ['quotation_number', 'customer_id'];
```

### Controllers

**Keep controllers thin** - delegate business logic to services.

```php
// ❌ BAD: Business logic in controller
class JobController extends Controller
{
    public function updateStatus(Request $request, Job $job)
    {
        $job->status = $request->status;
        $job->save();
        
        // Send notifications
        $users = User::where('department', $job->department)->get();
        foreach ($users as $user) {
            $user->notify(new JobStatusChanged($job));
        }
        
        // Log activity
        ActivityLog::create([...]);
        
        return back();
    }
}

// ✅ GOOD: Delegate to service
class JobController extends Controller
{
    public function updateStatus(
        UpdateJobStatusRequest $request,
        Job $job,
        JobService $jobService
    ) {
        $jobService->updateStatus($job, $request->validated());
        
        return back()->with('success', 'Job status updated');
    }
}
```

### Services

Create service classes for complex business logic.

```php
// app/Services/JobService.php
namespace App\Services;

use App\Models\Job;
use App\Notifications\JobStatusChanged;
use Illuminate\Support\Facades\DB;

class JobService
{
    public function updateStatus(Job $job, array $data): Job
    {
        return DB::transaction(function () use ($job, $data) {
            $oldStatus = $job->status;
            
            $job->update([
                'status' => $data['status'],
                'status_changed_by' => auth()->id(),
            ]);
            
            $this->logStatusChange($job, $oldStatus, $data['status']);
            $this->notifyStakeholders($job);
            
            return $job->fresh();
        });
    }
    
    private function logStatusChange(Job $job, string $old, string $new): void
    {
        activity()
            ->performedOn($job)
            ->causedBy(auth()->user())
            ->withProperties(['old' => $old, 'new' => $new])
            ->log('status_changed');
    }
    
    private function notifyStakeholders(Job $job): void
    {
        $job->stakeholders->each(fn($user) => 
            $user->notify(new JobStatusChanged($job))
        );
    }
}
```

### Queries

Use query scopes and avoid N+1 queries.

```php
// ❌ BAD: N+1 query problem
$jobs = Job::all();
foreach ($jobs as $job) {
    echo $job->customer->name; // N+1!
}

// ✅ GOOD: Eager loading
$jobs = Job::with('customer', 'assignedUser')->get();
foreach ($jobs as $job) {
    echo $job->customer->name;
}

// ✅ GOOD: Use query scopes
class Job extends Model
{
    public function scopeActive($query)
    {
        return $query->where('status', '!=', 'completed');
    }
    
    public function scopeForWorkshop($query, $workshopId)
    {
        return $query->where('workshop_id', $workshopId);
    }
}

// Usage
$jobs = Job::active()->forWorkshop($workshopId)->get();
```

### Validation

Use Form Requests for validation.

```php
// app/Http/Requests/StoreQuotationRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuotationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Quotation::class);
    }
    
    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'exists:customers,id'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.description' => ['required', 'string', 'max:255'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
        ];
    }
    
    public function messages(): array
    {
        return [
            'customer_id.required' => 'Please select a customer',
            'items.required' => 'At least one item is required',
        ];
    }
}
```

### Enums (PHP 8.1+)

Use enums for fixed sets of values.

```php
// app/Enums/JobMode.php
namespace App\Enums;

enum JobMode: string
{
    case KEW_PA_10 = 'KEW_PA_10';
    case NORMAL = 'NORMAL';
    
    public function label(): string
    {
        return match($this) {
            self::KEW_PA_10 => 'KEW.PA-10 (Government)',
            self::NORMAL => 'Normal Workshop Job',
        };
    }
    
    public function requiresApproval(): bool
    {
        return $this === self::KEW_PA_10;
    }
}

// Usage in model
class Job extends Model
{
    protected $casts = [
        'job_mode' => JobMode::class,
    ];
}

// Usage in code
if ($job->job_mode->requiresApproval()) {
    // Handle approval workflow
}
```

---

## JavaScript / Vue Standards

### ESLint Configuration

Follow Airbnb style guide with Vue plugin.

```bash
# Auto-fix
npm run lint:fix
```

### Component Structure

**Single File Components (SFC)** with consistent ordering.

```vue
<!-- ✅ GOOD: pages/Jobs/Index.vue -->
<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import JobCard from '@/Components/JobCard.vue';
import type { Job, PaginatedData } from '@/types';

// Props
interface Props {
  jobs: PaginatedData<Job>;
  filters: {
    search?: string;
    status?: string;
  };
}

const props = defineProps<Props>();

// State
const isLoading = ref(false);

// Computed
const hasActiveFilters = computed(() => 
  Boolean(props.filters.search || props.filters.status)
);

// Methods
function loadJobs(page: number) {
  router.get(route('jobs.index', { page }), {}, {
    preserveState: true,
    preserveScroll: true,
  });
}

// Lifecycle
onMounted(() => {
  console.log('Jobs page mounted');
});
</script>

<template>
  <div class="jobs-page">
    <header class="page-header">
      <h1>Workshop Jobs</h1>
    </header>

    <div v-if="isLoading" class="loading">
      Loading...
    </div>

    <div v-else class="jobs-grid">
      <JobCard
        v-for="job in jobs.data"
        :key="job.id"
        :job="job"
        @click="viewJob(job.id)"
      />
    </div>
  </div>
</template>

<style scoped lang="postcss">
.jobs-page {
  @apply container mx-auto px-4 py-8;
}

.page-header {
  @apply mb-6;
  
  h1 {
    @apply text-3xl font-bold text-gray-900;
  }
}

.jobs-grid {
  @apply grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4;
}
</style>
```

### Component Naming

```typescript
// Components: PascalCase
import JobCard from '@/Components/JobCard.vue';
import StatusBadge from '@/Components/StatusBadge.vue';

// Composables: camelCase with "use" prefix
import { useJobs } from '@/composables/useJobs';
import { useAuth } from '@/composables/useAuth';

// Utils: camelCase
import { formatCurrency } from '@/utils/formatters';
```

### TypeScript Types

Define types for all props and API responses.

```typescript
// resources/js/types/models.ts
export interface Job {
  id: string;
  job_number: string;
  job_mode: 'KEW_PA_10' | 'NORMAL';
  customer: Customer;
  status: JobStatus;
  created_at: string;
  updated_at: string;
}

export interface Customer {
  id: string;
  name: string;
  email: string;
  phone: string;
}

export type JobStatus = 
  | 'pending'
  | 'in_progress'
  | 'completed'
  | 'cancelled';

// Generics for paginated data
export interface PaginatedData<T> {
  data: T[];
  current_page: number;
  last_page: number;
  per_page: number;
  total: number;
}
```

### Composables

Extract reusable logic into composables.

```typescript
// resources/js/composables/useJobs.ts
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import type { Job, PaginatedData } from '@/types';

export function useJobs() {
  const isLoading = ref(false);
  const error = ref<string | null>(null);

  function loadJobs(filters = {}) {
    isLoading.value = true;
    error.value = null;

    router.get(
      route('jobs.index'),
      filters,
      {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
          isLoading.value = false;
        },
        onError: (errors) => {
          error.value = 'Failed to load jobs';
          isLoading.value = false;
        },
      }
    );
  }

  function deleteJob(jobId: string) {
    if (!confirm('Are you sure?')) return;

    router.delete(route('jobs.destroy', jobId));
  }

  return {
    isLoading,
    error,
    loadJobs,
    deleteJob,
  };
}
```

---

## TypeScript / React Native Standards

### Component Structure

```typescript
// src/screens/jobs/JobListScreen.tsx
import React, { useEffect, useState } from 'react';
import { View, FlatList, StyleSheet } from 'react-native';
import { useJobs } from '../../hooks/useJobs';
import JobCard from '../../components/jobs/JobCard';
import LoadingSpinner from '../../components/common/LoadingSpinner';
import ErrorMessage from '../../components/common/ErrorMessage';
import type { Job } from '../../types/models';

interface Props {
  navigation: any; // Use proper navigation type
}

export default function JobListScreen({ navigation }: Props) {
  const { jobs, isLoading, error, fetchJobs } = useJobs();

  useEffect(() => {
    fetchJobs();
  }, []);

  if (isLoading) {
    return <LoadingSpinner />;
  }

  if (error) {
    return <ErrorMessage message={error} onRetry={fetchJobs} />;
  }

  return (
    <View style={styles.container}>
      <FlatList
        data={jobs}
        keyExtractor={(item) => item.id}
        renderItem={({ item }) => (
          <JobCard
            job={item}
            onPress={() => navigation.navigate('JobDetail', { id: item.id })}
          />
        )}
      />
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#f5f5f5',
  },
});
```

### Custom Hooks

```typescript
// src/hooks/useJobs.ts
import { useState, useCallback } from 'react';
import { JobService } from '../services/api/jobService';
import type { Job } from '../types/models';

export function useJobs() {
  const [jobs, setJobs] = useState<Job[]>([]);
  const [isLoading, setIsLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);

  const fetchJobs = useCallback(async () => {
    setIsLoading(true);
    setError(null);

    try {
      const data = await JobService.fetchJobs();
      setJobs(data);
    } catch (err) {
      setError('Failed to load jobs');
      console.error(err);
    } finally {
      setIsLoading(false);
    }
  }, []);

  return {
    jobs,
    isLoading,
    error,
    fetchJobs,
  };
}
```

### Styling

Use StyleSheet for performance.

```typescript
// ❌ BAD: Inline styles
<View style={{ padding: 16, backgroundColor: '#fff' }}>

// ✅ GOOD: StyleSheet
const styles = StyleSheet.create({
  container: {
    padding: 16,
    backgroundColor: '#fff',
  },
});

<View style={styles.container}>
```

---

## Database Conventions

### Table Names

- **Lowercase snake_case**
- **Plural nouns**: `jobs`, `customers`, `quotations`
- **Pivot tables**: Alphabetical order: `job_user`, `role_user`

### Column Names

- **Lowercase snake_case**: `customer_name`, `created_at`
- **Foreign keys**: `customer_id`, `workshop_id`
- **Booleans**: Prefix with `is_` or `has_`: `is_active`, `has_approval`
- **Timestamps**: Use `created_at`, `updated_at`, `deleted_at` (soft deletes)

### Indexes

```php
// Add indexes for foreign keys and frequently queried columns
Schema::create('jobs', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->string('job_number')->unique();
    $table->foreignUuid('workshop_id')->constrained();
    $table->foreignUuid('customer_id')->constrained();
    $table->enum('status', ['pending', 'active', 'completed'])->index();
    $table->timestamps();
    
    // Composite index for common queries
    $table->index(['workshop_id', 'status', 'created_at']);
});
```

---

## Git Workflow

### Branch Naming

```
main                  # Production-ready code
develop               # Development branch
feature/job-mode      # New features
bugfix/sync-error     # Bug fixes
hotfix/security-fix   # Urgent production fixes
```

### Commit Messages

Follow [Conventional Commits](https://www.conventionalcommits.org/).

```bash
# Format
<type>(<scope>): <subject>

# Types
feat:     New feature
fix:      Bug fix
docs:     Documentation only
style:    Formatting, semicolons
refactor: Code restructuring
test:     Adding tests
chore:    Maintenance

# Examples
feat(quotations): add PDF export functionality
fix(sync): resolve conflict resolution race condition
docs(api): update mobile API authentication guide
refactor(jobs): extract status logic to service class
test(invoices): add payment processing tests
```

### Pull Request Template

```markdown
## Description
Brief description of changes

## Type of Change
- [ ] Bug fix
- [ ] New feature
- [ ] Breaking change
- [ ] Documentation update

## Testing
- [ ] Unit tests pass
- [ ] Feature tests added/updated
- [ ] Manually tested on iOS
- [ ] Manually tested on Android

## Screenshots (if applicable)

## Checklist
- [ ] Code follows project standards
- [ ] Self-review completed
- [ ] Documentation updated
- [ ] No new warnings
```

---

## Code Review Guidelines

### What to Review

1. **Functionality**: Does it work as intended?
2. **Code Quality**: Is it readable and maintainable?
3. **Performance**: Any N+1 queries or inefficiencies?
4. **Security**: Any SQL injection, XSS vulnerabilities?
5. **Tests**: Are there appropriate tests?

### Review Comments

```markdown
# ✅ GOOD: Constructive feedback
**Suggestion**: Consider extracting this logic into a service class for better testability.

**Nitpick**: We typically use camelCase for method names.

**Question**: Does this handle the case where customer_id is null?

**Praise**: Great job with the comprehensive test coverage!

# ❌ BAD: Unconstructive
This is wrong.
Why did you do it this way?
```

---

**Remember**: Code is read more often than it's written. Prioritize clarity over cleverness.
