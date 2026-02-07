<?php

namespace App\Providers;

use App\Models\Customer;
use App\Models\InspectionReport;
use App\Models\JobNote;
use App\Models\JobPhoto;

use App\Models\RepairCompletionReport;
use App\Models\WorkshopJob;
use App\Policies\CustomerPolicy;
use App\Policies\InspectionReportPolicy;
use App\Policies\JobNotePolicy;
use App\Policies\JobPhotoPolicy;

use App\Policies\RepairCompletionReportPolicy;
use App\Policies\WorkshopJobPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        WorkshopJob::class => WorkshopJobPolicy::class,
        JobNote::class => JobNotePolicy::class,
        Customer::class => CustomerPolicy::class,

        InspectionReport::class => InspectionReportPolicy::class,
        RepairCompletionReport::class => RepairCompletionReportPolicy::class,
        JobPhoto::class => JobPhotoPolicy::class,
        \App\Models\Workshop::class => \App\Policies\WorkshopPolicy::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register policies
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }

        // Define KEW.PA-10 approval gate
        Gate::define('approve-kew-inspection', function (User $user, WorkshopJob $job) {
            // Only supervisors and admins can approve KEW.PA-10 inspections
            if (!$user->hasAnyRole(['penyelia', 'pentadbiran'])) {
                return false;
            }

            // Can only approve KEW.PA-10 jobs
            if ($job->job_mode !== \App\Enums\JobMode::KEW_PA_10) {
                return false;
            }

            // Job must be in pending approval status
            return $job->kew_approval_status === 'pending';
        });
    }
}
