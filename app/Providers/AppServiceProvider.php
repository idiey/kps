<?php

namespace App\Providers;

use App\Models\Customer;
use App\Models\InspectionReport;
use App\Models\JobNote;
use App\Models\JobPhoto;
use App\Models\KewPA10;
use App\Models\RepairCompletionReport;
use App\Models\WorkshopJob;
use App\Policies\CustomerPolicy;
use App\Policies\InspectionReportPolicy;
use App\Policies\JobNotePolicy;
use App\Policies\JobPhotoPolicy;
use App\Policies\KewPA10Policy;
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
        KewPA10::class => KewPA10Policy::class,
        InspectionReport::class => InspectionReportPolicy::class,
        RepairCompletionReport::class => RepairCompletionReportPolicy::class,
        JobPhoto::class => JobPhotoPolicy::class,
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
    }
}
