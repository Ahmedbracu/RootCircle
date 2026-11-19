@extends('layouts.app')
@section('title', 'Edit Profile')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header-rc p-3">
                <h5 class="mb-0 text-white fw-bold">
                    <i class="bi bi-pencil-square me-2"></i>Edit Profile
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

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Current profile picture preview -->
                    <div class="text-center mb-4">
                        @if($user->profile_picture)
                            <img src="{{ chr(47) . $user->profile_picture }}"
                                 class="avatar-lg mb-2" alt="Current photo" id="photoPreview">
                        @else
                            <div class="avatar-lg bg-rc-light d-flex align-items-center justify-content-center mx-auto mb-2"
                                 style="font-size:2.5rem;" id="photoPlaceholder">🌿</div>
                            <img src="" class="avatar-lg mb-2 d-none" alt="Preview" id="photoPreview">
                        @endif
                        <div class="small text-muted">Profile Photo</div>
                    </div>

                    <!-- Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">
                            <i class="bi bi-person me-1 text-rc"></i>Name
                        </label>
                        <input type="text" id="name" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $user->name) }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Profile Picture -->
                    <div class="mb-3">
                        <label for="profile_picture" class="form-label fw-semibold">
                            <i class="bi bi-camera me-1 text-rc"></i>Profile Picture
                        </label>
                        <input type="file" id="profile_picture" name="profile_picture"
                               class="form-control @error('profile_picture') is-invalid @enderror"
                               accept="image/*">
                        <div class="form-text">JPG, PNG or WEBP. Max 2MB.</div>
                        @error('profile_picture') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Location -->
                    <div class="mb-3">
                        <label for="location" class="form-label fw-semibold">
                            <i class="bi bi-geo-alt me-1 text-rc"></i>Location
                        </label>
                        <input type="text" id="location" name="location"
                               class="form-control @error('location') is-invalid @enderror"
                               value="{{ old('location', $user->location) }}"
                               placeholder="e.g. Dhaka, Bangladesh">
                        @error('location') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Bio -->
                    <div class="mb-3">
                        <label for="bio" class="form-label fw-semibold">
                            <i class="bi bi-chat-left-text me-1 text-rc"></i>Bio
                        </label>
                        <textarea id="bio" name="bio" rows="4"
                                  class="form-control @error('bio') is-invalid @enderror"
                                  placeholder="Tell the community about yourself...">{{ old('bio', $user->bio) }}</textarea>
                        @error('bio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Gardening Interests -->
                    <div class="mb-4">
                        <label for="gardening_interests" class="form-label fw-semibold">
                            <i class="bi bi-flower1 me-1 text-rc"></i>Gardening Interests
                        </label>
                        <textarea id="gardening_interests" name="gardening_interests" rows="3"
                                  class="form-control @error('gardening_interests') is-invalid @enderror"
                                  placeholder="e.g. Succulents, Orchids, Vegetable Gardening, Bonsai">{{ old('gardening_interests', is_array($user->gardening_interests) ? implode(', ', $user->gardening_interests) : $user->gardening_interests) }}</textarea>
                        <div class="form-text">Separate interests with commas.</div>
                        @error('gardening_interests') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-rc px-4">
                            <i class="bi bi-check-lg me-1"></i>Save Changes
                        </button>
                        <a href="{{ route('profile.show') }}" class="btn btn-secondary px-4">
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
    // Live photo preview
    document.getElementById('profile_picture').addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => {
            const preview = document.getElementById('photoPreview');
            const placeholder = document.getElementById('photoPlaceholder');
            preview.src = e.target.result;
            preview.classList.remove('d-none');
            if (placeholder) placeholder.classList.add('d-none');
        };
        reader.readAsDataURL(file);
    });
</script>
@endpush
