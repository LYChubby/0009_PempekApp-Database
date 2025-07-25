<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMenuRequest extends FormRequest
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
            'nama' => 'sometimes|required|string|max:255',
            'deskripsi' => 'sometimes|nullable|string',
            'harga' => 'sometimes|required|integer|min:0',
            'gambar' => 'sometimes|nullable|image|mimes:jpg,jpeg,png',
        ];
    }
}
