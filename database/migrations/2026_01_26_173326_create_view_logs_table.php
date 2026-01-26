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
        Schema::create('view_logs', function (Blueprint $table) {
            $table->id();
            $table->morphs('viewable'); // viewable_type, viewable_id
            $table->string('ip_address')->nullable();
            $table->string('session_id')->index();
            $table->timestamp('viewed_at');
            
            // Index for fast lookups when checking spam
            $table->index(['viewable_type', 'viewable_id', 'ip_address', 'viewed_at'], 'view_spam_check_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('view_logs');
    }
};
