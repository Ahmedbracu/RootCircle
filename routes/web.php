<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

// ── Root redirect ────────────────────────────────────────
Route::get('/', fn() => redirect()->route('login'));

// ── Storage file server (no symlink needed) ───────────────
Route::get('/storage/{path}', function (string $path) {
    if (!Storage::disk('public')->exists($path)) {
        abort(404);
    }
    return Storage::disk('public')->response($path);
})->where('path', '.*');

// ── Guest routes ─────────────────────────────────────────
Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',   [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegistration'])->name('register');
Route::post('/register',[AuthController::class, 'register']);

// ── Authenticated routes ──────────────────────────────────
Route::middleware('auth')->group(function () {

    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ── Feature 1: User Profile Management ───────────────
    Route::get('/profile',      [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile',      [ProfileController::class, 'update'])->name('profile.update');

    // ── Feature 2: Plant Posts & Photo Sharing ───────────
    Route::get('/posts',           [PostController::class, 'index'])->name('posts.index');
    Route::get('/posts/create',    [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts',          [PostController::class, 'store'])->name('posts.store');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

    // ── Feature 20: Admin Dashboard ──────────────────────
    Route::prefix('admin')->name('admin.')->middleware(AdminMiddleware::class)->group(function () {
        Route::get('/',             [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users',        [AdminController::class, 'users'])->name('users');
        Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');
        Route::get('/posts',        [AdminController::class, 'posts'])->name('posts');
        Route::delete('/posts/{post}', [AdminController::class, 'deletePost'])->name('posts.delete');
    });
});
