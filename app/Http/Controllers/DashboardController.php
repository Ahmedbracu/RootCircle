<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        $stats = [
            'posts'       => $user->posts()->count(),
            'collections' => $user->plantCollections()->count(),
            'plants'      => $user->plants()->count(),
            'growth_logs' => \App\Models\PlantGrowthLog::whereIn(
                                 'plant_id',
                                 $user->plants()->pluck('id')
                             )->count(),
        ];

        // Most recent plants (up to 3) for the dashboard preview
        $recentPlants = $user->plants()
            ->with('collection')
            ->latest()
            ->take(3)
            ->get();

        return view('dashboard', compact('stats', 'recentPlants'));
    }
}
