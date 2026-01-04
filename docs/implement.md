 وصف المشكلة
خطة تفصيلية لربط البحث الديناميكي مع Elasticsearch



صفحات البحث الحالية في المشروع الجديد (
static-search.blade.php
 و 
advanced-search.blade.php
) تستخدم بيانات وهمية (Mock Data) بدلاً من البيانات الحقيقية من Elasticsearch. المطلوب هو ربط هذه الصفحات بـ Elasticsearch كما في المشروع القديم BMS_v1.

📊 تحليل الوضع الحالي
المشروع القديم (BMS_v1) - ما هو موجود ✅
الملف	الوظيفة
UltraFastSearchService.php
خدمة البحث الرئيسية مع Elasticsearch
SearchController.php
API للبحث في المحتوى
SearchAllController.php
API للبحث في الكتب والمؤلفين والأقسام
.env
إعدادات Elasticsearch (http://145.223.98.97:9201)
المشروع الجديد - ما هو مفقود ❌
الملف	الحالة
UltraFastSearchService.php
غير موجود
SearchController.php
غير موجود
SearchAllController.php
غير موجود
إعدادات Elasticsearch في 
config/services.php
غير موجودة
Routes للبحث	أساسية فقط
🏗️ خطة التنفيذ التفصيلية
المرحلة الأولى: إعداد البنية التحتية 🔧
الخطوة 1.1: تثبيت حزمة Elasticsearch
composer require elasticsearch/elasticsearch:^7.0
IMPORTANT

يجب تثبيت الإصدار 7.x للتوافق مع خادم Elasticsearch 7.17.3 الموجود على 145.223.98.97:9201

الخطوة 1.2: إضافة إعدادات Elasticsearch
[MODIFY] 
services.php
إضافة إعدادات Elasticsearch إلى ملف 
config/services.php
:

return [
    // ... existing config
    
+   'elasticsearch' => [
+       'host' => env('ELASTICSEARCH_HOST', 'http://145.223.98.97:9201'),
+       'index' => env('ELASTICSEARCH_INDEX', 'pages_new_search'),
+   ],
];
الخطوة 1.3: إضافة متغيرات البيئة
[MODIFY] 
.env
# Elasticsearch Configuration
ELASTICSEARCH_HOST=http://145.223.98.97:9201
ELASTICSEARCH_INDEX=pages_new_search
ELASTICSEARCH_TIMEOUT=120
ELASTICSEARCH_CONNECT_TIMEOUT=30
المرحلة الثانية: نسخ الخدمات من المشروع القديم 📦
الخطوة 2.1: إنشاء UltraFastSearchService
[NEW] 
UltraFastSearchService.php
نسخ الملف من المشروع القديم مع التعديلات اللازمة للتوافق مع المشروع الجديد.

الوظائف الرئيسية:

search()
 - البحث في محتوى الكتب
getAvailableFilters()
 - جلب قوائم الفلاتر المتاحة
buildOptimizedQuery()
 - بناء استعلامات Elasticsearch
buildExactMatchQuery()
 - البحث المطابق
buildFlexibleMatchQuery()
 - البحث المرن
buildMorphologicalQuery()
 - البحث الصرفي
exact_match
flexible_match
morphological
Search Request
Search Type?
buildExactMatchQuery
buildFlexibleMatchQuery
buildMorphologicalQuery
buildOptimizedQuery
Elasticsearch Query
Transform Results
Return JSON
الخطوة 2.2: إنشاء SearchController
[NEW] 
SearchController.php
نسخ من المشروع القديم مع التأكد من التوافق.

Endpoints:

GET /api/ultra-search - البحث في المحتوى
GET /api/filter-options - خيارات الفلترة
GET /api/available-filters - الفلاتر المتاحة
الخطوة 2.3: إنشاء SearchAllController
[NEW] 
SearchAllController.php
نسخ من المشروع القديم.

Endpoints:

GET /api/search-all/authors - البحث في المؤلفين
GET /api/search-all/books - البحث في عناوين الكتب
GET /api/search-all/sections - قائمة الأقسام
GET /api/search-all/unified - البحث الموحد
GET /api/search-all/suggestions - الاقتراحات
المرحلة الثالثة: إضافة الـ Routes 🛤️
الخطوة 3.1: تحديث ملف Routes
[MODIFY] 
web.php
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SearchAllController;
// Search API Routes
Route::prefix('api')->group(function () {
    // Ultra-fast search APIs
    Route::get('/ultra-search', [SearchController::class, 'apiSearch'])->name('api.ultra-search');
    Route::get('/filter-options', [SearchController::class, 'getFilterOptions'])->name('api.filter-options');
    Route::get('/available-filters', [SearchController::class, 'getAvailableFilters'])->name('api.available-filters');
    // Advanced Search APIs
    Route::prefix('search-all')->name('api.search.')->group(function () {
        Route::get('/authors', [SearchAllController::class, 'searchAuthors'])->name('authors');
        Route::get('/books', [SearchAllController::class, 'searchBookTitles'])->name('books');
        Route::get('/sections', [SearchAllController::class, 'getBookSections'])->name('sections');
        Route::get('/unified', [SearchAllController::class, 'searchUnified'])->name('unified');
        Route::get('/suggestions', [SearchAllController::class, 'searchSuggestions'])->name('suggestions');
        Route::get('/stats', [SearchAllController::class, 'getSearchStats'])->name('stats');
    });
});
المرحلة الرابعة: تحديث صفحات البحث (Frontend) 🎨
الخطوة 4.1: تحديث static-search.blade.php
[MODIFY] 
static-search.blade.php
التغييرات المطلوبة:

استبدال Mock Data بـ API Calls

إزالة mockData الثابتة
إضافة دوال لجلب البيانات من API
إضافة Alpine.js Data للبحث

x-data="{
    // البيانات الأساسية
    query: '',
    results: [],
    loading: false,
    error: null,
    
    // إعدادات البحث
    searchType: 'flexible_match',
    wordOrder: 'any_order',
    searchMode: 'content', // books, authors, content
    
    // الصفحات
    currentPage: 1,
    totalPages: 1,
    totalResults: 0,
    
    // الفلاتر
    filters: {
        books: [],
        authors: [],
        sections: []
    },
    selectedFilters: {
        books: [],
        authors: [],
        sections: []
    },
    
    // دوال البحث
    async performSearch() {
        if (!this.query && !this.hasSelectedFilters) return;
        
        this.loading = true;
        this.error = null;
        
        try {
            const params = new URLSearchParams({
                q: this.query,
                page: this.currentPage,
                search_type: this.searchType,
                word_order: this.wordOrder,
                section_id: this.selectedFilters.sections.join(','),
                author_id: this.selectedFilters.authors.join(','),
                book_id: this.selectedFilters.books.join(',')
            });
            
            const response = await fetch(`/api/ultra-search?${params}`);
            const data = await response.json();
            
            if (data.success) {
                this.results = data.data;
                this.totalResults = data.pagination.total;
                this.totalPages = data.pagination.last_page;
            } else {
                this.error = data.message;
            }
        } catch (e) {
            this.error = 'حدث خطأ في الاتصال';
        } finally {
            this.loading = false;
        }
    },
    
    async loadFilters() {
        try {
            const response = await fetch('/api/available-filters');
            const data = await response.json();
            
            if (data.success) {
                this.filters = data.data;
            }
        } catch (e) {
            console.error('Failed to load filters:', e);
        }
    },
    
    // ... المزيد من الدوال
}"
تحديث قالب عرض النتائج

