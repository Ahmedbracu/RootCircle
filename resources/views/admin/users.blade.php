@extends('layouts.app')
@section('title', 'Manage Users')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-rc mb-0"><i class="bi bi-people me-2"></i>Manage Users</h3>
        <p class="text-muted mb-0">{{ $users->total() }} registered users</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm">← Back</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Location</th>
                        <th>Posts</th>
                        <th>Role</th>
                        <th>Joined</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if($user->profile_picture)
                                    <img src="{{ chr(47) . $user->profile_picture }}" class="avatar-sm" alt="">
                                @else
                                    <div class="avatar-sm bg-rc-light d-flex align-items-center justify-content-center">🌿</div>
                                @endif
                                <span class="fw-semibold">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="text-muted small">{{ $user->email }}</td>
                        <td class="text-muted small">{{ $user->location ?? '—' }}</td>
                        <td>{{ $user->posts()->count() }}</td>
                        <td>
                            <span class="badge bg-rc-light text-rc">{{ $user->role }}</span>
                        </td>
                        <td class="text-muted small">{{ $user->created_at->format('d M Y') }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.users.delete', $user) }}"
                                  onsubmit="return confirm('Delete {{ $user->name }}? This cannot be undone.')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">No users found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">{{ $users->links() }}</div>
@endsection
