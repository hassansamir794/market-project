@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')
    <div class="mt-6 max-w-2xl">
        <a class="text-sm text-gray-600 hover:text-gray-900" href="{{ route('admin.products.index') }}">
            ← Back
        </a>

        <div class="mt-4 bg-white border rounded-3xl shadow-sm p-6">
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
            @endphp

            <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="block mb-1 font-semibold">Name</label>
                    <input
                        class="w-full border rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-black/20"
                        name="name"
                        value="{{ old('name', $product->name) }}"
                        required
                    >
                </div>

                <div>
                    <label class="block mb-1 font-semibold">Price</label>
                    <input
                        class="w-full border rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-black/20"
                        name="price"
                        type="number"
                        step="0.01"
                        value="{{ old('price', $product->price) }}"
                        required
                    >
                </div>

                <div>
                    <label class="block mb-1 font-semibold">Description</label>
                    <textarea
                        class="w-full border rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-black/20"
                        name="description"
                        rows="4"
                    >{{ old('description', $product->description) }}</textarea>
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
                            <option value="{{ $cat->id }}" @selected(in_array($cat->id, $selected))>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>

                    <p class="text-sm text-gray-500 mt-2">
                        Tip: Hold <strong>Ctrl</strong> (Windows) to select multiple categories.
                    </p>
                </div>

                <div>
                    <label class="block mb-1 font-semibold">Replace Image (optional)</label>
                    <input class="w-full" name="image" type="file" accept="image/*">

                    @if($product->image)
                        <img
                            class="mt-3 w-full h-56 object-cover rounded-2xl border"
                            src="{{ asset('storage/' . $product->image) }}"
                            alt="{{ $product->name }}"
                        >
                    @endif
                </div>

                <div class="flex gap-3">
                    <button class="px-5 py-3 rounded-xl bg-black text-white font-semibold hover:opacity-90 transition" type="submit">
                        Update
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
