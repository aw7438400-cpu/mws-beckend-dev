<?php

namespace App\Http\Requests\Admin\Update;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmotionalCheckinRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'mood' => 'nullable|string|max:50',
            'intensity' => 'nullable|integer|min:1|max:10',
            'note' => 'nullable|string|max:500',
            'checked_in_at' => 'nullable|date',
        ];
    }
}
