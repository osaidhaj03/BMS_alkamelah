# تقرير إعادة هيكلة قاعدة البيانات
# Database Restructure Report

**التاريخ:** 1 ديسمبر 2025  
**قاعدة البيانات:** bms_v2  
**الاتصال:** bms_v2 (.env)

---

## 📊 ملخص الجداول الحالية

| # | الجدول | عدد السجلات | حجم البيانات | حجم الفهارس | الحالة |
|---|--------|-------------|--------------|-------------|--------|
| 1 | authors | 2,635 | 5.7 MB | 256 KB | ✅ جيد |
| 2 | author_book | 3,816 | 256 KB | 576 KB | ✅ جيد |
| 3 | books | 11,343 | 9.8 MB | 4.3 MB | ⚠️ يحتاج تنظيف |
| 4 | book_extracted_metadata | 2,056 | 448 KB | 432 KB | ✅ جيد |
| 5 | book_sections | 40 | 16 KB | 32 KB | ✅ جيد |
| 6 | chapters | 1,629,252 | 230 MB | 440 MB | ✅ جيد |
| 7 | pages | 4,156,281 | 16.9 GB | 1.4 GB | ✅ جيد (كبير) |
| 8 | publishers | 1,710 | 320 KB | 144 KB | ✅ جيد |
| 9 | volumes | 22,222 | 2.6 MB | 6.4 MB | ✅ جيد |
| 10 | sections | 0 | 16 KB | 16 KB | ❌ فارغ/مكرر |
| 11 | users | 1 | 16 KB | 16 KB | ✅ جيد |
| 12 | roles | 1 | 16 KB | 16 KB | ✅ جيد |
| 13 | permissions | 99 | 16 KB | 16 KB | ✅ جيد |
| 14 | role_has_permissions | 99 | 16 KB | 16 KB | ✅ جيد |
| 15 | model_has_roles | 1 | 16 KB | 16 KB | ✅ جيد |
| 16 | model_has_permissions | 0 | 16 KB | 16 KB | ✅ جيد |
| 17 | cache | 5 | 32 KB | 0 | ✅ جيد |
| 18 | cache_locks | 0 | 16 KB | 0 | ✅ جيد |
| 19 | sessions | 1 | 48 KB | 32 KB | ✅ جيد |
| 20 | jobs | 0 | 16 KB | 16 KB | ✅ جيد |
| 21 | job_batches | 0 | 16 KB | 0 | ✅ جيد |
| 22 | failed_jobs | 0 | 16 KB | 16 KB | ✅ جيد |
| 23 | migrations | 4 | 16 KB | 0 | ✅ جيد |
| 24 | password_reset_tokens | 0 | 16 KB | 0 | ✅ جيد |

---

## 🔴 المشاكل المكتشفة

### 1. جدول `books` - أعمدة مكررة ومربكة

الأعمدة المكررة في جدول الكتب:

| العمود الأصلي | العمود المكرر | المشكلة |
|--------------|--------------|---------|
| `pages_count` | `page_count` | نفس الغرض - عدد الصفحات |
| `volumes_count` | `volume_count` | نفس الغرض - عدد المجلدات |
| `edition` | `edition_number` و `edition_DATA` | ثلاثة أعمدة للطبعة! |

**الأعمدة المطلوب حذفها:**
- `edition_DATA` - عمود غير واضح الاستخدام
- `page_count` - مكرر مع `pages_count`
- `volume_count` - مكرر مع `volumes_count`
- `edition_number` - مكرر مع `edition`

### 2. جدول `sections` vs `book_sections`

يوجد جدولان للأقسام:
- `sections` - **فارغ** (0 سجلات) ولكن يرتبط بـ books عبر `ososa`
- `book_sections` - يحتوي 40 سجل ويعمل بشكل صحيح

**التوصية:** 
- حذف جدول `sections` الفارغ
- أو نقل العلاقة من `ososa` إلى `book_section_id`

### 3. عمود `ososa` في جدول `books`

- اسم غير واضح (ososa?)
- يرتبط بجدول `sections` الفارغ
- يجب إعادة تسميته أو حذفه

### 4. عمود `author_role` مكرر

- موجود في جدول `authors`
- موجود في جدول `books`
- المكان الصحيح هو جدول `author_book` (موجود كـ `role`)

---

## 🟢 التوصيات والإجراءات

### المرحلة 1: تنظيف جدول `books`

#### أ. حذف الأعمدة المكررة

