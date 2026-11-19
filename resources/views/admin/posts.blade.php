@extends('layouts.app')
@section('title', 'Manage Posts')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-rc mb-0"><i class="bi bi-images me-2"></i>Manage Posts</h3>
        <p class="text-muted mb-0">{{ $posts->total() }} total posts</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm">← Back</a>
</div>

<div class="row g-3">
    @forelse($posts as $post)
    <div class="col-md-4">
        <div class="card h-100">
            <img src="{{ chr(47) . $post->image_url }}"
                 class="card-img-top" style="height:180px; object-fit:cover;" alt="post">
            <div class="card-body d-flex flex-column">
                <p class="card-text flex-grow-1 small">{{ Str::limit($post->caption, 80) }}</p>
                <div class="text-muted small mb-2">
                    <i class="bi bi-person me-1"></i>{{ $post->user->name }}<br>
                    <i class="bi bi-clock me-1"></i>{{ $post->created_at->diffForHumans() }}
                </div>
                <form method="POST" action="{{ route('admin.posts.delete', $post) }}"
                      onsubmit="return confirm('Delete this post?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger w-100">
                        <i class="bi bi-trash me-1"></i>Remove Post
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5 text-muted">No posts yet.</div>
    @endforelse
</div>

<div class="mt-4">{{ $posts->links() }}</div>
@endsection
