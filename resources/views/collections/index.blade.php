@extends('layouts.app')
@section('title', 'My Garden')

@section('content')
{{-- Page header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-rc mb-0"><i class="bi bi-collection me-2"></i>My Garden</h3>
        <p class="text-muted mb-0">Your personal plant collections</p>
    </div>
    <a href="{{ route('collections.create') }}" class="btn btn-rc">
        <i class="bi bi-plus-circle me-1"></i>New Collection
    </a>
</div>

@if($collections->isEmpty())
    <div class="text-center py-5">
        <div style="font-size:4rem;">🪴</div>
        <h5 class="text-muted mt-3">No collections yet</h5>
        <p class="text-muted">Create your first plant collection to get started.</p>
        <a href="{{ route('collections.create') }}" class="btn btn-rc">Create Collection</a>
    </div>
@else
    <div class="row g-4">
        @foreach($collections as $collection)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100">
                {{-- Coloured header strip --}}
                <div class="card-header-rc p-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 text-white fw-bold text-truncate pe-2">
                        <i class="bi bi-collection-fill me-2"></i>{{ $collection->name }}
                    </h6>
                    <span class="badge bg-white text-rc">{{ $collection->plants_count }} plant{{ $collection->plants_count === 1 ? '' : 's' }}</span>
                </div>

                <div class="card-body d-flex flex-column">
                    <p class="text-muted small flex-grow-1">
                        {{ $collection->description ?: 'No description.' }}
                    </p>
                    <p class="text-muted" style="font-size:.75rem;">
                        <i class="bi bi-clock me-1"></i>Created {{ $collection->created_at->diffForHumans() }}
                    </p>

                    <div class="d-flex gap-2 mt-auto">
                        <a href="{{ route('collections.show', $collection) }}" class="btn btn-rc btn-sm flex-grow-1">
                            <i class="bi bi-eye me-1"></i>View
                        </a>
                        <a href="{{ route('collections.edit', $collection) }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form method="POST" action="{{ route('collections.destroy', $collection) }}"
                              onsubmit="return confirm('Delete «{{ $collection->name }}» and all its plants?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-outline-danger btn-sm">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endif
@endsection