```sql
-- حذف الأعمدة المكررة من جدول books
ALTER TABLE books DROP COLUMN edition_DATA;
ALTER TABLE books DROP COLUMN page_count;
ALTER TABLE books DROP COLUMN volume_count;
ALTER TABLE books DROP COLUMN edition_number;
ALTER TABLE books DROP COLUMN author_role;
ALTER TABLE books DROP COLUMN author_death_year;
```

#### ب. إعادة تسمية عمود ososa

```sql
-- إعادة تسمية عمود ososa إلى section_id
-- أولاً حذف المفاتيح الخارجية
ALTER TABLE books DROP FOREIGN KEY books_ibfk_1;
ALTER TABLE books DROP FOREIGN KEY books_section_id_foreign;

-- إعادة التسمية
ALTER TABLE books CHANGE COLUMN ososa section_id BIGINT NULL;
```

### المرحلة 2: حذف جدول sections الفارغ

```sql
-- حذف جدول sections الفارغ بعد نقل البيانات
DROP TABLE IF EXISTS sections;
```

### المرحلة 3: تنظيف جدول authors

```sql
-- حذف عمود author_role من جدول authors (موجود في author_book)
ALTER TABLE authors DROP COLUMN author_role;
```

---

## 📋 هيكل الجداول المقترح بعد التنظيف

### جدول `books` (بعد التنظيف)

| العمود | النوع | الوصف |
|--------|------|-------|
| id | BIGINT | المفتاح الأساسي |
| shamela_id | VARCHAR(50) | معرف الشاملة |
| title | VARCHAR(255) | عنوان الكتاب |
| description | TEXT | وصف الكتاب |
| slug | VARCHAR(200) | الرابط المختصر |
| cover_image | VARCHAR(255) | صورة الغلاف |
| pages_count | INT | عدد الصفحات |
| volumes_count | INT | عدد المجلدات |
| status | ENUM | الحالة |
| visibility | ENUM | الظهور |
| source_url | VARCHAR(255) | رابط المصدر |
| book_section_id | BIGINT | القسم |
| publisher_id | BIGINT | الناشر |
| edition | INT | رقم الطبعة |
| has_original_pagination | TINYINT | هل له ترقيم أصلي |
| publication_year | YEAR | سنة النشر |
| publication_place | VARCHAR(255) | مكان النشر |
| isbn | VARCHAR(20) | الرقم الدولي |
| edition_info | TEXT | معلومات الطبعة |
| additional_notes | TEXT | ملاحظات إضافية |
| created_at | TIMESTAMP | تاريخ الإنشاء |
| updated_at | TIMESTAMP | تاريخ التحديث |

### جدول `authors` (بعد التنظيف)

| العمود | النوع | الوصف |
|--------|------|-------|
| id | BIGINT | المفتاح الأساسي |
| full_name | VARCHAR(255) | الاسم الكامل |
| slug | VARCHAR(255) | الرابط المختصر |
| biography | TEXT | السيرة الذاتية |
| image | VARCHAR(255) | الصورة |
| madhhab | ENUM | المذهب |
| is_living | TINYINT | على قيد الحياة |
| birth_year_type | ENUM | نوع سنة الميلاد |
| birth_year | INT | سنة الميلاد |
| death_year_type | ENUM | نوع سنة الوفاة |
| death_year | INT | سنة الوفاة |
| birth_date | DATE | تاريخ الميلاد |
| death_date | DATE | تاريخ الوفاة |
| created_at | TIMESTAMP | تاريخ الإنشاء |
| updated_at | TIMESTAMP | تاريخ التحديث |

---

## 🆕 جداول جديدة مقترحة

### 1. جدول `book_tags` (وسوم الكتب)

