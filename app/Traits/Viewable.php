<?php

namespace App\Traits;

use App\Models\ViewLog;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Request;

trait Viewable
{
    /**
     * Get all view logs for the model.
     */
    public function viewLogs(): MorphMany
    {
        return $this->morphMany(ViewLog::class, 'viewable');
    }

    /**
     * Log a view for the model if it hasn't been viewed by this user/IP recently.
     */
    public function logView(): void
    {
        $ip = Request::ip();
        $sessionId = session()->getId();
        
        // Skip if no session ID (e.g. API or bot)
        if (!$sessionId) {
            return;
        }

        // Check if viewed in the last 24 hours
        $hasViewed = $this->viewLogs()
            ->where('viewed_at', '>=', Carbon::now()->subHours(24))
            ->where(function ($query) use ($ip, $sessionId) {
                $query->where('session_id', $sessionId)
                      ->orWhere('ip_address', $ip);
            })
            ->exists();

        if (!$hasViewed) {
            // Log the view
            $this->viewLogs()->create([
                'ip_address' => $ip,
                'session_id' => $sessionId,
                'viewed_at' => Carbon::now(),
            ]);

            // Increment the counter on the main model
            $this->increment('views_count');
        }
    }
}
