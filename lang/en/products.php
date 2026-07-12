<?php

return [
    'deleted' => 'Product deleted successfully.',

    'validation' => [
        'name.required' => 'The product name is required.',
        'name.string' => 'The product name must be a string.',
        'name.max' => 'The product name must not exceed 255 characters.',

        'price.required' => 'The product price is required.',
        'price.numeric' => 'The price must be a number.',
        'price.min' => 'The price cannot be negative.',
        'price.max' => 'The price cannot exceed 999999.99.',

        'quantity.required' => 'The product quantity is required.',
        'quantity.integer' => 'The quantity must be an integer.',
        'quantity.min' => 'The quantity cannot be negative.',
        'quantity.max' => 'The quantity cannot exceed 999999.',

        'description.max' => 'The description must not exceed 1000 characters.',

        'sku.max' => 'The SKU must not exceed 100 characters.',
        'sku.unique' => 'A product with this SKU already exists.',

        'image.max' => 'The image URL must not exceed 500 characters.',

        'is_active.boolean' => 'The active status must be true or false.',

        'properties.array' => 'The properties parameter must be an array.',
        'properties.*.array' => 'Each property must be an array of values.',
        'properties.*.*.string' => 'Property values must be strings.',
        'properties.*.*.max' => 'Property values must not exceed 255 characters.',

        'page.integer' => 'The page parameter must be an integer.',
        'page.min' => 'The page parameter must be greater than 0.',
    ],
];
