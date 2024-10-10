<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', static function (Blueprint $table) {
            $table->string('phone', 15)->nullable();
            $table->tinyInteger('status')->nullable();
            $table->string('ip_address', 50)->nullable();
            $table->timestamp('last_activity')->nullable();
            $table->string('otp', 10)->nullable();
            $table->timestamp('otp_expires_at')->nullable();
            $table->timestamp('otp_block_time')->nullable();
            $table->integer('otp_request_attempt')->nullable();
            $table->integer('wrong_otp_attempt')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', static function (Blueprint $table) {
            $table->dropColumn(['phone', 'status', 'ip_address', 'last_activity', 'otp', 'otp_expires_at', 'otp_block_time', 'otp_request_attempt', 'wrong_otp_attempt']);
        });
    }
};
