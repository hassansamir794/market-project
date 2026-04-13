<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'description',
        'image',
        'stock',
        'is_available',
        'views',
    ];

    protected $appends = [
        'gallery_images',
    ];

    // ✅ Product can belong to many categories
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function orderRequests()
    {
        return $this->hasMany(OrderRequest::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order')->orderBy('id');
    }

    public function getGalleryImagesAttribute(): array
    {
        if ($this->relationLoaded('images')) {
            $paths = $this->images->pluck('path')->filter()->values()->all();
        } else {
            $paths = $this->images()->pluck('path')->filter()->values()->all();
        }

        if ($this->image && ! in_array($this->image, $paths, true)) {
            array_unshift($paths, $this->image);
        }

        return array_values(array_unique(array_filter($paths)));
    }
}
