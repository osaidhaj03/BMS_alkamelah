<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('page_visits', function (Blueprint $table) {
            // 3A: GeoIP columns
            $table->string('country', 100)->nullable()->after('os');
            $table->string('city', 100)->nullable()->after('country');
            $table->string('country_code', 2)->nullable()->after('city');

            // 3C: Referrer source type
            $table->string('source_type', 50)->nullable()->after('country_code');

            $table->index('country_code');
            $table->index('source_type');
        });
    }

    public function down(): void
    {
        Schema::table('page_visits', function (Blueprint $table) {
            $table->dropIndex(['country_code']);
            $table->dropIndex(['source_type']);
            $table->dropColumn(['country', 'city', 'country_code', 'source_type']);
        });
    }
};
