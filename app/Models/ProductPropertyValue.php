<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductPropertyValue extends Model
{
    use HasFactory;

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

    /**
     * Scope для фильтрации по значению
     */
    public function scopeByValue($query, $value)
    {
        return $query->where('value', $value);
    }

    /**
     * Scope для фильтрации по нескольким значениям
     */
    public function scopeByValues($query, array $values)
    {
        return $query->whereIn('value', $values);
    }
}
