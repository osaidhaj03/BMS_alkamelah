<?php

namespace App\Traits;

trait Viewable
{
    /**
     * Get all view logs for the model.
     */

    /**
     * Log a view for the model if it hasn't been viewed in this session recently.
     * Uses simple session tracking, resets on browser close/clear.
     */
    public function logView(): void
    {
        $key = 'viewed_' . class_basename($this) . '_' . $this->id;

        if (!session()->has($key)) {
            // Increment the counter on the main model
            $this->increment('views_count');
            
            // Mark as viewed in session (lasts until session expires)
            session()->put($key, true);
        }
    }
}
