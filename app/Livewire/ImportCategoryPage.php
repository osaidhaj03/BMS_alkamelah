<?php

namespace App\Livewire;

use App\Models\Book;
use App\Models\BookSection;
use App\Services\TurathScraperService;
use App\Services\MetadataParserService;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('استيراد قسم كامل من تراث')]
class ImportCategoryPage extends Component
{
    // Input
    public string $categoryUrl = '';
    public string $manualBookIds = '';
    public ?int $sectionId = null;
    public array $sections = [];

    // Books list
    public array $books = [];
    public int $currentBookIndex = 0;

    // Import state
    public bool $isImporting = false;
    public bool $isFetching = false;
    public int $progress = 0;
    public int $completedBooks = 0;
    public int $failedBooks = 0;

    // Logs
    public array $importLog = [];
    public string $statusMessage = '';

    // Options
    public bool $skipPages = false;

    public function mount()
    {
        $this->sections = BookSection::pluck('name', 'id')->toArray();
    }

    public function render()
    {
        return view('livewire.import-category-page')
            ->layout('components.layouts.app');
    }

    /**
     * Load books from manual IDs input
     */
    public function loadBooks()
    {
        $this->isFetching = true;
        $this->books = [];
        $this->importLog = [];
        $this->statusMessage = '';

        // Parse IDs from input
        $input = $this->manualBookIds;
        $ids = preg_split('/[\s,\n\r]+/', $input);
        $ids = array_filter($ids, fn($id) => is_numeric(trim($id)));
        $ids = array_map('trim', $ids);
        $ids = array_unique($ids);

        if (empty($ids)) {
            $this->statusMessage = 'لم يتم العثور على IDs صالحة. أدخل أرقام الكتب مفصولة بفاصلة أو سطر جديد.';
            $this->isFetching = false;
            return;
        }

        $this->addLog("📚 تم العثور على " . count($ids) . " كتاب");

        foreach ($ids as $index => $bookId) {
            $this->addLog("📖 جاري جلب معلومات الكتاب {$bookId} (" . ($index + 1) . "/" . count($ids) . ")...");

            try {
                $response = Http::timeout(10)->get("https://api.turath.io/book", [
                    'id' => $bookId,
                    'ver' => 3
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $meta = $data['meta'] ?? [];
                    $indexes = $data['indexes'] ?? [];

                    // Extract author from info
                    $authorName = '؟';
                    if (!empty($meta['info'])) {
                        if (preg_match('/المؤلف:\s*([^\n]+)/', $meta['info'], $match)) {
                            $authorName = trim($match[1]);
                            $authorName = explode('(', $authorName)[0];
                            $authorName = mb_substr($authorName, 0, 40);
                        }
                    }

                    // Calculate pages
                    $pages = 0;
                    if (!empty($indexes['page_map'])) {
                        $pages = count($indexes['page_map']);
                    } elseif (!empty($indexes['volume_bounds'])) {
                        foreach ($indexes['volume_bounds'] as $bounds) {
                            $pages += ($bounds[1] - $bounds[0] + 1);
                        }
                    }

                    $this->books[] = [
                        'id' => $bookId,
                        'name' => $meta['name'] ?? "كتاب {$bookId}",
                        'author' => $authorName,
                        'pages' => $pages ?: '؟',
                        'status' => 'pending', // pending, importing, done, error
                        'message' => '',
                    ];

                    $this->addLog("✅ " . ($meta['name'] ?? $bookId));
                } else {
                    $this->books[] = [
                        'id' => $bookId,
                        'name' => "كتاب {$bookId}",
                        'author' => '؟',
                        'pages' => '؟',
                        'status' => 'pending',
                        'message' => '',
                    ];
                    $this->addLog("⚠️ لم يتم جلب معلومات الكتاب {$bookId}");
                }
            } catch (\Exception $e) {
                $this->books[] = [
                    'id' => $bookId,
                    'name' => "كتاب {$bookId}",
                    'author' => '؟',
                    'pages' => '؟',
                    'status' => 'pending',
                    'message' => '',
                ];
                $this->addLog("❌ خطأ في جلب الكتاب {$bookId}: " . $e->getMessage());
            }
        }

        $this->addLog("📊 تم تحميل " . count($this->books) . " كتاب");
        $this->isFetching = false;
    }

    /**
     * Start batch import
     */
    public function startImport()
    {
        if (empty($this->books)) {
            $this->statusMessage = 'لا توجد كتب للاستيراد. قم بتحميل الكتب أولاً.';
            return;
        }

        $this->isImporting = true;
        $this->currentBookIndex = 0;
        $this->completedBooks = 0;
        $this->failedBooks = 0;
        $this->progress = 0;

        $this->addLog("🚀 بدء عملية الاستيراد...");
    }

    /**
     * Import next book in batch (called by wire:poll)
     */
    public function importNextBook()
    {
        if (!$this->isImporting || $this->currentBookIndex >= count($this->books)) {
            $this->finishImport();
            return;
        }

        $book = &$this->books[$this->currentBookIndex];

        // Skip already processed books
        if ($book['status'] === 'done' || $book['status'] === 'error') {
            $this->currentBookIndex++;
            return;
        }

        $book['status'] = 'importing';
        $this->addLog("📖 استيراد: {$book['name']} ({$book['id']})");

        try {
            // Check if book already exists
            $existingBook = Book::where('shamela_id', (string) $book['id'])->first();
            if ($existingBook) {
                $book['status'] = 'done';
                $book['message'] = 'موجود مسبقاً';
                $this->addLog("⏭️ الكتاب موجود مسبقاً: {$book['name']}");
                $this->completedBooks++;
            } else {
                // Import book using existing ImportTurathPage logic
                $importPage = new ImportTurathPage();
                $importPage->bookUrl = (string) $book['id'];
                $importPage->skipPages = $this->skipPages;
                $importPage->sectionId = $this->sectionId;
                $importPage->forceReimport = false;

                // Start import
                $importPage->startImport();

                // Wait for completion (simplified - in reality would need async handling)
                while ($importPage->isImporting) {
                    $importPage->importBatch();
                    usleep(100000); // 100ms delay
                }

                $book['status'] = 'done';
                $book['message'] = 'تم الاستيراد';
                $this->addLog("✅ تم استيراد: {$book['name']}");
                $this->completedBooks++;
            }
        } catch (\Exception $e) {
            $book['status'] = 'error';
            $book['message'] = $e->getMessage();
            $this->addLog("❌ فشل استيراد: {$book['name']} - " . $e->getMessage());
            $this->failedBooks++;
        }

        $this->currentBookIndex++;
        $this->updateProgress();
    }

    /**
     * Update progress percentage
     */
    protected function updateProgress()
    {
        if (count($this->books) > 0) {
            $this->progress = round(($this->currentBookIndex / count($this->books)) * 100);
        }
    }

    /**
     * Finish import process
     */
    protected function finishImport()
    {
        $this->isImporting = false;
        $this->addLog("🎉 اكتملت عملية الاستيراد!");
        $this->addLog("📊 النتيجة: {$this->completedBooks} نجاح، {$this->failedBooks} فشل");
        $this->statusMessage = "اكتمل الاستيراد: {$this->completedBooks} نجاح، {$this->failedBooks} فشل";
    }

    /**
     * Stop import
     */
    public function stopImport()
    {
        $this->isImporting = false;
        $this->addLog("⏸️ تم إيقاف الاستيراد");
        $this->statusMessage = "تم إيقاف الاستيراد عند الكتاب رقم " . ($this->currentBookIndex + 1);
    }

    /**
     * Reset form
     */
    public function resetForm()
    {
        $this->reset([
            'categoryUrl',
            'manualBookIds',
            'books',
            'currentBookIndex',
            'isImporting',
            'isFetching',
            'progress',
            'completedBooks',
            'failedBooks',
            'importLog',
            'statusMessage'
        ]);
    }

    /**
     * Add log entry
     */
    protected function addLog(string $message): void
    {
        $time = now()->format('H:i:s');
        $this->importLog[] = "[{$time}] {$message}";
    }
}
