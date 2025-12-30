<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('customer'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $customerId = $this->route('customer')->id;

        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('customers', 'email')->ignore($customerId)],
            'phone' => ['sometimes', 'required', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'department' => ['nullable', 'string', 'max:100'],
            'government_entity' => ['nullable', 'string', 'max:100'],
        ];
    }

    /**
     * Get custom attribute names for error messages.
     */
    public function attributes(): array
    {
        return [
            'government_entity' => 'government entity/ministry',
        ];
    }
}
