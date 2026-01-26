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
        // Drop complex tracking tables
        Schema::dropIfExists('view_logs');
        Schema::dropIfExists('favorites');

        // Ensure stats columns exist on books
        Schema::table('books', function (Blueprint $table) {
            if (!Schema::hasColumn('books', 'shares_count')) {
                $table->bigInteger('shares_count')->default(0)->after('favorites_count');
            }
        });

        // Ensure stats columns exist on authors
        Schema::table('authors', function (Blueprint $table) {
            if (!Schema::hasColumn('authors', 'shares_count')) {
                $table->bigInteger('shares_count')->default(0)->after('favorites_count');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We won't restore the complex tables in down() fully as data is lost anyway,
        // but we can recreate the empty tables if needed or just revert the columns.
        // For simplicity in this "counter-only" direction, we'll just drop the new column.
        
        Schema::table('books', function (Blueprint $table) {
            if (Schema::hasColumn('books', 'shares_count')) {
                $table->dropColumn('shares_count');
            }
        });

        Schema::table('authors', function (Blueprint $table) {
            if (Schema::hasColumn('authors', 'shares_count')) {
                $table->dropColumn('shares_count');
            }
        });
    }
};
