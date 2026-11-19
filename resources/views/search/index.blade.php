@extends('layouts.app')
@section('title', 'Search')

@push('styles')
<style>
.search-hero {
    background: linear-gradient(135deg, var(--rc-green) 0%, var(--rc-green-mid) 100%);
    border-radius: 16px;
    padding: 2rem;
    color: #fff;
    margin-bottom: 1.5rem;
}
.search-hero .form-control {
    border: none;
    border-radius: 50px;
    padding: .65rem 1.25rem;
    font-size: 1rem;
}
.search-hero .btn-search {
    border-radius: 50px;
    padding: .65rem 1.5rem;
    background: #fff;
    color: var(--rc-green);
    font-weight: 600;
    border: none;
}
.search-hero .btn-search:hover { background: #d8f3dc; }
.type-pill { cursor: pointer; }
.type-pill.active { background-color: var(--rc-green) !important; color: #fff !important; }
.result-section-title {
    font-size: 1rem;
    font-weight: 700;
    color: var(--rc-green);
    border-bottom: 2px solid #d8f3dc;
    padding-bottom: .4rem;
    margin-bottom: 1rem;
}
.user-card:hover { transform: translateY(-2px); transition: .2s; }
.plant-card:hover { transform: translateY(-2px); transition: .2s; }
.post-thumb { width: 100%; height: 170px; object-fit: cover; border-radius: 8px; }
</style>
@endpush

@section('content')

{{-- ── Search Hero ── --}}
<div class="search-hero">
    <h4 class="fw-bold mb-1"><i class="bi bi-search me-2"></i>Search & Discover</h4>
    <p class="mb-3 opacity-75">Find users, plants, and community posts by keyword.</p>

    <form action="{{ route('search.index') }}" method="GET" id="searchForm">
        <div class="d-flex gap-2 flex-wrap">
            <input type="text"
                   name="q"
                   id="searchInput"
                   class="form-control flex-grow-1"
                   placeholder="Search users, plants, posts…"
                   value="{{ $query }}"
                   autocomplete="off">
            <input type="hidden" name="type" id="typeInput" value="{{ $type }}">
            <button type="submit" class="btn btn-search">
                <i class="bi bi-search me-1"></i>Search
            </button>
        </div>

        {{-- Type filter pills --}}
        <div class="d-flex gap-2 mt-3 flex-wrap">
            @foreach(['all' => '🔍 All', 'users' => '👥 Users', 'plants' => '🌿 Plants', 'posts' => '📸 Posts'] as $key => $label)
                <span class="badge rounded-pill border border-white type-pill px-3 py-2
                             {{ $type === $key ? 'active' : 'text-white' }}"
                      style="{{ $type !== $key ? 'background:rgba(255,255,255,.2)' : '' }}"
                      onclick="setType('{{ $key }}')">
                    {{ $label }}
                </span>
            @endforeach
        </div>
    </form>
</div>

{{-- ── Results ── --}}
@if($query === '')
    <div class="text-center py-5 text-muted">
        <div style="font-size:4rem;">🔍</div>
        <h5 class="mt-3">Type something to search</h5>
        <p>Search for community members, plants, or posts by keyword.</p>
    </div>

@elseif($users->isEmpty() && $plants->isEmpty() && $posts->isEmpty())
    <div class="text-center py-5 text-muted">
        <div style="font-size:4rem;">😕</div>
        <h5 class="mt-3">No results for "{{ $query }}"</h5>
        <p>Try a different keyword or broaden your search type.</p>
    </div>

@else
    {{-- ── Users ── --}}
    @if($users->isNotEmpty())
    <div class="mb-4">
        <div class="result-section-title">
            <i class="bi bi-people me-1"></i>Users
            <span class="text-muted fw-normal">({{ $users->count() }} found)</span>
        </div>
        <div class="row g-3">
            @foreach($users as $u)
            <div class="col-sm-6 col-md-4 col-lg-3">
                <a href="{{ route('users.profile', $u) }}" class="text-decoration-none">
                    <div class="card user-card text-center p-3 h-100">
                        @if($u->profile_picture)
                            <img src="{{ '/' . $u->profile_picture }}"
                                 class="rounded-circle mx-auto mb-2"
                                 style="width:64px; height:64px; object-fit:cover;" alt="avatar">
                        @else
                            <div class="rounded-circle bg-rc-light mx-auto mb-2 d-flex align-items-center justify-content-center"
                                 style="width:64px; height:64px; font-size:1.6rem;">🌿</div>
                        @endif
                        <div class="fw-semibold text-dark">{{ $u->name }}</div>
                        @if($u->location)
                            <div class="text-muted small">
                                <i class="bi bi-geo-alt"></i> {{ $u->location }}
                            </div>
                        @endif
                        <div class="text-muted small mt-1">
                            {{ $u->followers()->count() }} followers
                        </div>
                        @if($u->id !== auth()->id())
                            @php $isFollowing = auth()->user()->following()->where('following_id', $u->id)->exists(); @endphp
                            <form method="POST" action="{{ route('users.follow', $u) }}" class="mt-2">
                                @csrf
                                <button type="submit" class="btn btn-sm {{ $isFollowing ? 'btn-rc' : 'btn-rc-outline' }} w-100" style="font-size:.75rem;">
                                    {{ $isFollowing ? '✓ Following' : '+ Follow' }}
                                </button>
                            </form>
                        @endif
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ── Plants ── --}}
    @if($plants->isNotEmpty())
    <div class="mb-4">
        <div class="result-section-title">
            <i class="bi bi-flower1 me-1"></i>Plants
            <span class="text-muted fw-normal">({{ $plants->count() }} found)</span>
        </div>
        <div class="row g-3">
            @foreach($plants as $plant)
            <div class="col-sm-6 col-md-4 col-lg-3">
                <a href="{{ route('plants.show', $plant) }}" class="text-decoration-none">
                    <div class="card plant-card h-100">
                        @if($plant->photo_url)
                            <img src="{{ '/' . $plant->photo_url }}"
                                 class="card-img-top" style="height:140px; object-fit:cover;" alt="{{ $plant->name }}">
                        @else
                            <div class="bg-rc-light d-flex align-items-center justify-content-center"
                                 style="height:140px; font-size:3rem; border-radius: 12px 12px 0 0;">🌿</div>
                        @endif
                        <div class="card-body p-3">
                            <div class="fw-semibold text-dark">{{ $plant->name }}</div>
                            @if($plant->species)
                                <div class="text-muted small fst-italic">{{ $plant->species }}</div>
                            @endif
                            <div class="text-muted small mt-1">
                                <i class="bi bi-person me-1"></i>{{ $plant->user->name }}
                            </div>
                            @if($plant->condition)
                                <span class="badge mt-1"
                                      style="background:#d8f3dc; color:var(--rc-green); font-size:.7rem;">
                                    {{ ucfirst($plant->condition) }}
                                </span>
                            @endif
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ── Posts ── --}}
    @if($posts->isNotEmpty())
    <div class="mb-4">
        <div class="result-section-title">
            <i class="bi bi-images me-1"></i>Posts
            <span class="text-muted fw-normal">({{ $posts->count() }} found)</span>
        </div>
        <div class="row g-3">
            @foreach($posts as $post)
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="card h-100">
                    <img src="{{ '/' . $post->image_url }}" class="post-thumb card-img-top" alt="post">
                    <div class="card-body p-3">
                        <p class="small mb-2">{{ Str::limit($post->caption, 90) }}</p>
                        <div class="d-flex align-items-center gap-2">
                            @if($post->user->profile_picture)
                                <img src="{{ '/' . $post->user->profile_picture }}" class="avatar-sm" alt="avatar">
                            @else
                                <div class="avatar-sm bg-rc-light d-flex align-items-center justify-content-center" style="font-size:.9rem;">🌿</div>
                            @endif
                            <div>
                                <a href="{{ route('users.profile', $post->user) }}" class="fw-semibold small text-dark text-decoration-none">
                                    {{ $post->user->name }}
                                </a>
                                <div class="text-muted" style="font-size:.7rem;">{{ $post->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                        <div class="d-flex gap-3 mt-2 text-muted small">
                            <span><i class="bi bi-heart me-1"></i>{{ $post->likes->count() }}</span>
                            <span><i class="bi bi-chat me-1"></i>{{ $post->comments->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

@endif

@endsection

@push('scripts')
<script>
function setType(type) {
    document.getElementById('typeInput').value = type;
    // If there's already a query, submit the form immediately
    if (document.getElementById('searchInput').value.trim() !== '') {
        document.getElementById('searchForm').submit();
    } else {
        // Just update pill highlight
        document.querySelectorAll('.type-pill').forEach(el => {
            el.classList.remove('active');
            el.style.background = 'rgba(255,255,255,.2)';
        });
        event.target.classList.add('active');
        event.target.style.background = '';
    }
}
</script>
@endpush
