<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table): void {
            $table->boolean('is_reviewed')->default(false)->after('visibility');
            $table->string('reviewed_by')->nullable()->after('is_reviewed');
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table): void {
            $table->dropColumn(['is_reviewed', 'reviewed_by']);
        });
    }
};
