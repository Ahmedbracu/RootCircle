<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function index(Request $request): View
    {
        $query = trim($request->input('q', ''));
        $type  = $request->input('type', 'all'); // all | users | plants | posts

        $users  = collect();
        $plants = collect();
        $posts  = collect();

        if ($query !== '') {
            if (in_array($type, ['all', 'users'])) {
                $users = User::where('name', 'like', "%{$query}%")
                    ->orWhere('bio', 'like', "%{$query}%")
                    ->orWhere('location', 'like', "%{$query}%")
                    ->latest()
                    ->take(20)
                    ->get();
            }

            if (in_array($type, ['all', 'plants'])) {
                $plants = Plant::with(['user', 'collection'])
                    ->where('name', 'like', "%{$query}%")
                    ->orWhere('species', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                    ->latest()
                    ->take(20)
                    ->get();
            }

            if (in_array($type, ['all', 'posts'])) {
                $posts = Post::with('user')
                    ->where('caption', 'like', "%{$query}%")
                    ->latest()
                    ->take(20)
                    ->get();
            }
        }

        return view('search.index', compact('query', 'type', 'users', 'plants', 'posts'));
    }
}
