<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductPublicController;
use App\Http\Controllers\CategoryPublicController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\OrderRequestController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ReviewAdminController;
use App\Http\Controllers\Admin\OrderRequestAdminController;

/*
|-------------------------------------------------------------------------- 
| Web Routes
|-------------------------------------------------------------------------- 
*/

// ✅ Home page (Landing)
Route::get('/', [HomeController::class, 'index'])->name('home');

// ✅ Language switch
Route::get('/lang/{locale}', function (string $locale) {
    $supported = config('localization.supported_locales', ['en']);
    if (in_array($locale, $supported, true)) {
        session()->put('app_locale', $locale);
    }
    return redirect()->back();
})->name('lang.switch');

// ✅ About page
Route::view('/about', 'about')->name('about');

// ✅ Public (Customer) - no login needed
Route::get('products', [ProductPublicController::class, 'index'])->name('products.index')->middleware('throttle:60,1');
Route::get('products/{product}', [ProductPublicController::class, 'show'])->name('products.show')->middleware('throttle:120,1');
Route::get('categories/{slug}', [CategoryPublicController::class, 'show'])->name('categories.show')->middleware('throttle:60,1');
Route::post('products/{product}/reviews', [ReviewController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('products.reviews.store');
Route::post('products/{product}/order-requests', [OrderRequestController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('products.order-requests.store');

// ✅ Admin area - login + admin only
Route::middleware(['auth', 'admin', 'throttle:60,1'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
    Route::get('reviews', [ReviewAdminController::class, 'index'])->name('reviews.index');
    Route::put('reviews/{review}', [ReviewAdminController::class, 'update'])->name('reviews.update');
    Route::delete('reviews/{review}', [ReviewAdminController::class, 'destroy'])->name('reviews.destroy');
    Route::post('reviews/bulk', [ReviewAdminController::class, 'bulk'])->name('reviews.bulk');

    Route::get('order-requests', [OrderRequestAdminController::class, 'index'])->name('order-requests.index');
    Route::put('order-requests/{orderRequest}', [OrderRequestAdminController::class, 'update'])->name('order-requests.update');
    Route::delete('order-requests/{orderRequest}', [OrderRequestAdminController::class, 'destroy'])->name('order-requests.destroy');
    Route::post('order-requests/bulk', [OrderRequestAdminController::class, 'bulk'])->name('order-requests.bulk');
});

// ✅ SEO: Sitemap
Route::get('/sitemap.xml', function () {
    $items = [];
    $items[] = ['loc' => route('home'), 'priority' => '1.0'];
    $items[] = ['loc' => route('products.index'), 'priority' => '0.9'];
    $items[] = ['loc' => route('about'), 'priority' => '0.7'];

    \App\Models\Category::select('slug')->chunk(200, function ($categories) use (&$items) {
        foreach ($categories as $category) {
            $items[] = ['loc' => route('categories.show', $category->slug), 'priority' => '0.6'];
        }
    });

    \App\Models\Product::select('id')->chunk(200, function ($products) use (&$items) {
        foreach ($products as $product) {
            $items[] = ['loc' => route('products.show', $product), 'priority' => '0.6'];
        }
    });

    $xml = view('sitemap', compact('items'))->render();

    return response($xml, 200)->header('Content-Type', 'application/xml');
})->name('sitemap');

// ✅ Breeze profile routes (keep)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
