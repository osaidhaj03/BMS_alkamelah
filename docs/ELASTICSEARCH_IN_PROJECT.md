# 🔍 Elasticsearch in BMS Al-Kamelah Project
# نظام Elasticsearch في مشروع المكتبة الكاملة

---

## 🌐 English Section

### Project Overview

The **BMS Al-Kamelah (المكتبة الكاملة)** project uses Elasticsearch to provide ultra-fast search across millions of Arabic book pages. This document explains how Elasticsearch is integrated and used in this specific project.

### Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                        User Interface                            │
│         (static-search.blade.php / advanced-search.blade.php)   │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                        Laravel Backend                           │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │   Routes: /api/ultra-search, /api/available-filters       │   │
│  └──────────────────────────────────────────────────────────┘   │
│                              │                                   │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │              Controllers                                  │   │
│  │   - SearchController.php                                  │   │
│  │   - SearchAllController.php                               │   │
│  └──────────────────────────────────────────────────────────┘   │
│                              │                                   │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │              Services                                     │   │
│  │   - UltraFastSearchService.php                            │   │
│  │     ├── search()                                          │   │
│  │     ├── buildOptimizedQuery()                             │   │
│  │     └── getAvailableFilters()                             │   │
│  └──────────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                    Elasticsearch Server                          │
│                 http://145.223.98.97:9201                        │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │   Index: pages_new_search                                 │   │
│  │   Documents: 4.3+ million pages                           │   │
│  │   Size: ~18 GB                                            │   │
│  │                                                           │   │
│  │   Fields:                                                 │   │
│  │   - content (with exact, flexible, stemmed sub-fields)    │   │
│  │   - book_id, book_title                                   │   │
│  │   - author_names, author_ids                              │   │
│  │   - page_number, book_section_id                          │   │
│  └──────────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────────┘
```

### Configuration Files

#### 1. Environment Variables (`.env`)

```env
# Elasticsearch Configuration
ELASTICSEARCH_HOST=http://145.223.98.97:9201
ELASTICSEARCH_INDEX=pages_new_search
ELASTICSEARCH_TIMEOUT=120
ELASTICSEARCH_CONNECT_TIMEOUT=30

# Laravel Scout Configuration
SCOUT_DRIVER=elastic
SCOUT_QUEUE=false
```

#### 2. Services Config (`config/services.php`)

```php
'elasticsearch' => [
    'host' => env('ELASTICSEARCH_HOST', 'http://145.223.98.97:9201'),
    'index' => env('ELASTICSEARCH_INDEX', 'pages_new_search'),
],
```

### Key Files

| File | Purpose |
|------|---------|
| `app/Services/UltraFastSearchService.php` | Core search logic with Elasticsearch |
| `app/Http/Controllers/SearchController.php` | API endpoints for content search |
| `app/Http/Controllers/SearchAllController.php` | API for books/authors/sections search |
| `app/Models/Page.php` | Page model with `toSearchableArray()` |
| `config/services.php` | Elasticsearch configuration |

### Search Types

The project supports three search types:

| Type | Internal Name | Description | Example |
|------|--------------|-------------|---------|
| **Exact** | `exact_match` | Literal matching | "الصلاة" finds only "الصلاة" |
| **Flexible** | `flexible_match` | With prefixes | "صلاة" finds "الصلاة", "بصلاة" |
| **Morphological** | `morphological` | Root-based | "صلاة" finds "صلى", "يصلي" |

### Word Order Options

| Option | Internal Name | Slop Value | Description |
|--------|--------------|------------|-------------|
| **Consecutive** | `consecutive` | 0 | Words must be adjacent |
| **Same Paragraph** | `same_paragraph` | 50 | Words within 50 words |
| **Any Order** | `any_order` | N/A | Uses match with AND |

### API Endpoints

#### 1. Content Search

```
GET /api/ultra-search?q=الصلاة&search_type=flexible_match&page=1
```

**Parameters:**
- `q`: Search query (required)
- `search_type`: exact_match, flexible_match, morphological
- `word_order`: consecutive, same_paragraph, any_order
- `section_id`: Filter by section (comma-separated for multiple)
- `author_id`: Filter by author (comma-separated)
- `book_id`: Filter by book (comma-separated)
- `page`: Page number
- `per_page`: Results per page (default: 15)

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 12345,
      "book_id": 123,
      "book_title": "المجموع شرح المهذب",
      "page_number": 42,
      "author_names": "الإمام النووي",
      "highlight": "...فإن كانت <mark>الصلاة</mark> مكتوبة...",
      "score": 15.234
    }
  ],
  "pagination": {
    "current_page": 1,
    "last_page": 100,
    "per_page": 15,
    "total": 1500
  }
}
```

#### 2. Available Filters

```
GET /api/available-filters?type=all
```

