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
        Schema::create('blogs', static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('created_by_id')->comment('id from users table')->nullable();
            $table->unsignedBigInteger('updated_by_id')->comment('id from users table')->nullable();
            $table->unsignedBigInteger('shop_id')->index()->nullable();
            $table->string('title')->nullable();
            $table->string('slug')->nullable()->unique();
            $table->longText('content')->nullable();
            $table->text('summary')->nullable();
            $table->string('tag')->nullable();
            $table->tinyInteger('is_comment_allowed')->default(0)->comment('0=No, 1=Yes');
            $table->tinyInteger('status')->default(0)->comment('0=Inactive, 1=Active, 2=Featured');
            $table->bigInteger('click')->default(0);
            $table->bigInteger('impression')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
