@extends('layouts.app')
@section('title', 'Plant Posts')

@section('content')
<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-rc mb-0"><i class="bi bi-images me-2"></i>Plant Posts</h3>
        <p class="text-muted mb-0">See what the community is growing 🌱</p>
    </div>
    <a href="{{ route('posts.create') }}" class="btn btn-rc">
        <i class="bi bi-plus-circle me-1"></i>Share a Plant
    </a>
</div>

@if($posts->isEmpty())
    <div class="text-center py-5">
        <div style="font-size:4rem;">🌿</div>
        <h5 class="text-muted mt-3">No posts yet!</h5>
        <p class="text-muted">Be the first to share your plant with the community.</p>
        <a href="{{ route('posts.create') }}" class="btn btn-rc">Create First Post</a>
    </div>
@else
    <div class="row g-4">
        @foreach($posts as $post)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100">
                <!-- Post image -->
                <img src="{{ chr(47) . $post->image_url }}"
                     class="card-img-top post-image" alt="plant photo">

                <div class="card-body d-flex flex-column">
                    <!-- Author -->
                    <div class="d-flex align-items-center gap-2 mb-2">
                        @if($post->user->profile_picture)
                            <img src="{{ chr(47) . $post->user->profile_picture }}"
                                 class="avatar-sm" alt="avatar">
                        @else
                            <div class="avatar-sm bg-rc-light d-flex align-items-center justify-content-center"
                                 style="font-size:1.1rem;">🌿</div>
                        @endif
                        <div>
                            <div class="fw-semibold small">{{ $post->user->name }}</div>
                            <div class="text-muted" style="font-size:.75rem;">
                                <i class="bi bi-clock me-1"></i>{{ $post->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>

                    <!-- Caption -->
                    <p class="card-text flex-grow-1">{{ $post->caption }}</p>

                    @if($post->user->location)
                        <div class="text-muted small mt-auto">
                            <i class="bi bi-geo-alt me-1"></i>{{ $post->user->location }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $posts->links() }}
    </div>
@endif
@endsection
