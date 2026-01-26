<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViewLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'viewable_type',
        'viewable_id',
        'ip_address',
        'session_id',
        'viewed_at',
    ];

    protected $casts = [
        'viewed_at' => 'datetime',
    ];

    public function viewable()
    {
        return $this->morphTo();
    }
}
