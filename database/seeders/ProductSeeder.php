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
        $products = [
            [
                'name' => 'Настольный светильник',
                'price' => 2300.00,
                'quantity' => 15,
                'description' => 'Современный настольный светильник с регулируемой яркостью',
                'sku' => 'LAMP-001',
                'is_active' => true,
            ],
            [
                'name' => 'Люстра хрустальная',
                'price' => 15000.00,
                'quantity' => 3,
                'description' => 'Роскошная хрустальная люстра для гостиной',
                'sku' => 'CHAND-001',
                'is_active' => true,
            ],
            [
                'name' => 'Торшер напольный',
                'price' => 4500.00,
                'quantity' => 8,
                'description' => 'Стильный напольный торшер с тканевым абажуром',
                'sku' => 'FLOOR-001',
                'is_active' => true,
            ],
            [
                'name' => 'Светодиодная лента',
                'price' => 800.00,
                'quantity' => 25,
                'description' => 'RGB светодиодная лента с пультом управления',
                'sku' => 'LED-001',
                'is_active' => true,
            ],
            [
                'name' => 'Бра настенное',
                'price' => 1800.00,
                'quantity' => 12,
                'description' => 'Элегантное настенное бра для спальни',
                'sku' => 'WALL-001',
                'is_active' => true,
            ],
        ];

        foreach ($products as $productData) {
            Product::create($productData);
        }

        // Добавим еще несколько товаров для тестирования пагинации
        foreach (range(6, 50) as $i) {
            Product::create([
                'name' => "Товар $i",
                'price' => rand(500, 5000),
                'quantity' => rand(1, 50),
                'description' => "Описание товара $i",
                'sku' => "SKU$i",
                'is_active' => rand(0, 1) === 1, // Некоторые товары неактивны
            ]);
        }
    }
}
