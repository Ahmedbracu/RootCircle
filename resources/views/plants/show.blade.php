@extends('layouts.app')
@section('title', $plant->name)

@section('content')
{{-- Breadcrumb --}}
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('collections.index') }}" class="text-rc">My Garden</a></li>
        <li class="breadcrumb-item"><a href="{{ route('collections.show', $plant->collection_id) }}" class="text-rc">{{ $plant->collection->name }}</a></li>
        <li class="breadcrumb-item active">{{ $plant->name }}</li>
    </ol>
</nav>

<div class="row g-4">

    {{-- ── LEFT: Plant info card ────────────────────────────── --}}
    <div class="col-lg-4">
        <div class="card mb-3">
            {{-- Plant photo --}}
            @if($plant->photo_url)
                <img src="{{ '/' . $plant->photo_url }}"
                     class="card-img-top" style="height:260px; object-fit:cover;" alt="{{ $plant->name }}">
            @else
                <div class="d-flex align-items-center justify-content-center bg-rc-light"
                     style="height:200px; font-size:4rem; border-radius:12px 12px 0 0;">🌿</div>
            @endif

            <div class="card-body">
                <h4 class="fw-bold text-rc mb-1">{{ $plant->name }}</h4>
                @if($plant->species)
                    <p class="text-muted fst-italic mb-2">{{ $plant->species }}</p>
                @endif

                @if($plant->condition)
                    @php
                        $condColor = match($plant->condition) {
                            'Excellent'  => 'success',
                            'Good'       => 'primary',
                            'Fair'       => 'warning',
                            'Struggling' => 'danger',
                            'Recovering' => 'danger',
                            default      => 'secondary',
                        };
                    @endphp
                    <span class="badge bg-{{ $condColor }} mb-3">{{ $plant->condition }}</span>
                @endif

                @if($plant->description)
                    <p class="card-text small text-muted mb-3">{{ $plant->description }}</p>
                @endif

                <ul class="list-unstyled small text-muted mb-3">
                    @if($plant->acquisition_date)
                    <li class="mb-1">
                        <i class="bi bi-calendar-check text-rc me-2"></i>
                        Acquired {{ $plant->acquisition_date->format('d M Y') }}
                    </li>
                    @endif
                    <li class="mb-1">
                        <i class="bi bi-graph-up text-rc me-2"></i>
                        {{ $growthLogs->count() }} growth {{ Str::plural('entry', $growthLogs->count()) }}
                    </li>
                    <li>
                        <i class="bi bi-clock text-rc me-2"></i>
                        Added {{ $plant->created_at->diffForHumans() }}
                    </li>
                </ul>

                <div class="d-flex gap-2">
                    <a href="{{ route('plants.edit', $plant) }}" class="btn btn-rc btn-sm flex-grow-1">
                        <i class="bi bi-pencil me-1"></i>Edit Plant
                    </a>
                    <form method="POST" action="{{ route('plants.destroy', $plant) }}"
                          onsubmit="return confirm('Remove {{ addslashes($plant->name) }}? This cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ── RIGHT: Growth Tracking ───────────────────────────── --}}
    <div class="col-lg-8">

        {{-- Add growth log form --}}
        <div class="card mb-4">
            <div class="card-header-rc p-3">
                <h6 class="mb-0 text-white fw-bold">
                    <i class="bi bi-plus-circle me-2"></i>Log Growth Entry
                </h6>
            </div>
            <div class="card-body p-4">

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('growth-logs.store', $plant) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">

                        {{-- Date --}}
                        <div class="col-md-4">
                            <label for="logged_at" class="form-label fw-semibold">
                                <i class="bi bi-calendar me-1 text-rc"></i>Date <span class="text-danger">*</span>
                            </label>
                            <input type="date" id="logged_at" name="logged_at"
                                   class="form-control @error('logged_at') is-invalid @enderror"
                                   value="{{ old('logged_at', date('Y-m-d')) }}"
                                   max="{{ date('Y-m-d') }}" required>
                            @error('logged_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Photo --}}
                        <div class="col-md-8">
                            <label for="growth_photo" class="form-label fw-semibold">
                                <i class="bi bi-camera me-1 text-rc"></i>Photo <span class="text-muted small">(optional)</span>
                            </label>
                            <input type="file" id="growth_photo" name="photo"
                                   class="form-control @error('photo') is-invalid @enderror"
                                   accept="image/*">
                            @error('photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Note --}}
                        <div class="col-12">
                            <label for="note" class="form-label fw-semibold">
                                <i class="bi bi-journal-text me-1 text-rc"></i>Observation Note
                            </label>
                            <textarea id="note" name="note" rows="2"
                                      class="form-control @error('note') is-invalid @enderror"
                                      placeholder="New leaves, root growth, any issues?">{{ old('note') }}</textarea>
                            @error('note')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-rc">
                                <i class="bi bi-plus-circle me-1"></i>Add Entry
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Growth log timeline --}}
        <h5 class="fw-bold text-rc mb-3">
            <i class="bi bi-graph-up me-2"></i>Growth History
            <span class="badge bg-rc-light text-rc ms-1" style="font-size:.8rem;">{{ $growthLogs->count() }}</span>
        </h5>

        @if($growthLogs->isEmpty())
            <div class="card">
                <div class="card-body text-center py-4">
                    <div style="font-size:2.5rem;">📅</div>
                    <p class="text-muted mt-2 mb-0">No growth entries yet. Log the first one above!</p>
                </div>
            </div>
        @else
            <div class="position-relative ps-4" style="border-left: 3px solid #52b788;">
                @foreach($growthLogs as $log)
                <div class="mb-4 position-relative">
                    {{-- Timeline dot --}}
                    <div class="position-absolute bg-white rounded-circle border border-2 d-flex align-items-center justify-content-center"
                         style="width:30px; height:30px; left:-42px; top:6px; border-color:#52b788 !important; font-size:.85rem;">
                        {{ $log->photo_url ? '📸' : '📝' }}
                    </div>

                    <div class="card">
                        @if($log->photo_url)
                            <img src="{{ '/' . $log->photo_url }}"
                                 class="card-img-top" style="height:220px; object-fit:cover; border-radius:12px 12px 0 0;" alt="Growth log photo">
                        @endif
                        <div class="card-body py-3 px-3">
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <div>
                                    <span class="fw-semibold text-rc">
                                        <i class="bi bi-calendar2-check me-1"></i>
                                        {{ \Carbon\Carbon::parse($log->logged_at)->format('d M Y') }}
                                    </span>
                                    <span class="text-muted small ms-2">
                                        ({{ \Carbon\Carbon::parse($log->logged_at)->diffForHumans() }})
                                    </span>
                                </div>
                                {{-- Delete entry --}}
                                <form method="POST"
                                      action="{{ route('growth-logs.destroy', ['plant' => $plant->id, 'growthLog' => $log->id]) }}"
                                      onsubmit="return confirm('Delete this growth entry?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2">
                                        <i class="bi bi-trash" style="font-size:.8rem;"></i>
                                    </button>
                                </form>
                            </div>

                            @if($log->note)
                                <p class="mt-2 mb-0 small">{{ $log->note }}</p>
                            @else
                                <p class="mt-2 mb-0 small text-muted fst-italic">No note recorded.</p>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif

    </div>{{-- /col --}}
</div>{{-- /row --}}
@endsection
