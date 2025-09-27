<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Property;
use App\Models\ProductPropertyValue;

class ProductPropertyValueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $propertyValues = [
            'Цвет' => ['белый', 'чёрный', 'синий', 'красный', 'золотой', 'серебряный'],
            'Бренд' => ['Philips', 'Xiaomi', 'Samsung', 'IKEA', 'Osram', 'Cree'],
            'Материал' => ['металл', 'пластик', 'стекло', 'дерево', 'хрусталь'],
            'Мощность' => ['5W', '10W', '15W', '20W', '30W', '50W'],
            'Тип лампы' => ['LED', 'галогенная', 'люминесцентная', 'накаливания'],
            'Стиль' => ['современный', 'классический', 'минимализм', 'лофт', 'винтаж'],
            'Размер' => ['малый', 'средний', 'большой', 'очень большой']
        ];

        $properties = Property::all()->keyBy('name');

        foreach (Product::all() as $product) {
            // Для каждого товара добавляем случайные свойства
            $selectedProperties = array_rand($propertyValues, rand(3, 5));
            
            if (!is_array($selectedProperties)) {
                $selectedProperties = [$selectedProperties];
            }

            foreach ($selectedProperties as $propertyName) {
                if (isset($properties[$propertyName]) && isset($propertyValues[$propertyName])) {
                    $values = $propertyValues[$propertyName];
                    $selectedValue = $values[array_rand($values)];
                    
                    ProductPropertyValue::create([
                        'product_id' => $product->id,
                        'property_id' => $properties[$propertyName]->id,
                        'value' => $selectedValue,
                    ]);
                }
            }
        }
    }
}
