<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitSummary extends Model
{
    protected $table = 'visits_summary';

    protected $fillable = [
        'date',
        'total_visits',
        'unique_visitors',
        'total_sessions',
        'device_stats',
        'browser_stats',
        'top_pages',
        'hourly_stats',
    ];

    protected $casts = [
        'date' => 'date',
        'device_stats' => 'array',
        'browser_stats' => 'array',
        'top_pages' => 'array',
        'hourly_stats' => 'array',
    ];
}
