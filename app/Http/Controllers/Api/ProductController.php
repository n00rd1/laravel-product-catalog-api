<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductIndexRequest;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(ProductIndexRequest $request)
    {
        // Базовый запрос с оптимизированной загрузкой связей
        $query = Product::query()
            ->with(['propertyValues.property']);

        // 1) Фильтрация по свойствам с оптимизацией
        if ($request->has('properties') && !empty($request->input('properties'))) {
            $this->applyPropertyFilters($query, $request->input('properties'));
        }

        // 2) Загружаем продукты с пагинацией по 40
        $products = $query->paginate(40);

        // 3) Собираем фильтры только если они нужны (оптимизация)
        $filters = $this->getAvailableFilters();

        // 4) Возвращаем результат в правильном формате
        return response()->json([
            'filters' => $filters,
            'products' => new ProductCollection($products),
        ]);
    }

    /**
     * Применяет фильтры по свойствам к запросу
     */
    private function applyPropertyFilters($query, array $properties): void
    {
        foreach ($properties as $propertyName => $values) {
            if (empty($values) || !is_array($values)) {
                continue;
            }

            // Очищаем значения от пустых строк
            $values = array_filter($values, fn($value) => !empty(trim($value)));
            
            if (empty($values)) {
                continue;
            }

            $query->whereHas('propertyValues', function ($q) use ($propertyName, $values) {
                $q->whereHas('property', function ($qq) use ($propertyName) {
                    $qq->where('name', $propertyName);
                })->whereIn('value', $values);
            });
        }
    }

    /**
     * Получает доступные фильтры (оптимизированно)
     */
    private function getAvailableFilters(): array
    {
        // Используем кэширование для фильтров, так как они редко изменяются
        return cache()->remember('product_filters', 3600, function () {
            return \App\Models\Property::with(['productValues' => function ($query) {
                $query->select('property_id', 'value')
                    ->distinct();
            }])
            ->get()
            ->mapWithKeys(function ($property) {
                return [
                    $property->name => $property->productValues
                        ->pluck('value')
                        ->unique()
                        ->values()
                        ->toArray()
                ];
            })
            ->toArray();
        });
    }

    /**
     * Создать товар
     */
    public function store(ProductStoreRequest $request)
    {
        $product = Product::create($request->validated());
        $product->load(['propertyValues.property']);
        
        // Очищаем кэш фильтров при добавлении нового товара
        cache()->forget('product_filters');
        
        return response()->json(new ProductResource($product), 201);
    }

    /**
     * Получить один товар
     */
    public function show($id)
    {
        $product = Product::with(['propertyValues.property'])->findOrFail($id);
        return response()->json(new ProductResource($product));
    }

    /**
     * Обновить товар
     */
    public function update(ProductUpdateRequest $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->update($request->validated());
        $product->load(['propertyValues.property']);
        
        // Очищаем кэш фильтров при обновлении товара
        cache()->forget('product_filters');
        
        return response()->json(new ProductResource($product));
    }

    /**
     * Удалить товар
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        
        // Очищаем кэш фильтров при удалении товара
        cache()->forget('product_filters');
        
        return response()->json([
            'message' => 'Товар успешно удален',
            'success' => true
        ]);
    }
}
