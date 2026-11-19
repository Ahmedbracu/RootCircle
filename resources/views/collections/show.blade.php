@extends('layouts.app')
@section('title', $collection->name)

@section('content')
{{-- Breadcrumb --}}
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('collections.index') }}" class="text-rc">My Garden</a></li>
        <li class="breadcrumb-item active">{{ $collection->name }}</li>
    </ol>
</nav>

{{-- Collection header --}}
<div class="card mb-4">
    <div class="card-header-rc p-4">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
            <div>
                <h3 class="text-white fw-bold mb-1">
                    <i class="bi bi-collection-fill me-2"></i>{{ $collection->name }}
                </h3>
                @if($collection->description)
                    <p class="text-white-50 mb-0 small">{{ $collection->description }}</p>
                @endif
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('plants.create', $collection) }}" class="btn btn-light btn-sm">
                    <i class="bi bi-plus-circle me-1"></i>Add Plant
                </a>
                <a href="{{ route('collections.edit', $collection) }}" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-pencil me-1"></i>Edit
                </a>
            </div>
        </div>
    </div>
    <div class="card-body px-4 py-2">
        <span class="text-muted small">
            <i class="bi bi-calendar me-1"></i>Created {{ $collection->created_at->format('d M Y') }}
            &nbsp;·&nbsp;
            <i class="bi bi-flower1 me-1"></i><strong>{{ $plants->count() }}</strong> {{ Str::plural('plant', $plants->count()) }}
        </span>
    </div>
</div>

{{-- Plants grid --}}
@if($plants->isEmpty())
    <div class="text-center py-5">
        <div style="font-size:4rem;">🌱</div>
        <h5 class="text-muted mt-3">No plants yet</h5>
        <p class="text-muted">Add your first plant to this collection.</p>
        <a href="{{ route('plants.create', $collection) }}" class="btn btn-rc">Add Plant</a>
    </div>
@else
    <div class="row g-4">
        @foreach($plants as $plant)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100">
                {{-- Plant photo --}}
                @if($plant->photo_url)
                    <img src="{{ '/' . $plant->photo_url }}"
                         class="card-img-top" style="height:200px; object-fit:cover;" alt="{{ $plant->name }}">
                @else
                    <div class="d-flex align-items-center justify-content-center bg-rc-light"
                         style="height:200px; font-size:3.5rem; border-radius:12px 12px 0 0;">🌿</div>
                @endif

                <div class="card-body d-flex flex-column">
                    <h6 class="fw-bold text-rc mb-1">{{ $plant->name }}</h6>
                    @if($plant->species)
                        <p class="text-muted small mb-1 fst-italic">{{ $plant->species }}</p>
                    @endif

                    @if($plant->condition)
                        <span class="badge bg-rc-light text-rc mb-2" style="width:fit-content;">
                            {{ ucfirst($plant->condition) }}
                        </span>
                    @endif

                    <p class="card-text small text-muted flex-grow-1">
                        {{ Str::limit($plant->description, 80) ?: '—' }}
                    </p>

                    @if($plant->acquisition_date)
                        <p class="text-muted mb-2" style="font-size:.75rem;">
                            <i class="bi bi-calendar-check me-1"></i>Acquired {{ $plant->acquisition_date->format('d M Y') }}
                        </p>
                    @endif

                    {{-- Growth log count --}}
                    <p class="text-muted mb-2" style="font-size:.75rem;">
                        <i class="bi bi-graph-up me-1"></i>{{ $plant->growthLogs()->count() }} growth {{ Str::plural('entry', $plant->growthLogs()->count()) }}
                    </p>

                    <div class="d-flex gap-2 mt-auto">
                        <a href="{{ route('plants.show', $plant) }}" class="btn btn-rc btn-sm flex-grow-1">
                            <i class="bi bi-eye me-1"></i>View
                        </a>
                        <a href="{{ route('plants.edit', $plant) }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form method="POST" action="{{ route('plants.destroy', $plant) }}"
                              onsubmit="return confirm('Remove {{ $plant->name }} from this collection?')">
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
