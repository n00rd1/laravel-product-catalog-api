<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
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
            'price' => 'required|numeric|min:0|max:999999.99',
            'quantity' => 'required|integer|min:0|max:999999',
            'description' => 'nullable|string|max:1000',
            'sku' => 'nullable|string|max:100|unique:products,sku',
            'image' => 'nullable|string|max:500',
            'is_active' => 'boolean',
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
            'name.required' => 'Название товара обязательно.',
            'name.max' => 'Название товара не должно превышать 255 символов.',
            'price.required' => 'Цена товара обязательна.',
            'price.numeric' => 'Цена должна быть числом.',
            'price.min' => 'Цена не может быть отрицательной.',
            'price.max' => 'Цена не может превышать 999999.99.',
            'quantity.required' => 'Количество товара обязательно.',
            'quantity.integer' => 'Количество должно быть целым числом.',
            'quantity.min' => 'Количество не может быть отрицательным.',
            'quantity.max' => 'Количество не может превышать 999999.',
            'description.max' => 'Описание не должно превышать 1000 символов.',
            'sku.max' => 'Артикул не должен превышать 100 символов.',
            'sku.unique' => 'Товар с таким артикулом уже существует.',
            'image.max' => 'Ссылка на изображение не должна превышать 500 символов.',
            'is_active.boolean' => 'Статус активности должен быть true или false.',
        ];
    }
}
