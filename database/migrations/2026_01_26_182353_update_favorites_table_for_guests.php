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
        Schema::disableForeignKeyConstraints();
        
        try {
            Schema::table('favorites', function (Blueprint $table) {
                // Drop the old unique constraint if it exists (try/catch to avoid errors)
                try {
                    // Try dropping by name
                    $table->dropUnique('user_favorite_unique');
                } catch (\Exception $e) {
                    // If failed, try dropping by columns
                    try {
                        $table->dropUnique(['user_id', 'favoritable_type', 'favoritable_id']);
                    } catch (\Exception $e2) {
                        // Ignore if doesn't exist
                    }
                }
                
                // Make user_id nullable if not already
                $table->unsignedBigInteger('user_id')->nullable()->change();
                
                // Add tracking columns if they don't exist
                if (!Schema::hasColumn('favorites', 'ip_address')) {
                    $table->string('ip_address')->nullable()->after('user_id');
                }
                if (!Schema::hasColumn('favorites', 'session_id')) {
                    $table->string('session_id')->nullable()->after('ip_address');
                }
                
                try {
                    $table->index(['user_id', 'favoritable_type', 'favoritable_id'], 'fav_user_index');
                    $table->index(['ip_address', 'session_id', 'favoritable_type', 'favoritable_id'], 'fav_guest_index');
                } catch (\Exception $e) {
                    // Indexes likely exist
                }
            });
        } finally {
            Schema::enableForeignKeyConstraints();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('favorites', function (Blueprint $table) {
            // Clean up new columns
            $table->dropIndex(['ip_address', 'session_id', 'favoritable_type', 'favoritable_id']);
            $table->dropIndex(['user_id', 'favoritable_type', 'favoritable_id']);
            $table->dropColumn(['ip_address', 'session_id']);
            
            // Revert user_id to not nullable (WARNING: This will fail if there are guest favorites)
            // Ideally we would delete guest favorites before this:
            // DB::table('favorites')->whereNull('user_id')->delete();
            $table->foreignId('user_id')->nullable(false)->change();
            
            // Restore original unique constraint
            $table->unique(['user_id', 'favoritable_type', 'favoritable_id'], 'user_favorite_unique');
        });
    }
};
