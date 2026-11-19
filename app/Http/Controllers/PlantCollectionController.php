<?php

namespace App\Http\Controllers;

use App\Models\PlantCollection;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PlantCollectionController extends Controller
{
    // ── Index: list the authenticated user's collections ─────
    public function index(): View
    {
        $collections = Auth::user()
            ->plantCollections()
            ->withCount('plants')
            ->latest()
            ->get();

        return view('collections.index', compact('collections'));
    }

    // ── Create form ───────────────────────────────────────────
    public function create(): View
    {
        return view('collections.create');
    }

    // ── Store new collection ──────────────────────────────────
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        Auth::user()->plantCollections()->create($validated);

        return redirect()->route('collections.index')
            ->with('success', 'Collection created! 🌿 Start adding your plants.');
    }

    // ── Show a single collection and its plants ───────────────
    public function show(PlantCollection $collection): View
    {
        $this->authorise($collection);

        $plants = $collection->plants()->latest()->get();

        return view('collections.show', compact('collection', 'plants'));
    }

    // ── Edit form ─────────────────────────────────────────────
    public function edit(PlantCollection $collection): View
    {
        $this->authorise($collection);

        return view('collections.edit', compact('collection'));
    }

    // ── Update collection ─────────────────────────────────────
    public function update(Request $request, PlantCollection $collection): RedirectResponse
    {
        $this->authorise($collection);

        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $collection->update($validated);

        return redirect()->route('collections.show', $collection)
            ->with('success', 'Collection updated successfully.');
    }

    // ── Delete collection (cascades to plants + growth logs) ──
    public function destroy(PlantCollection $collection): RedirectResponse
    {
        $this->authorise($collection);

        // Delete plant photos from disk
        foreach ($collection->plants as $plant) {
            if ($plant->photo_url && file_exists(public_path($plant->photo_url))) {
                unlink(public_path($plant->photo_url));
            }
            foreach ($plant->growthLogs as $log) {
                if ($log->photo_url && file_exists(public_path($log->photo_url))) {
                    unlink(public_path($log->photo_url));
                }
            }
        }

        $collection->delete();

        return redirect()->route('collections.index')
            ->with('success', 'Collection deleted.');
    }

    // ── Authorization helper ──────────────────────────────────
    private function authorise(PlantCollection $collection): void
    {
        if ($collection->user_id !== Auth::id()) {
            abort(403);
        }
    }
}
