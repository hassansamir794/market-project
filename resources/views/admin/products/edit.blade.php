@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')
    <div class="mt-6 max-w-4xl">
        <a class="text-sm text-gray-600 hover:text-gray-900" href="{{ route('admin.products.index') }}">
            <- Back
        </a>

        <div class="mt-4 form-panel">
            <h1 class="text-2xl font-bold mb-6">Edit Product</h1>

            @if ($errors->any())
                <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-800">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @php
                $selected = old('category_ids', $product->categories->pluck('id')->toArray());
                $currencySymbol = config('currency.symbol', 'IQD');
                $currencyDecimals = (int) config('currency.decimals', 0);
                $priceStep = $currencyDecimals > 0 ? '0.01' : '1';
                $rate = (float) config('currency.rate', 1);
                $safeRate = $rate > 0 ? $rate : 1;
                $displayPrice = $product->price * $safeRate;
                $galleryImages = $product->images;
                $deletedImageIds = collect(old('delete_image_ids', []))->map(fn ($id) => (int) $id)->all();
            @endphp

            <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="field-label">Name</label>
                    <input class="input-clean" name="name" value="{{ old('name', $product->name) }}" required>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="field-label">Stock</label>
                        <input class="input-clean" name="stock" type="number" step="1" min="0" value="{{ old('stock', $product->stock ?? 0) }}" required>
                    </div>

                    <div>
                        <label class="field-label">Price ({{ $currencySymbol }})</label>
                        <input class="input-clean" name="price" type="number" step="{{ $priceStep }}" min="0" value="{{ old('price', $displayPrice) }}" required>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <input id="is_available" name="is_available" type="checkbox" value="1" class="h-4 w-4" @checked(old('is_available', (bool) ($product->is_available ?? true)))>
                    <label for="is_available" class="font-semibold">Available for sale</label>
                </div>

                <div>
                    <label class="field-label">Description</label>
                    <textarea class="textarea-clean" name="description" rows="4">{{ old('description', $product->description) }}</textarea>
                </div>

                <div>
                    <label class="field-label">Categories</label>
                    <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-2">
                        @foreach($categories as $cat)
                            <label class="flex items-center gap-3 rounded-xl border border-stone-200/80 bg-white/70 px-3 py-2">
                                <input type="checkbox" name="category_ids[]" value="{{ $cat->id }}" class="h-4 w-4" @checked(in_array($cat->id, $selected))>
                                <span class="text-sm font-medium text-gray-800">{{ $cat->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="field-label">Replace Cover Image</label>
                        <input class="w-full rounded-xl border border-dashed border-stone-300 bg-white/60 px-3 py-3" name="image" type="file" accept="image/*">
                        <p class="mt-2 text-sm text-gray-600">Uploading a new cover will replace the current main image.</p>
                    </div>

                    <div>
                        <label class="field-label">Add Gallery Images</label>
                        <input class="w-full rounded-xl border border-dashed border-stone-300 bg-white/60 px-3 py-3" name="gallery_images[]" type="file" accept="image/*" multiple>
                        <p class="mt-2 text-sm text-gray-600">You can keep existing images and add more here.</p>
                    </div>
                </div>

                @if($galleryImages->count())
                    <div>
                        <label class="field-label">Existing Images</label>
                        <div class="mt-3 grid grid-cols-2 sm:grid-cols-3 gap-4">
                            @foreach($galleryImages as $image)
                                <label class="glass-card overflow-hidden">
                                    <img class="h-32 w-full object-cover" src="{{ asset('storage/' . $image->path) }}" alt="{{ $product->name }}">
                                    <div class="p-3 text-sm">
                                        <div class="font-semibold text-gray-900">
                                            {{ $image->path === $product->image ? 'Cover image' : 'Gallery image' }}
                                        </div>
                                        <div class="mt-2 flex items-center gap-2">
                                            <input type="checkbox" name="delete_image_ids[]" value="{{ $image->id }}" class="h-4 w-4" @checked(in_array($image->id, $deletedImageIds, true))>
                                            <span class="text-gray-600">Remove this image</span>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="flex flex-col sm:flex-row gap-3">
                    <button class="btn-primary" type="submit">Update</button>
                    <a class="btn-outline" href="{{ route('admin.products.index') }}">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
