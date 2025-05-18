<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Property;

class PropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $properties = ['Цвет', 'Бренд', 'Материал'];

        foreach ($properties as $name) {
            Property::create(['name' => $name]);
        }
    }
}