```sql
CREATE TABLE book_tags (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    color VARCHAR(7) DEFAULT '#000000',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_book_tags_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 2. جدول `book_tag` (الربط بين الكتب والوسوم)

```sql
CREATE TABLE book_tag (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    book_id BIGINT UNSIGNED NOT NULL,
    tag_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL,
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES book_tags(id) ON DELETE CASCADE,
    UNIQUE KEY unique_book_tag (book_id, tag_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 3. جدول `reading_progress` (تقدم القراءة)

```sql
CREATE TABLE reading_progress (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    book_id BIGINT UNSIGNED NOT NULL,
    volume_id BIGINT UNSIGNED NULL,
    page_id BIGINT UNSIGNED NULL,
    last_page_number INT DEFAULT 0,
    progress_percentage DECIMAL(5,2) DEFAULT 0.00,
    last_read_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE,
    FOREIGN KEY (volume_id) REFERENCES volumes(id) ON DELETE SET NULL,
    FOREIGN KEY (page_id) REFERENCES pages(id) ON DELETE SET NULL,
    UNIQUE KEY unique_user_book (user_id, book_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 4. جدول `bookmarks` (العلامات المرجعية)

```sql
CREATE TABLE bookmarks (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    book_id BIGINT UNSIGNED NOT NULL,
    page_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(255) NULL,
    note TEXT NULL,
    color VARCHAR(7) DEFAULT '#FFD700',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE,
    FOREIGN KEY (page_id) REFERENCES pages(id) ON DELETE CASCADE,
    INDEX idx_bookmarks_user_book (user_id, book_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 5. جدول `favorites` (المفضلة)

```sql
CREATE TABLE favorites (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    book_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_favorite (user_id, book_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 📊 مخطط العلاقات المحدث

```
┌─────────────────┐     ┌─────────────────┐     ┌─────────────────┐
│     users       │     │     books       │     │    authors      │
├─────────────────┤     ├─────────────────┤     ├─────────────────┤
│ id              │     │ id              │     │ id              │
│ name            │     │ title           │     │ full_name       │
│ email           │     │ slug            │     │ madhhab         │
└────────┬────────┘     │ book_section_id │     │ death_year      │
         │              │ publisher_id    │     └────────┬────────┘
         │              └────────┬────────┘              │
         │                       │                       │
         │    ┌──────────────────┼──────────────────┐    │
         │    │                  │                  │    │
         ▼    ▼                  ▼                  ▼    ▼
┌─────────────────┐     ┌─────────────────┐     ┌─────────────────┐
│ reading_progress│     │    volumes      │     │   author_book   │
├─────────────────┤     ├─────────────────┤     ├─────────────────┤
│ user_id    ────►│     │ book_id    ────►│     │ book_id    ────►│
│ book_id         │     │ number          │     │ author_id  ────►│
│ progress_%      │     │ title           │     │ role            │
└─────────────────┘     └────────┬────────┘     └─────────────────┘
                                 │
         ┌───────────────────────┼───────────────────────┐
         │                       │                       │
         ▼                       ▼                       ▼
┌─────────────────┐     ┌─────────────────┐     ┌─────────────────┐
│   bookmarks     │     │    chapters     │     │     pages       │
├─────────────────┤     ├─────────────────┤     ├─────────────────┤
│ user_id         │     │ book_id         │     │ book_id         │
│ book_id         │     │ volume_id       │     │ volume_id       │
│ page_id         │     │ parent_id       │     │ chapter_id      │
└─────────────────┘     │ title           │     │ page_number     │
                        └─────────────────┘     │ content         │
                                                └─────────────────┘
```

---

## ⚡ خطوات التنفيذ

### الخطوة 1: النسخ الاحتياطي
```bash
mysqldump -u root -p bms_v2 > bms_v2_backup_$(date +%Y%m%d).sql
```

### الخطوة 2: تنظيف جدول books
```sql
-- تشغيل أوامر حذف الأعمدة المكررة
```

### الخطوة 3: إنشاء الجداول الجديدة
```sql
-- تشغيل أوامر إنشاء الجداول الجديدة
```

### الخطوة 4: تحديث Laravel Models
```bash
php artisan make:model BookTag -m
php artisan make:model ReadingProgress -m
php artisan make:model Bookmark -m
php artisan make:model Favorite -m
```

---

## ✅ ملخص التغييرات

| النوع | التفاصيل | الأولوية |
|-------|----------|----------|
| حذف أعمدة | 6 أعمدة من books + 1 من authors | 🔴 عالي |
| حذف جداول | sections (فارغ) | 🟡 متوسط |
| إعادة تسمية | ososa → section_id | 🟡 متوسط |
| جداول جديدة | 5 جداول | 🟢 اختياري |

---

## 📝 ملاحظات هامة

1. **قبل أي تغيير**: تأكد من عمل نسخة احتياطية كاملة
2. **اختبر محلياً أولاً**: لا تطبق على قاعدة البيانات الإنتاجية مباشرة
3. **حدّث الـ Models**: بعد تغيير الجداول، حدّث ملفات Laravel Models
4. **حدّث الـ Migrations**: أنشئ ملفات migration جديدة للتوثيق

---

*تم إنشاء هذا التقرير بواسطة GitHub Copilot*
