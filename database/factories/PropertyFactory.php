<?php

namespace Database\Factories;

use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Property>
 */
class PropertyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Property::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $properties = [
            'Цвет', 'Бренд', 'Материал', 'Мощность', 'Тип лампы', 
            'Стиль', 'Размер', 'Форма', 'Напряжение', 'Страна производитель'
        ];

        return [
            'name' => $this->faker->randomElement($properties),
        ];
    }
}
