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
        Schema::table('books', function (Blueprint $table) {
            $table->bigInteger('views_count')->default(0)->after('status'); // Use after for cleaner schema
            $table->bigInteger('favorites_count')->default(0)->after('views_count');
        });

        Schema::table('authors', function (Blueprint $table) {
            $table->bigInteger('views_count')->default(0)->after('biography');
            $table->bigInteger('favorites_count')->default(0)->after('views_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn(['views_count', 'favorites_count']);
        });

        Schema::table('authors', function (Blueprint $table) {
            $table->dropColumn(['views_count', 'favorites_count']);
        });
    }
};
