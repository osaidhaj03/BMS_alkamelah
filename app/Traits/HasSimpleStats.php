<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Session;

trait HasSimpleStats
{
    /**
     * Log a view for the model if it hasn't been viewed in this session recently.
     */
    public function logView(): void
    {
        $key = 'viewed_' . class_basename($this) . '_' . $this->id;

        if (!Session::has($key)) {
            $this->increment('views_count');
            Session::put($key, true);
        }
    }

    /**
     * Check if the model is favorited by the current session.
     * $user parameter is kept for compatibility but ignored in simple mode.
     */
    public function isFavoritedBy(?User $user = null): bool
    {
        $key = 'favorited_' . class_basename($this) . '_' . $this->id;
        return Session::has($key);
    }

    /**
     * Toggle favorite status.
     * $user parameter is kept for compatibility but ignored in simple mode.
     */
    public function toggleFavorite(?User $user = null): bool
    {
        $key = 'favorited_' . class_basename($this) . '_' . $this->id;

        if (Session::has($key)) {
            // Already favorited -> Unfavorite
            if ($this->favorites_count > 0) {
                $this->decrement('favorites_count');
            }
            Session::forget($key);
            return false;
        } else {
            // Not favorited -> Favorite
            $this->increment('favorites_count');
            Session::put($key, true);
            return true;
        }
    }
}
