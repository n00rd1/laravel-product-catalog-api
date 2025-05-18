<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Добавим 10 тестовых товаров
        foreach (range(1, 50) as $i) {
            Product::create([
                'name' => "Товар $i",
                'price' => rand(500, 5000),
                'quantity' => rand(1, 50),
                'description' => "Описание товара $i",
                'sku' => "SKU$i",
                'image' => null,
                'is_active' => true,
            ]);
        }
    }
}
