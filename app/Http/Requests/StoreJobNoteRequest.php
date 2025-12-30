<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreJobNoteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('createNote', $this->route('job'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'content' => ['required', 'string'],
            'is_public' => ['boolean'],
            'note_type' => ['nullable', 'string', Rule::in(['general', 'diagnostic', 'repair', 'parts', 'customer_communication'])],
            'attachments.*' => ['nullable', 'file', 'max:10240'], // 10MB max
        ];
    }

    /**
     * Get custom attribute names for error messages.
     */
    public function attributes(): array
    {
        return [
            'is_public' => 'visibility',
            'note_type' => 'note type',
        ];
    }
}
