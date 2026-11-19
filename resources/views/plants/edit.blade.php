@extends('layouts.app')
@section('title', 'Edit Plant')

@section('content')
{{-- Breadcrumb --}}
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('collections.index') }}" class="text-rc">My Garden</a></li>
        <li class="breadcrumb-item"><a href="{{ route('collections.show', $plant->collection_id) }}" class="text-rc">{{ $plant->collection->name }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('plants.show', $plant) }}" class="text-rc">{{ $plant->name }}</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>
</nav>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header-rc p-3">
                <h5 class="mb-0 text-white fw-bold">
                    <i class="bi bi-pencil-square me-2"></i>Edit Plant
                </h5>
            </div>
            <div class="card-body p-4">

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('plants.update', $plant) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Current photo + upload new --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-image me-1 text-rc"></i>Plant Photo
                        </label>
                        <div class="text-center mb-2">
                            @if($plant->photo_url)
                                <img src="{{ '/' . $plant->photo_url }}" id="photoPreview"
                                     class="rounded-3" style="max-height:200px; max-width:100%; object-fit:contain;" alt="{{ $plant->name }}">
                            @else
                                <div class="d-flex align-items-center justify-content-center bg-rc-light rounded-3 mx-auto"
                                     style="height:100px; width:100px; font-size:2.5rem;" id="photoPlaceholder">🌿</div>
                                <img src="" id="photoPreview" class="d-none rounded-3"
                                     style="max-height:200px; max-width:100%; object-fit:contain;" alt="Preview">
                            @endif
                        </div>
                        <input type="file" id="photo" name="photo"
                               class="form-control @error('photo') is-invalid @enderror"
                               accept="image/*">
                        <div class="form-text">Leave blank to keep existing photo.</div>
                        @error('photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Collection (allow moving) --}}
                    <div class="mb-3">
                        <label for="collection_id" class="form-label fw-semibold">
                            <i class="bi bi-collection me-1 text-rc"></i>Collection
                        </label>
                        <select id="collection_id" name="collection_id"
                                class="form-select @error('collection_id') is-invalid @enderror" required>
                            @foreach($collections as $col)
                                <option value="{{ $col->id }}"
                                    {{ old('collection_id', $plant->collection_id) == $col->id ? 'selected' : '' }}>
                                    {{ $col->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('collection_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Name --}}
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">
                            <i class="bi bi-tag me-1 text-rc"></i>Plant Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="name" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $plant->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Species --}}
                    <div class="mb-3">
                        <label for="species" class="form-label fw-semibold">
                            <i class="bi bi-search me-1 text-rc"></i>Species / Variety
                        </label>
                        <input type="text" id="species" name="species"
                               class="form-control @error('species') is-invalid @enderror"
                               value="{{ old('species', $plant->species) }}">
                        @error('species')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Description --}}
                    <div class="mb-3">
                        <label for="description" class="form-label fw-semibold">
                            <i class="bi bi-chat-left-text me-1 text-rc"></i>Notes / Description
                        </label>
                        <textarea id="description" name="description" rows="3"
                                  class="form-control @error('description') is-invalid @enderror">{{ old('description', $plant->description) }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row g-3 mb-4">
                        {{-- Condition --}}
                        <div class="col-md-6">
                            <label for="condition" class="form-label fw-semibold">
                                <i class="bi bi-heart me-1 text-rc"></i>Condition
                            </label>
                            <select id="condition" name="condition" class="form-select">
                                <option value="">— Select —</option>
                                @foreach(['Excellent','Good','Fair','Struggling','Recovering'] as $c)
                                    <option value="{{ $c }}"
                                        {{ old('condition', $plant->condition) === $c ? 'selected' : '' }}>{{ $c }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Acquisition date --}}
                        <div class="col-md-6">
                            <label for="acquisition_date" class="form-label fw-semibold">
                                <i class="bi bi-calendar-check me-1 text-rc"></i>Date Acquired
                            </label>
                            <input type="date" id="acquisition_date" name="acquisition_date"
                                   class="form-control"
                                   value="{{ old('acquisition_date', $plant->acquisition_date?->format('Y-m-d')) }}"
                                   max="{{ date('Y-m-d') }}">
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-rc px-4">
                            <i class="bi bi-check-lg me-1"></i>Save Changes
                        </button>
                        <a href="{{ route('plants.show', $plant) }}" class="btn btn-secondary px-4">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('photo').addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => {
            const preview     = document.getElementById('photoPreview');
            const placeholder = document.getElementById('photoPlaceholder');
            preview.src = e.target.result;
            preview.classList.remove('d-none');
            if (placeholder) placeholder.classList.add('d-none');
        };
        reader.readAsDataURL(file);
    });
</script>
@endpush
