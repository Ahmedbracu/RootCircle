<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PlantCollectionController;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\PlantGrowthLogController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\CareScheduleController;
use App\Http\Controllers\PropagationController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\FeedController;
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

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ── Feature 9: Search & Discovery ────────────────────
    Route::get('/search', [SearchController::class, 'index'])->name('search.index');

    // ── Feature 12: Personalized News Feed ───────────────
    Route::get('/feed', [FeedController::class, 'index'])->name('feed.index');

    // ── Feature 1: User Profile Management ───────────────
    Route::get('/profile',      [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile',      [ProfileController::class, 'update'])->name('profile.update');

    // ── Feature 2: Plant Posts & Photo Sharing ───────────
    Route::get('/posts',           [PostController::class, 'index'])->name('posts.index');
    Route::get('/posts/create',    [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts',          [PostController::class, 'store'])->name('posts.store');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

    // ── Feature 4: Personal Plant Collection ─────────────
    Route::get('/collections',                       [PlantCollectionController::class, 'index'])->name('collections.index');
    Route::get('/collections/create',                [PlantCollectionController::class, 'create'])->name('collections.create');
    Route::post('/collections',                      [PlantCollectionController::class, 'store'])->name('collections.store');
    Route::get('/collections/{collection}',          [PlantCollectionController::class, 'show'])->name('collections.show');
    Route::get('/collections/{collection}/edit',     [PlantCollectionController::class, 'edit'])->name('collections.edit');
    Route::put('/collections/{collection}',          [PlantCollectionController::class, 'update'])->name('collections.update');
    Route::delete('/collections/{collection}',       [PlantCollectionController::class, 'destroy'])->name('collections.destroy');

    // ── Plants (scoped to a collection) ──────────────────
    Route::get('/collections/{collection}/plants/create', [PlantController::class, 'create'])->name('plants.create');
    Route::post('/collections/{collection}/plants',       [PlantController::class, 'store'])->name('plants.store');
    Route::get('/plants/{plant}',                         [PlantController::class, 'show'])->name('plants.show');
    Route::get('/plants/{plant}/edit',                    [PlantController::class, 'edit'])->name('plants.edit');
    Route::put('/plants/{plant}',                         [PlantController::class, 'update'])->name('plants.update');
    Route::delete('/plants/{plant}',                      [PlantController::class, 'destroy'])->name('plants.destroy');

    // ── Feature 3: Plant Growth Tracking ─────────────────
    Route::post('/plants/{plant}/growth-logs',                  [PlantGrowthLogController::class, 'store'])->name('growth-logs.store');
    Route::delete('/plants/{plant}/growth-logs/{growthLog}',    [PlantGrowthLogController::class, 'destroy'])->name('growth-logs.destroy');

    // ── Feature 5: Plant Care Management ────────────────
    Route::post('/plants/{plant}/care-schedules',                              [CareScheduleController::class, 'store'])->name('care-schedules.store');
    Route::put('/plants/{plant}/care-schedules/{careSchedule}',                [CareScheduleController::class, 'update'])->name('care-schedules.update');
    Route::patch('/plants/{plant}/care-schedules/{careSchedule}/complete',     [CareScheduleController::class, 'markComplete'])->name('care-schedules.complete');
    Route::delete('/plants/{plant}/care-schedules/{careSchedule}',             [CareScheduleController::class, 'destroy'])->name('care-schedules.destroy');

    // ── Feature 16: Plant Propagation Tracker ────────────
    Route::post('/plants/{plant}/propagations',                    [PropagationController::class, 'store'])->name('propagations.store');
    Route::put('/plants/{plant}/propagations/{propagation}',       [PropagationController::class, 'update'])->name('propagations.update');
    Route::delete('/plants/{plant}/propagations/{propagation}',    [PropagationController::class, 'destroy'])->name('propagations.destroy');

    // ── Feature 3: Community Interaction ─────────────────
    Route::post('/posts/{post}/like',              [LikeController::class, 'toggle'])->name('posts.like');
    Route::post('/posts/{post}/comments',          [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}',           [CommentController::class, 'destroy'])->name('comments.destroy');

    // ── Feature: Follow & Unfollow ────────────────────────
    Route::post('/users/{user}/follow',            [FollowController::class, 'toggle'])->name('users.follow');
    Route::get('/users/{user}/profile',            [ProfileController::class, 'showUser'])->name('users.profile');

    // ── Feature 20: Admin Dashboard ──────────────────────
    Route::prefix('admin')->name('admin.')->middleware(AdminMiddleware::class)->group(function () {
        Route::get('/',             [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users',        [AdminController::class, 'users'])->name('users');
        Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');
        Route::get('/posts',        [AdminController::class, 'posts'])->name('posts');
        Route::delete('/posts/{post}', [AdminController::class, 'deletePost'])->name('posts.delete');
    });
});
