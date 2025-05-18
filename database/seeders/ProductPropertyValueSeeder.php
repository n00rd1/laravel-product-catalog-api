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
        $colors = ['белый', 'чёрный', 'синий', 'красный'];
        $brands = ['Philips', 'Xiaomi', 'Samsung'];
        $materials = ['металл', 'пластик'];

        $colorId = Property::where('name', 'Цвет')->first()->id;
        $brandId = Property::where('name', 'Бренд')->first()->id;
        $materialId = Property::where('name', 'Материал')->first()->id;

        foreach (Product::all() as $product) {
            ProductPropertyValue::create([
                'product_id' => $product->id,
                'property_id' => $colorId,
                'value' => $colors[array_rand($colors)],
            ]);
            ProductPropertyValue::create([
                'product_id' => $product->id,
                'property_id' => $brandId,
                'value' => $brands[array_rand($brands)],
            ]);
            ProductPropertyValue::create([
                'product_id' => $product->id,
                'property_id' => $materialId,
                'value' => $materials[array_rand($materials)],
            ]);
        }
    }
}
