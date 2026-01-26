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
            if (!Schema::hasColumn('books', 'views_count')) {
                $table->bigInteger('views_count')->default(0)->after('id');
            }
            if (!Schema::hasColumn('books', 'favorites_count')) {
                $table->bigInteger('favorites_count')->default(0)->after('views_count');
            }
        });

        Schema::table('authors', function (Blueprint $table) {
            if (!Schema::hasColumn('authors', 'views_count')) {
                $table->bigInteger('views_count')->default(0)->after('id');
            }
            if (!Schema::hasColumn('authors', 'favorites_count')) {
                $table->bigInteger('favorites_count')->default(0)->after('views_count');
            }
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
