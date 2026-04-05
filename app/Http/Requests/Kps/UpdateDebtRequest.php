<?php

namespace App\Http\Requests\Kps;

use App\Models\Kps\Debt;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDebtRequest extends FormRequest
{
    public function authorize(): bool
    {
        $debt = $this->route('debt');
        return $debt instanceof Debt ? $this->user()->can('update', $debt) : false;
    }

    public function rules(): array
    {
        return [
            'priority' => ['required', 'integer', 'min:1'],
            'balance' => ['required', 'numeric', 'min:0'],
            'monthly_potongan_limit' => ['nullable', 'numeric', 'min:0'],
            'due_date' => ['nullable', 'date'],
            'description' => ['nullable', 'string', 'max:255'],
        ];
    }
}
