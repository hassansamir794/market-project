@extends('layouts.app')

@section('title', 'Profile | Rasan Market')

@section('content')
    <div class="py-12">
        <div class="max-w-4xl mx-auto space-y-6">
            <div class="form-panel">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="form-panel">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="form-panel">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
@endsection
