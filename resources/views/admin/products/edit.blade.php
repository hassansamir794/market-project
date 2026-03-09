@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')
    <div class="mt-6 max-w-2xl">
        <a class="text-sm text-gray-600 hover:text-gray-900" href="{{ route('admin.products.index') }}">
            ← Back
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
            @endphp

            <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="field-label">Name</label>
                    <input
                        class="input-clean"
                        name="name"
                        value="{{ old('name', $product->name) }}"
                        required
                    >
                </div>

                <div>
                    <label class="field-label">Stock</label>
                    <input
                        class="input-clean"
                        name="stock"
                        type="number"
                        step="1"
                        min="0"
                        value="{{ old('stock', $product->stock ?? 0) }}"
                        required
                    >
                </div>

                <div class="flex items-center gap-3">
                    <input
                        id="is_available"
                        name="is_available"
                        type="checkbox"
                        value="1"
                        class="h-4 w-4"
                        @checked(old('is_available', (bool) ($product->is_available ?? true)))
                    >
                    <label for="is_available" class="font-semibold">Available for sale</label>
                </div>

                <div>
                    <label class="field-label">Price ({{ $currencySymbol }})</label>
                    <input
                        class="input-clean"
                        name="price"
                        type="number"
                        step="{{ $priceStep }}"
                        min="0"
                        value="{{ old('price', $displayPrice) }}"
                        required
                    >
                </div>

                <div>
                    <label class="field-label">Description</label>
                    <textarea
                        class="textarea-clean"
                        name="description"
                        rows="4"
                    >{{ old('description', $product->description) }}</textarea>
                </div>

                {{-- ✅ Categories --}}
                <div>
                    <label class="field-label">Categories</label>

                    <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-2">
                        @foreach($categories as $cat)
                            <label class="flex items-center gap-3 rounded-xl border border-gray-200 bg-white px-3 py-2">
                                <input
                                    type="checkbox"
                                    name="category_ids[]"
                                    value="{{ $cat->id }}"
                                    class="h-4 w-4"
                                    @checked(in_array($cat->id, $selected))
                                >
                                <span class="text-sm font-medium text-gray-800">{{ $cat->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="field-label">Replace Image (optional)</label>
                    <input class="w-full" name="image" type="file" accept="image/*">

                    @if($product->image)
                        <img
                            class="mt-3 w-full h-56 object-cover rounded-2xl border"
                            src="{{ asset('storage/' . $product->image) }}"
                            alt="{{ $product->name }}"
                        >
                    @endif
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <button class="btn-primary" type="submit">
                        Update
                    </button>

                    <a class="btn-outline"
                       href="{{ route('admin.products.index') }}">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
