<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Property extends Model
{
    use HasFactory;

    // Разрешённые к массовому заполнению поля
    protected $fillable = [
        'name',
    ];

    /**
     * Связь с таблицей product_property_values (одно ко многим)
     */
    public function productValues()
    {
        return $this->hasMany(ProductPropertyValue::class);
    }

    /**
     * Связь "многие ко многим" с Product через product_property_values
     * + получаем значение свойства
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_property_values')
            ->withPivot('value');
    }

    /**
     * Scope для получения уникальных значений свойства
     */
    public function scopeWithUniqueValues($query)
    {
        return $query->with(['productValues' => function ($q) {
            $q->select('property_id', 'value')->distinct();
        }]);
    }
}
