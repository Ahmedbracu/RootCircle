@extends('layouts.app')
@section('title', 'Create Post')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header-rc p-3">
                <h5 class="mb-0 text-white fw-bold">
                    <i class="bi bi-camera me-2"></i>Share a Plant Post
                </h5>
            </div>
            <div class="card-body p-4">

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Image upload -->
                    <div class="mb-4">
                        <label for="image" class="form-label fw-semibold">
                            <i class="bi bi-image me-1 text-rc"></i>Plant Photo <span class="text-danger">*</span>
                        </label>

                        <!-- Drop zone -->
                        <div id="dropZone"
                             class="border border-2 border-dashed rounded-3 p-5 text-center"
                             style="cursor:pointer; border-color:#52b788 !important;">
                            <div id="dropPlaceholder">
                                <div style="font-size:2.5rem;">🌿</div>
                                <p class="text-muted mb-1">Click or drag a photo here</p>
                                <p class="text-muted small">JPG, PNG or WEBP · Max 5MB</p>
                            </div>
                            <img id="imagePreview" src="" alt="Preview"
                                 class="d-none rounded-3" style="max-height:300px; max-width:100%; object-fit:contain;">
                        </div>

                        <input type="file" id="image" name="image"
                               class="form-control mt-2 @error('image') is-invalid @enderror"
                               accept="image/*" required>
                        @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Caption -->
                    <div class="mb-4">
                        <label for="caption" class="form-label fw-semibold">
                            <i class="bi bi-chat-left-text me-1 text-rc"></i>Caption <span class="text-danger">*</span>
                        </label>
                        <textarea id="caption" name="caption" rows="4"
                                  class="form-control @error('caption') is-invalid @enderror"
                                  placeholder="Tell the community about this plant — what species is it, how are you caring for it, any fun facts?">{{ old('caption') }}</textarea>
                        <div class="form-text text-end"><span id="charCount">0</span>/2000</div>
                        @error('caption') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-rc px-4">
                            <i class="bi bi-send me-1"></i>Publish Post
                        </button>
                        <a href="{{ route('posts.index') }}" class="btn btn-secondary px-4">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Image preview on file select
    const imageInput   = document.getElementById('image');
    const preview      = document.getElementById('imagePreview');
    const placeholder  = document.getElementById('dropPlaceholder');
    const dropZone     = document.getElementById('dropZone');
    const captionField = document.getElementById('caption');
    const charCount    = document.getElementById('charCount');

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

    imageInput.addEventListener('change', () => showPreview(imageInput.files[0]));

    // Click on drop zone opens file picker
    dropZone.addEventListener('click', () => imageInput.click());

    // Drag & drop
    dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('bg-rc-light'); });
    dropZone.addEventListener('dragleave', ()  => dropZone.classList.remove('bg-rc-light'));
    dropZone.addEventListener('drop', e => {
        e.preventDefault();
        dropZone.classList.remove('bg-rc-light');
        const file = e.dataTransfer.files[0];
        if (file) {
            const dt = new DataTransfer();
            dt.items.add(file);
            imageInput.files = dt.files;
            showPreview(file);
        }
    });

    // Character counter
    captionField.addEventListener('input', () => {
        charCount.textContent = captionField.value.length;
    });
</script>
@endpush
