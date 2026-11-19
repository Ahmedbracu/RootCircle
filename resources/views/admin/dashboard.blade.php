@extends('layouts.app')
@section('title', 'Admin Dashboard')

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <div style="font-size:2rem;">🛡️</div>
    <div>
        <h3 class="fw-bold mb-0 text-rc">Admin Dashboard</h3>
        <p class="text-muted mb-0">RootCircle control panel</p>
    </div>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card p-4 text-center">
            <div class="fs-1 fw-bold text-rc">{{ $stats['users'] }}</div>
            <div class="text-muted">Registered Users</div>
            <a href="{{ route('admin.users') }}" class="btn btn-rc btn-sm mt-2">Manage Users</a>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-4 text-center">
            <div class="fs-1 fw-bold text-rc">{{ $stats['posts'] }}</div>
            <div class="text-muted">Plant Posts</div>
            <a href="{{ route('admin.posts') }}" class="btn btn-rc btn-sm mt-2">Manage Posts</a>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-4 text-center">
            <div class="fs-1 fw-bold text-rc">—</div>
            <div class="text-muted">More features coming</div>
            <button class="btn btn-secondary btn-sm mt-2" disabled>Coming Soon</button>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Recent Users --}}
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header-rc p-3">
                <h6 class="mb-0 text-white fw-bold"><i class="bi bi-people me-2"></i>Recent Users</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr><th>Name</th><th>Email</th><th>Joined</th></tr>
                        </thead>
                        <tbody>
                            @forelse($recentUsers as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td class="text-muted small">{{ $user->email }}</td>
                                <td class="text-muted small">{{ $user->created_at->diffForHumans() }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center text-muted">No users yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Posts --}}
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header-rc p-3">
                <h6 class="mb-0 text-white fw-bold"><i class="bi bi-images me-2"></i>Recent Posts</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr><th>Author</th><th>Caption</th><th>Posted</th></tr>
                        </thead>
                        <tbody>
                            @forelse($recentPosts as $post)
                            <tr>
                                <td>{{ $post->user->name }}</td>
                                <td class="small">{{ Str::limit($post->caption, 40) }}</td>
                                <td class="text-muted small">{{ $post->created_at->diffForHumans() }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center text-muted">No posts yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
