<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Page;
use App\Models\Chapter;
use Illuminate\Http\Request;

class BookReaderController extends Controller
{
    /**
     * Step 1: Basic Book Display - Load real data from database
     * 
     * @param int $bookId
     * @param int|null $pageNumber
     */
    public function show($bookId, $pageNumber = 1)
    {
        // Load book with relationships
        $book = Book::with([
            'authors' => fn($q) => $q->orderByPivot('display_order'),
            'bookSection',
            'volumes' => fn($q) => $q->orderBy('number'),
        ])->findOrFail($bookId);

        // Load current page
        $currentPage = Page::where('book_id', $bookId)
            ->where('page_number', $pageNumber)
            ->with(['chapter', 'volume'])
            ->first();

        // If page not found, get first page
        if (!$currentPage) {
            $currentPage = Page::where('book_id', $bookId)
                ->orderBy('page_number')
                ->first();
            
            if ($currentPage) {
                $pageNumber = $currentPage->page_number;
            }
        }

        // Load chapters for TOC (root chapters with ALL nested children)
        $chapters = Chapter::where('book_id', $bookId)
            ->whereNull('parent_id')
            ->orderBy('order')
            ->with('allChildren')
            ->get();

        // Get total pages count
        $totalPages = Page::where('book_id', $bookId)->count();

        // Get navigation info
        $previousPage = $currentPage ? Page::where('book_id', $bookId)
            ->where('page_number', '<', $pageNumber)
            ->orderByDesc('page_number')
            ->first() : null;

        $nextPage = $currentPage ? Page::where('book_id', $bookId)
            ->where('page_number', '>', $pageNumber)
            ->orderBy('page_number')
            ->first() : null;

        // Get pages for display (current page only for now)
        $pages = $currentPage ? collect([$currentPage]) : collect();

        $currentPageNum = $pageNumber;

        return view('pages.book-preview', compact(
            'book',
            'pages',
            'chapters',
            'currentPage',
            'currentPageNum',
            'totalPages',
            'nextPage',
            'previousPage'
        ));
    }
}