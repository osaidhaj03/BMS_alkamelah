<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('page_visits', function (Blueprint $table) {
            $table->string('device_type', 20)->nullable()->after('user_agent');   // mobile, desktop, tablet
            $table->string('browser', 100)->nullable()->after('device_type');      // Chrome 120, Safari 17
            $table->string('os', 100)->nullable()->after('browser');               // Windows 11, Android 14

            $table->index('device_type');
        });
    }

    public function down(): void
    {
        Schema::table('page_visits', function (Blueprint $table) {
            $table->dropIndex(['device_type']);
            $table->dropColumn(['device_type', 'browser', 'os']);
        });
    }
};
