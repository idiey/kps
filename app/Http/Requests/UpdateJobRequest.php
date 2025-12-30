<?php

namespace App\Http\Requests;

use App\Enums\JobPriority;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateJobRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('job'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_id' => ['sometimes', 'required', 'integer', 'exists:customers,id'],
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'required', 'string'],
            'priority' => ['nullable', Rule::enum(JobPriority::class)],
            'vehicle_registration' => ['nullable', 'string', 'max:20'],
            'asset_tag' => ['nullable', 'string', 'max:50'],
            'estimated_cost' => ['nullable', 'numeric', 'min:0'],
            'actual_cost' => ['nullable', 'numeric', 'min:0'],
            'due_date' => ['nullable', 'date'],
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
            'actual_cost' => 'actual cost',
            'due_date' => 'due date',
            'assigned_to' => 'technician',
        ];
    }
}
