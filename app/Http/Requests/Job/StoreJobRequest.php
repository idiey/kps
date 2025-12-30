<?php

namespace App\Http\Requests\Job;

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
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'assigned_to' => ['nullable', 'integer', 'exists:users,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'priority' => ['required', Rule::enum(JobPriority::class)],
            'vehicle_registration' => ['nullable', 'string', 'max:20'],
            'asset_tag' => ['nullable', 'string', 'max:50'],
            'estimated_cost' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'due_date' => ['nullable', 'date', 'after_or_equal:today'],
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'customer_id' => 'customer',
            'assigned_to' => 'assigned technician',
            'vehicle_registration' => 'vehicle registration number',
            'asset_tag' => 'asset tag',
            'estimated_cost' => 'estimated cost',
            'due_date' => 'due date',
        ];
    }

    /**
     * Get custom error messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'customer_id.required' => 'Please select a customer for this job.',
            'customer_id.exists' => 'The selected customer does not exist.',
            'title.required' => 'Job title is required.',
            'priority.required' => 'Please select a priority level.',
            'due_date.after_or_equal' => 'Due date cannot be in the past.',
        ];
    }
}
