<?php

namespace App\Http\Requests\Kps;

use App\Models\Kps\MonthlyDeduction;
use Illuminate\Foundation\Http\FormRequest;

class StoreMonthlyDeductionBatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', MonthlyDeduction::class);
    }

    public function rules(): array
    {
        return [
            'site_id' => ['required', 'exists:sites,id'],
            'month' => ['required', 'date_format:Y-m'],
            'entries' => ['required', 'array', 'min:1'],
            'entries.*.peneroka_id' => ['required', 'exists:penerokas,id'],
            'entries.*.amount' => ['required', 'numeric', 'min:0.01'],
        ];
    }
}