إضافة Loading States و Error Handling

الخطوة 4.2: تحديث header.blade.php
[MODIFY] 
header.blade.php
التغييرات:

ربط حقل البحث بـ API
تحديث Filter Modal لجلب بيانات حقيقية
إضافة Event Dispatch للتواصل مع المكونات الأخرى
الخطوة 4.3: تحديث results-sidebar.blade.php
[MODIFY] 
results-sidebar.blade.php
التغييرات:

استبدال المحتوى الثابت بـ Dynamic Content
إضافة x-for loop للنتائج الحقيقية
إضافة Loading و Empty States
<div class="flex-1 overflow-y-auto" x-data>
    <!-- Loading State -->
    <div x-show="$store.search.loading" class="p-4 text-center">
        <svg class="animate-spin h-8 w-8 mx-auto text-green-600">...</svg>
        <p class="mt-2 text-gray-500">جاري البحث...</p>
    </div>
    
    <!-- Results -->
    <template x-for="result in $store.search.results" :key="result.id">
        <div class="group relative bg-white hover:bg-gray-50 rounded-lg p-3 cursor-pointer border border-gray-100">
            <div class="flex justify-between items-start mb-1">
                <h4 class="font-bold text-gray-800" x-text="result.book_title"></h4>
                <span class="text-xs bg-gray-100 px-1.5 py-0.5 rounded" x-text="'صـ ' + result.page_number"></span>
            </div>
            <p class="text-xs text-gray-500 mb-2" x-text="result.author_names"></p>
            <div class="text-xs text-gray-600 leading-relaxed font-serif" x-html="result.highlight"></div>
        </div>
    </template>
    
    <!-- Empty State -->
    <div x-show="!$store.search.loading && $store.search.results.length === 0" class="p-8 text-center">
        <p class="text-gray-500">لا توجد نتائج</p>
    </div>
