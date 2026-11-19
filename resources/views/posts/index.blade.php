@extends('layouts.app')
@section('title', 'Plant Posts')

@push('styles')
<style>
.like-btn.liked { color: #e74c3c !important; border-color: #e74c3c !important; }
.comment-section { border-top: 1px solid #eee; }
.comment-item { background: #f8fdf9; border-radius: 8px; }
</style>
@endpush

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
                    <!-- Author row -->
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <a href="{{ route('users.profile', $post->user) }}" class="d-flex align-items-center gap-2 text-decoration-none text-dark">
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
                        </a>

                        <!-- Follow button (only for other users) -->
                        @if($post->user->id !== auth()->id())
                            @php $isFollowing = auth()->user()->following()->where('following_id', $post->user->id)->exists(); @endphp
                            <form method="POST" action="{{ route('users.follow', $post->user) }}" class="ms-auto">
                                @csrf
                                <button type="submit" class="btn btn-sm {{ $isFollowing ? 'btn-rc' : 'btn-rc-outline' }}" style="font-size:.75rem; padding:2px 10px;">
                                    {{ $isFollowing ? '✓ Following' : '+ Follow' }}
                                </button>
                            </form>
                        @endif
                    </div>

                    <!-- Caption -->
                    <p class="card-text flex-grow-1">{{ $post->caption }}</p>

                    @if($post->user->location)
                        <div class="text-muted small">
                            <i class="bi bi-geo-alt me-1"></i>{{ $post->user->location }}
                        </div>
                    @endif

                    <!-- Like & Comment counts -->
                    <div class="d-flex align-items-center gap-3 mt-2 mb-2">
                        <!-- Like button (AJAX) -->
                        <button class="btn btn-sm btn-outline-secondary like-btn {{ $post->isLikedBy(auth()->user()) ? 'liked' : '' }}"
                                data-post-id="{{ $post->id }}"
                                onclick="toggleLike({{ $post->id }}, this)">
                            <i class="bi bi-heart{{ $post->isLikedBy(auth()->user()) ? '-fill' : '' }} me-1"></i>
                            <span class="like-count">{{ $post->likes()->count() }}</span>
                        </button>
                        <!-- Comment count toggle -->
                        <button class="btn btn-sm btn-outline-secondary" type="button"
                                data-bs-toggle="collapse" data-bs-target="#comments-{{ $post->id }}">
                            <i class="bi bi-chat me-1"></i>{{ $post->comments()->count() }}
                        </button>
                        <!-- Delete (own posts only) -->
                        @if($post->user_id === auth()->id())
                            <form method="POST" action="{{ route('posts.destroy', $post) }}" class="ms-auto"
                                  onsubmit="return confirm('Delete this post?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        @endif
                    </div>

                    <!-- Comments section (collapsible) -->
                    <div class="collapse comment-section pt-2" id="comments-{{ $post->id }}">
                        <!-- Existing comments -->
                        @foreach($post->comments()->with('user')->latest()->get() as $comment)
                        <div class="comment-item p-2 mb-1 d-flex gap-2 align-items-start">
                            <div class="fw-semibold small text-rc" style="min-width:70px;">{{ $comment->user->name }}</div>
                            <div class="small flex-grow-1">{{ $comment->body }}</div>
                            @if($comment->user_id === auth()->id())
                            <form method="POST" action="{{ route('comments.destroy', $comment) }}">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm p-0 text-danger" title="Delete"><i class="bi bi-x"></i></button>
                            </form>
                            @endif
                        </div>
                        @endforeach

                        <!-- Add comment form -->
                        <form method="POST" action="{{ route('comments.store', $post) }}" class="d-flex gap-2 mt-2">
                            @csrf
                            <input type="text" name="body" class="form-control form-control-sm"
                                   placeholder="Add a comment…" required maxlength="1000">
                            <button type="submit" class="btn btn-sm btn-rc">Post</button>
                        </form>
                    </div>
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

@push('scripts')
<script>
function toggleLike(postId, btn) {
    fetch(`/posts/${postId}/like`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        }
    })
    .then(r => r.json())
    .then(data => {
        btn.querySelector('.like-count').textContent = data.count;
        const icon = btn.querySelector('i');
        if (data.liked) {
            btn.classList.add('liked');
            icon.className = 'bi bi-heart-fill me-1';
        } else {
            btn.classList.remove('liked');
            icon.className = 'bi bi-heart me-1';
        }
    });
}
</script>
@endpush
