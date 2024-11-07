<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreThemeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:themes,name',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The theme is required.',
            'name.string' => 'The theme must be a string.',
            'name.max' => 'The theme must not exceed 255 characters.',
            'name.unique' => 'The theme has already been taken.',
        ];
    }
}
