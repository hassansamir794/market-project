@extends('layouts.app')

@section('title', 'Add Product')

@section('content')
    <div class="mt-6 max-w-2xl">
        <a class="text-sm text-gray-600 hover:text-gray-900" href="{{ route('admin.products.index') }}">
            ← Back
        </a>

        <div class="mt-4 bg-white border rounded-3xl shadow-sm p-6">
            <h1 class="text-2xl font-bold mb-6">Add Product</h1>

            @if ($errors->any())
                <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-800">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <div>
                    <label class="block mb-1 font-semibold">Name</label>
                    <input
                        class="w-full border rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-black/20"
                        name="name"
                        value="{{ old('name') }}"
                        required
                    >
                </div>

                <div>
                    <label class="block mb-1 font-semibold">Stock</label>
                    <input
                        class="w-full border rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-black/20"
                        name="stock"
                        type="number"
                        step="1"
                        min="0"
                        value="{{ old('stock', 0) }}"
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
                        @checked(old('is_available', true))
                    >
                    <label for="is_available" class="font-semibold">Available for sale</label>
                </div>

                @php
                    $currencySymbol = config('currency.symbol', 'IQD');
                    $currencyDecimals = (int) config('currency.decimals', 0);
                    $priceStep = $currencyDecimals > 0 ? '0.01' : '1';
                @endphp

                <div>
                    <label class="block mb-1 font-semibold">Price ({{ $currencySymbol }})</label>
                    <input
                        class="w-full border rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-black/20"
                        name="price"
                        type="number"
                        step="{{ $priceStep }}"
                        min="0"
                        value="{{ old('price') }}"
                        required
                    >
                </div>

                <div>
                    <label class="block mb-1 font-semibold">Description</label>
                    <textarea
                        class="w-full border rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-black/20"
                        name="description"
                        rows="4"
                    >{{ old('description') }}</textarea>
                </div>

                {{-- ✅ Categories --}}
                <div>
                    <label class="block mb-1 font-semibold">Categories</label>

                    <select
                        name="category_ids[]"
                        multiple
                        class="w-full border rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-black/20"
                    >
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" @selected(in_array($cat->id, old('category_ids', [])))>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>

                    <p class="text-sm text-gray-500 mt-2">
                        Tip: Hold <strong>Ctrl</strong> (Windows) to select multiple categories.
                    </p>
                </div>

                <div>
                    <label class="block mb-1 font-semibold">Image (optional)</label>
                    <input class="w-full" name="image" type="file" accept="image/*">
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <button class="px-5 py-3 rounded-xl bg-black text-white font-semibold hover:opacity-90 transition" type="submit">
                        Save
                    </button>

                    <a class="px-5 py-3 rounded-xl border font-semibold hover:bg-gray-50 transition"
                       href="{{ route('admin.products.index') }}">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
