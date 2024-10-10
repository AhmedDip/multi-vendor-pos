<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    final public function up(): void
    {
        Schema::create('products', static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('created_by_id')->comment('id from users table')->nullable();
            $table->unsignedBigInteger('updated_by_id')->comment('id from users table')->nullable();
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->string('sku')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('category_id')->index()->nullable();
            $table->unsignedBigInteger('brand_id')->index()->nullable();
            $table->float('price')->nullable();
            $table->float('discount_price')->nullable();
            $table->integer('stock')->nullable();
            $table->integer('sort_order')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->unsignedBigInteger('shop_id')->index()->nullable();
            $table->softDeletes();
            $table->timestamps();
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
