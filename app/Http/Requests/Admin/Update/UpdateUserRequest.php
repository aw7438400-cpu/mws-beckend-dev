<?php

namespace App\Http\Requests\Admin\Update;

use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Atur sesuai permission jika diperlukan
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $userId = $this->route('uuid'); // Ambil UUID dari route parameter

        return [
            // Users table
            'name' => 'required|string|max:150',
            'email' => "required|string|email|max:150|unique:users,email,{$userId},uuid",
            'password' => ['nullable', 'confirmed', Password::min(6)->mixedCase()->letters()->numbers()],
            'status' => 'nullable|in:active,inactive',

            // Profiles table
            'gender' => 'nullable|in:male,female',
            'date_of_birth' => 'nullable|date',
            'phone' => 'nullable|string|max:30',
            'address' => 'nullable|string',
            'avatar_url' => 'nullable|url|max:255',
        ];
    }
}
