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
        private readonly string $language = 'ms'
    ) {}

    public function headings(): array
    {
        if ($this->language === 'en') {
            return ['No.', 'Peneroka Name', 'IC Number', 'Salary'];
        }

        return ['Bil', 'NAMA PENEROKA', 'No. IC', 'Gaji'];
    }

    public function collection(): Collection
    {
        $rows = collect();

        $penerokas = Peneroka::query()
            ->where('site_id', $this->site->id)
            ->orderBy('name')
            ->get();

        foreach ($penerokas->values() as $index => $peneroka) {
            $rows->push([
                $index + 1,
                $peneroka->name,
                $peneroka->ic_number,
                '',
            ]);
        }

        return $rows;
    }
}
