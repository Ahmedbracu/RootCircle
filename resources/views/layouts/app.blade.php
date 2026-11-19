<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RootCircle | @yield('title', 'Home')</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --rc-green:      #2d6a4f;
            --rc-green-mid:  #40916c;
            --rc-green-light:#52b788;
            --rc-bg:         #f1f8f4;
        }

        body { background-color: var(--rc-bg); font-family: 'Segoe UI', sans-serif; }

        /* ── Navbar ── */
        .navbar-rc { background-color: var(--rc-green); }
        .navbar-rc .navbar-brand,
        .navbar-rc .nav-link { color: #fff !important; }
        .navbar-rc .nav-link:hover { color: #d8f3dc !important; }
        .navbar-rc .navbar-toggler-icon { filter: invert(1); }

        /* ── Buttons ── */
        .btn-rc          { background-color: var(--rc-green);      color: #fff; border: none; }
        .btn-rc:hover    { background-color: var(--rc-green-mid);  color: #fff; }
        .btn-rc-outline  { border: 2px solid var(--rc-green); color: var(--rc-green); background: transparent; }
        .btn-rc-outline:hover { background-color: var(--rc-green); color: #fff; }

        /* ── Cards ── */
        .card { border: none; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,.07); }
        .card-header-rc { background-color: var(--rc-green); color: #fff; border-radius: 12px 12px 0 0 !important; }

        /* ── Misc ── */
        .avatar-lg { width: 110px; height: 110px; object-fit: cover; border-radius: 50%; border: 4px solid var(--rc-green-light); }
        .avatar-sm { width: 40px;  height: 40px;  object-fit: cover; border-radius: 50%; }
        .text-rc   { color: var(--rc-green) !important; }
        .bg-rc-light { background-color: #d8f3dc; }
        .post-image { width: 100%; max-height: 420px; object-fit: cover; border-radius: 8px; }
    </style>

    @stack('styles')
</head>
<body>

<!-- ══════════ Navbar ══════════ -->
<nav class="navbar navbar-expand-lg navbar-rc shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold fs-4" href="{{ route('dashboard') }}">
            🌿 RootCircle
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            @auth
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'fw-bold' : '' }}"
                       href="{{ route('dashboard') }}">
                        <i class="bi bi-house-door"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('posts.*') ? 'fw-bold' : '' }}"
                       href="{{ route('posts.index') }}">
                        <i class="bi bi-images"></i> Plant Posts
                    </a>
                </li>
                @if(auth()->user()?->role === 'admin')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.*') ? 'fw-bold' : '' }}"
                       href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-shield-check"></i> Admin
                    </a>
                </li>
                @endif
            </ul>

            <ul class="navbar-nav ms-auto align-items-center gap-2">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('profile.show') }}">
                        @if(auth()->user()->profile_picture)
                            <img src="{{ chr(47) . auth()->user()->profile_picture }}"
                                 class="avatar-sm me-1" alt="avatar">
                        @else
                            <i class="bi bi-person-circle fs-5"></i>
                        @endif
                        {{ auth()->user()->name }}
                    </a>
                </li>
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-rc-outline text-white border-white">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
            @endauth
        </div>
    </div>
</nav>
<!-- ══════════════════════════════ -->

<main class="container py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @yield('content')
</main>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
