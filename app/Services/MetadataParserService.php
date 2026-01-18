<?php

namespace App\Services;

/**
 * خدمة تحليل البيانات الوصفية المستخرجة من Turath.io
 * 
 * تستخرج معلومات المؤلف، المحقق، الناشر من حقل meta.info
 */
class MetadataParserService
{
    /**
     * الأرقام العربية وما يقابلها بالإنجليزية
     */
    protected array $arabicNumbers = [
        '٠' => '0',
        '١' => '1',
        '٢' => '2',
        '٣' => '3',
        '٤' => '4',
        '٥' => '5',
        '٦' => '6',
        '٧' => '7',
        '٨' => '8',
        '٩' => '9',
    ];

    /**
     * تحليل معلومات الكتاب من حقل info
     * 
     * @param string $info نص المعلومات
     * @return array البيانات المستخرجة
     */
    public function parseBookInfo(string $info): array
    {
        return [
            'book_name' => $this->extractField($info, 'الكتاب'),
            'author_name' => $this->extractField($info, 'المؤلف'),
            'editor_name' => $this->extractField($info, 'المحقق'),
            'reviewer_name' => $this->extractField($info, 'راجعها وقدم لها')
                ?? $this->extractField($info, 'المراجع'),
            'publisher_name' => $this->extractField($info, 'الناشر'),
            'pages_count' => $this->extractNumber($info, 'عدد الصفحات'),
            'volumes_count' => $this->extractNumber($info, 'عدد الأجزاء')
                ?? $this->extractNumber($info, 'عدد المجلدات'),
            'edition' => $this->extractField($info, 'الطبعة'),
            'publication_year' => $this->extractYear($info),
        ];
    }

    /**
     * استخراج حقل معين من النص
     * 
     * @param string $text النص الكامل
     * @param string $fieldName اسم الحقل
     * @return string|null القيمة المستخرجة
     */
    public function extractField(string $text, string $fieldName): ?string
    {
        $pattern = "/{$fieldName}:\s*([^\n]+)/u";

        if (preg_match($pattern, $text, $matches)) {
            return trim($matches[1]);
        }

        return null;
    }

    /**
     * استخراج رقم (يدعم الأرقام العربية)
     * 
     * @param string $text النص الكامل
     * @param string $fieldName اسم الحقل
     * @return int|null الرقم المستخرج
     */
    public function extractNumber(string $text, string $fieldName): ?int
    {
        $pattern = "/{$fieldName}:\s*([٠-٩0-9]+)/u";

        if (preg_match($pattern, $text, $matches)) {
            $number = $this->convertArabicNumbers($matches[1]);
            return (int) $number;
        }

        return null;
    }

    /**
     * استخراج سنة النشر
     * 
     * @param string $text النص الكامل
     * @return int|null السنة
     */
    public function extractYear(string $text): ?int
    {
        // البحث عن سنة هجرية أو ميلادية
        $patterns = [
            '/سنة النشر:\s*([٠-٩0-9]{4})/u',
            '/(\d{4})\s*هـ/',
            '/(\d{4})\s*م/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                $year = $this->convertArabicNumbers($matches[1]);
                return (int) $year;
            }
        }

        return null;
    }

    /**
     * استخراج سنة وفاة المؤلف من اسمه
     * 
     * مثال: "محيي الدين النووي (٦٣١ - ٦٧٦ هـ)"
     * 
     * @param string $authorName اسم المؤلف
     * @return array ['birth_year' => int|null, 'death_year' => int|null]
     */
    public function extractAuthorDates(string $authorName): array
    {
        $result = [
            'birth_year' => null,
            'death_year' => null,
            'clean_name' => $authorName,
        ];

        // البحث عن نمط (سنة - سنة هـ)
        $pattern = '/\(([٠-٩0-9]+)\s*[-–]\s*([٠-٩0-9]+)\s*هـ?\)/u';

        if (preg_match($pattern, $authorName, $matches)) {
            $result['birth_year'] = (int) $this->convertArabicNumbers($matches[1]);
            $result['death_year'] = (int) $this->convertArabicNumbers($matches[2]);
            $result['clean_name'] = trim(preg_replace($pattern, '', $authorName));
        } else {
            // البحث عن نمط (ت: سنة هـ) - تاريخ الوفاة فقط
            $deathPattern = '/\(ت[:\s]*([٠-٩0-9]+)\s*هـ?\)/u';
            if (preg_match($deathPattern, $authorName, $matches)) {
                $result['death_year'] = (int) $this->convertArabicNumbers($matches[1]);
                $result['clean_name'] = trim(preg_replace($deathPattern, '', $authorName));
            }
        }

        return $result;
    }

    /**
     * تحويل الأرقام العربية إلى إنجليزية
     * 
     * @param string $text النص
     * @return string النص بأرقام إنجليزية
     */
    public function convertArabicNumbers(string $text): string
    {
        return strtr($text, $this->arabicNumbers);
    }

    /**
     * تحديد المذهب من النص
     * 
     * @param string $text النص
     * @return string|null المذهب
     */
    public function detectMadhhab(string $text): ?string
    {
        $madhahib = [
            'الحنفي' => 'المذهب الحنفي',
            'المالكي' => 'المذهب المالكي',
            'الشافعي' => 'المذهب الشافعي',
            'الحنبلي' => 'المذهب الحنبلي',
        ];

        foreach ($madhahib as $keyword => $madhhab) {
            if (str_contains($text, $keyword)) {
                return $madhhab;
            }
        }

        return null;
    }

    /**
     * تنظيف اسم الكتاب
     * 
     * @param string $name الاسم الأصلي
     * @return string الاسم المنظف
     */
    public function cleanBookName(string $name): string
    {
        // إزالة الأقواس والمعلومات الإضافية إذا كان الاسم طويلاً جداً
        $cleaned = trim($name);

        // إزالة أرقام المجلدات من الاسم
        $cleaned = preg_replace('/\s*[-–]\s*المجلد\s*\d+/u', '', $cleaned);
        $cleaned = preg_replace('/\s*[-–]\s*الجزء\s*\d+/u', '', $cleaned);

        return trim($cleaned);
    }

    /**
     * إنشاء slug من اسم الكتاب العربي
     * 
     * @param string $name اسم الكتاب
     * @return string الـ slug
     */
    public function generateSlug(string $name): string
    {
        // إزالة التشكيل
        $slug = preg_replace('/[\x{064B}-\x{065F}]/u', '', $name);

        // استبدال المسافات بشرطات
        $slug = preg_replace('/\s+/', '-', trim($slug));

        // إزالة الرموز غير المسموحة
        $slug = preg_replace('/[^\p{L}\p{N}\-]/u', '', $slug);

        // تقليل الشرطات المتعددة
        $slug = preg_replace('/-+/', '-', $slug);

        // إزالة الشرطات من البداية والنهاية
        $slug = trim($slug, '-');

        // تحديد الطول
        if (mb_strlen($slug) > 200) {
            $slug = mb_substr($slug, 0, 200);
            $slug = preg_replace('/-[^-]*$/', '', $slug); // قطع عند آخر كلمة كاملة
        }

        return $slug ?: 'book-' . time();
    }
}
