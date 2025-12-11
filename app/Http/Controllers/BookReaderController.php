<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookReaderController extends Controller
{
    public function show($id)
    {
        // Static Book Data
        $book = (object) [
            'id' => 1,
            'title' => 'آداب الفتوى والمفتي والمستفتي',
            'author' => 'الإمام النووي',
            'cover_image' => 'book_cover_placeholder.jpg',
        ];

        // Static Pages Data
        $pages = collect();
        for ($i = 1; $i <= 5; $i++) {
            $pages->push((object) [
                'id' => $i,
                'page_number' => $i,
                'content' => "Text for page $i",
                'display_content' => "<p>Page $i content...</p>",
                'book' => $book
            ]);
        }

        // Static Chapters Data
        $chapters = collect([
            (object)['id' => 1, 'title' => 'المقدمة', 'page_number' => 1, 'start_page' => 1],
            (object)['id' => 2, 'title' => 'شروط المفتي', 'page_number' => 16, 'start_page' => 16],
            (object)['id' => 3, 'title' => 'آداب المستفتي', 'page_number' => 31, 'start_page' => 31],
            (object)['id' => 4, 'title' => 'أصول الإفتاء', 'page_number' => 46, 'start_page' => 46],
            (object)['id' => 5, 'title' => 'قواعد الترجيح', 'page_number' => 61, 'start_page' => 61],
        ]);

        $currentPageNum = 1;
        $totalPages = 120;
        $nextPage = 2;
        $previousPage = null;

        return view('pages.book-preview', compact(
            'book',
            'pages',
            'chapters',
            'currentPageNum',
            'totalPages',
            'nextPage',
            'previousPage'
        ));
    }
}