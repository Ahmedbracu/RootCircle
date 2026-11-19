@extends('layouts.app')
@section('title', 'Register')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="text-center mb-4">
            <div style="font-size:3rem;">🌿</div>
            <h3 class="fw-bold text-rc">Join RootCircle</h3>
            <p class="text-muted">Start sharing your plant journey today</p>
        </div>
        <div class="card">
            <div class="card-header-rc p-3">
                <h5 class="mb-0 text-white fw-bold"><i class="bi bi-person-plus me-2"></i>Create Account</h5>
            </div>
            <div class="card-body p-4">

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">Full Name</label>
                        <input id="name" name="name" type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" required autofocus>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Email</label>
                        <input id="email" name="email" type="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold">Password</label>
                        <input id="password" name="password" type="password"
                               class="form-control @error('password') is-invalid @enderror" required>
                        <div class="form-text">Minimum 8 characters.</div>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label fw-semibold">Confirm Password</label>
                        <input id="password_confirmation" name="password_confirmation"
                               type="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-rc w-100">Create Account</button>
                </form>

                <hr>
                <p class="text-center mb-0">
                    Already have an account?
                    <a href="{{ route('login') }}" class="text-rc fw-semibold">Log in</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
