<?php

namespace App\Http\Requests;

use App\Enums\JobStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateJobStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('updateStatus', $this->route('job'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $job = $this->route('job');
        $allowedStatuses = $job->status->allowedTransitions();

        return [
            'status' => ['required', Rule::enum(JobStatus::class), Rule::in(array_map(fn($s) => $s->value, $allowedStatuses))],
            'notes' => ['nullable', 'string', 'max:1000'],
            'field_data' => ['nullable', 'array'],
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'status.in' => 'Invalid status transition. The job cannot move to the selected status.',
        ];
    }
}
