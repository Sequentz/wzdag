<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWordRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:words,name,' . $this->route('word'),
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The word is required.',
            'name.string' => 'The word  must be a string.',
            'name.max' => 'The word must not exceed 255 characters.',
            'name.unique' => 'The word has already been taken.',
        ];
    }
}
