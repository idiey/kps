<?php

namespace App\Http\Requests\Kps;

use Illuminate\Foundation\Http\FormRequest;

class StoreSiteBulkUploadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->hasPermissionTo('kps.manage_sites');
    }

    public function rules(): array
    {
        return [
            'month' => ['required', 'date_format:Y-m'],
            'file' => [
                'required',
                'file',
                'mimes:xlsx,xls',
                'max:10240',
            ],
        ];
    }
}
