<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\Property;
use App\Models\ProductPropertyValue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_has_property_values_relationship()
    {
        $product = Product::factory()->create();
        $property = Property::factory()->create();
        
        ProductPropertyValue::factory()->create([
            'product_id' => $product->id,
            'property_id' => $property->id,
            'value' => 'тестовое значение'
        ]);

        $this->assertInstanceOf(ProductPropertyValue::class, $product->propertyValues->first());
        $this->assertEquals('тестовое значение', $product->propertyValues->first()->value);
    }

    public function test_product_has_properties_relationship()
    {
        $product = Product::factory()->create();
        $property = Property::factory()->create(['name' => 'Цвет']);
        
        ProductPropertyValue::factory()->create([
            'product_id' => $product->id,
            'property_id' => $property->id,
            'value' => 'красный'
        ]);

        $this->assertInstanceOf(Property::class, $product->properties->first());
        $this->assertEquals('Цвет', $product->properties->first()->name);
    }

    public function test_active_scope_works()
    {
        Product::factory()->create(['is_active' => true]);
        Product::factory()->create(['is_active' => false]);

        $activeProducts = Product::active()->get();

        $this->assertCount(1, $activeProducts);
        $this->assertTrue($activeProducts->first()->is_active);
    }

    public function test_in_stock_scope_works()
    {
        Product::factory()->create(['quantity' => 10]);
        Product::factory()->create(['quantity' => 0]);

        $inStockProducts = Product::inStock()->get();

        $this->assertCount(1, $inStockProducts);
        $this->assertGreaterThan(0, $inStockProducts->first()->quantity);
    }

    public function test_product_casts_work_correctly()
    {
        $product = Product::factory()->create([
            'price' => '1000.50',
            'quantity' => '25',
            'is_active' => '1'
        ]);

        $this->assertIsFloat($product->price);
        $this->assertIsInt($product->quantity);
        $this->assertIsBool($product->is_active);
        $this->assertEquals(1000.50, $product->price);
        $this->assertEquals(25, $product->quantity);
        $this->assertTrue($product->is_active);
    }

    public function test_soft_delete_works()
    {
        $product = Product::factory()->create();
        $productId = $product->id;

        $product->delete();

        $this->assertSoftDeleted('products', ['id' => $productId]);
        $this->assertDatabaseHas('products', ['id' => $productId]);
    }
}