**Response:**
```json
{
  "success": true,
  "data": {
    "books": [
      {"id": 1, "name": "صحيح البخاري", "count": 5000}
    ],
    "authors": [
      {"id": 1, "name": "الإمام النووي", "count": 3000}
    ],
    "sections": [
      {"id": 1, "name": "الفقه", "count": 10000}
    ]
  }
}
```

#### 3. Search Authors

```
GET /api/search-all/authors?q=النووي&limit=20
```

#### 4. Search Books

```
GET /api/search-all/books?q=فقه&section_id=1
```

### How to Run a Search

1. User enters query in search box
2. Frontend sends AJAX request to `/api/ultra-search`
3. `SearchController::apiSearch()` validates input
4. `UltraFastSearchService::search()` builds Elasticsearch query
5. Query is sent to Elasticsearch server
6. Results are transformed and returned as JSON
7. Frontend displays results with highlights

### Index Structure

```json
{
  "settings": {
    "analysis": {
      "analyzer": {
        "arabic_exact": {
          "type": "custom",
          "tokenizer": "standard",
          "filter": ["lowercase"]
        },
        "arabic_flexible": {
          "type": "custom",
          "tokenizer": "standard",
          "char_filter": ["arabic_normalization_custom"],
          "filter": ["lowercase", "arabic_normalization"]
        },
        "arabic_stemmed": {
          "type": "custom",
          "tokenizer": "standard",
          "filter": ["lowercase", "arabic_stemmer"]
        }
      }
    }
  },
  "mappings": {
    "properties": {
      "content": {
        "type": "text",
        "analyzer": "arabic_flexible",
        "fields": {
          "exact": {"type": "text", "analyzer": "arabic_exact"},
          "flexible": {"type": "text", "analyzer": "arabic_flexible"},
          "stemmed": {"type": "text", "analyzer": "arabic_stemmed"}
        }
      }
    }
  }
}
```

---

## 🌍 القسم العربي

### نظرة عامة على المشروع

يستخدم مشروع **المكتبة الكاملة (BMS Al-Kamelah)** محرك Elasticsearch لتوفير بحث فائق السرعة عبر ملايين صفحات الكتب العربية. يشرح هذا المستند كيفية دمج واستخدام Elasticsearch في هذا المشروع تحديداً.

### البنية المعمارية

```
┌─────────────────────────────────────────────────────────────────┐
│                      واجهة المستخدم                              │
│         (static-search.blade.php / advanced-search.blade.php)   │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                       Laravel Backend                            │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │   المسارات: /api/ultra-search, /api/available-filters     │   │
│  └──────────────────────────────────────────────────────────┘   │
│                              │                                   │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │              المتحكمات                                    │   │
│  │   - SearchController.php                                  │   │
│  │   - SearchAllController.php                               │   │
│  └──────────────────────────────────────────────────────────┘   │
│                              │                                   │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │              الخدمات                                      │   │
│  │   - UltraFastSearchService.php                            │   │
│  │     ├── search()                                          │   │
│  │     ├── buildOptimizedQuery()                             │   │
│  │     └── getAvailableFilters()                             │   │
│  └──────────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                     خادم Elasticsearch                          │
│                 http://145.223.98.97:9201                        │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │   الفهرس: pages_new_search                                │   │
│  │   المستندات: أكثر من 4.3 مليون صفحة                       │   │
│  │   الحجم: ~18 جيجابايت                                     │   │
│  │                                                           │   │
│  │   الحقول:                                                 │   │
│  │   - content (مع حقول فرعية: exact, flexible, stemmed)     │   │
│  │   - book_id, book_title                                   │   │
│  │   - author_names, author_ids                              │   │
│  │   - page_number, book_section_id                          │   │
│  └──────────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────────┘
```

### ملفات الإعداد

#### 1. متغيرات البيئة (`.env`)

```env
# إعدادات Elasticsearch
ELASTICSEARCH_HOST=http://145.223.98.97:9201
ELASTICSEARCH_INDEX=pages_new_search
ELASTICSEARCH_TIMEOUT=120
ELASTICSEARCH_CONNECT_TIMEOUT=30

# إعدادات Laravel Scout
SCOUT_DRIVER=elastic
SCOUT_QUEUE=false
```

#### 2. ملف الخدمات (`config/services.php`)

```php
'elasticsearch' => [
    'host' => env('ELASTICSEARCH_HOST', 'http://145.223.98.97:9201'),
    'index' => env('ELASTICSEARCH_INDEX', 'pages_new_search'),
],
```

### الملفات الرئيسية

| الملف | الوظيفة |
|------|---------|
| `app/Services/UltraFastSearchService.php` | منطق البحث الأساسي مع Elasticsearch |
| `app/Http/Controllers/SearchController.php` | نقاط API للبحث في المحتوى |
| `app/Http/Controllers/SearchAllController.php` | API للبحث في الكتب/المؤلفين/الأقسام |
| `app/Models/Page.php` | نموذج الصفحة مع `toSearchableArray()` |
| `config/services.php` | إعدادات Elasticsearch |

