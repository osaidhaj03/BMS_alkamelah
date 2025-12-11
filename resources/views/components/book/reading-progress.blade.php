<div class="fixed bottom-4 left-4 z-30">
    <style>
        .reading-menu-dropdown {
            position: absolute;
            bottom: 100%;
            left: 0;
            background: var(--bg-paper);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-main);
            box-shadow: var(--shadow-dropdown);
            min-width: 200px;
            padding: 0.5rem;
            margin-bottom: 0.5rem;
            display: none;
            flex-direction: column;
            gap: 0.25rem;
            z-index: 40;
        }
        .reading-menu-dropdown.show {
            display: flex;
        }
        .menu-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            color: var(--text-main);
            font-family: var(--font-ui);
            font-size: 0.9rem;
            transition: all 0.2s;
            background: transparent;
            width: 100%;
            text-align: right;
        }
        .menu-item:hover {
            background-color: var(--bg-hover);
            color: var(--accent-color);
        }
        .menu-item svg {
            width: 18px;
            height: 18px;
            stroke: currentColor;
            stroke-width: 2;
            fill: none;
        }
        .menu-divider {
            height: 1px;
            background: var(--border-color);
            margin: 0.25rem 0;
        }
    </style>
    <div class="relative">
        <div class="reading-menu-dropdown" id="reading-menu">
             <!-- Mobile Part Selector -->
             <div class="lg:hidden pb-2 mb-2 border-b border-gray-200" style="border-color: var(--border-color);">
                <div style="padding: 5px 10px; font-weight: bold; color: var(--text-muted); font-size: 0.75rem;">المجلد</div>
                <select class="w-full bg-transparent text-sm p-2 rounded cursor-pointer" 
                        style="font-family: var(--font-ui); color: var(--text-main); text-align: right; border: 1px solid var(--border-color);"
                        id="mobile-part-selector">
                    <option value="1">المجلد الأول</option>
                    <option value="2">المجلد الثاني</option>
                    <option value="3">المجلد الثالث</option>
                    <option value="4">المجلد الرابع</option>
                </select>
             </div>

             <div style="padding: 5px 10px; font-weight: bold; color: var(--text-muted); font-size: 0.75rem;">العرض</div>
            
            <button class="menu-item" id="menu-increase">
                <svg viewBox="0 0 24 24"><path d="M12 4V20M4 12H20"/></svg>
                <span>تكبير الخط</span>
            </button>
            <button class="menu-item" id="menu-decrease">
                <svg viewBox="0 0 24 24"><path d="M5 12H19"/></svg>
                <span>تصغير الخط</span>
            </button>
            
            <div class="menu-divider"></div>
            
            <button class="menu-item" id="menu-theme">
                <svg viewBox="0 0 24 24"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
                <span>الوضع الليلي</span>
            </button>
            
            <button class="menu-item" id="fullscreen-btn">
                <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"/></svg>
                <span>توسيع الشاشة</span>
            </button>

            <div class="menu-divider"></div>
            
            <button class="menu-item" id="menu-share">
                <svg viewBox="0 0 24 24"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/><polyline points="16 6 12 2 8 6"/><line x1="12" y1="2" x2="12" y2="15"/></svg>
                <span>مشاركة الكتاب</span>
            </button>
        </div>
    </div>
    
    <!-- Search Popup -->
    <div class="relative">
        <div class="reading-menu-dropdown" id="search-popup" style="min-width: 250px;">
             <div style="padding: 5px 10px; font-weight: bold; color: var(--text-muted); font-size: 0.75rem;">بحث في الكتاب</div>
             <div class="p-2">
                <div class="relative">
                    <input type="text" id="mobile-search-input" placeholder="اكتب كلمة البحث..." 
                           class="w-full p-2 pl-8 rounded border text-sm"
                           style="border-color: var(--border-color); font-family: var(--font-ui); background: var(--bg-body); color: var(--text-main);">
                    <button class="absolute left-2 top-2 text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </button>
                </div>
             </div>
        </div>
    </div>
    <div class="flex items-center gap-3 px-4 py-2 rounded-full shadow-lg" 
         style="background-color: var(--bg-sidebar); border: 1px solid var(--border-color); box-shadow: var(--shadow-soft);">
        
        <!-- Circular Progress Indicator -->
        <div class="relative w-10 h-10">
            <svg class="w-10 h-10 transform -rotate-90" viewBox="0 0 36 36">
                <!-- Background Circle -->
                <path class="text-gray-200"
                      stroke="currentColor"
                      stroke-width="3"
                      fill="none"
                      d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                
                <!-- Progress Circle -->
                <path class="transition-all duration-300"
                      stroke="var(--accent-color)"
                      stroke-width="3"
                      stroke-linecap="round"
                      fill="none"
                      stroke-dasharray="8, 92"
                      d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                      id="progress-circle"/>
            </svg>
            
            <!-- Percentage Text -->
            <div class="absolute inset-0 flex items-center justify-center">
                <span class="text-xs font-bold" style="color: var(--accent-color); font-family: var(--font-ui);" id="progress-percentage">
                    8%
                </span>
            </div>
        </div>
        
        <!-- Part/Volume Selector -->
        <div class="hidden lg:block border-l border-gray-200 pl-3 ml-1" style="border-color: var(--border-color);">
            <select class="bg-transparent text-sm focus:outline-none cursor-pointer" 
                    style="font-family: var(--font-ui); color: var(--text-main);"
                    id="part-selector">
                <option value="1">المجلد الأول</option>
                <option value="2">المجلد الثاني</option>
                <option value="3">المجلد الثالث</option>
                <option value="4">المجلد الرابع</option>
            </select>
        </div>

        <!-- Page Counter -->
        <div class="flex items-center text-sm gap-1" style="font-family: var(--font-ui); color: var(--text-secondary);">
            <span>صفحة</span>
            <input type="number" 
                   value="10" 
                   min="1" 
                   max="120"
                   id="page-input"
                   class="w-12 text-center bg-transparent border-b border-gray-300 focus:border-green-600 focus:outline-none transition-colors"
                   style="color: var(--text-main);">
            <span id="total-pages-text">من 120</span>
        </div>
        
        <!-- Quick Jump Buttons -->
        <div class="flex items-center gap-1">
            <!-- Next Chapter -->
            <button class="p-1.5 rounded hover:bg-gray-100 transition-colors" 
                    title="الفصل السابق"
                    id="next-chapter-btn">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path>
                </svg>
            </button>
            <!-- Previous Chapter -->
            <button class="p-1.5 rounded hover:bg-gray-100 transition-colors" 
                    title="الفصل التالي"
                    id="prev-chapter-btn">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
                </svg>
            </button>
            

            
            <!-- Search Button (Mobile) 
            <button class="p-1.5 rounded hover:bg-gray-100 transition-colors lg:hidden" 
                    title="بحث"
                    id="reading-search-toggle">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </button>
           -->
            <!-- More Menu Button -->
            <button class="p-1.5 rounded hover:bg-gray-100 transition-colors" 
                    title="المزيد"
                    id="reading-menu-toggle">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/>
                </svg>
            </button>
        </div>
    </div>
