<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use App\Models\PlantCollection;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PlantController extends Controller
{
    // ── Create form (scoped to a collection) ──────────────────
    public function create(PlantCollection $collection): View
    {
        $this->authoriseCollection($collection);

        return view('plants.create', compact('collection'));
    }

    // ── Store new plant ───────────────────────────────────────
    public function store(Request $request, PlantCollection $collection): RedirectResponse
    {
        $this->authoriseCollection($collection);

        $validated = $request->validate([
            'name'             => ['required', 'string', 'max:255'],
            'species'          => ['nullable', 'string', 'max:255'],
            'description'      => ['nullable', 'string', 'max:2000'],
            'condition'        => ['nullable', 'string', 'max:100'],
            'acquisition_date' => ['nullable', 'date'],
            'photo'            => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $photoUrl = null;
        if ($request->hasFile('photo')) {
            $file     = $request->file('photo');
            $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('plant_photos'), $filename);
            $photoUrl = 'plant_photos/' . $filename;
        }

        $collection->plants()->create([
            'user_id'          => Auth::id(),
            'name'             => $validated['name'],
            'species'          => $validated['species'] ?? null,
            'description'      => $validated['description'] ?? null,
            'condition'        => $validated['condition'] ?? null,
            'acquisition_date' => $validated['acquisition_date'] ?? null,
            'photo_url'        => $photoUrl,
        ]);

        return redirect()->route('collections.show', $collection)
            ->with('success', 'Plant added to your collection! 🌱');
    }

    // ── Show a single plant + its growth log ──────────────────
    public function show(Plant $plant): View
    {
        $this->authorisePlant($plant);

        $growthLogs = $plant->growthLogs()
            ->orderBy('logged_at', 'desc')
            ->get();

        return view('plants.show', compact('plant', 'growthLogs'));
    }

    // ── Edit form ─────────────────────────────────────────────
    public function edit(Plant $plant): View
    {
        $this->authorisePlant($plant);

        // All collections of this user (for moving plant between collections)
        $collections = Auth::user()->plantCollections()->get();

        return view('plants.edit', compact('plant', 'collections'));
    }

    // ── Update plant ──────────────────────────────────────────
    public function update(Request $request, Plant $plant): RedirectResponse
    {
        $this->authorisePlant($plant);

        $validated = $request->validate([
            'name'             => ['required', 'string', 'max:255'],
            'species'          => ['nullable', 'string', 'max:255'],
            'description'      => ['nullable', 'string', 'max:2000'],
            'condition'        => ['nullable', 'string', 'max:100'],
            'acquisition_date' => ['nullable', 'date'],
            'collection_id'    => ['required', 'exists:plant_collections,id'],
            'photo'            => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($plant->photo_url && file_exists(public_path($plant->photo_url))) {
                unlink(public_path($plant->photo_url));
            }
            $file     = $request->file('photo');
            $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('plant_photos'), $filename);
            $validated['photo_url'] = 'plant_photos/' . $filename;
        }

        unset($validated['photo']);

        $plant->update($validated);

        return redirect()->route('plants.show', $plant)
            ->with('success', 'Plant updated successfully.');
    }

    // ── Delete plant ──────────────────────────────────────────
    public function destroy(Plant $plant): RedirectResponse
    {
        $this->authorisePlant($plant);

        $collectionId = $plant->collection_id;

        // Delete plant photo
        if ($plant->photo_url && file_exists(public_path($plant->photo_url))) {
            unlink(public_path($plant->photo_url));
        }
        // Delete growth log photos
        foreach ($plant->growthLogs as $log) {
            if ($log->photo_url && file_exists(public_path($log->photo_url))) {
                unlink(public_path($log->photo_url));
            }
        }

        $plant->delete();

        return redirect()->route('collections.show', $collectionId)
            ->with('success', 'Plant removed from collection.');
    }

    // ── Authorisation helpers ─────────────────────────────────
    private function authoriseCollection(PlantCollection $collection): void
    {
        if ($collection->user_id !== Auth::id()) {
            abort(403);
        }
    }

    private function authorisePlant(Plant $plant): void
    {
        if ($plant->user_id !== Auth::id()) {
            abort(403);
        }
    }
}
