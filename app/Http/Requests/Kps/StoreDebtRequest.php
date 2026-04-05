<?php

namespace App\Http\Requests\Kps;

use App\Models\Kps\Debt;
use Illuminate\Foundation\Http\FormRequest;

class StoreDebtRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Debt::class);
    }

    public function rules(): array
    {
        return [
            'peneroka_id' => ['required', 'exists:penerokas,id'],
            'priority' => ['required', 'integer', 'min:1'],
            'balance' => ['required', 'numeric', 'min:0'],
            'monthly_potongan_limit' => ['nullable', 'numeric', 'min:0'],
            'due_date' => ['nullable', 'date'],
            'description' => ['nullable', 'string', 'max:255'],
        ];
    }
}
