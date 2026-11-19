@extends('layouts.app')
@section('title', 'New Collection')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header-rc p-3">
                <h5 class="mb-0 text-white fw-bold">
                    <i class="bi bi-plus-circle me-2"></i>Create New Collection
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

                <form method="POST" action="{{ route('collections.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">
                            <i class="bi bi-collection me-1 text-rc"></i>Collection Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="name" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}"
                               placeholder="e.g. Indoor Plants, Succulents, Herb Garden"
                               required autofocus>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label fw-semibold">
                            <i class="bi bi-chat-left-text me-1 text-rc"></i>Description
                        </label>
                        <textarea id="description" name="description" rows="4"
                                  class="form-control @error('description') is-invalid @enderror"
                                  placeholder="Describe what plants are in this collection...">{{ old('description') }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-rc px-4">
                            <i class="bi bi-check-lg me-1"></i>Create Collection
                        </button>
                        <a href="{{ route('collections.index') }}" class="btn btn-secondary px-4">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