</div>

<!-- Scroll to Top Button 
<button class="fixed bottom-4 left-4 p-3 rounded-full shadow-lg transition-all duration-300 opacity-0 pointer-events-none z-30"
        style="background-color: var(--accent-color); color: white;"
        id="scroll-to-top-btn">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
    </svg>
</button>
-->

<script>
document.addEventListener('DOMContentLoaded', function() {
    const progressCircle = document.getElementById('progress-circle');
    const progressPercentage = document.getElementById('progress-percentage');
    const pageCounter = document.getElementById('page-counter');
    const scrollToTopBtn = document.getElementById('scroll-to-top-btn');
    const contentArea = document.querySelector('main');
    
    let currentPage = 10;
    const totalPages = 120;
    
    // Update progress circle
    function updateProgress() {
        const percentage = Math.round((currentPage / totalPages) * 100);
        const circumference = 2 * Math.PI * 15.9155;
        const strokeDasharray = `${(percentage / 100) * circumference}, ${circumference}`;
        
        if (progressCircle) {
            progressCircle.style.strokeDasharray = strokeDasharray;
        }
        
        if (progressPercentage) {
            progressPercentage.textContent = `${percentage}%`;
        }
        
        // Update input if it's not the active element (to avoid interrupting typing)
        const pageInput = document.getElementById('page-input');
        if (pageInput && document.activeElement !== pageInput) {
            pageInput.value = currentPage;
        }
    }
    
    // Show/hide scroll to top button
    function handleScroll() {
        if (!contentArea || !scrollToTopBtn) return;
        
        if (contentArea.scrollTop > 300) {
            scrollToTopBtn.classList.remove('opacity-0', 'pointer-events-none');
            scrollToTopBtn.classList.add('opacity-100');
        } else {
            scrollToTopBtn.classList.add('opacity-0', 'pointer-events-none');
            scrollToTopBtn.classList.remove('opacity-100');
        }
    }
    
    // Scroll to top functionality
    scrollToTopBtn?.addEventListener('click', function() {
        contentArea?.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
    
    // Listen for scroll events
    contentArea?.addEventListener('scroll', handleScroll);
    
    // Chapter navigation buttons
    const prevChapterBtn = document.getElementById('prev-chapter-btn');
    const nextChapterBtn = document.getElementById('next-chapter-btn');
    
    prevChapterBtn?.addEventListener('click', function() {
        // In dynamic version, this would navigate to previous chapter
        console.log('Navigate to previous chapter');
    });
    
    nextChapterBtn?.addEventListener('click', function() {
        // In dynamic version, this would navigate to next chapter  
        console.log('Navigate to next chapter');
    });

    // Menu Toggle Logic
    const menuToggleBtn = document.getElementById('reading-menu-toggle');
    const menuDropdown = document.getElementById('reading-menu');
    
    menuToggleBtn?.addEventListener('click', function(e) {
        e.stopPropagation();
        menuDropdown.classList.toggle('show');
        if(searchPopup) searchPopup.classList.remove('show');
    });
    
    // Search Toggle Logic
    const searchToggleBtn = document.getElementById('reading-search-toggle');
    const searchPopup = document.getElementById('search-popup');

    searchToggleBtn?.addEventListener('click', function(e) {
        e.stopPropagation();
        searchPopup.classList.toggle('show');
        if(menuDropdown) menuDropdown.classList.remove('show');
        
        // Focus input
        if(searchPopup.classList.contains('show')) {
            setTimeout(() => document.getElementById('mobile-search-input')?.focus(), 100);
        }
    });

    // Also link the Header Mobile Search Button
    const headerSearchBtn = document.getElementById('header-search-mobile-btn');
    headerSearchBtn?.addEventListener('click', function(e) {
        e.stopPropagation();
        
        // NEW: Open Dedicated Search Sidebar
        const searchSidebarOverlay = document.getElementById('search-sidebar-overlay');
        const bookSearchMobile = document.getElementById('book-search-mobile');
        
        if(searchSidebarOverlay) {
            searchSidebarOverlay.classList.remove('hidden');
            
            // Close other menus if open
            if(menuDropdown) menuDropdown.classList.remove('show');
            if(searchPopup) searchPopup.classList.remove('show');
            const sidebarOverlay = document.getElementById('sidebar-overlay');
            if(sidebarOverlay) sidebarOverlay.classList.add('hidden'); // Close TOC if open
            
            // Focus search input in sidebar
            setTimeout(() => {
                if(bookSearchMobile) bookSearchMobile.focus();
            }, 100);
        }
    });

    // Close menu when clicking outside
    document.addEventListener('click', function(e) {
        if (menuDropdown && !menuDropdown.contains(e.target) && e.target !== menuToggleBtn) {
            menuDropdown.classList.remove('show');
        }
        if (searchPopup && !searchPopup.contains(e.target) && e.target !== searchToggleBtn && !e.target.closest('#mobile-search-input')) {
            searchPopup.classList.remove('show');
        }
    });

    // Fullscreen Toggle Logic
    const fullscreenBtn = document.getElementById('fullscreen-btn');
    fullscreenBtn?.addEventListener('click', function() {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen().catch(err => {
                console.log(`Error attempting to enable fullscreen: ${err.message}`);
            });
            menuDropdown.classList.remove('show');
            if(searchPopup) searchPopup.classList.remove('show');
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
                menuDropdown.classList.remove('show');
                if(searchPopup) searchPopup.classList.remove('show');
            }
        }
    });
    
    // Initialize
    updateProgress();
    handleScroll();
    
    // Part Selector Logic
    const partSelector = document.getElementById('part-selector');
    partSelector?.addEventListener('change', function(e) {
        console.log(`Switched to Part/Volume: ${e.target.value}`);
    });
    
    // Mobile Part Selector Logic
    const mobilePartSelector = document.getElementById('mobile-part-selector');
    mobilePartSelector?.addEventListener('change', function(e) {
        console.log(`Switched to Part/Volume (Mobile): ${e.target.value}`);
        // Sync with desktop selector if needed
        if(partSelector) partSelector.value = e.target.value;
    });

    // Page Input Logic
    const pageInput = document.getElementById('page-input');
    pageInput?.addEventListener('change', function(e) {
        let val = parseInt(e.target.value);
        if(val < 1) val = 1;
        if(val > totalPages) val = totalPages;
        
        currentPage = val;
        updateProgress();
        // In a real app, this would scroll to the specific page
    });
    
    // Simulate page changes for demo (remove in dynamic version)
    setInterval(() => {
        currentPage = Math.min(currentPage + 1, totalPages);
        updateProgress();
        
        if (currentPage >= totalPages) {
            currentPage = 1; // Reset for demo
        }
    }, 3000);
});
</script>