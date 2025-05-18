<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // Разрешённые к массовому заполнению поля
    protected $fillable = [
        'name',
        'price',
        'quantity',
        'description',
        'sku',
        'image',
        'is_active',
    ];

    /**
     * Связь с таблицей product_property_values (одно ко многим)
     */
    public function propertyValues()
    {
        return $this->hasMany(ProductPropertyValue::class);
    }

    /**
     * Связь "многие ко многим" с properties через product_property_values, получаем значение свойства
     */
    public function properties()
    {
        return $this->belongsToMany(Property::class, 'product_property_values')
            ->withPivot('value');
    }
}
