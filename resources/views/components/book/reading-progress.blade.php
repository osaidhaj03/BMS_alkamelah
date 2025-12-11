<div class="fixed bottom-4 left-4 z-30">
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
        
        <!-- Page Counter -->
        <div class="text-sm" style="font-family: var(--font-ui); color: var(--text-secondary);">
            <span id="page-counter">صفحة 10 من 120</span>
        </div>
        
        <!-- Quick Jump Buttons -->
        <div class="flex items-center gap-1">
            <!-- Previous Chapter -->
            <button class="p-1.5 rounded hover:bg-gray-100 transition-colors" 
                    title="الفصل السابق"
                    id="prev-chapter-btn">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
                </svg>
            </button>
            
            <!-- Next Chapter -->
            <button class="p-1.5 rounded hover:bg-gray-100 transition-colors" 
                    title="الفصل التالي"
                    id="next-chapter-btn">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>
    </div>
</div>

<!-- Scroll to Top Button -->
<button class="fixed bottom-4 right-4 p-3 rounded-full shadow-lg transition-all duration-300 opacity-0 pointer-events-none z-30"
        style="background-color: var(--accent-color); color: white;"
        id="scroll-to-top-btn">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
    </svg>
</button>

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
        
        if (pageCounter) {
            pageCounter.textContent = `صفحة ${currentPage} من ${totalPages}`;
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
    
    // Initialize
    updateProgress();
    handleScroll();
    
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