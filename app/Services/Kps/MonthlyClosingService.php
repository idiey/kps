<?php

namespace App\Services\Kps;

use App\Models\Kps\AuditLog;
use App\Models\Kps\MonthlyDeduction;
use App\Models\Kps\Site;
use App\Models\User;
use Carbon\Carbon;

class MonthlyClosingService
{
    public function closeMonth(Site $site, Carbon $month, ?User $user = null): int
    {
        $monthDate = $month->copy()->startOfMonth()->toDateString();

        $affected = MonthlyDeduction::query()
            ->where('site_id', $site->id)
            ->whereDate('month', $monthDate)
            ->update([
                'is_closed' => true,
                'closed_at' => now(),
            ]);

        AuditLog::create([
            'site_id' => $site->id,
            'user_id' => $user?->id,
            'action' => 'month_closed',
            'auditable_type' => Site::class,
            'auditable_id' => $site->id,
            'metadata' => [
                'month' => $monthDate,
                'deductions_closed' => $affected,
            ],
        ]);

        return $affected;
    }

    public function isClosed(Site $site, Carbon $month): bool
    {
        $monthDate = $month->copy()->startOfMonth()->toDateString();

        return MonthlyDeduction::query()
            ->where('site_id', $site->id)
            ->whereDate('month', $monthDate)
            ->where('is_closed', true)
            ->exists();
    }
}
