@extends('layouts.app')

@section('title', 'Admin Categories')

@section('content')
    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-3 mt-6 mb-6">
        <h1 class="text-2xl font-bold">Categories</h1>
        <a href="{{ route('admin.categories.create') }}" class="bg-black text-white px-4 py-2 rounded-xl text-center">
            + Add Category
        </a>
    </div>

    <div class="bg-white border rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
        <table class="w-full min-w-[560px]">
            <thead class="bg-gray-50">
            <tr>
                <th class="p-3 text-left">Name</th>
                <th class="p-3 text-left">Slug</th>
                <th class="p-3 text-left">Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse($categories as $category)
                <tr class="border-t">
                    <td class="p-3">{{ $category->name }}</td>
                    <td class="p-3 text-gray-600">{{ $category->slug }}</td>
                    <td class="p-3">
                        <div class="flex flex-wrap gap-3">
                        <a class="underline" href="{{ route('admin.categories.edit', $category) }}">Edit</a>

                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                              onsubmit="return confirm('Delete this category?')">
                            @csrf
                            @method('DELETE')
                            <button class="underline text-red-600">Delete</button>
                        </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td class="p-3" colspan="3">No categories yet.</td></tr>
            @endforelse
            </tbody>
        </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $categories->links() }}
    </div>
@endsection
