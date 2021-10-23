<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    public const OPEN = 1;
    public const CLOSED = 0;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function scopeOnlyOpen($query)
    {
        return $query->where('status', self::OPEN);
    }

    public function isClosed()
    {
        return self::CLOSED === $this->status;
    }
}
