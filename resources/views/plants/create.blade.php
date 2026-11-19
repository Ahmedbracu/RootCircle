@extends('layouts.app')
@section('title', 'Add Plant')

@section('content')
{{-- Breadcrumb --}}
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('collections.index') }}" class="text-rc">My Garden</a></li>
        <li class="breadcrumb-item"><a href="{{ route('collections.show', $collection) }}" class="text-rc">{{ $collection->name }}</a></li>
        <li class="breadcrumb-item active">Add Plant</li>
    </ol>
</nav>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header-rc p-3">
                <h5 class="mb-0 text-white fw-bold">
                    <i class="bi bi-flower1 me-2"></i>Add Plant to «{{ $collection->name }}»
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

                <form method="POST" action="{{ route('plants.store', $collection) }}" enctype="multipart/form-data">
                    @csrf

                    {{-- Photo upload --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-image me-1 text-rc"></i>Plant Photo
                        </label>
                        <div id="dropZone" class="border border-2 border-dashed rounded-3 p-4 text-center"
                             style="cursor:pointer; border-color:#52b788 !important;">
                            <div id="dropPlaceholder">
                                <div style="font-size:2rem;">🌿</div>
                                <p class="text-muted mb-0 small">Click or drag a photo here (optional)</p>
                                <p class="text-muted small mb-0">JPG, PNG, WEBP · Max 5MB</p>
                            </div>
                            <img id="imagePreview" src="" alt="Preview"
                                 class="d-none rounded-3" style="max-height:220px; max-width:100%; object-fit:contain;">
                        </div>
                        <input type="file" id="photo" name="photo"
                               class="form-control mt-2 @error('photo') is-invalid @enderror"
                               accept="image/*">
                        @error('photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Name --}}
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">
                            <i class="bi bi-tag me-1 text-rc"></i>Plant Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="name" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}"
                               placeholder="e.g. Monstera Deliciosa"
                               required autofocus>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Species --}}
                    <div class="mb-3">
                        <label for="species" class="form-label fw-semibold">
                            <i class="bi bi-search me-1 text-rc"></i>Species / Variety
                        </label>
                        <input type="text" id="species" name="species"
                               class="form-control @error('species') is-invalid @enderror"
                               value="{{ old('species') }}"
                               placeholder="e.g. Monstera deliciosa 'Thai Constellation'">
                        @error('species')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Description --}}
                    <div class="mb-3">
                        <label for="description" class="form-label fw-semibold">
                            <i class="bi bi-chat-left-text me-1 text-rc"></i>Notes / Description
                        </label>
                        <textarea id="description" name="description" rows="3"
                                  class="form-control @error('description') is-invalid @enderror"
                                  placeholder="Any notes about this plant...">{{ old('description') }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row g-3 mb-4">
                        {{-- Condition --}}
                        <div class="col-md-6">
                            <label for="condition" class="form-label fw-semibold">
                                <i class="bi bi-heart me-1 text-rc"></i>Condition
                            </label>
                            <select id="condition" name="condition" class="form-select @error('condition') is-invalid @enderror">
                                <option value="">— Select —</option>
                                @foreach(['Excellent','Good','Fair','Struggling','Recovering'] as $c)
                                    <option value="{{ $c }}" {{ old('condition') === $c ? 'selected' : '' }}>{{ $c }}</option>
                                @endforeach
                            </select>
                            @error('condition')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Acquisition date --}}
                        <div class="col-md-6">
                            <label for="acquisition_date" class="form-label fw-semibold">
                                <i class="bi bi-calendar-check me-1 text-rc"></i>Date Acquired
                            </label>
                            <input type="date" id="acquisition_date" name="acquisition_date"
                                   class="form-control @error('acquisition_date') is-invalid @enderror"
                                   value="{{ old('acquisition_date') }}"
                                   max="{{ date('Y-m-d') }}">
                            @error('acquisition_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-rc px-4">
                            <i class="bi bi-plus-circle me-1"></i>Add Plant
                        </button>
                        <a href="{{ route('collections.show', $collection) }}" class="btn btn-secondary px-4">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const photoInput  = document.getElementById('photo');
    const preview     = document.getElementById('imagePreview');
    const placeholder = document.getElementById('dropPlaceholder');
    const dropZone    = document.getElementById('dropZone');

    function showPreview(file) {
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            preview.classList.remove('d-none');
            placeholder.classList.add('d-none');
        };
        reader.readAsDataURL(file);
    }

    photoInput.addEventListener('change', () => showPreview(photoInput.files[0]));
    dropZone.addEventListener('click', () => photoInput.click());
    dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('bg-rc-light'); });
    dropZone.addEventListener('dragleave', () => dropZone.classList.remove('bg-rc-light'));
    dropZone.addEventListener('drop', e => {
        e.preventDefault();
        dropZone.classList.remove('bg-rc-light');
        const file = e.dataTransfer.files[0];
        if (file) {
            const dt = new DataTransfer();
            dt.items.add(file);
            photoInput.files = dt.files;
            showPreview(file);
        }
    });
</script>
@endpush
