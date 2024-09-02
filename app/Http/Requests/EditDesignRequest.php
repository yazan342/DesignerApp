<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class EditDesignRequest extends FormRequest
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
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'category_id' => 'sometimes|exists:categories,id',
            'prepare_duration' => 'sometimes|string|max:50',
            'image' => 'sometimes|image|mimes:jpg,jpeg,png|max:2048',
            'price' => 'sometimes|numeric|min:0',
            'sizes' => 'sometimes|array',
            'sizes.*' => 'exists:sizes,id',
            'colors' => 'sometimes|array',
            'colors.*' => 'exists:colors,id',
        ];
    }


    protected function failedValidation($validator): void
    {
        throw new HttpResponseException(apiErrors($validator->errors()));
    }
}
