<?php

namespace App\Http\Requests;

use App\Enums\JobPriority;
use App\Enums\JobStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreJobRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\WorkshopJob::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            // STRICT: Job mode is required and must be valid enum value
            'job_mode' => ['required', Rule::enum(\App\Enums\JobMode::class)],
            
            // Core fields
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'priority' => ['nullable', Rule::enum(JobPriority::class)],
            'estimated_cost' => ['nullable', 'numeric', 'min:0'],
            'due_date' => ['nullable', 'date', 'after_or_equal:today'],
            'assigned_to' => ['nullable', 'integer', 'exists:users,id'],
        ];

        // Conditional validation based on job_mode
        if ($this->input('job_mode') === \App\Enums\JobMode::KEW_PA_10->value) {
            // KEW.PA-10 specific required fields
            $rules = array_merge($rules, [
                'kew_vehicle_registration' => ['required', 'string', 'max:255'],
                'kew_asset_tag' => ['required', 'string', 'max:255'],
                'kew_department_name' => ['required', 'string', 'max:255'],
                'kew_inspection_date' => ['required', 'date', 'before_or_equal:today'],
                'kew_inspector_name' => ['required', 'string', 'max:255'],
                'kew_inspector_ic' => ['required', 'string', 'regex:/^\d{6}-\d{2}-\d{4}$/'],
                'kew_findings' => ['required', 'string', 'min:10'],
                'kew_recommendations' => ['required', 'string', 'min:10'],
            ]);
        } elseif ($this->input('job_mode') === \App\Enums\JobMode::NORMAL->value) {
            // Normal job requires customer
            $rules['customer_id'] = ['required', 'integer', 'exists:customers,id'];
        }

        return $rules;
    }

    /**
     * Get custom attribute names for error messages.
     */
    public function attributes(): array
    {
        return [
            'job_mode' => 'job mode',
            'customer_id' => 'customer',
            'vehicle_registration' => 'vehicle registration',
            'asset_tag' => 'asset tag',
            'estimated_cost' => 'estimated cost',
            'due_date' => 'due date',
            'assigned_to' => 'technician',
            
            // KEW.PA-10 specific fields
            'kew_vehicle_registration' => 'vehicle registration number',
            'kew_asset_tag' => 'asset tag number',
            'kew_department_name' => 'department name',
            'kew_inspection_date' => 'inspection date',
            'kew_inspector_name' => 'inspector name',
            'kew_inspector_ic' => 'inspector IC number',
            'kew_findings' => 'inspection findings',
            'kew_recommendations' => 'recommendations',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        \Illuminate\Support\Facades\Log::error('Job Creation Validation Failed', [
            'errors' => $validator->errors()->toArray(),
            'input' => $this->all(),
            'user' => $this->user()?->id,
        ]);

        parent::failedValidation($validator);
    }
}
