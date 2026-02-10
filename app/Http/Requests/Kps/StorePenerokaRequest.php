<?php

namespace App\Http\Requests\Kps;

use App\Models\Kps\Peneroka;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePenerokaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Peneroka::class);
    }

    public function rules(): array
    {
        return [
            'site_id' => ['required', 'exists:sites,id'],
            'name' => ['required', 'string', 'max:255'],
            'ic_number' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('penerokas', 'ic_number')->where('site_id', $this->input('site_id')),
            ],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
        ];
    }
}
