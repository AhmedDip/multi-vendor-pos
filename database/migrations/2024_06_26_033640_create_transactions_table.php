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
        Schema::create('transactions', static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('created_by_id')->comment('id from users table')->nullable();
            $table->unsignedBigInteger('updated_by_id')->comment('id from users table')->nullable();
            $table->unsignedBigInteger('order_id')->index()->nullable();
            $table->unsignedBigInteger('payment_method_id')->index()->nullable();
            $table->float('total_payable_amount')->nullable();
            $table->float('total_paid_amount')->nullable();
            $table->float('total_due_amount')->nullable();
            $table->tinyInteger('payment_type')->nullable();
            $table->string('sender_number')->nullable();
            $table->string('trx_id')->nullable();
            $table->tinyInteger('payment_status')->nullable();
            $table->tinyInteger('order_status')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
