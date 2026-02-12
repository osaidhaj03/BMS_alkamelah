<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visits_summary', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique(); // ملخص ليوم واحد
            
            $table->unsignedInteger('total_visits')->default(0);
            $table->unsignedInteger('unique_visitors')->default(0);
            $table->unsignedInteger('total_sessions')->default(0);
            
            // JSON stats
            $table->json('device_stats')->nullable(); // {desktop: 50, mobile: 20}
            $table->json('browser_stats')->nullable(); // {Chrome: 40, Safari: 10}
            $table->json('top_pages')->nullable();    // [{"url": "...", "count": 10}]
            $table->json('hourly_stats')->nullable(); // {00: 5, 01: 2, ...}
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visits_summary');
    }
};
