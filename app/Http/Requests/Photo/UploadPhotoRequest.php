<?php

namespace App\Http\Requests\Photo;

use App\Enums\PhotoStage;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UploadPhotoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\JobPhoto::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'photo' => ['required', 'image', 'mimes:jpeg,jpg,png', 'max:10240'], // 10MB
            'photos' => ['sometimes', 'array', 'max:10'], // For bulk upload
            'photos.*' => ['image', 'mimes:jpeg,jpg,png', 'max:10240'],
            'photo_stage' => ['required', Rule::enum(PhotoStage::class)],
            'description' => ['nullable', 'string', 'max:500'],
            'location_context' => ['nullable', 'string', 'max:255'],
            'is_public' => ['boolean'],
            'inspection_report_id' => ['nullable', 'integer', 'exists:inspection_reports,id'],
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     */
    public function attributes(): array
    {
        return [
            'photo' => 'photo file',
            'photo_stage' => 'photo stage',
            'location_context' => 'location context',
            'is_public' => 'public visibility',
            'inspection_report_id' => 'inspection report',
        ];
    }
}
