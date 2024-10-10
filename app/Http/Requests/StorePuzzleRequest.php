<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePuzzleRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'theme_id' => 'required|exists:themes,id',
            'words' => 'required|array|min:1',
            'words.*' => 'string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ];
    }

    /**
     * Get custom error messages for validation.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required.',
            'theme_id.required' => 'The theme id field is required.',
            'theme_id.exists' => 'The selected theme is invalid.',
            'words.required' => 'The words field is required.',
            'words.*.string' => 'Each word must be a valid string.',
        ];
    }
}
