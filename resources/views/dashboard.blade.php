@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="row g-4">
    <!-- Welcome card -->
    <div class="col-12">
        <div class="card p-4">
            <div class="d-flex align-items-center gap-3">
                @if(auth()->user()->profile_picture)
                    <img src="{{ chr(47) . auth()->user()->profile_picture }}"
                         class="avatar-lg" alt="avatar">
                @else
                    <div class="avatar-lg bg-rc-light d-flex align-items-center justify-content-center"
                         style="font-size:2.5rem;">🌿</div>
                @endif
                <div>
                    <h3 class="mb-0 fw-bold">Welcome back, {{ auth()->user()->name }}!</h3>
                    <p class="text-muted mb-0">
                        {{ auth()->user()->location ? '📍 ' . auth()->user()->location : 'No location set yet.' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick actions -->
    <div class="col-md-4">
        <div class="card h-100 p-4 text-center">
            <div style="font-size:2.5rem;">👤</div>
            <h5 class="mt-2 fw-bold text-rc">My Profile</h5>
            <p class="text-muted small">Update your bio, location, and photo.</p>
            <a href="{{ route('profile.show') }}" class="btn btn-rc mt-auto">View Profile</a>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100 p-4 text-center">
            <div style="font-size:2.5rem;">🌱</div>
            <h5 class="mt-2 fw-bold text-rc">Plant Posts</h5>
            <p class="text-muted small">Share your plants with the community.</p>
            <a href="{{ route('posts.index') }}" class="btn btn-rc mt-auto">Browse Posts</a>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100 p-4 text-center">
            <div style="font-size:2.5rem;">📸</div>
            <h5 class="mt-2 fw-bold text-rc">Share a Plant</h5>
            <p class="text-muted small">Upload a photo and write a caption.</p>
            <a href="{{ route('posts.create') }}" class="btn btn-rc mt-auto">Create Post</a>
        </div>
    </div>
</div>
@endsection
