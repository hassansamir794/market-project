<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        // Market open/close (Asia/Baghdad): 9:00 AM -> 1:00 AM (next day)
        $now = Carbon::now('Asia/Baghdad');

        $openTime = Carbon::createFromTime(9, 0, 0, 'Asia/Baghdad');
        $closeTime = Carbon::createFromTime(1, 0, 0, 'Asia/Baghdad');

        $isOpen = $now->between($openTime, Carbon::createFromTime(23, 59, 59, 'Asia/Baghdad'))
            || $now->between(Carbon::createFromTime(0, 0, 0, 'Asia/Baghdad'), $closeTime);

        $categories = Category::orderBy('name')->take(8)->get();

        $latestProducts = Product::with('categories')
            ->latest()
            ->take(6)
            ->get();

        return view('home', compact('isOpen', 'categories', 'latestProducts'));
    }
}
