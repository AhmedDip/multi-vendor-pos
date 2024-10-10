<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {

            $table->dropColumn(['total_due_amount', 'discount_percentage', 'discount', 'payment_type', 'order_status', 'total_paid_amount']);
            $table->renameColumn('total_payable_amount', 'amount');
            $table->renameColumn('sender_number', 'sender_account');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->float('total_payable_amount')->nullable()->after('discount_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->float('total_due_amount')->nullable();
            $table->integer('discount_percentage')->nullable();
            $table->float('discount')->nullable();
            $table->tinyInteger('payment_type')->nullable();
            $table->tinyInteger('order_status')->nullable();


            $table->renameColumn('amount', 'total_payable_amount');
            $table->renameColumn('sender_account', 'sender_number');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('total_payable_amount');
        });
    }
};
