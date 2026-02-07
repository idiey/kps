<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GeneralReportExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithStyles
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return collect($this->data['jobs'] ?? $this->data['customers'] ?? []);
    }

    public function headings(): array
    {
        // Determine headings based on report type
        if (isset($this->data['jobs'])) {
            return [
                'Job No',
                'Customer',
                'Vehicle',
                'Status',
                'Job Mode',
                'Created Date',
                'Assigned To',
            ];
        } elseif (isset($this->data['customers'])) {
            return [
                'Customer Name',
                'Email',
                'Phone  ',
                'Type',
                'Department',
                'Total Jobs',
            ];
        }

        return ['Data'];
    }

    public function map($row): array
    {
        // Map data based on report type
        if (isset($this->data['jobs'])) {
            return [
                $row->job_no ?? '-',
                $row->customer->name ?? '-',
                $row->vehicle->registration_no ?? '-',
                ucfirst($row->status ?? '-'),
                $row->job_mode === 'kew_pa_10' ? 'KEW.PA-10' : 'Normal',
                $row->created_at->format('d/m/Y'),
                $row->assignedTo->name ?? 'Unassigned',
            ];
        } elseif (isset($this->data['customers'])) {
            return [
                $row->name,
                $row->email,
                $row->phone ?? '-',
                ucfirst($row->customer_type ?? '-'),
                $row->department ?? '-',
                $row->jobs_count ?? 0,
            ];
        }

        return [$row];
    }

    public function title(): string
    {
        return $this->data['title'] ?? 'Report';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => ['font' => ['bold' => true]],
        ];
    }
}
