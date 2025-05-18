<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        // Фильтрация по свойствам
        if ($request->has('properties')) {
            foreach ($request->input('properties') as $propertyName => $values) {
                $query->whereHas('propertyValues.property', function ($q) use ($propertyName, $values) {
                    $q->where('name', $propertyName)
                        ->whereIn('product_property_values.value', $values);
                });
            }
        }

        // Пагинация по 40
        $products = $query->with(['propertyValues.property'])->paginate(40);

        return response()->json($products);
    }

    // Создать товар
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'sku' => 'nullable|string',
            'image' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        $product = Product::create($data);
        return response()->json($product, 201);
    }

    // Получить один товар
    public function show($id)
    {
        $product = Product::with(['propertyValues.property'])->findOrFail($id);
        return response()->json($product);
    }

    // Обновить товар
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $data = $request->validate([
            'name' => 'string',
            'price' => 'numeric|min:0',
            'quantity' => 'integer|min:0',
            'description' => 'nullable|string',
            'sku' => 'nullable|string',
            'image' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        $product->update($data);
        return response()->json($product);
    }

    // Удалить товар
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json(['success' => true]);
    }
}
