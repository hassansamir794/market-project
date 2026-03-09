@extends('layouts.app')

@section('title', 'Add Category')

@section('content')
    <div class="mt-6 max-w-xl">
        <a class="text-sm text-gray-600 hover:text-gray-900" href="{{ route('admin.categories.index') }}">Back</a>

        <div class="mt-4 form-panel">
            <h1 class="text-xl font-bold mb-4">Add Category</h1>

            @if ($errors->any())
                <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-800">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.categories.store') }}">
                @csrf

                <label class="field-label">Name</label>
                <input class="input-clean" name="name" value="{{ old('name') }}" required>

                <button class="mt-4 btn-primary">Save</button>
            </form>
        </div>
    </div>
@endsection
