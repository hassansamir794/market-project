<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['categories', 'images'])->latest()->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateProduct($request);
        $validated['is_available'] = (bool) ($validated['is_available'] ?? false);
        $validated['price'] = $this->normalizePrice((float) $validated['price']);

        if ($request->hasFile('image')) {
            $validated['image'] = $this->storeOptimizedImage($request->file('image'));
        }

        $product = Product::create($validated);
        $product->categories()->sync($request->input('category_ids', []));

        $this->syncGalleryAfterSave($product, $request);
        $this->forgetProductCaches($product);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product created successfully');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        $product->load(['categories', 'images']);

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $this->validateProduct($request);
        $validated['is_available'] = (bool) ($validated['is_available'] ?? false);
        $validated['price'] = $this->normalizePrice((float) $validated['price']);

        $previousPrimaryImage = $product->image;

        if ($request->hasFile('image')) {
            if ($previousPrimaryImage) {
                $this->deleteImageByPath($product, $previousPrimaryImage);
            }

            $validated['image'] = $this->storeOptimizedImage($request->file('image'));
        }

        $product->update($validated);
        $product->categories()->sync($request->input('category_ids', []));

        $deleteImageIds = collect($request->input('delete_image_ids', []))
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->values()
            ->all();

        if ($deleteImageIds !== []) {
            $imagesToDelete = $product->images()->whereIn('id', $deleteImageIds)->get();

            foreach ($imagesToDelete as $image) {
                $this->deleteImageRecord($image);
            }
        }

        $this->syncGalleryAfterSave($product, $request);

        $product->refresh();
        $product->load('images');

        $primaryPath = $product->images->first()?->path;

        if ($product->image !== $primaryPath) {
            $product->forceFill(['image' => $primaryPath])->save();
        }

        $this->forgetProductCaches($product);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product updated successfully');
    }

    public function destroy(Product $product)
    {
        $product->categories()->detach();

        foreach ($product->images as $image) {
            $this->deleteImagePair($image->path);
        }

        if ($product->image && $product->images->doesntContain(fn ($image) => $image->path === $product->image)) {
            $this->deleteImagePair($product->image);
        }

        $product->delete();
        $this->forgetProductCaches($product);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product deleted successfully');
    }

    private function validateProduct(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'is_available' => ['nullable', 'boolean'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'gallery_images' => ['nullable', 'array'],
            'gallery_images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'delete_image_ids' => ['nullable', 'array'],
            'delete_image_ids.*' => ['integer', 'exists:product_images,id'],
            'category_ids' => ['nullable', 'array'],
            'category_ids.*' => ['integer', 'exists:categories,id'],
        ]);
    }

    private function syncGalleryAfterSave(Product $product, Request $request): void
    {
        $product->load('images');

        if ($product->image && $product->images->doesntContain(fn ($image) => $image->path === $product->image)) {
            ProductImage::create([
                'product_id' => $product->id,
                'path' => $product->image,
                'sort_order' => 0,
            ]);
        }

        if ($request->hasFile('gallery_images')) {
            $nextOrder = (int) $product->images()->max('sort_order') + 1;

            foreach ($request->file('gallery_images', []) as $file) {
                $path = $this->storeOptimizedImage($file);

                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $path,
                    'sort_order' => $nextOrder,
                ]);

                if (! $product->image) {
                    $product->forceFill(['image' => $path])->save();
                }

                $nextOrder++;
            }
        }
    }

    private function normalizePrice(float $price): float
    {
        $rate = (float) config('currency.rate', 1);
        $safeRate = $rate > 0 ? $rate : 1;

        return $price / $safeRate;
    }

    private function forgetProductCaches(Product $product): void
    {
        Cache::forget('home.latest_products');
        Cache::forget('product.show.' . $product->id);
    }

    private function deleteImageByPath(Product $product, string $path): void
    {
        $imageRecord = $product->images()->where('path', $path)->first();

        if ($imageRecord) {
            $this->deleteImageRecord($imageRecord);

            return;
        }

        $this->deleteImagePair($path);
    }

    private function deleteImageRecord(ProductImage $image): void
    {
        $this->deleteImagePair($image->path);
        $image->delete();
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
