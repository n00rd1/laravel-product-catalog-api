<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Базовый запрос
        $query = Product::query();

        // 1) Фильтрация по свойствам
        if ($request->has('properties')) {
            foreach ($request->input('properties') as $propertyName => $values) {
                // Для каждого свойства применяем отдельный фильтр
                $query->whereHas('propertyValues', function ($q) use ($propertyName, $values) {
                    // Связываем по названию свойства
                    $q->whereHas('property', function ($qq) use ($propertyName) {
                        $qq->where('name', $propertyName);
                    })
                        // Оставляем только те значения, которые переданы во входящих
                        ->whereIn('value', $values);
                });
            }
        }

        // 2) Собираем уникальные фильтры по всем товарам (для фронта)
        $filters = [];
        foreach (\App\Models\Property::with('productValues')->get() as $property) {
            $filters[$property->name] = $property->productValues
                ->pluck('value')
                ->unique()
                ->values()
                ->toArray();
        }

        // 3) Загружаем продукты с их свойствами, с пагинацией по 40
        $products = $query
            ->with(['propertyValues.property'])
            ->paginate(40);

        // 4) Возвращаем результат
        return response()->json([
//            'filters'  => $filters,     // фильтры для фронта
            'products' => $products,    // сами товары (с пагинацией)
        ]);
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
