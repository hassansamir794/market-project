<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('categories')->latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'is_available' => ['nullable', 'boolean'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],

            // ✅ categories validation
            'category_ids' => ['nullable', 'array'],
            'category_ids.*' => ['integer', 'exists:categories,id'],
        ]);

        $validated['is_available'] = (bool) ($validated['is_available'] ?? false);

        $rate = (float) config('currency.rate', 1);
        $safeRate = $rate > 0 ? $rate : 1;
        $validated['price'] = (float) $validated['price'] / $safeRate;

        if ($request->hasFile('image')) {
            $validated['image'] = $this->storeOptimizedImage($request->file('image'));
        }

        $product = Product::create($validated);

        // ✅ attach categories
        $product->categories()->sync($request->input('category_ids', []));

        Cache::forget('home.latest_products');

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product created successfully');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        $product->load('categories');

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'is_available' => ['nullable', 'boolean'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],

            // ✅ categories validation
            'category_ids' => ['nullable', 'array'],
            'category_ids.*' => ['integer', 'exists:categories,id'],
        ]);

        $validated['is_available'] = (bool) ($validated['is_available'] ?? false);

        $rate = (float) config('currency.rate', 1);
        $safeRate = $rate > 0 ? $rate : 1;
        $validated['price'] = (float) $validated['price'] / $safeRate;

        if ($request->hasFile('image')) {
            if ($product->image) {
                $this->deleteImagePair($product->image);
            }
            $validated['image'] = $this->storeOptimizedImage($request->file('image'));
        }

        $product->update($validated);

        // ✅ sync categories
        $product->categories()->sync($request->input('category_ids', []));

        Cache::forget('home.latest_products');
        Cache::forget('product.show.' . $product->id);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product updated successfully');
    }

    public function destroy(Product $product)
    {
        // detach categories first (clean)
        $product->categories()->detach();

        if ($product->image) {
            $this->deleteImagePair($product->image);
        }

        $product->delete();

        Cache::forget('home.latest_products');
        Cache::forget('product.show.' . $product->id);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product deleted successfully');
    }

    private function storeOptimizedImage($uploadedFile): string
    {
        $maxWidth = 1400;
        $thumbWidth = 480;
        $quality = 82;

        if (! function_exists('imagecreatetruecolor') || ! function_exists('imagewebp')) {
            return $uploadedFile->store('products', 'public');
        }

        $mime = $uploadedFile->getMimeType();
        $source = null;

        if ($mime === 'image/jpeg') {
            $source = @imagecreatefromjpeg($uploadedFile->getPathname());
        } elseif ($mime === 'image/png') {
            $source = @imagecreatefrompng($uploadedFile->getPathname());
        } elseif ($mime === 'image/webp' && function_exists('imagecreatefromwebp')) {
            $source = @imagecreatefromwebp($uploadedFile->getPathname());
        }

        if (! $source) {
            return $uploadedFile->store('products', 'public');
        }

        [$width, $height] = getimagesize($uploadedFile->getPathname());
        if (! $width || ! $height) {
            imagedestroy($source);
            return $uploadedFile->store('products', 'public');
        }

        $uuid = Str::uuid()->toString();
        $filename = 'products/' . $uuid . '.webp';
        $thumbFilename = 'products/thumbs/' . $uuid . '.webp';

        $mainData = $this->buildWebpData($source, $width, $height, min($width, $maxWidth), $quality);
        $thumbData = $this->buildWebpData($source, $width, $height, min($width, $thumbWidth), $quality);

        imagedestroy($source);

        if (! $mainData || ! $thumbData) {
            return $uploadedFile->store('products', 'public');
        }

        Storage::disk('public')->put($filename, $mainData);
        Storage::disk('public')->put($thumbFilename, $thumbData);

        return $filename;
    }

    private function buildWebpData($source, int $width, int $height, int $targetWidth, int $quality): ?string
    {
        $targetHeight = (int) round($height * ($targetWidth / $width));

        $canvas = imagecreatetruecolor($targetWidth, $targetHeight);
        if (! $canvas) {
            return null;
        }

        imagealphablending($canvas, true);
        imagesavealpha($canvas, true);
        imagecopyresampled($canvas, $source, 0, 0, 0, 0, $targetWidth, $targetHeight, $width, $height);

        ob_start();
        $success = imagewebp($canvas, null, $quality);
        $data = ob_get_clean();

        imagedestroy($canvas);

        if (! $success) {
            return null;
        }

        return $data ?: null;
    }

    private function deleteImagePair(string $path): void
    {
        Storage::disk('public')->delete($path);
        $thumbPath = str_replace('products/', 'products/thumbs/', $path);
        Storage::disk('public')->delete($thumbPath);
    }
}
