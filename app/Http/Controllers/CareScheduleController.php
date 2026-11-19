<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use App\Models\CareSchedule;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CareScheduleController extends Controller
{
    // ── Store a new care schedule for a plant ─────────────────
    public function store(Request $request, Plant $plant): RedirectResponse
    {
        if ($plant->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'task'        => ['required', 'string', 'max:255'],
            'frequency'   => ['required', 'string', 'max:100', 'in:daily,every_2_days,weekly,biweekly,monthly'],
            'next_due_at' => ['nullable', 'date'],
            'notes'       => ['nullable', 'string', 'max:2000'],
        ]);

        CareSchedule::create([
            'plant_id'    => $plant->id,
            'task'        => $validated['task'],
            'frequency'   => $validated['frequency'],
            'next_due_at' => $validated['next_due_at'] ?? now(),
            'notes'       => $validated['notes'] ?? null,
        ]);

        return redirect()->route('plants.show', $plant)
            ->with('success', 'Care task added! 💧');
    }

    // ── Update an existing care schedule ─────────────────────
    public function update(Request $request, Plant $plant, CareSchedule $careSchedule): RedirectResponse
    {
        if ($plant->user_id !== Auth::id() || $careSchedule->plant_id !== $plant->id) {
            abort(403);
        }

        $validated = $request->validate([
            'task'        => ['required', 'string', 'max:255'],
            'frequency'   => ['required', 'string', 'max:100', 'in:daily,every_2_days,weekly,biweekly,monthly'],
            'next_due_at' => ['nullable', 'date'],
            'notes'       => ['nullable', 'string', 'max:2000'],
        ]);

        $careSchedule->update($validated);

        return redirect()->route('plants.show', $plant)
            ->with('success', 'Care task updated.');
    }

    // ── Mark task complete & auto-advance next due date ──────
    public function markComplete(Plant $plant, CareSchedule $careSchedule): RedirectResponse
    {
        if ($plant->user_id !== Auth::id() || $careSchedule->plant_id !== $plant->id) {
            abort(403);
        }

        $nextDue = Carbon::now();

        $nextDue = match ($careSchedule->frequency) {
            'daily'       => $nextDue->addDay(),
            'every_2_days'=> $nextDue->addDays(2),
            'weekly'      => $nextDue->addWeek(),
            'biweekly'    => $nextDue->addWeeks(2),
            'monthly'     => $nextDue->addMonth(),
            default       => $nextDue->addWeek(),
        };

        $careSchedule->update(['next_due_at' => $nextDue]);

        return redirect()->route('plants.show', $plant)
            ->with('success', 'Task completed! Next due: ' . $nextDue->format('d M Y') . ' ✅');
    }

    // ── Delete a care schedule ───────────────────────────────
    public function destroy(Plant $plant, CareSchedule $careSchedule): RedirectResponse
    {
        if ($plant->user_id !== Auth::id() || $careSchedule->plant_id !== $plant->id) {
            abort(403);
        }

        $careSchedule->delete();

        return redirect()->route('plants.show', $plant)
            ->with('success', 'Care task removed.');
    }
}
