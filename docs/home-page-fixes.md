# إصلاح أخطاء الصفحة الرئيسية - Home Page Fixes

## ملخص المشكلات والحلول

### 1. خطأ `RouteNotFoundException: Route [book.read] not defined`

**السبب:** استخدام رابط `route('book.read')` في قالب الكتب بينما هذا الـ route معلق (commented) في `web.php`

**الحل:** تم إزالة روابط الـ routes غير الموجودة من:

| الملف | التغيير |
|-------|---------|
| `resources/views/livewire/books-table.blade.php` | إزالة `route('book.read')` و `route('authors.details')` |
| `resources/views/livewire/authors-table.blade.php` | إزالة `route('authors.details')` |

---

### 2. خطأ `QueryException: Unknown column 'full_name'`

**السبب:** تغيير هيكل قاعدة البيانات من عمود `full_name` واحد إلى ثلاثة أعمدة:
- `first_name`
- `middle_name`
- `last_name`

**الحل:** تم تحديث الاستعلامات في:

#### `app/Livewire/AuthorsTable.php`
```php
// البحث - قبل:
$q->where('full_name', 'like', '%' . $this->search . '%')

// البحث - بعد:
$q->where('first_name', 'like', '%' . $this->search . '%')
  ->orWhere('middle_name', 'like', '%' . $this->search . '%')
  ->orWhere('last_name', 'like', '%' . $this->search . '%')

// الترتيب - قبل:
->orderBy('full_name', 'asc')

// الترتيب - بعد:
->orderBy('first_name', 'asc')
```

#### `app/Livewire/BooksTable.php`
```php
// البحث في المؤلفين - قبل:
$authorQuery->where('full_name', 'like', '%' . $this->search . '%');

// البحث في المؤلفين - بعد:
$authorQuery->where('first_name', 'like', '%' . $this->search . '%')
            ->orWhere('middle_name', 'like', '%' . $this->search . '%')
            ->orWhere('last_name', 'like', '%' . $this->search . '%');
```

---

## الملفات المعدلة

1. `app/Livewire/AuthorsTable.php`
2. `app/Livewire/BooksTable.php`
3. `resources/views/livewire/books-table.blade.php`
4. `resources/views/livewire/authors-table.blade.php`

---

## خطوات النشر على السيرفر

```bash
# 1. الدخول لمجلد المشروع
cd /path/to/your/project

# 2. سحب التحديثات من GitHub
git pull origin main

# 3. مسح الـ Cache
php artisan cache:clear
php artisan view:clear
php artisan config:clear
php artisan route:clear
```

أو زيارة: `https://alkamelah1.anwaralolmaa.com/clear-cache-secret-2024`

---

## ملاحظات

- الـ `full_name` لا يزال موجود كـ **accessor** في موديل `Author.php` (يجمع الاسم الأول والأوسط والأخير)
- يمكن استخدامه في العرض (views) لكن **ليس في استعلامات قاعدة البيانات**
