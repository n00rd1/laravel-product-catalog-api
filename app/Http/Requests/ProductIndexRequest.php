<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductIndexRequest extends FormRequest
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
            'properties' => 'sometimes|array',
            'properties.*' => 'array',
            'properties.*.*' => 'string|max:255',
            'page' => 'sometimes|integer|min:1',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'properties.array' => 'Параметр properties должен быть массивом.',
            'properties.*.array' => 'Каждое свойство должно быть массивом значений.',
            'properties.*.*.string' => 'Значения свойств должны быть строками.',
            'properties.*.*.max' => 'Значения свойств не должны превышать 255 символов.',
            'page.integer' => 'Параметр page должен быть целым числом.',
            'page.min' => 'Параметр page должен быть больше 0.',
        ];
    }
}
