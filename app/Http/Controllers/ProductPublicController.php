<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductPublicController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');
        $category = $request->query('category'); // slug

        $categories = Category::orderBy('name')->get();

        $products = Product::query()
            ->when($q, function ($query) use ($q) {
                $query->where('name', 'like', '%' . $q . '%');
            })
            ->when($category, function ($query) use ($category) {
                $query->whereHas('categories', function ($q2) use ($category) {
                    $q2->where('slug', $category);
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('products.index', compact('products', 'q', 'categories', 'category'));
    }

    public function show(Product $product)
    {
        $product->load('categories');
        return view('products.show', compact('product'));
    }
}
