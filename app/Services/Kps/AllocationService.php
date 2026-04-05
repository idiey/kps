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
            $deduction->loadMissing('site');
            $weightage = (float) ($deduction->site?->hutang_weightage_pct ?? 100);
            $weightage = min(100, max(0, $weightage));

            $allocationBudget = round(((float) $deduction->amount * $weightage) / 100, 2);
            $remainingBudget = $allocationBudget;
            $allocatedAmount = 0.0;
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
                if ($remainingBudget <= 0) {
                    break;
                }

                $debtLimit = $debt->monthly_potongan_limit === null
                    ? $remainingBudget
                    : min($remainingBudget, (float) $debt->monthly_potongan_limit);

                $payable = round(min($debtLimit, (float) $debt->balance), 2);
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

                $remainingBudget = round($remainingBudget - $payable, 2);
                $allocatedAmount = round($allocatedAmount + $payable, 2);
                $allocations[] = $allocation;
            }

            $deduction->unallocated_amount = round(max(0, (float) $deduction->amount - $allocatedAmount), 2);
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
