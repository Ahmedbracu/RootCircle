@extends('layouts.app')
@section('title', 'Login')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="text-center mb-4">
            <div style="font-size:3rem;">🌿</div>
            <h3 class="fw-bold text-rc">Welcome to RootCircle</h3>
            <p class="text-muted">A community for plant enthusiasts</p>
        </div>
        <div class="card">
            <div class="card-header-rc p-3">
                <h5 class="mb-0 text-white fw-bold"><i class="bi bi-box-arrow-in-right me-2"></i>Log In</h5>
            </div>
            <div class="card-body p-4">

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Email</label>
                        <input id="email" name="email" type="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" required autofocus>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-4">
                        <label for="password" class="form-label fw-semibold">Password</label>
                        <input id="password" name="password" type="password"
                               class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn btn-rc w-100">Log In</button>
                </form>

                <hr>
                <p class="text-center mb-0">
                    New to RootCircle?
                    <a href="{{ route('register') }}" class="text-rc fw-semibold">Create an account</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
