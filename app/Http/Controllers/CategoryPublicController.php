<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryPublicController extends Controller
{
    public function show(Request $request, string $slug)
    {
        $categoryModel = Category::where('slug', $slug)->firstOrFail();

        $request->merge(['category' => $categoryModel->slug]);
        $pageTitle = $categoryModel->name . ' | Market';
        $metaDescription = 'Browse products in ' . $categoryModel->name . ' at Market.';

        return app(ProductPublicController::class)->index($request)
            ->with([
                'pageTitle' => $pageTitle,
                'metaDescription' => $metaDescription,
                'categoryModel' => $categoryModel,
            ]);
    }
}
