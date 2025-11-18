<?php

namespace App\Http\Requests\Admin\Store;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
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
            'student_name' => 'required|string',
            'grade' => 'nullable|string',
            'tier' => 'required|in:tier_1,tier_2,tier_3',
            'type' => 'nullable|string',
            'mentor_id' => 'nullable|exists:users,id',
            'strategy' => 'nullable|string',
            'progress_status' => 'in:on_track,improving,needs_attention'
        ];
    }
}
