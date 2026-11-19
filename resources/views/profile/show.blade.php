@extends('layouts.app')
@section('title', 'My Profile')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">

        <!-- Profile card -->
        <div class="card mb-4">
            <div class="card-header-rc p-4">
                <div class="d-flex align-items-center gap-3">
                    @if($user->profile_picture)
                        <img src="{{ chr(47) . $user->profile_picture }}"
                             class="avatar-lg" alt="avatar">
                    @else
                        <div class="avatar-lg bg-white d-flex align-items-center justify-content-center"
                             style="font-size:2.5rem; border:4px solid #fff;">🌿</div>
                    @endif
                    <div>
                        <h3 class="text-white mb-0 fw-bold">{{ $user->name }}</h3>
                        @if($user->location)
                            <p class="text-white-50 mb-0"><i class="bi bi-geo-alt"></i> {{ $user->location }}</p>
                        @endif
                    </div>
                    <a href="{{ route('profile.edit') }}" class="btn btn-light btn-sm ms-auto">
                        <i class="bi bi-pencil"></i> Edit Profile
                    </a>
                </div>
            </div>
            <div class="card-body p-4">

                <!-- Bio -->
                <h6 class="fw-bold text-rc"><i class="bi bi-person-lines-fill me-1"></i>About</h6>
                <p class="text-muted">
                    {{ $user->bio ?: 'No bio yet. Tell the community about yourself!' }}
                </p>

                <hr>

                <!-- Gardening interests -->
                <h6 class="fw-bold text-rc"><i class="bi bi-flower1 me-1"></i>Gardening Interests</h6>
                @if($user->gardening_interests && count($user->gardening_interests) > 0)
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($user->gardening_interests as $interest)
                            <span class="badge bg-rc-light text-rc px-3 py-2 rounded-pill">
                                🌿 {{ trim($interest) }}
                            </span>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">No gardening interests listed yet.</p>
                @endif

                <hr>

                <!-- Stats -->
                <h6 class="fw-bold text-rc"><i class="bi bi-bar-chart me-1"></i>Activity</h6>
                <div class="row text-center g-3">
                    <div class="col-6">
                        <div class="p-3 bg-rc-light rounded-3">
                            <div class="fs-3 fw-bold text-rc">{{ $user->posts()->count() }}</div>
                            <div class="small text-muted">Plant Posts</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-rc-light rounded-3">
                            <div class="fs-3 fw-bold text-rc">{{ $user->email }}</div>
                            <div class="small text-muted">Email</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- User's posts -->
        @if($user->posts()->count() > 0)
        <h5 class="fw-bold text-rc mb-3"><i class="bi bi-images me-1"></i>My Plant Posts</h5>
        <div class="row g-3">
            @foreach($user->posts()->latest()->get() as $post)
            <div class="col-md-6">
                <div class="card h-100">
                    <img src="{{ chr(47) . $post->image_url }}"
                         class="card-img-top" style="height:200px; object-fit:cover;" alt="post">
                    <div class="card-body d-flex flex-column">
                        <p class="card-text flex-grow-1">{{ Str::limit($post->caption, 100) }}</p>
                        <small class="text-muted">{{ $post->created_at->diffForHumans() }}</small>

                        <form method="POST" action="{{ route('posts.destroy', $post) }}"
                              class="mt-3"
                              onsubmit="return confirm('Delete this post?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger w-100">
                                <i class="bi bi-trash me-1"></i>Delete Post
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

    </div>
</div>
@endsection
