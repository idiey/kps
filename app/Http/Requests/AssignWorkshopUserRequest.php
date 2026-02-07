<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AssignWorkshopUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('manageUsers', $this->route('workshop'));
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'user_id' => [
                'required',
                'exists:users,id',
                Rule::unique('workshop_user')
                    ->where('workshop_id', $this->route('workshop')->id),
            ],
            'role' => [
                'required',
                'string',
                Rule::in(['supervisor', 'technician', 'staff']),
            ],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'user_id' => 'user',
            'role' => 'site role',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'user_id.unique' => 'This user is already assigned to this workshop.',
            'role.in' => 'The role must be one of: supervisor, technician, or staff.',
        ];
    }
}
