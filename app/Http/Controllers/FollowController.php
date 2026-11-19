<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function toggle(User $user)
    {
        $authUser = Auth::user();

        if ($authUser->id === $user->id) {
            return back()->with('error', 'You cannot follow yourself.');
        }

        if ($authUser->following()->where('following_id', $user->id)->exists()) {
            $authUser->following()->detach($user->id);
            $message = 'Unfollowed ' . $user->name;
        } else {
            $authUser->following()->attach($user->id);
            $message = 'Now following ' . $user->name;
        }

        return back()->with('success', $message);
    }
}
