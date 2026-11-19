<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(): View
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    public function showUser(User $user): View
    {
        return view('profile.show', compact('user'));
    }

    public function edit(): View
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'                => ['required', 'string', 'max:255'],
            'bio'                 => ['nullable', 'string', 'max:1000'],
            'location'            => ['nullable', 'string', 'max:255'],
            'gardening_interests' => ['nullable', 'string', 'max:1000'],
            'profile_picture'     => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture && file_exists(public_path($user->profile_picture))) {
                unlink(public_path($user->profile_picture));
            }
            $file     = $request->file('profile_picture');
            $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
            // Save to public/profile_pictures/ — accessible at /profile_pictures/filename
            $file->move(public_path('profile_pictures'), $filename);
            $validated['profile_picture'] = 'profile_pictures/' . $filename;
        }

        $user->update($validated);

        return redirect()->route('profile.show')
            ->with('success', 'Profile updated successfully!');
    }
}
