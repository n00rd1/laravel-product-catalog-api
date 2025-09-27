<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, SoftDeletes;

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
     * Атрибуты, которые должны быть приведены к типам
     */
    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer',
        'is_active' => 'boolean',
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

    /**
     * Scope для активных товаров
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope для товаров с количеством больше 0
     */
    public function scopeInStock($query)
    {
        return $query->where('quantity', '>', 0);
    }
}
