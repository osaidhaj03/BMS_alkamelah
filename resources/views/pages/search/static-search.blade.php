@extends('layouts.app')

@section('title', 'بحث')

@section('content')
    @php
        $booksCount = \App\Models\Book::count();
        $authorsCount = \App\Models\Author::count();
        $pagesCount = \App\Models\Page::count();
    @endphp
    <div class="min-h-screen flex flex-col items-center justify-center p-4 relative bg-[#fafafa]" dir="rtl"
        x-data="staticSearch()" @init="init()" @keydown.enter="handleSearch()">

        <!-- Section Background Pattern -->
        <div class="absolute inset-0 pointer-events-none"
            style="background-image: url('{{ asset('assets/Frame 1321314420.png') }}'); background-repeat: repeat; background-size: 800px; background-attachment: fixed;">
        </div>

        <div class="w-full max-w-2xl z-10 flex flex-col items-center gap-8 -mt-20">

            <!-- Large Logo -->
            <a href="{{ route('home') }}" class="flex flex-col items-center gap-4 hover:opacity-90 transition-opacity">
                <img src="{{ asset('images/المكتبة الكاملة.png') }}" alt="المكتبة الكاملة" class="h-24 md:h-32 w-auto">
                <h1 class="sr-only">المكتبة الكاملة</h1>
            </a>

            <!-- Search Container using Component -->
            @include('components.SearchBar')

        </div>
        <!-- Filter Modals -->
        @include('components.BooksFilterModal')
        @include('components.ContentFilterModal')
        @include('components.AuthorsFilterModal')

        <!-- Footer Links -->
        <div class="absolute bottom-0 w-full border-t border-gray-200 text-sm"
            style="background-color: #2C6E4A; border-top-color: #BA4749;">
            <div class="max-w-7xl mx-auto px-4 py-4 flex justify-center md:justify-between flex-wrap gap-4 text-white">
                <div class="flex gap-6 font-medium">
                    <a href="#" class="hover:underline opacity-90 hover:opacity-100">عن المكتبة</a>
                    <a href="#" class="hover:underline opacity-90 hover:opacity-100">المساعدة</a>
                    <a href="#" class="hover:underline opacity-90 hover:opacity-100">سياسة الخصوصية</a>
                </div>
                <div class="flex gap-4 opacity-75">
                    <span>الإصدار 1.0</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        function staticSearch() {
            return {
                query: '',
                searchMode: 'books',
                settingsOpen: false,
                filterModalOpen: false,
                showDropdown: false,
                searchType: 'flexible_match',
                wordOrder: 'any_order',
                wordMatch: 'all_words',

                // Suggestions
                suggestions: [],
                loadingSuggestions: false,

                // Books filters
                booksFilterTab: 'sections',
                sectionFilters: [],
                authorFilters: [],
                bookFilters: [],
                bookMadhhabFilters: [],
                sections: [],
                authorsForFilter: [],
                booksForFilter: [],
                sectionSearch: '',
                authorSearch: '',
                bookSearch: '',

                // Filter Pagination & Loading
                authorsPage: 1,
                hasMoreAuthors: false,
                loadingMoreAuthors: false,
                authorsLoading: false,
                booksPageForFilter: 1,
                hasMoreBooksForFilter: false,
                loadingMoreBooksForFilter: false,
                booksLoadingForFilter: false,

                // Authors filters
                authorsFilterTab: 'madhhab',
                madhhabFilters: [],
                centuryFilters: [],
                deathDateFrom: '',
                deathDateTo: '',

                availableMadhhabs: ['المذهب الحنفي', 'المذهب المالكي', 'المذهب الشافعي', 'المذهب الحنبلي'],
                availableCenturies: {
                    1: 'القرن الأول', 2: 'القرن الثاني', 3: 'القرن الثالث', 4: 'القرن الرابع', 5: 'القرن الخامس',
                    6: 'القرن السادس', 7: 'القرن السابع', 8: 'القرن الثامن', 9: 'القرن التاسع', 10: 'القرن العاشر',
                    11: 'القرن الحادي عشر', 12: 'القرن الثاني عشر', 13: 'القرن الثالث عشر', 14: 'القرن الرابع عشر', 15: 'القرن الخامس عشر'
                },

                get placeholderText() {
                    if (this.searchMode === 'books') return 'بحث في {{ number_format($booksCount) }} كتاب...';
                    if (this.searchMode === 'authors') return 'بحث في {{ number_format($authorsCount) }} مؤلف...';
                    return 'بحث في {{ number_format($booksCount) }} كتاب و {{ number_format($pagesCount) }} صفحة...';
                },

                init() {
                    this.fetchSections();
                    this.fetchAuthorsForFilter();
                    this.fetchBooksForFilter();
                },

                async fetchSuggestions() {
                    if (!this.query.trim() || this.query.length < 2) {
                        this.suggestions = [];
                        return;
                    }

                    this.loadingSuggestions = true;
                    try {
                        if (this.searchMode === 'books') {
                            const response = await fetch(`/api/books?search=${encodeURIComponent(this.query)}`);
                            const data = await response.json();
                            this.suggestions = (data.data || []).slice(0, 8).map(b => ({ id: b.id, name: b.title, type: 'book' }));
                        } else if (this.searchMode === 'authors') {
                            const response = await fetch(`/api/authors?search=${encodeURIComponent(this.query)}`);
                            const data = await response.json();
                            this.suggestions = (data.data || []).slice(0, 8).map(a => ({ id: a.id, name: a.name, type: 'author' }));
                        }
                    } catch (e) {
                        console.error('Error fetching suggestions:', e);
                    }
                    this.loadingSuggestions = false;
                },

                async fetchSections() {
                    try {
                        const response = await fetch(`/api/sections?search=${encodeURIComponent(this.sectionSearch)}`);
                        this.sections = await response.json();
                    } catch (e) { console.error(e); }
                },

                async fetchAuthorsForFilter(page = 1, append = false) {
                    this.authorsLoading = !append;
                    this.loadingMoreAuthors = append;
                    try {
                        const response = await fetch(`/api/authors?search=${encodeURIComponent(this.authorSearch)}&page=${page}`);
                        const data = await response.json();
                        if (append) {
                            this.authorsForFilter = [...this.authorsForFilter, ...(data.data || [])];
                        } else {
                            this.authorsForFilter = data.data || [];
                        }
                        this.authorsPage = data.current_page;
                        this.hasMoreAuthors = data.current_page < data.last_page;
                    } catch (e) { console.error(e); }
                    this.authorsLoading = false;
                    this.loadingMoreAuthors = false;
                },

                async fetchBooksForFilter(page = 1, append = false) {
                    this.booksLoadingForFilter = !append;
                    this.loadingMoreBooksForFilter = append;
                    try {
                        const response = await fetch(`/api/books?search=${encodeURIComponent(this.bookSearch)}&page=${page}`);
                        const data = await response.json();
                        if (append) {
                            this.booksForFilter = [...this.booksForFilter, ...data.data.map(b => ({ id: b.id, name: b.title }))];
                        } else {
                            this.booksForFilter = data.data.map(b => ({ id: b.id, name: b.title }));
                        }
                        this.booksPageForFilter = data.current_page;
                        this.hasMoreBooksForFilter = data.current_page < data.last_page;
                    } catch (e) { console.error(e); }
                    this.booksLoadingForFilter = false;
                    this.loadingMoreBooksForFilter = false;
                },

                getSuggestionUrl(item) {
                    if (item.type === 'book') {
                        return `/book/${item.id}`;
                    }
                    return `/authors?search=${encodeURIComponent(item.name)}`;
                },

                handleSearch() {
                    if (!this.query.trim()) return;

                    let url = '';
                    const params = new URLSearchParams();
                    params.set('search', this.query);

                    if (this.searchMode === 'books') {
                        url = '/books';
                        this.sectionFilters.forEach(id => params.append('sectionFilters[]', id));
                        this.authorFilters.forEach(id => params.append('authorFilters[]', id));
                    } else if (this.searchMode === 'authors') {
                        url = '/authors';
                        this.madhhabFilters.forEach(m => params.append('madhhabFilters[]', m));
                        this.centuryFilters.forEach(c => params.append('centuryFilters[]', c));
                        if (this.deathDateFrom) params.set('deathDateFrom', this.deathDateFrom);
                        if (this.deathDateTo) params.set('deathDateTo', this.deathDateTo);
                    } else {
                        url = '/search';
                        params.set('q', this.query);
                        params.set('search_type', this.searchType);
                        params.set('word_order', this.wordOrder);
                        params.set('word_match', this.wordMatch);

                        // Add filters for content search
                        if (this.sectionFilters.length > 0) params.set('section_id', this.sectionFilters.join(','));
                        if (this.authorFilters.length > 0) params.set('author_id', this.authorFilters.join(','));
                        if (this.bookFilters.length > 0) params.set('book_id', this.bookFilters.join(','));
                        if (this.bookMadhhabFilters.length > 0) params.set('book_madhhab', this.bookMadhhabFilters.join(','));
                    }

                    window.location.href = url + '?' + params.toString();
                },

                toggleFilter(type, id) {
                    if (type === 'section') {
                        if (this.sectionFilters.includes(id)) {
                            this.sectionFilters = this.sectionFilters.filter(i => i !== id);
                        } else {
                            this.sectionFilters.push(id);
                        }
                    } else if (type === 'author') {
                        if (this.authorFilters.includes(id)) {
                            this.authorFilters = this.authorFilters.filter(i => i !== id);
                        } else {
                            this.authorFilters.push(id);
                        }
                    } else if (type === 'book') {
                        if (this.bookFilters.includes(id)) {
                            this.bookFilters = this.bookFilters.filter(i => i !== id);
                        } else {
                            this.bookFilters.push(id);
                        }
                    }
                },

                toggleMadhhabFilter(m) {
                    if (this.madhhabFilters.includes(m)) {
                        this.madhhabFilters = this.madhhabFilters.filter(i => i !== m);
                    } else {
                        this.madhhabFilters.push(m);
                    }
                },

                toggleCenturyFilter(c) {
                    if (this.centuryFilters.includes(c)) {
                        this.centuryFilters = this.centuryFilters.filter(i => i !== c);
                    } else {
                        this.centuryFilters.push(c);
                    }
                },

                toggleBookMadhhabFilter(m) {
                    if (this.bookMadhhabFilters.includes(m)) {
                        this.bookMadhhabFilters = this.bookMadhhabFilters.filter(i => i !== m);
                    } else {
                        this.bookMadhhabFilters.push(m);
                    }
                },

                clearBooksFilters() {
                    this.sectionFilters = [];
                    this.authorFilters = [];
                    this.bookFilters = [];
                    this.bookMadhhabFilters = [];
                },

                clearAuthorsFilters() {
                    this.madhhabFilters = [];
                    this.centuryFilters = [];
                    this.deathDateFrom = '';
                    this.deathDateTo = '';
                },

                getActiveFiltersCount() {
                    if (this.searchMode === 'books' || this.searchMode === 'content') {
                        return this.sectionFilters.length + this.authorFilters.length + this.bookFilters.length + this.bookMadhhabFilters.length;
                    } else if (this.searchMode === 'authors') {
                        let count = this.madhhabFilters.length + this.centuryFilters.length;
                        if (this.deathDateFrom || this.deathDateTo) count++;
                        return count;
                    }
                    return 0;
                }
            }
        }
    </script>
@endsection