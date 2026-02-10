<?php

namespace App\Http\Requests\Kps;

use App\Models\Kps\Peneroka;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePenerokaRequest extends FormRequest
{
    public function authorize(): bool
    {
        $peneroka = $this->route('peneroka');
        return $peneroka instanceof Peneroka ? $this->user()->can('update', $peneroka) : false;
    }

    public function rules(): array
    {
        $peneroka = $this->route('peneroka');
        $siteId = $peneroka?->site_id ?? $this->input('site_id');

        return [
            'site_id' => ['required', 'exists:sites,id'],
            'name' => ['required', 'string', 'max:255'],
            'ic_number' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('penerokas', 'ic_number')
                    ->where('site_id', $siteId)
                    ->ignore($peneroka?->id),
            ],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
        ];
    }
}
