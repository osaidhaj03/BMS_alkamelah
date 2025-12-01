<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// مسار مؤقت لمسح الـ Cache - احذفه بعد الاستخدام
Route::get('/clear-cache-secret-2024', function () {
    Artisan::call('optimize:clear');
    Artisan::call('filament:optimize-clear');
    
    // حذف ملفات views المؤقتة يدوياً
    $viewsPath = storage_path('framework/views');
    $files = glob($viewsPath . '/*');
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
        }
    }
    
    return 'All caches cleared successfully! ✅<br><br>
            <strong>Important:</strong> Delete this route from routes/web.php after use.';
});
