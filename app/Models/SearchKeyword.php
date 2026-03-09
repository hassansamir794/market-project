<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchKeyword extends Model
{
    use HasFactory;

    protected $fillable = [
        'keyword',
        'count',
        'last_searched_at',
    ];

    protected $casts = [
        'count' => 'integer',
        'last_searched_at' => 'datetime',
    ];
}

