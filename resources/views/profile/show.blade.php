@extends('layouts.app')
@section('title', $user->id === auth()->id() ? 'My Profile' : $user->name . "'s Profile")

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

                    <div class="ms-auto d-flex gap-2">
                        @if($user->id === auth()->id())
                            <a href="{{ route('profile.edit') }}" class="btn btn-light btn-sm">
                                <i class="bi bi-pencil"></i> Edit Profile
                            </a>
                        @else
                            @php $isFollowing = auth()->user()->following()->where('following_id', $user->id)->exists(); @endphp
                            <form method="POST" action="{{ route('users.follow', $user) }}">
                                @csrf
                                <button type="submit" class="btn btn-sm {{ $isFollowing ? 'btn-light' : 'btn-warning fw-bold' }}">
                                    {{ $isFollowing ? '✓ Following' : '+ Follow' }}
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body p-4">

                <!-- Followers / Following stats -->
                <div class="row text-center g-3 mb-3">
                    <div class="col-4">
                        <div class="p-3 bg-rc-light rounded-3">
                            <div class="fs-3 fw-bold text-rc">{{ $user->posts()->count() }}</div>
                            <div class="small text-muted">Posts</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 bg-rc-light rounded-3">
                            <div class="fs-3 fw-bold text-rc">{{ $user->followers()->count() }}</div>
                            <div class="small text-muted">Followers</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 bg-rc-light rounded-3">
                            <div class="fs-3 fw-bold text-rc">{{ $user->following()->count() }}</div>
                            <div class="small text-muted">Following</div>
                        </div>
                    </div>
                </div>

                <hr>

                <!-- Bio -->
                <h6 class="fw-bold text-rc"><i class="bi bi-person-lines-fill me-1"></i>About</h6>
                <p class="text-muted">
                    {{ $user->bio ?: 'No bio yet.' }}
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

            </div>
        </div>

        <!-- User's posts -->
        @if($user->posts()->count() > 0)
        <h5 class="fw-bold text-rc mb-3"><i class="bi bi-images me-1"></i>{{ $user->id === auth()->id() ? 'My' : $user->name . "'s" }} Plant Posts</h5>
        <div class="row g-3">
            @foreach($user->posts()->with(['likes','comments'])->latest()->get() as $post)
            <div class="col-md-6">
                <div class="card h-100">
                    <img src="{{ chr(47) . $post->image_url }}"
                         class="card-img-top" style="height:200px; object-fit:cover;" alt="post">
                    <div class="card-body d-flex flex-column">
                        <p class="card-text flex-grow-1">{{ Str::limit($post->caption, 100) }}</p>
                        <div class="d-flex align-items-center gap-3 mt-auto">
                            <small class="text-muted">{{ $post->created_at->diffForHumans() }}</small>
                            <span class="text-muted small"><i class="bi bi-heart me-1"></i>{{ $post->likes->count() }}</span>
                            <span class="text-muted small"><i class="bi bi-chat me-1"></i>{{ $post->comments->count() }}</span>
                            @if($user->id === auth()->id())
                            <form method="POST" action="{{ route('posts.destroy', $post) }}"
                                  class="ms-auto"
                                  onsubmit="return confirm('Delete this post?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger w-100">
                                    <i class="bi bi-trash me-1"></i>Delete Post
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

    </div>
</div>
@endsection
