<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateThemeRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Authorization logic, set to true if no authorization is needed
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:themes,name,' . $this->route('theme'), // Ensure uniqueness except for the current theme
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The theme name is required.',
            'name.string' => 'The theme name must be a string.',
            'name.max' => 'The theme name must not exceed 255 characters.',
            'name.unique' => 'The theme name has already been taken.',
        ];
    }
}
