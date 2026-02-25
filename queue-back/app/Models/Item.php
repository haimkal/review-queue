<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'title',
        'content',
        'state',
        'review_note',
        'risk_score',
        'flags',
        'reviewed_at',
    ];

    protected $casts = [
        'flags' => 'array',
        'reviewed_at' => 'datetime',
    ];
}
