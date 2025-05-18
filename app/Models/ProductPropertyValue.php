<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPropertyValue extends Model
{
    // Разрешённые к массовому заполнению поля
    protected $fillable = [
        'product_id',
        'property_id',
        'value',
    ];

    /**
     * Связь с товаром
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Связь со свойством
     */
    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
