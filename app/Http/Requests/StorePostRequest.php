<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
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
            'id' => 'exists:posts,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'image' => ($this->id ? 'nullable' : 'required') . '|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }
}
