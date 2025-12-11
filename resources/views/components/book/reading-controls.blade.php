<div class="flex items-center justify-between pt-8 mt-8 border-t" style="border-color: var(--border-color);">
    
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentPage = 1;
    let fontSize = 'normal';
    const totalPages = 120;
    
    // Font size controls
    const increaseFontBtn = document.getElementById('increase-font-btn');
    const decreaseFontBtn = document.getElementById('decrease-font-btn');
    const fontSizeDisplay = document.getElementById('font-size-display');
    const contentWrapper = document.getElementById('book-content-wrapper');
    
    const fontSizes = {
        'small': { size: '16px', label: 'صغير' },
        'normal': { size: '18px', label: 'عادي' },
        'large': { size: '20px', label: 'كبير' },
        'extra-large': { size: '22px', label: 'كبير جداً' }
    };
    
    const fontOrder = ['small', 'normal', 'large', 'extra-large'];
    let currentFontIndex = 1; // Start with 'normal'
    
    increaseFontBtn?.addEventListener('click', function() {
        if (currentFontIndex < fontOrder.length - 1) {
            currentFontIndex++;
            updateFontSize();
        }
    });
    
    decreaseFontBtn?.addEventListener('click', function() {
        if (currentFontIndex > 0) {
            currentFontIndex--;
            updateFontSize();
        }
    });
    
    function updateFontSize() {
        const fontKey = fontOrder[currentFontIndex];
        const fontInfo = fontSizes[fontKey];
        
        if (contentWrapper && fontInfo) {
            contentWrapper.style.fontSize = fontInfo.size;
            fontSizeDisplay.textContent = fontInfo.label;
        }
    }
    
    // Page jump functionality
    const pageJumpInput = document.getElementById('page-jump-input');
    const jumpToPageBtn = document.getElementById('jump-to-page-btn');
    
    jumpToPageBtn?.addEventListener('click', function() {
        const targetPage = parseInt(pageJumpInput.value);
        if (targetPage >= 1 && targetPage <= totalPages) {
            // In dynamic version, this would load the actual page
            console.log('Jump to page:', targetPage);
            currentPage = targetPage;
            updatePageDisplay();
        }
    });
    
    pageJumpInput?.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            jumpToPageBtn?.click();
        }
    });
    
    function updatePageDisplay() {
        const currentPageDisplay = document.getElementById('current-page-display');
        if (currentPageDisplay) {
            currentPageDisplay.textContent = currentPage;
        }
    }
    
    // Initialize
    updateFontSize();
    updatePageDisplay();
});
</script>