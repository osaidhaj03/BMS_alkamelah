<?php

namespace App\Livewire;

use App\Models\Book;
use App\Models\Page;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\Attributes\On;

/**
 * Book Content Reader Component
 * 
 * يعرض محتوى الكتاب مع التنقل بين الصفحات بدون إعادة تحميل الصفحة بالكامل
 */
class BookContentReader extends Component
{
    /**
     * معرف الكتاب
     */
    public int $bookId;

    /**
     * رقم الصفحة الحالية
     */
    #[Url(as: 'page', except: 1, history: true)]
    public int $currentPageNum = 1;

    /**
     * إجمالي الصفحات
     */
    public int $totalPages = 1;

    /**
     * الكتاب
     */
    public ?Book $book = null;

    /**
     * الصفحة الحالية
     */
    public ?Page $currentPage = null;

    /**
     * الصفحة التالية
     */
    public ?Page $nextPage = null;

    /**
     * الصفحة السابقة
     */
    public ?Page $previousPage = null;

    /**
     * Mount the component
     */
    public function mount(int $bookId, int $pageNumber = 1, ?Book $book = null): void
    {
        $this->bookId = $bookId;
        $this->currentPageNum = $pageNumber;
        $this->book = $book;

        $this->loadPageData();
    }

    /**
     * تحميل بيانات الصفحة
     */
    public function loadPageData(): void
    {
        // تحميل الكتاب إذا لم يكن محملاً
        if (!$this->book) {
            $this->book = Book::find($this->bookId);
        }

        // تحميل الصفحة الحالية
        $this->currentPage = Page::where('book_id', $this->bookId)
            ->where('page_number', $this->currentPageNum)
            ->with(['chapter', 'volume'])
            ->first();

        // إذا لم توجد الصفحة، الذهاب للأولى
        if (!$this->currentPage) {
            $this->currentPage = Page::where('book_id', $this->bookId)
                ->orderBy('page_number')
                ->first();

            if ($this->currentPage) {
                $this->currentPageNum = $this->currentPage->page_number;
            }
        }

        // إجمالي الصفحات
        $this->totalPages = Page::where('book_id', $this->bookId)->count();

        // الصفحة السابقة
        $this->previousPage = Page::where('book_id', $this->bookId)
            ->where('page_number', '<', $this->currentPageNum)
            ->orderByDesc('page_number')
            ->first();

        // الصفحة التالية
        $this->nextPage = Page::where('book_id', $this->bookId)
            ->where('page_number', '>', $this->currentPageNum)
            ->orderBy('page_number')
            ->first();

        // إرسال حدث للتمرير لأعلى الصفحة
        $this->dispatch('page-changed', pageNumber: $this->currentPageNum);
    }

    /**
     * الانتقال للصفحة التالية
     */
    public function nextPageAction(): void
    {
        if ($this->nextPage) {
            $this->currentPageNum = $this->nextPage->page_number;
            $this->loadPageData();
        }
    }

    /**
     * الانتقال للصفحة السابقة
     */
    public function previousPageAction(): void
    {
        if ($this->previousPage) {
            $this->currentPageNum = $this->previousPage->page_number;
            $this->loadPageData();
        }
    }

    /**
     * الانتقال لصفحة معينة
     * يستمع للحدث من الـ sidebar
     */
    #[On('goToPage')]
    public function goToPage(int $pageNumber): void
    {
        $pageNumber = max(1, min($pageNumber, $this->totalPages));
        $this->currentPageNum = $pageNumber;
        $this->loadPageData();
    }

    /**
     * نسبة التقدم
     */
    public function getProgressProperty(): float
    {
        return $this->totalPages > 0
            ? round(($this->currentPageNum / $this->totalPages) * 100)
            : 0;
    }

    public function render()
    {
        return view('livewire.book-content-reader');
    }
}
