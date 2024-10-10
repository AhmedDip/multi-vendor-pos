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
        Schema::table('discounts', static function (Blueprint $table) {
            $table->string('discountable_type')->nullable()->index()->after('shop_id');
            $table->unsignedBigInteger('discountable_id')->nullable()->index()->after('discountable_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('discounts', function (Blueprint $table) {
            $table->dropColumn('discountable_type');
            $table->dropColumn('discountable_id');
        });
    }
};
