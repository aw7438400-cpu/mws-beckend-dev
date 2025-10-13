<?php

namespace App\Http\Requests\Admin\Index;

use Illuminate\Foundation\Http\FormRequest;

class IndexUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Bisa diatur sesuai kebutuhan permission
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * Digunakan untuk filter/search user.
     */
    public function rules(): array
    {
        return [
            'search' => 'nullable|string|max:150', // Bisa search by name atau email
            'status' => 'nullable|in:active,inactive', // Filter berdasarkan status user
            'per_page' => 'nullable|integer|min:1|max:100', // Pagination
            'page' => 'nullable|integer|min:1', // Nomor halaman
        ];
    }
}
