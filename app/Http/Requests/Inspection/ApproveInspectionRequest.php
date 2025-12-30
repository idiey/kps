<?php

namespace App\Http\Requests\Inspection;

use Illuminate\Foundation\Http\FormRequest;

class ApproveInspectionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $inspection = $this->route('inspection');
        return $this->user()->can('approve', $inspection);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'notes' => ['nullable', 'string', 'max:1000'],
            'digital_signature' => ['required', 'string'],
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     */
    public function attributes(): array
    {
        return [
            'digital_signature' => 'digital signature',
        ];
    }
}
