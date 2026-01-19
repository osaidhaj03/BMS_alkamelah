<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Book;

class BookSource extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }
}
