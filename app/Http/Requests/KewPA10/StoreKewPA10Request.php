<?php

namespace App\Http\Requests\KewPA10;

use App\Enums\JobPriority;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreKewPA10Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\KewPA10::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'kew_pa_10_number' => ['required', 'string', 'max:50', 'unique:kew_pa_10s,kew_pa_10_number'],
            'government_department_id' => ['required', 'integer', 'exists:government_departments,id'],
            'asset_id' => ['required', 'integer', 'exists:assets,id'],
            'description' => ['required', 'string'],
            'priority' => ['required', Rule::enum(JobPriority::class)],
            'budget_allocation_reference' => ['nullable', 'string', 'max:100'],
            'kew_pa_10_document_path' => ['nullable', 'string', 'max:255'],
            'received_date' => ['nullable', 'date'],
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     */
    public function attributes(): array
    {
        return [
            'kew_pa_10_number' => 'KEW.PA-10 number',
            'government_department_id' => 'government department',
            'asset_id' => 'asset',
            'budget_allocation_reference' => 'budget allocation reference',
            'kew_pa_10_document_path' => 'document path',
            'received_date' => 'received date',
        ];
    }
}
