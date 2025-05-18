<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Создаёт таблицу для хранения значений свойств товаров.
     */
    public function up(): void
    {
        Schema::create('product_property_values', function (Blueprint $table) {
            $table->id(); // ID записи
            $table->unsignedBigInteger('product_id'); // ID товара
            $table->unsignedBigInteger('property_id'); // ID свойства
            $table->string('value'); // Значение свойства (например, "красный", "Philips")
            $table->timestamps();

            // Внешние ключи
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');

            // Уникальная пара "товар+свойство+значение" (опционально)
            $table->unique(['product_id', 'property_id', 'value']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_property_values');
    }
};
