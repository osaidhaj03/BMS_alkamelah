<div x-data="{
    localFavorited: false,
    localCount: {{ $favoritesCount }},
    modelKey: '{{ str_replace('\\', '-', get_class($model)) }}-{{ $model->id }}',
    init() {
        // Check local storage for guest favorited status
        const favorites = JSON.parse(localStorage.getItem('guest_favorites') || '[]');
        if (favorites.includes(this.modelKey)) {
            this.localFavorited = true;
        }
        
        // Listen for server-side guest event
        Livewire.on('guest-toggle-favorite', (data) => {
             // Handle checking against current model
             if (data[0].modelId == {{ $model->id }} && data[0].modelType == '{{ addslashes(get_class($model)) }}') {
                 this.toggleLocal();
             }
        });
    },
    toggleLocal() {
        let favorites = JSON.parse(localStorage.getItem('guest_favorites') || '[]');
        
        if (this.localFavorited) {
            // Remove
            favorites = favorites.filter(id => id !== this.modelKey);
            this.localFavorited = false;
            this.localCount = Math.max(0, this.localCount - 1);
        } else {
            // Add
            favorites.push(this.modelKey);
            this.localFavorited = true;
            this.localCount++;
        }
        
        localStorage.setItem('guest_favorites', JSON.stringify(favorites));
    }
}"
class="flex items-center gap-1">
    <button wire:click="toggleFavorite" 
            class="group flex items-center gap-2 px-4 py-2 rounded-full transition-all duration-300"
            :class="localFavorited || {{ $isFavorited ? 'true' : 'false' }} ? 'bg-red-50 text-red-600' : 'bg-gray-50 text-gray-600 hover:bg-red-50 hover:text-red-500'">
        
        <div class="relative w-6 h-6">
            <!-- Outline Heart (Show if NOT favorited) -->
            <svg class="w-6 h-6 absolute inset-0 transition-opacity duration-300" 
                 :class="localFavorited || {{ $isFavorited ? 'true' : 'false' }} ? 'opacity-0' : 'opacity-100'"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                </path>
            </svg>

            <!-- Solid Heart (Show if favorited) -->
            <svg class="w-6 h-6 absolute inset-0 transition-all duration-300 transform" 
                 :class="localFavorited || {{ $isFavorited ? 'true' : 'false' }} ? 'opacity-100 scale-100' : 'opacity-0 scale-50'"
                 fill="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                </path>
            </svg>
        </div>

        <span class="font-bold text-sm" x-text="new Intl.NumberFormat('en-US').format(localCount)">
            {{ number_format($favoritesCount) }}
        </span>
    </button>
</div>
