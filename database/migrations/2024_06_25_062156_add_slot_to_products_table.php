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
        Schema::table('products', static function (Blueprint $table) {
            $table->integer('slot')->nullable()->after('duration');
            $table->integer('sold')->nullable()->after('slot');
            $table->float('cost_price')->nullable()->after('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('slot');
            $table->dropColumn('sold');
            $table->dropColumn('cost_price');
        });
    }
};
