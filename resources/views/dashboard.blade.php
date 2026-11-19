@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="row g-4">

    {{-- ── Welcome card ──────────────────────────────────── --}}
    <div class="col-12">
        <div class="card p-4">
            <div class="d-flex align-items-center gap-3 flex-wrap">
                @if(auth()->user()->profile_picture)
                    <img src="{{ '/' . auth()->user()->profile_picture }}"
                         class="avatar-lg" alt="avatar">
                @else
                    <div class="avatar-lg bg-rc-light d-flex align-items-center justify-content-center"
                         style="font-size:2.5rem;">🌿</div>
                @endif
                <div class="flex-grow-1">
                    <h3 class="mb-0 fw-bold">Welcome back, {{ auth()->user()->name }}!</h3>
                    <p class="text-muted mb-0">
                        {{ auth()->user()->location ? '📍 ' . auth()->user()->location : 'No location set yet.' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Stats row ─────────────────────────────────────── --}}
    <div class="col-6 col-md-3">
        <div class="card p-3 text-center">
            <div class="fs-2 fw-bold text-rc">{{ $stats['posts'] }}</div>
            <div class="text-muted small">Plant Posts</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card p-3 text-center">
            <div class="fs-2 fw-bold text-rc">{{ $stats['collections'] }}</div>
            <div class="text-muted small">Collections</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card p-3 text-center">
            <div class="fs-2 fw-bold text-rc">{{ $stats['plants'] }}</div>
            <div class="text-muted small">Plants</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card p-3 text-center">
            <div class="fs-2 fw-bold text-rc">{{ $stats['growth_logs'] }}</div>
            <div class="text-muted small">Growth Entries</div>
        </div>
    </div>

    {{-- ── Quick action cards ─────────────────────────────── --}}
    <div class="col-md-4">
        <div class="card h-100 p-4 text-center d-flex flex-column">
            <div style="font-size:2.5rem;">👤</div>
            <h5 class="mt-2 fw-bold text-rc">My Profile</h5>
            <p class="text-muted small flex-grow-1">Update your bio, location, and photo.</p>
            <a href="{{ route('profile.show') }}" class="btn btn-rc">View Profile</a>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100 p-4 text-center d-flex flex-column">
            <div style="font-size:2.5rem;">🪴</div>
            <h5 class="mt-2 fw-bold text-rc">My Garden</h5>
            <p class="text-muted small flex-grow-1">Manage your plant collections and track growth.</p>
            <a href="{{ route('collections.index') }}" class="btn btn-rc">Open Garden</a>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100 p-4 text-center d-flex flex-column">
            <div style="font-size:2.5rem;">🌱</div>
            <h5 class="mt-2 fw-bold text-rc">Plant Posts</h5>
            <p class="text-muted small flex-grow-1">Share your plants with the community.</p>
            <a href="{{ route('posts.index') }}" class="btn btn-rc">Browse Posts</a>
        </div>
    </div>

    {{-- ── Feature 9 & 12 cards ────────────────────────────── --}}
    <div class="col-md-6">
        <div class="card h-100 p-4 text-center d-flex flex-column"
             style="background: linear-gradient(135deg,#2d6a4f,#52b788); color:#fff;">
            <div style="font-size:2.5rem;">🔍</div>
            <h5 class="mt-2 fw-bold">Search & Discover</h5>
            <p class="small flex-grow-1 opacity-75">Find users, plants, and community posts by keyword.</p>
            <a href="{{ route('search.index') }}" class="btn btn-light fw-bold">Search Now</a>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card h-100 p-4 text-center d-flex flex-column"
             style="background: linear-gradient(135deg,#40916c,#74c69d); color:#fff;">
            <div style="font-size:2.5rem;">📰</div>
            <h5 class="mt-2 fw-bold">My Feed</h5>
            <p class="small flex-grow-1 opacity-75">See recent posts from people you follow.</p>
            <a href="{{ route('feed.index') }}" class="btn btn-light fw-bold">Open Feed</a>
        </div>
    </div>

    {{-- ── Recent plants preview ──────────────────────────── --}}
    @if($recentPlants->isNotEmpty())
    <div class="col-12">
        <div class="card">
            <div class="card-header-rc p-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 text-white fw-bold">
                    <i class="bi bi-flower1 me-2"></i>Recently Added Plants
                </h6>
                <a href="{{ route('collections.index') }}" class="btn btn-light btn-sm">
                    View All
                </a>
            </div>
            <div class="card-body p-0">
                <div class="row g-0">
                    @foreach($recentPlants as $plant)
                    <div class="col-md-4 {{ !$loop->last ? 'border-end' : '' }}">
                        <div class="p-3 d-flex align-items-center gap-3">
                            @if($plant->photo_url)
                                <img src="{{ '/' . $plant->photo_url }}"
                                     class="rounded-circle flex-shrink-0"
                                     style="width:52px; height:52px; object-fit:cover;" alt="{{ $plant->name }}">
                            @else
                                <div class="rounded-circle bg-rc-light flex-shrink-0 d-flex align-items-center justify-content-center"
                                     style="width:52px; height:52px; font-size:1.4rem;">🌿</div>
                            @endif
                            <div class="overflow-hidden">
                                <div class="fw-semibold text-rc text-truncate">{{ $plant->name }}</div>
                                <div class="text-muted small text-truncate">
                                    {{ $plant->species ?: $plant->collection->name }}
                                </div>
                                <a href="{{ route('plants.show', $plant) }}" class="btn btn-rc btn-sm py-0 mt-1">
                                    View →
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="col-12">
        <div class="card p-4 text-center">
            <div style="font-size:3rem;">🌱</div>
            <h6 class="mt-2 text-muted">No plants yet — start building your garden!</h6>
            <a href="{{ route('collections.create') }}" class="btn btn-rc btn-sm mt-2">
                <i class="bi bi-plus-circle me-1"></i>Create First Collection
            </a>
        </div>
    </div>
    @endif

</div>
@endsection
