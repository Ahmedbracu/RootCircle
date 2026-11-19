<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(): View
    {
        $posts = Post::with('user')->latest()->paginate(12);
        return view('posts.index', compact('posts'));
    }

    public function create(): View
    {
        return view('posts.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'caption' => ['required', 'string', 'max:2000'],
            'image'   => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $file     = $request->file('image');
        $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();

        // Save directly to public/post_images/ — accessible at /post_images/filename
        $file->move(public_path('post_images'), $filename);

        Post::create([
            'user_id'   => Auth::id(),
            'caption'   => $request->caption,
            'image_url' => 'post_images/' . $filename,
        ]);

        return redirect()->route('posts.index')
            ->with('success', 'Your plant post has been published! 🌿');
    }

    public function destroy(Post $post): RedirectResponse
    {
        if ($post->user_id !== Auth::id()) {
            abort(403);
        }
        $filePath = public_path($post->image_url);
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        $post->delete();
        return redirect()->route('posts.index')->with('success', 'Post deleted.');
    }
}
