<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'name',
        'phone',
        'quantity',
        'note',
        'status',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