### أنواع البحث

يدعم المشروع ثلاثة أنواع من البحث:

| النوع | الاسم الداخلي | الوصف | مثال |
|------|--------------|-------|------|
| **المطابق** | `exact_match` | مطابقة حرفية | "الصلاة" تجد "الصلاة" فقط |
| **المرن** | `flexible_match` | مع اللواصق | "صلاة" تجد "الصلاة"، "بصلاة" |
| **الصرفي** | `morphological` | حسب الجذر | "صلاة" تجد "صلى"، "يصلي" |

### خيارات ترتيب الكلمات

| الخيار | الاسم الداخلي | القيمة | الوصف |
|--------|--------------|--------|------|
| **متتالية** | `consecutive` | 0 | يجب أن تكون الكلمات متجاورة |
| **نفس الفقرة** | `same_paragraph` | 50 | الكلمات ضمن 50 كلمة |
| **أي ترتيب** | `any_order` | لا يوجد | يستخدم match مع AND |

### نقاط الـ API

#### 1. البحث في المحتوى

```
GET /api/ultra-search?q=الصلاة&search_type=flexible_match&page=1
```

**المعاملات:**
- `q`: كلمة البحث (مطلوبة)
- `search_type`: exact_match, flexible_match, morphological
- `word_order`: consecutive, same_paragraph, any_order
- `section_id`: تصفية حسب القسم (مفصولة بفاصلة للتعدد)
- `author_id`: تصفية حسب المؤلف
- `book_id`: تصفية حسب الكتاب
- `page`: رقم الصفحة
- `per_page`: النتائج لكل صفحة (افتراضي: 15)

**الاستجابة:**
```json
{
  "success": true,
  "data": [
    {
      "id": 12345,
      "book_id": 123,
      "book_title": "المجموع شرح المهذب",
      "page_number": 42,
      "author_names": "الإمام النووي",
      "highlight": "...فإن كانت <mark>الصلاة</mark> مكتوبة...",
      "score": 15.234
    }
  ],
  "pagination": {
    "current_page": 1,
    "last_page": 100,
    "per_page": 15,
    "total": 1500
  }
}
```

#### 2. الفلاتر المتاحة

```
GET /api/available-filters?type=all
```

**الاستجابة:**
```json
{
  "success": true,
  "data": {
    "books": [
      {"id": 1, "name": "صحيح البخاري", "count": 5000}
    ],
    "authors": [
      {"id": 1, "name": "الإمام النووي", "count": 3000}
    ],
    "sections": [
      {"id": 1, "name": "الفقه", "count": 10000}
    ]
  }
}
```

### كيفية تنفيذ البحث

1. المستخدم يدخل كلمة البحث
2. الواجهة ترسل طلب AJAX إلى `/api/ultra-search`
3. `SearchController::apiSearch()` يتحقق من المدخلات
4. `UltraFastSearchService::search()` يبني استعلام Elasticsearch
5. الاستعلام يُرسل لخادم Elasticsearch
6. النتائج تُحوّل وتُرجع كـ JSON
7. الواجهة تعرض النتائج مع التظليل

---

## 📊 Statistics / الإحصائيات

| Metric | Value |
|--------|-------|
| **Total Documents** | 4,309,914+ pages |
| **Index Size** | ~18 GB |
| **Average Search Time** | < 200ms |
| **Supported Languages** | Arabic (primary), English |
| **Search Types** | 3 (Exact, Flexible, Morphological) |

---

## 🛠️ Troubleshooting / حل المشاكل

### Common Issues

| Issue | Solution |
|-------|----------|
| Connection refused | Check if Elasticsearch is running on `145.223.98.97:9201` |
| 0 results | Verify index name and analyzers are configured |
| Slow search | Run `_forcemerge` on index |
| Timeout | Increase `ELASTICSEARCH_TIMEOUT` in `.env` |

### Debug Mode

To enable debug logging, add to `.env`:
```env
LOG_LEVEL=debug
```

Then check `storage/logs/laravel.log` for Elasticsearch queries.

---

## 🔗 Related Documentation

- [How Elasticsearch Works](file:///c:/Users/osaidsalah002/Documents/BMS_alkamelah/docs/ELASTICSEARCH_HOW_IT_WORKS.md)
- [Health Check Guide](file:///c:/Users/osaidsalah002/Documents/BMS_alkamelah/docs/ELASTICSEARCH_HEALTH_CHECK.md)
- [Implementation Plan](file:///C:/Users/osaidsalah002/.gemini/antigravity/brain/1dbe0afa-6ed1-40fc-8e69-08cc558cd311/implementation_plan.md)
