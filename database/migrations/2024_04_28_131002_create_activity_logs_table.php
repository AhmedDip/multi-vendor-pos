<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    final public function up(): void
    {
        Schema::create('activity_logs', static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('created_by_id')->comment('id from users table')->nullable();
            $table->unsignedBigInteger('updated_by_id')->comment('id from users table')->nullable();
            $table->morphs('logable');
            $table->string('note')->nullable();
            $table->string('ip')->nullable();
            $table->string('action')->nullable();
            $table->string('route')->nullable();
            $table->string('method')->nullable();
            $table->string('agent')->nullable();
            $table->text('old_data')->nullable();
            $table->text('new_data')->nullable();
            $table->string('attached_files')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
