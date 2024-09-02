<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateDesignRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'prepare_duration' => 'required|string|max:50',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'price' => 'required|numeric|min:0',
            'sizes' => 'sometimes|array',
            'sizes.*' => 'exists:sizes,id',
            'colors' => 'sometimes|array',
            'colors.*' => 'exists:colors,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The design name is required.',
            'description.required' => 'The design description is required.',
            'category_id.required' => 'Please select a valid category.',
            'image.required' => 'Please upload a valid image for the design.',
            'sizes.required' => 'Please select at least one size.',
            'colors.required' => 'Please select at least one color.',
        ];
    }


    protected function failedValidation($validator): void
    {
        throw new HttpResponseException(apiErrors($validator->errors()));
    }
}
