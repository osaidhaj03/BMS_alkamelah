<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_visits', function (Blueprint $table) {
            $table->id();
            $table->string('session_id', 255)->nullable()->index();
            $table->string('ip_address', 45)->index();
            $table->string('url', 2048);
            $table->string('route_name', 255)->nullable();
            $table->string('page_title', 255)->nullable();
            $table->unsignedInteger('duration_seconds')->nullable();
            $table->boolean('is_bot')->default(false)->index();
            $table->string('bot_name', 100)->nullable();
            $table->string('referer', 2048)->nullable();
            $table->string('user_agent', 512)->nullable();
            $table->timestamp('visited_at')->index();
            $table->timestamps();

            $table->index(['session_id', 'visited_at']);
            $table->index('route_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_visits');
    }
};