</div>
الخطوة 4.4: تحديث preview-pane.blade.php
[MODIFY] 
preview-pane.blade.php
التغييرات:

إظهار محتوى الصفحة المحددة
التكامل مع Book Reader للقراءة
المرحلة الخامسة: إنشاء Alpine Store للبحث 🗄️
الخطوة 5.1: إنشاء Search Store
[NEW] 
search-store.js
document.addEventListener('alpine:init', () => {
    Alpine.store('search', {
        // State
        query: '',
        results: [],
        loading: false,
        error: null,
        currentPage: 1,
        totalPages: 1,
        totalResults: 0,
        selectedResult: null,
        
        // Search Settings
        searchType: 'flexible_match',
        wordOrder: 'any_order',
        
        // Filters
        availableFilters: {
            books: [],
            authors: [],
            sections: []
        },
        selectedFilters: {
            books: [],
            authors: [],
            sections: []
        },
        
        // Actions
        async search() {
            // Implementation
        },
        
        async loadFilters() {
            // Implementation
        },
        
        selectResult(result) {
            this.selectedResult = result;
        },
        
        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
                this.search();
            }
        },
        
        prevPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
                this.search();
            }
        }
    });
});
📋 ملخص الملفات المطلوبة
ملفات جديدة (NEW)
الملف	الحجم تقريباً
app/Services/UltraFastSearchService.php
~28 KB
app/Http/Controllers/SearchController.php
~10 KB
app/Http/Controllers/SearchAllController.php
~12 KB
resources/js/search-store.js	~5 KB
ملفات محدثة (MODIFY)
الملف	التغييرات
config/services.php
إضافة elasticsearch config
.env
إضافة متغيرات ES
routes/web.php
إضافة API routes
resources/views/pages/search/static-search.blade.php
ربط API
resources/views/components/search/header.blade.php
ربط API
resources/views/components/search/results-sidebar.blade.php
Dynamic content
resources/views/components/search/preview-pane.blade.php
Dynamic content
✅ خطة التحقق
اختبارات آلية
# 1. تشغيل الخادم
php artisan serve
# 2. اختبار API endpoints
curl http://localhost:8000/api/available-filters
curl "http://localhost:8000/api/ultra-search?q=الصلاة"
curl "http://localhost:8000/api/search-all/books?q=فقه"
curl "http://localhost:8000/api/search-all/authors?q=النووي"
اختبارات يدوية
فتح صفحة البحث /static-search
كتابة كلمة بحث والضغط على Enter
التحقق من ظهور النتائج الحقيقية
اختبار الفلاتر (كتب، مؤلفين، أقسام)
اختبار أنواع البحث المختلفة
اختبار Pagination
⏱️ الجدول الزمني المقترح
المرحلة	المدة	الأولوية
المرحلة 1: البنية التحتية	30 دقيقة	🔴 عالية
المرحلة 2: الخدمات	1 ساعة	🔴 عالية
المرحلة 3: Routes	15 دقيقة	🔴 عالية
المرحلة 4: Frontend	2 ساعة	🔴 عالية
المرحلة 5: Alpine Store	30 دقيقة	🟡 متوسطة
المجموع	~4 ساعات	-
TIP

يمكن البدء بالمراحل 1-3 أولاً لضمان عمل الـ API، ثم تحديث الـ Frontend تدريجياً.

WARNING

تأكد من أن خادم Elasticsearch (145.223.98.97:9201) قابل للوصول من بيئة التطوير الخاصة بك قبل البدء.