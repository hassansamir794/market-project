<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrafficVisit extends Model
{
    use HasFactory;

    protected $fillable = [
        'source',
        'referer_host',
        'path',
    ];
}

