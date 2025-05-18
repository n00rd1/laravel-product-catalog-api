<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Создаёт таблицу продуктов (товаров).
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // ID товара
            $table->string('name'); // Название товара
            $table->decimal('price', 12, 2); // Цена товара (до 9999999999.99)
            $table->unsignedInteger('quantity')->default(0); // Количество
            $table->text('description')->nullable(); // Описание
            $table->string('sku')->nullable(); // Артикул
            $table->string('image')->nullable(); // Ссылка на картинку
            $table->boolean('is_active')->default(true); // Активен или скрыт
            $table->timestamps(); // created_at, updated_at
            $table->softDeletes(); // deleted_at для мягкого удаления
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
