<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreWorkshopRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Workshop::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $companyId = $this->input('company_id') ?? $this->user()->company_id;

        return [
            'company_id' => [
                'nullable',
                'exists:companies,id',
            ],
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('workshops', 'code')
                    ->where(function ($query) use ($companyId) {
                        if ($companyId) {
                            return $query->where('company_id', $companyId);
                        }
                        return $query;
                    }),
            ],
            'address' => [
                'nullable',
                'string',
            ],
            'phone' => [
                'nullable',
                'string',
                'max:20',
            ],
            'email' => [
                'nullable',
                'email',
                'max:255',
            ],
            'operating_hours' => [
                'nullable',
                'array',
            ],
            'is_active' => [
                'boolean',
            ],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'company_id' => 'company/HQ',
            'code' => 'workshop code',
            'is_active' => 'active status',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'code.unique' => 'This workshop code already exists within the selected company.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // If user is HQ-level, force their company_id
        if ($this->user()->company_id) {
            $this->merge([
                'company_id' => $this->user()->company_id,
            ]);
        }
    }
}
