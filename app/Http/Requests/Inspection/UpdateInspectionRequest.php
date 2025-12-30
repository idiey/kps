<?php

namespace App\Http\Requests\Inspection;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInspectionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('inspection'));
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'asset_condition_current' => ['required', 'string'],
            'visual_damage_assessment' => ['required', 'string'],
            'functional_testing_results' => ['nullable', 'string'],
            'safety_hazards_identified' => ['nullable', 'string'],
            'additional_issues_discovered' => ['nullable', 'string'],
            'recommended_repairs' => ['required', 'string'],
            'digital_signature' => ['nullable', 'string'],
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     */
    public function attributes(): array
    {
        return [
            'asset_condition_current' => 'current asset condition',
            'visual_damage_assessment' => 'visual damage assessment',
            'functional_testing_results' => 'functional testing results',
            'safety_hazards_identified' => 'safety hazards',
            'additional_issues_discovered' => 'additional issues',
            'recommended_repairs' => 'recommended repairs',
            'digital_signature' => 'digital signature',
        ];
    }
}
