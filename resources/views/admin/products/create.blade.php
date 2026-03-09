@extends('layouts.app')

@section('title', 'Add Product')

@section('content')
    <div class="mt-6 max-w-2xl">
        <a class="text-sm text-gray-600 hover:text-gray-900" href="{{ route('admin.products.index') }}">
            ← Back
        </a>

        <div class="mt-4 form-panel">
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
                    <label class="field-label">Name</label>
                    <input
                        class="input-clean"
                        name="name"
                        value="{{ old('name') }}"
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
                    <label class="field-label">Price ({{ $currencySymbol }})</label>
                    <input
                        class="input-clean"
                        name="price"
                        type="number"
                        step="{{ $priceStep }}"
                        min="0"
                        value="{{ old('price') }}"
                        required
                    >
                </div>

                <div>
                    <label class="field-label">Description</label>
                    <textarea
                        class="textarea-clean"
                        name="description"
                        rows="4"
                    >{{ old('description') }}</textarea>
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
                                    @checked(in_array($cat->id, old('category_ids', [])))
                                >
                                <span class="text-sm font-medium text-gray-800">{{ $cat->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="field-label">Image (optional)</label>
                    <input class="w-full" name="image" type="file" accept="image/*">
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <button class="btn-primary" type="submit">
                        Save
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
