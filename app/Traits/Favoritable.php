<?php

namespace App\Traits;

use App\Models\User;

trait Favoritable
{
    /**
     * Get all favorites for the model.
     * (Deprecated: Logic moved to simple counters, relationship removed)
     */
    // public function favorites() ... removed

    /**
     * Check if the model is favorited by the current session.
     */
    public function isFavoritedBy(?User $user): bool
    {
        // Simple session check (ignoring user ID for "simple counters only" approach)
        $key = 'favorited_' . class_basename($this) . '_' . $this->id;
        return session()->has($key);
    }

    /**
     * Toggle favorite status.
     * Returns true if favorited, false if unfavorited.
     */
    public function toggleFavorite(?User $user): bool
    {
        $key = 'favorited_' . class_basename($this) . '_' . $this->id;

        if (session()->has($key)) {
            // Already favorited -> Unfavorite
            $this->decrement('favorites_count');
            session()->forget($key);
            return false;
        } else {
            // Not favorited -> Favorite
            $this->increment('favorites_count');
            session()->put($key, true);
            return true;
        }
    }
}
