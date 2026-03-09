<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'name',
        'rating',
        'comment',
        'is_approved',
        'admin_seen_at',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_approved' => 'boolean',
        'admin_seen_at' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
