<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
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
                'nullable',
                Rule::requiredWithout('new_user.name'),
                'exists:users,id',
                Rule::unique('workshop_user')
                    ->where('workshop_id', $this->route('workshop')->id),
            ],
            'role' => [
                'required',
                'string',
                Rule::in(['site_admin', 'supervisor', 'technician', 'staff']),
            ],
            'new_user' => [
                'nullable',
                'array',
            ],
            'new_user.name' => [
                'required_without:user_id',
                'string',
                'max:255',
            ],
            'new_user.email' => [
                'required_without:user_id',
                'string',
                'email',
                'max:255',
                'unique:users,email',
            ],
            'new_user.password' => [
                'required_without:user_id',
                'confirmed',
                Password::defaults(),
            ],
            'new_user.role' => [
                'nullable',
                'string',
                'exists:roles,name',
                Rule::when(
                    $this->user()?->hasRole('company_admin'),
                    Rule::notIn(['pentadbiran'])
                ),
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
            'role.in' => 'The role must be one of: site admin, supervisor, technician, or staff.',
            'new_user.password.confirmed' => 'The password confirmation does not match.',
        ];
    }
}
