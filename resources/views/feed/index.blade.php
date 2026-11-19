@extends('layouts.app')
@section('title', 'My Feed')

@push('styles')
<style>
.feed-header {
    background: linear-gradient(135deg, var(--rc-green) 0%, var(--rc-green-mid) 100%);
    border-radius: 16px;
    padding: 1.5rem 2rem;
    color: #fff;
    margin-bottom: 1.5rem;
}
.tab-pill {
    border-radius: 50px;
    padding: .4rem 1.25rem;
    font-weight: 600;
    font-size: .9rem;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    transition: .2s;
}
.tab-pill.active {
    background: #fff;
    color: var(--rc-green);
}
.tab-pill:not(.active) {
    background: rgba(255,255,255,.2);
    color: #fff;
}
.tab-pill:not(.active):hover {
    background: rgba(255,255,255,.35);
    color: #fff;
}
.feed-post-card { border-radius: 14px; overflow: hidden; }
.feed-post-img  { width: 100%; max-height: 420px; object-fit: cover; }
.like-btn.liked { color: #e74c3c !important; border-color: #e74c3c !important; }
.comment-section { border-top: 1px solid #eee; }
.comment-item    { background: #f8fdf9; border-radius: 8px; }
.empty-state     { text-align: center; padding: 4rem 1rem; color: #6c757d; }
</style>
@endpush

@section('content')

{{-- ── Feed Header & Tabs ── --}}
<div class="feed-header">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
            <h4 class="fw-bold mb-0"><i class="bi bi-rss me-2"></i>My Feed</h4>
            <p class="mb-0 opacity-75 mt-1">Your personalized plant community updates</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('feed.index', ['tab' => 'following']) }}"
               class="tab-pill {{ $tab === 'following' ? 'active' : '' }}">
                <i class="bi bi-people me-1"></i>Following
                @if($followingCount > 0)
                    <span class="badge rounded-pill ms-1"
                          style="{{ $tab === 'following' ? 'background:var(--rc-green); color:#fff' : 'background:#fff; color:var(--rc-green)' }}">
                        {{ $followingCount }}
                    </span>
                @endif
            </a>
            <a href="{{ route('feed.index', ['tab' => 'discover']) }}"
               class="tab-pill {{ $tab === 'discover' ? 'active' : '' }}">
                <i class="bi bi-compass me-1"></i>Discover
            </a>
        </div>
    </div>
</div>

{{-- ── Posts ── --}}
@if($posts->isEmpty())
    <div class="empty-state">
        @if($tab === 'following')
            <div style="font-size:4rem;">🌱</div>
            <h5 class="mt-3">Nothing in your feed yet</h5>
            <p>Follow other gardeners to see their posts here!</p>
            <a href="{{ route('search.index') }}" class="btn btn-rc mt-2">
                <i class="bi bi-search me-1"></i>Discover People to Follow
            </a>
        @else
            <div style="font-size:4rem;">🌿</div>
            <h5 class="mt-3">No community posts yet</h5>
            <p>Be the first to share a plant with the community!</p>
            <a href="{{ route('posts.create') }}" class="btn btn-rc mt-2">
                <i class="bi bi-plus-circle me-1"></i>Share a Plant
            </a>
        @endif
    </div>
@else

    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-9">

            @foreach($posts as $post)
            <div class="card feed-post-card mb-4">

                {{-- Author row --}}
                <div class="card-body pb-2">
                    <div class="d-flex align-items-center gap-3">
                        <a href="{{ route('users.profile', $post->user) }}" class="d-flex align-items-center gap-2 text-decoration-none text-dark flex-grow-1">
                            @if($post->user->profile_picture)
                                <img src="{{ '/' . $post->user->profile_picture }}" class="avatar-sm" alt="avatar">
                            @else
                                <div class="avatar-sm bg-rc-light d-flex align-items-center justify-content-center" style="font-size:1.1rem;">🌿</div>
                            @endif
                            <div>
                                <div class="fw-semibold">{{ $post->user->name }}</div>
                                <div class="text-muted" style="font-size:.75rem;">
                                    <i class="bi bi-clock me-1"></i>{{ $post->created_at->diffForHumans() }}
                                    @if($post->user->location)
                                        &nbsp;·&nbsp;<i class="bi bi-geo-alt me-1"></i>{{ $post->user->location }}
                                    @endif
                                </div>
                            </div>
                        </a>

                        {{-- Follow toggle (not own posts) --}}
                        @if($post->user->id !== auth()->id())
                            @php $isFollowing = auth()->user()->following()->where('following_id', $post->user->id)->exists(); @endphp
                            <form method="POST" action="{{ route('users.follow', $post->user) }}">
                                @csrf
                                <button type="submit"
                                        class="btn btn-sm {{ $isFollowing ? 'btn-rc' : 'btn-rc-outline' }}"
                                        style="font-size:.75rem; padding:3px 12px;">
                                    {{ $isFollowing ? '✓ Following' : '+ Follow' }}
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                {{-- Post image --}}
                <img src="{{ '/' . $post->image_url }}" class="feed-post-img" alt="plant photo">

                {{-- Caption + actions --}}
                <div class="card-body">
                    <p class="mb-3">{{ $post->caption }}</p>

                    <div class="d-flex align-items-center gap-3">
                        {{-- Like button (AJAX) --}}
                        <button class="btn btn-sm btn-outline-secondary like-btn {{ $post->isLikedBy(auth()->user()) ? 'liked' : '' }}"
                                onclick="toggleLike({{ $post->id }}, this)">
                            <i class="bi bi-heart{{ $post->isLikedBy(auth()->user()) ? '-fill' : '' }} me-1"></i>
                            <span class="like-count">{{ $post->likes->count() }}</span>
                        </button>

                        {{-- Comment toggle --}}
                        <button class="btn btn-sm btn-outline-secondary" type="button"
                                data-bs-toggle="collapse" data-bs-target="#feed-comments-{{ $post->id }}">
                            <i class="bi bi-chat me-1"></i>{{ $post->comments->count() }}
                        </button>

                        {{-- Delete (own) --}}
                        @if($post->user_id === auth()->id())
                            <form method="POST" action="{{ route('posts.destroy', $post) }}"
                                  class="ms-auto" onsubmit="return confirm('Delete this post?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        @endif
                    </div>

                    {{-- Comments (collapsible) --}}
                    <div class="collapse comment-section pt-3 mt-2" id="feed-comments-{{ $post->id }}">
                        @foreach($post->comments->sortByDesc('created_at') as $comment)
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

                        <form method="POST" action="{{ route('comments.store', $post) }}" class="d-flex gap-2 mt-2">
                            @csrf
                            <input type="text" name="body" class="form-control form-control-sm"
                                   placeholder="Add a comment…" required maxlength="1000">
                            <button type="submit" class="btn btn-sm btn-rc">Post</button>
                        </form>
                    </div>
                </div>

            </div>
            @endforeach

            {{-- Pagination --}}
            <div class="d-flex justify-content-center mt-2 mb-4">
                {{ $posts->links() }}
            </div>

        </div>
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
