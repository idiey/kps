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
        return [
            'customer_id' => ['nullable', 'integer', 'exists:customers,id'],
            'workflow_id' => ['nullable', 'integer', 'exists:workflows,id'],
            'template_id' => ['nullable', 'integer', 'exists:job_templates,id'],
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'status' => ['required', Rule::enum(JobStatus::class)],
            'priority' => ['nullable', Rule::enum(JobPriority::class)],
            'vehicle_registration' => ['nullable', 'string', 'max:20'],
            'asset_tag' => ['nullable', 'string', 'max:50'],
            'estimated_cost' => ['nullable', 'numeric', 'min:0'],
            'due_date' => ['nullable', 'date', 'after_or_equal:today'],
            'assigned_to' => ['nullable', 'integer', 'exists:users,id'],
        ];
    }

    /**
     * Get custom attribute names for error messages.
     */
    public function attributes(): array
    {
        return [
            'customer_id' => 'customer',
            'vehicle_registration' => 'vehicle registration',
            'asset_tag' => 'asset tag',
            'estimated_cost' => 'estimated cost',
            'due_date' => 'due date',
            'assigned_to' => 'technician',
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
