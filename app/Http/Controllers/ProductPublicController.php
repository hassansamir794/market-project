<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProductPublicController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');
        $category = $request->query('category'); // slug
        $minPriceInput = $request->query('min_price');
        $maxPriceInput = $request->query('max_price');
        $sort = $request->query('sort', 'newest');
        $rate = (float) config('currency.rate', 1);
        $safeRate = $rate > 0 ? $rate : 1;
        $minPrice = is_numeric($minPriceInput) ? ((float) $minPriceInput / $safeRate) : $minPriceInput;
        $maxPrice = is_numeric($maxPriceInput) ? ((float) $maxPriceInput / $safeRate) : $maxPriceInput;

        $categories = Cache::remember('products.categories', 300, function () {
            return Category::orderBy('name')->get();
        });

        $productsQuery = Product::query()
            ->when($q, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('name', 'like', '%' . $q . '%')
                        ->orWhere('description', 'like', '%' . $q . '%');
                });
            })
            ->when($category, function ($query) use ($category) {
                $query->whereHas('categories', function ($q2) use ($category) {
                    $q2->where('slug', $category);
                });
            })
            ->when(is_numeric($minPrice), function ($query) use ($minPrice) {
                $query->where('price', '>=', (float) $minPrice);
            })
            ->when(is_numeric($maxPrice), function ($query) use ($maxPrice) {
                $query->where('price', '<=', (float) $maxPrice);
            });

        if ($sort === 'price_low') {
            $productsQuery->orderBy('price', 'asc');
        } elseif ($sort === 'price_high') {
            $productsQuery->orderBy('price', 'desc');
        } elseif ($sort === 'most_viewed') {
            $productsQuery->orderBy('views', 'desc');
        } else {
            $productsQuery->latest();
        }

        $page = (int) $request->query('page', 1);
        $cacheKey = 'products.list.' . md5(json_encode($request->query()));

        if ($page === 1) {
            $products = Cache::remember($cacheKey, 60, function () use ($productsQuery) {
                return $productsQuery->paginate(12)->withQueryString();
            });
        } else {
            $products = $productsQuery->paginate(12)->withQueryString();
        }

        return view(
            'products.index',
            compact('products', 'q', 'categories', 'category', 'minPrice', 'maxPrice', 'minPriceInput', 'maxPriceInput', 'sort')
        );
    }

    public function show(Product $product)
    {
        $product->increment('views');
        $cacheKey = 'product.show.' . $product->id;

        $data = Cache::remember($cacheKey, 120, function () use ($product) {
            $product->load('categories');
            $reviewsQuery = $product->reviews()->where('is_approved', true);
            $averageRating = (float) $reviewsQuery->avg('rating');
            $reviews = $product->reviews()->where('is_approved', true)->latest()->take(10)->get();
            return compact('product', 'averageRating', 'reviews');
        });

        return view('products.show', $data);
    }
}
