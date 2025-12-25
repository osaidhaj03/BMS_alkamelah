<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookReaderController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Search Page Prototype
Route::get('/search', function () {
    return view('pages.search.advanced-search');
})->name('search.index');

// Book Reader Routes
Route::get('/book/{bookId}/{pageNumber?}', [BookReaderController::class, 'show'])
    ->name('book.read')
    ->where(['bookId' => '[0-9]+', 'pageNumber' => '[0-9]+']);

// Book Search API (for in-book search)
Route::get('/book/{bookId}/search', [BookReaderController::class, 'search'])
    ->name('book.search')
    ->where(['bookId' => '[0-9]+']);

// Static Book Preview Route
Route::get('/preview/book-static', function () {
    return view('pages.book-preview');
})->name('book.preview');

// مسار مؤقت لمسح الـ Cache - احذفه بعد الاستخدام
Route::get('/clear-cache-secret-2024', function () {
    // مسح الـ cache يدوياً بدون استخدام artisan commands
    $paths = [
        storage_path('framework/views'),
        storage_path('framework/cache/data'),
        base_path('bootstrap/cache'),
    ];
    
    $cleared = [];
    
    foreach ($paths as $path) {
        if (is_dir($path)) {
            $files = glob($path . '/*');
            foreach ($files as $file) {
                if (is_file($file) && !str_contains($file, '.gitignore')) {
                    unlink($file);
                }
            }
            $cleared[] = $path;
        }
    }
    
    // مسح cache من جدول cache في قاعدة البيانات
    try {
        \Illuminate\Support\Facades\DB::table('cache')->truncate();
        $cleared[] = 'database cache table';
    } catch (\Exception $e) {
        // تجاهل إذا لم يكن هناك جدول cache
    }
    
    return '<h2>Cache Cleared Successfully! ✅</h2>
            <p>Cleared paths:</p>
            <ul><li>' . implode('</li><li>', $cleared) . '</li></ul>
            <p><strong>Important:</strong> Delete this route from routes/web.php after use.</p>
            <p><a href="/admin">Go to Admin Panel</a></p>';
});

Route::view('/static-search', 'pages.search.static-search')->name('search.static');

// ===================================================================
// FILTER API ROUTES
// ===================================================================
Route::prefix('api')->name('api.')->group(function () {
    
    // Books API for filter modal (with search & pagination)
    Route::get('/books', function(\Illuminate\Http\Request $request) {
        $query = \App\Models\Book::query();
        
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        
        return $query->select('id', 'title')
                     ->orderBy('title')
                     ->paginate(50);
    })->name('books');
    
    // Authors API for filter modal (with search & pagination)
    Route::get('/authors', function(\Illuminate\Http\Request $request) {
        $query = \App\Models\Author::query();
        
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $search = $request->search;
                $q->where('first_name', 'like', '%' . $search . '%')
                  ->orWhere('last_name', 'like', '%' . $search . '%')
                  ->orWhere('laqab', 'like', '%' . $search . '%')
                  ->orWhere('kunyah', 'like', '%' . $search . '%');
            });
        }
        
        $results = $query->select('id', 'first_name', 'last_name', 'laqab', 'kunyah')
                         ->orderBy('first_name')
                         ->paginate(50);
        
        // Transform to add full_name
        $results->getCollection()->transform(function ($author) {
            return [
                'id' => $author->id,
                'name' => trim(implode(' ', array_filter([
                    $author->laqab,
                    $author->kunyah,
                    $author->first_name,
                    $author->last_name,
                ])))
            ];
        });
        
        return $results;
    })->name('authors');
    
    // Sections API for filter modal (load all - typically 40-100 sections)
    Route::get('/sections', function(\Illuminate\Http\Request $request) {
        $query = \App\Models\BookSection::query();
        
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        return $query->select('id', 'name')
                     ->orderBy('sort_order')
                     ->orderBy('name')
                     ->get()
                     ->map(fn($s) => ['id' => $s->id, 'name' => $s->name]);
    })->name('sections');
    
    // Ultra-Fast Search API (Elasticsearch)
    Route::get('/ultra-search', function(\Illuminate\Http\Request $request) {
        try {
            $searchService = new \App\Services\UltraFastSearchService();
            
            $query = $request->input('q', '');
            $page = (int) $request->input('page', 1);
            $perPage = (int) $request->input('per_page', 10);
            
            // Build filters array
            $filters = [
                'search_type' => $request->input('search_type', 'flexible_match'),
                'word_order' => $request->input('word_order', 'any_order'),
            ];
            
            // Add optional filters
            if ($request->filled('book_id')) {
                $filters['book_id'] = explode(',', $request->input('book_id'));
            }
            if ($request->filled('author_id')) {
                $filters['author_id'] = explode(',', $request->input('author_id'));
            }
            if ($request->filled('section_id')) {
                $filters['section_id'] = explode(',', $request->input('section_id'));
            }
            
            $results = $searchService->search($query, $filters, $page, $perPage);
            
            // Transform to API response format
            return response()->json([
                'success' => true,
                'data' => collect($results['results'] ?? [])->map(function($item) {
                    return [
                        'id' => $item['id'] ?? null,
                        'book_title' => $item['book_title'] ?? '',
                        'author_name' => is_array($item['author_names'] ?? null) 
                            ? implode(', ', $item['author_names']) 
                            : ($item['author_names'] ?? ''),
                        'page_number' => $item['page_number'] ?? null,
                        'content' => $item['content'] ?? '',
                        'highlighted_content' => $item['highlighted_content'] ?? ($item['content'] ?? ''),
                        'book_id' => $item['book_id'] ?? null,
                    ];
                }),
                'pagination' => [
                    'current_page' => $results['current_page'] ?? $page,
                    'last_page' => $results['last_page'] ?? 1,
                    'per_page' => $results['per_page'] ?? $perPage,
                    'total' => $results['total'] ?? 0,
                ],
                'search_metadata' => $results['search_metadata'] ?? null,
            ]);
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Ultra-search API error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Search failed: ' . $e->getMessage(),
                'data' => [],
                'pagination' => [
                    'current_page' => 1,
                    'last_page' => 1,
                    'per_page' => 10,
                    'total' => 0,
                ]
            ], 500);
        }
    })->name('ultra-search');
});