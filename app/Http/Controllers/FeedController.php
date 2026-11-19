<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class FeedController extends Controller
{
    /**
     * Personalized news feed:
     *  - "Following" tab: posts from users the current user follows
     *  - "Discover"  tab: all community posts (excluding already-shown following posts if on that tab)
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $tab  = $request->input('tab', 'following'); // following | discover

        // IDs of users the current user follows
        $followingIds = $user->following()->pluck('users.id');

        if ($tab === 'following') {
            $posts = Post::with(['user', 'likes', 'comments'])
                ->whereIn('user_id', $followingIds)
                ->latest()
                ->paginate(10)
                ->withQueryString();
        } else {
            // Discover: all community posts, newest first
            $posts = Post::with(['user', 'likes', 'comments'])
                ->latest()
                ->paginate(10)
                ->withQueryString();
        }

        $followingCount = $followingIds->count();

        return view('feed.index', compact('posts', 'tab', 'followingCount'));
    }
}
