<?php

namespace App\Providers;

use App\Models\Kps\Debt;
use App\Models\Kps\MonthlyDeduction;
use App\Models\Kps\Peneroka;
use App\Models\Kps\Site as KpsSite;
use App\Policies\DebtPolicy;
use App\Policies\KpsSitePolicy;
use App\Policies\MonthlyDeductionPolicy;
use App\Policies\PenerokaPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the active KPS application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        KpsSite::class => KpsSitePolicy::class,
        Peneroka::class => PenerokaPolicy::class,
        Debt::class => DebtPolicy::class,
        MonthlyDeduction::class => MonthlyDeductionPolicy::class,
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
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }
}
