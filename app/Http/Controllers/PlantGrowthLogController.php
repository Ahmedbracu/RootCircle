<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use App\Models\PlantGrowthLog;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class PlantGrowthLogController extends Controller
{
    // ── Store a new growth log entry for a plant ──────────────
    public function store(Request $request, Plant $plant): RedirectResponse
    {
        if ($plant->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'note'      => ['nullable', 'string', 'max:2000'],
            'logged_at' => ['required', 'date'],
            'photo'     => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $photoUrl = null;
        if ($request->hasFile('photo')) {
            $file     = $request->file('photo');
            $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('growth_photos'), $filename);
            $photoUrl = 'growth_photos/' . $filename;
        }

        PlantGrowthLog::create([
            'plant_id'  => $plant->id,
            'note'      => $validated['note'] ?? null,
            'logged_at' => $validated['logged_at'],
            'photo_url' => $photoUrl,
        ]);

        return redirect()->route('plants.show', $plant)
            ->with('success', 'Growth log entry added! 📸');
    }

    // ── Delete a growth log entry ─────────────────────────────
    public function destroy(Plant $plant, PlantGrowthLog $growthLog): RedirectResponse
    {
        if ($plant->user_id !== Auth::id() || $growthLog->plant_id !== $plant->id) {
            abort(403);
        }

        if ($growthLog->photo_url && file_exists(public_path($growthLog->photo_url))) {
            unlink(public_path($growthLog->photo_url));
        }

        $growthLog->delete();

        return redirect()->route('plants.show', $plant)
            ->with('success', 'Growth log entry removed.');
    }
}
