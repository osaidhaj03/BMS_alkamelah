<?php

namespace App\Traits;

use App\Models\Favorite;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Favoritable
{
    /**
     * Get all favorites for the model.
     */
    public function favorites(): MorphMany
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }

    /**
     * Check if the model is favorited by the given user.
     */
    public function isFavoritedBy(?User $user): bool
    {
        if (!$user) {
            return false;
        }

        return $this->favorites()
            ->where('user_id', $user->id)
            ->exists();
    }

    /**
     * Toggle favorite status for the given user.
     * Returns true if favorited, false if unfavorited.
     */
    public function toggleFavorite(User $user): bool
    {
        $existingFavorite = $this->favorites()
            ->where('user_id', $user->id)
            ->first();

        if ($existingFavorite) {
            $existingFavorite->delete();
            $this->decrement('favorites_count');
            return false; // Unfavorited
        } else {
            $this->favorites()->create([
                'user_id' => $user->id,
            ]);
            $this->increment('favorites_count');
            return true; // Favorited
        }
    }
}
