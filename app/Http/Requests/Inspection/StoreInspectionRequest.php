<?php

namespace App\Http\Requests\Inspection;

use Illuminate\Foundation\Http\FormRequest;

class StoreInspectionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\InspectionReport::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'inspector_id' => ['required', 'integer', 'exists:users,id'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     */
    public function attributes(): array
    {
        return [
            'inspector_id' => 'inspector',
        ];
    }
}
