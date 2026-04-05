<?php

namespace App\Exports\Kps;

use App\Models\Kps\Peneroka;
use App\Models\Kps\Site;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SiteBulkTemplateExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function __construct(
        private readonly Site $site,
        private readonly string $monthDate
    ) {}

    public function headings(): array
    {
        return [
            'peneroka_name',
            'ic_number',
            'phone',
            'address',
            'total_hutang',
            'current_month_dividend',
        ];
    }

    public function collection(): Collection
    {
        $rows = collect();

        $penerokas = Peneroka::query()
            ->where('site_id', $this->site->id)
            ->with([
                'debts' => fn ($query) => $query
                    ->orderBy('priority')
                    ->orderBy('description'),
                'monthlyDeductions' => fn ($query) => $query
                    ->whereDate('month', $this->monthDate),
            ])
            ->orderBy('name')
            ->get();

        foreach ($penerokas as $peneroka) {
            $totalHutang = round((float) $peneroka->debts->sum('balance'), 2);
            $currentMonthDividend = round((float) $peneroka->monthlyDeductions->sum('amount'), 2);

            $rows->push([
                $peneroka->name,
                $peneroka->ic_number,
                $peneroka->phone,
                $peneroka->address,
                $totalHutang,
                $currentMonthDividend,
            ]);
        }

        return $rows;
    }
}
