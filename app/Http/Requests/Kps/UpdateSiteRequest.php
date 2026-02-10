<?php

namespace App\Http\Requests\Kps;

use App\Models\Kps\Site;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSiteRequest extends FormRequest
{
    public function authorize(): bool
    {
        $site = $this->route('site');
        return $site instanceof Site ? $this->user()->can('update', $site) : false;
    }

    public function rules(): array
    {
        $siteId = $this->route('site')?->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', Rule::unique('sites', 'code')->ignore($siteId)],
            'address' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'is_active' => ['boolean'],
        ];
    }
}
