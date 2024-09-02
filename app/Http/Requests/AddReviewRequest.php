<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AddReviewRequest extends FormRequest
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
            'design_id' => 'required|exists:designs,id',
            'rate' => 'required|numeric|min:1|max:5',
        ];
    }


    public function messages(): array
    {
        return [
            'design_id.required' => 'The design ID is required.',
            'rate.required' => 'The Rate is required.',

        ];
    }


    protected function failedValidation($validator): void
    {
        throw new HttpResponseException(apiErrors($validator->errors()));
    }
}
