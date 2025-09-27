<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Property;
use App\Models\ProductPropertyValue;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductPropertyValue>
 */
class ProductPropertyValueFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductPropertyValue::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $propertyValues = [
            'Цвет' => ['белый', 'чёрный', 'синий', 'красный', 'золотой', 'серебряный'],
            'Бренд' => ['Philips', 'Xiaomi', 'Samsung', 'IKEA', 'Osram', 'Cree'],
            'Материал' => ['металл', 'пластик', 'стекло', 'дерево', 'хрусталь'],
            'Мощность' => ['5W', '10W', '15W', '20W', '30W', '50W'],
            'Тип лампы' => ['LED', 'галогенная', 'люминесцентная', 'накаливания'],
            'Стиль' => ['современный', 'классический', 'минимализм', 'лофт', 'винтаж'],
            'Размер' => ['малый', 'средний', 'большой', 'очень большой'],
            'Форма' => ['круглая', 'квадратная', 'прямоугольная', 'овальная'],
            'Напряжение' => ['220V', '12V', '24V', '110V'],
            'Страна производитель' => ['Россия', 'Китай', 'Германия', 'Италия', 'Турция']
        ];

        $property = Property::inRandomOrder()->first();
        $propertyName = $property->name;
        $values = $propertyValues[$propertyName] ?? ['значение'];

        return [
            'product_id' => Product::factory(),
            'property_id' => $property->id,
            'value' => $this->faker->randomElement($values),
        ];
    }
}
