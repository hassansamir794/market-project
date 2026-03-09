@extends('layouts.app')

@section('title', 'Admin Categories')

@section('content')
    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-3 mt-6 mb-6">
        <h1 class="text-2xl font-bold">Categories</h1>
        <a href="{{ route('admin.categories.create') }}" class="btn-primary text-center">
            + Add Category
        </a>
    </div>

    <div class="space-y-3 md:hidden">
        @forelse($categories as $category)
            <div class="glass-card p-4">
                <div class="font-semibold text-gray-900">{{ $category->name }}</div>
                <div class="mt-2 text-sm text-gray-600">{{ $category->slug }}</div>
                <div class="mt-4 flex items-center gap-4">
                    <a class="action-link" href="{{ route('admin.categories.edit', $category) }}">Edit</a>
                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                          onsubmit="return confirm('Delete this category?')">
                        @csrf
                        @method('DELETE')
                        <button class="action-link-danger">Delete</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="glass-card p-6 text-center text-gray-500">No categories yet.</div>
        @endforelse
    </div>

    <div class="admin-table-wrap">
        <div class="overflow-x-auto">
        <table class="admin-table min-w-[560px]">
            <thead>
            <tr>
                <th class="p-3 text-left">Name</th>
                <th class="p-3 text-left">Slug</th>
                <th class="p-3 text-left">Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse($categories as $category)
                <tr>
                    <td class="p-3">{{ $category->name }}</td>
                    <td class="p-3 text-gray-600">{{ $category->slug }}</td>
                    <td class="p-3">
                        <div class="flex flex-wrap gap-3">
                        <a class="action-link" href="{{ route('admin.categories.edit', $category) }}">Edit</a>

                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                              onsubmit="return confirm('Delete this category?')">
                            @csrf
                            @method('DELETE')
                            <button class="action-link-danger">Delete</button>
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
