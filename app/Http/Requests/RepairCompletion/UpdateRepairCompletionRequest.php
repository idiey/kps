<?php

namespace App\Http\Requests\RepairCompletion;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRepairCompletionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('report'));
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'work_completed' => ['boolean'],
            'time_spent_hours' => ['required', 'numeric', 'min:0.1', 'max:999.99'],
            'work_description' => ['required', 'string'],
            'issues_encountered' => ['nullable', 'string'],
            'recommendations' => ['nullable', 'string'],
            'quality_rating' => ['required', 'integer', 'between:1,5'],
            'parts_used' => ['required', 'array', 'min:1'],
            'parts_used.*.name' => ['required', 'string', 'max:255'],
            'parts_used.*.quantity' => ['required', 'integer', 'min:1'],
            'parts_used.*.cost' => ['required', 'numeric', 'min:0'],
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     */
    public function attributes(): array
    {
        return [
            'work_completed' => 'work completion status',
            'time_spent_hours' => 'time spent (hours)',
            'work_description' => 'work description',
            'issues_encountered' => 'issues encountered',
            'recommendations' => 'recommendations',
            'quality_rating' => 'quality rating',
            'parts_used' => 'parts used',
            'parts_used.*.name' => 'part name',
            'parts_used.*.quantity' => 'part quantity',
            'parts_used.*.cost' => 'part cost',
        ];
    }
}
