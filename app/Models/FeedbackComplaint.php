<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class FeedbackComplaint extends Model
{
    protected $fillable = [
        'type',
        'category',
        'subject',
        'message',
        'name',
        'email',
        'phone',
        'related_type',
        'related_id',
        'status',
        'priority',
        'admin_notes',
        'resolved_at',
        'attachment_path',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationship to get the related model (Book, Author, etc.)
    public function relatedItem(): MorphTo
    {
        return $this->morphTo('related');
    }

    // Scope for pending complaints
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Scope for under review
    public function scopeUnderReview($query)
    {
        return $query->where('status', 'under_review');
    }

    // Scope for resolved
    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    // Scope for urgent priority
    public function scopeUrgent($query)
    {
        return $query->where('priority', 'urgent');
    }
}
