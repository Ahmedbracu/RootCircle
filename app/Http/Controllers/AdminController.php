<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $stats = [
            'users' => User::where('role', '!=', 'admin')->count(),
            'posts' => Post::count(),
        ];

        $recentUsers = User::where('role', '!=', 'admin')
            ->latest()->take(10)->get();

        $recentPosts = Post::with('user')->latest()->take(10)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentPosts'));
    }

    public function users(): View
    {
        $users = User::where('role', '!=', 'admin')->latest()->paginate(20);
        return view('admin.users', compact('users'));
    }

    public function deleteUser(User $user)
    {
        if ($user->role === 'admin') abort(403);
        $user->delete();
        return back()->with('success', 'User deleted.');
    }

    public function posts(): View
    {
        $posts = Post::with('user')->latest()->paginate(20);
        return view('admin.posts', compact('posts'));
    }

    public function deletePost(Post $post)
    {
        $post->delete();
        return back()->with('success', 'Post deleted.');
    }
}
