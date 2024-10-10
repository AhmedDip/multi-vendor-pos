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
        Schema::create('appointments', static function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('message')->nullable();
            // $table->unsignedBigInteger('customer_id')->index()->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            // $table->unsignedBigInteger('product_id')->nullable();
            $table->float('amount')->nullable();
            $table->timestamp('date')->nullable();
           
            $table->unsignedBigInteger('shop_id')->index()->nullable();
            $table->unsignedBigInteger('created_by_id')->comment('id from users table')->nullable();
            $table->unsignedBigInteger('updated_by_id')->comment('id from users table')->nullable();

            $table->softDeletes();
            $table->timestamps();

           
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
