<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Property;
use App\Models\ProductPropertyValue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_products_list()
    {
        // Создаем тестовые данные
        $product = Product::factory()->create();
        Property::factory()->create(['name' => 'Цвет']);
        ProductPropertyValue::factory()->create([
            'product_id' => $product->id,
            'property_id' => 1,
            'value' => 'белый'
        ]);

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'filters',
                'products' => [
                    'current_page',
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                            'price',
                            'quantity',
                            'properties' => [
                                '*' => [
                                    'name',
                                    'value'
                                ]
                            ]
                        ]
                    ],
                    'per_page',
                    'total',
                    'last_page'
                ]
            ]);
    }

    public function test_can_filter_products_by_properties()
    {
        // Создаем тестовые данные
        $product1 = Product::factory()->create(['name' => 'Светильник белый']);
        $product2 = Product::factory()->create(['name' => 'Светильник черный']);
        
        $colorProperty = Property::factory()->create(['name' => 'Цвет']);
        
        ProductPropertyValue::factory()->create([
            'product_id' => $product1->id,
            'property_id' => $colorProperty->id,
            'value' => 'белый'
        ]);
        
        ProductPropertyValue::factory()->create([
            'product_id' => $product2->id,
            'property_id' => $colorProperty->id,
            'value' => 'черный'
        ]);

        // Кириллица в query-string должна быть URL-encoded, иначе PHP
        // повреждает байты не-ASCII символов при разборе ключей массива
        $query = http_build_query(['properties' => ['Цвет' => ['белый']]]);
        $response = $this->getJson('/api/products?' . $query);

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'products.data');
        $response->assertJsonPath('products.data.0.name', 'Светильник белый');
    }

    public function test_can_create_product()
    {
        $productData = [
            'name' => 'Новый товар',
            'price' => 1000.50,
            'quantity' => 10,
            'description' => 'Описание товара',
            'sku' => 'TEST-001',
            'is_active' => true
        ];

        $response = $this->postJson('/api/products', $productData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'name',
                'price',
                'quantity',
                'properties'
            ]);

        $this->assertDatabaseHas('products', [
            'name' => 'Новый товар',
            'price' => 1000.50,
            'quantity' => 10
        ]);
    }

    public function test_can_get_single_product()
    {
        $product = Product::factory()->create();

        $response = $this->getJson("/api/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name',
                'price',
                'quantity',
                'properties'
            ]);
    }

    public function test_can_update_product()
    {
        $product = Product::factory()->create(['name' => 'Старое название']);

        $updateData = [
            'name' => 'Новое название',
            'price' => 2000.00
        ];

        $response = $this->putJson("/api/products/{$product->id}", $updateData);

        $response->assertStatus(200);
        $response->assertJsonPath('name', 'Новое название');
        $response->assertJsonPath('price', '2000.00');

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Новое название',
            'price' => 2000.00
        ]);
    }

    public function test_can_delete_product()
    {
        $product = Product::factory()->create();

        $response = $this->deleteJson("/api/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Product deleted successfully.',
                'success' => true
            ]);

        $this->assertSoftDeleted('products', [
            'id' => $product->id
        ]);
    }

    public function test_can_delete_product_with_russian_locale()
    {
        $product = Product::factory()->create();

        $response = $this->deleteJson("/api/products/{$product->id}?lang=ru");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Товар успешно удален.',
                'success' => true
            ]);
    }

    public function test_validation_errors_on_invalid_data()
    {
        $response = $this->postJson('/api/products', [
            'name' => '', // Пустое название
            'price' => -100, // Отрицательная цена
            'quantity' => 'invalid' // Неверный тип
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'price', 'quantity']);
    }

    public function test_pagination_works_correctly()
    {
        // Создаем 45 товаров (больше чем per_page = 40)
        Product::factory(45)->create();

        $response = $this->getJson('/api/products');

        $response->assertStatus(200);
        $response->assertJsonPath('products.per_page', 40);
        $response->assertJsonPath('products.current_page', 1);
        $response->assertJsonCount(40, 'products.data');
        $response->assertJsonPath('products.total', 45);
        $response->assertJsonPath('products.last_page', 2);
    }
}
