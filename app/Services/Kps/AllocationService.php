<?php

namespace App\Services\Kps;

use App\Models\Kps\Debt;
use App\Models\Kps\DeductionAllocation;
use App\Models\Kps\MonthlyDeduction;
use Illuminate\Support\Facades\DB;

class AllocationService
{
    /**
     * Allocate a monthly deduction amount across debts using the priority waterfall.
     */
    public function allocate(MonthlyDeduction $deduction): array
    {
        return DB::transaction(function () use ($deduction) {
            $remaining = $deduction->amount;
            $allocations = [];

            $debts = Debt::query()
                ->where('peneroka_id', $deduction->peneroka_id)
                ->where('balance', '>', 0)
                ->orderBy('priority')
                ->orderByRaw('due_date IS NULL')
                ->orderBy('due_date')
                ->orderBy('created_at')
                ->lockForUpdate()
                ->get();

            foreach ($debts as $debt) {
                if ($remaining <= 0) {
                    break;
                }

                $payable = min($remaining, $debt->balance);
                if ($payable <= 0) {
                    continue;
                }

                $allocation = DeductionAllocation::create([
                    'monthly_deduction_id' => $deduction->id,
                    'debt_id' => $debt->id,
                    'amount' => $payable,
                ]);

                $debt->balance = $debt->balance - $payable;
                $debt->save();

                $remaining -= $payable;
                $allocations[] = $allocation;
            }

            $deduction->unallocated_amount = max(0, $remaining);
            $deduction->save();

            return $allocations;
        });
    }

    /**
     * Re-allocate by reversing existing allocations first.
     */
    public function reallocate(MonthlyDeduction $deduction): array
    {
        return DB::transaction(function () use ($deduction) {
            $this->reverseAllocations($deduction);
            return $this->allocate($deduction);
        });
    }

    private function reverseAllocations(MonthlyDeduction $deduction): void
    {
        $deduction->allocations()->with('debt')->get()->each(function (DeductionAllocation $allocation) {
            $debt = $allocation->debt;
            if ($debt) {
                $debt->balance = $debt->balance + $allocation->amount;
                $debt->save();
            }
        });

        $deduction->allocations()->delete();
        $deduction->unallocated_amount = 0;
        $deduction->save();
    }
}
