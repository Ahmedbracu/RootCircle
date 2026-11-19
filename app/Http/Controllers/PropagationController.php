<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use App\Models\Propagation;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class PropagationController extends Controller
{
    // ── Start a new propagation ──────────────────────────────
    public function store(Request $request, Plant $plant): RedirectResponse
    {
        if ($plant->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'method'           => ['required', 'in:seed,cutting,division'],
            'start_date'       => ['required', 'date'],
            'root_development' => ['nullable', 'string', 'max:2000'],
            'status'           => ['nullable', 'in:in_progress,rooted,transplanted,failed'],
            'notes'            => ['nullable', 'string', 'max:2000'],
        ]);

        Propagation::create([
            'plant_id'         => $plant->id,
            'method'           => $validated['method'],
            'start_date'       => $validated['start_date'],
            'root_development' => $validated['root_development'] ?? null,
            'status'           => $validated['status'] ?? 'in_progress',
            'notes'            => $validated['notes'] ?? null,
        ]);

        return redirect()->route('plants.show', $plant)
            ->with('success', 'Propagation started! 🌱');
    }

    // ── Update a propagation record ─────────────────────────
    public function update(Request $request, Plant $plant, Propagation $propagation): RedirectResponse
    {
        if ($plant->user_id !== Auth::id() || $propagation->plant_id !== $plant->id) {
            abort(403);
        }

        $validated = $request->validate([
            'method'           => ['required', 'in:seed,cutting,division'],
            'start_date'       => ['required', 'date'],
            'root_development' => ['nullable', 'string', 'max:2000'],
            'status'           => ['nullable', 'in:in_progress,rooted,transplanted,failed'],
            'notes'            => ['nullable', 'string', 'max:2000'],
        ]);

        $propagation->update($validated);

        return redirect()->route('plants.show', $plant)
            ->with('success', 'Propagation updated.');
    }

    // ── Delete a propagation record ─────────────────────────
    public function destroy(Plant $plant, Propagation $propagation): RedirectResponse
    {
        if ($plant->user_id !== Auth::id() || $propagation->plant_id !== $plant->id) {
            abort(403);
        }

        $propagation->delete();

        return redirect()->route('plants.show', $plant)
            ->with('success', 'Propagation record removed.');
    }
}
