@extends('layouts.app')
@section('title', $plant->name)

@section('content')
{{-- Breadcrumb --}}
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('collections.index') }}" class="text-rc">My Garden</a></li>
        <li class="breadcrumb-item"><a href="{{ route('collections.show', $plant->collection_id) }}" class="text-rc">{{ $plant->collection->name }}</a></li>
        <li class="breadcrumb-item active">{{ $plant->name }}</li>
    </ol>
</nav>

<div class="row g-4">

    {{-- ── LEFT: Plant info card ────────────────────────────── --}}
    <div class="col-lg-4">
        <div class="card mb-3">
            {{-- Plant photo --}}
            @if($plant->photo_url)
                <img src="{{ '/' . $plant->photo_url }}"
                     class="card-img-top" style="height:260px; object-fit:cover;" alt="{{ $plant->name }}">
            @else
                <div class="d-flex align-items-center justify-content-center bg-rc-light"
                     style="height:200px; font-size:4rem; border-radius:12px 12px 0 0;">🌿</div>
            @endif

            <div class="card-body">
                <h4 class="fw-bold text-rc mb-1">{{ $plant->name }}</h4>
                @if($plant->species)
                    <p class="text-muted fst-italic mb-2">{{ $plant->species }}</p>
                @endif

                @if($plant->condition)
                    @php
                        $condColor = match($plant->condition) {
                            'Excellent'  => 'success',
                            'Good'       => 'primary',
                            'Fair'       => 'warning',
                            'Struggling' => 'danger',
                            'Recovering' => 'danger',
                            default      => 'secondary',
                        };
                    @endphp
                    <span class="badge bg-{{ $condColor }} mb-3">{{ $plant->condition }}</span>
                @endif

                @if($plant->description)
                    <p class="card-text small text-muted mb-3">{{ $plant->description }}</p>
                @endif

                <ul class="list-unstyled small text-muted mb-3">
                    @if($plant->acquisition_date)
                    <li class="mb-1">
                        <i class="bi bi-calendar-check text-rc me-2"></i>
                        Acquired {{ $plant->acquisition_date->format('d M Y') }}
                    </li>
                    @endif
                    <li class="mb-1">
                        <i class="bi bi-graph-up text-rc me-2"></i>
                        {{ $growthLogs->count() }} growth {{ Str::plural('entry', $growthLogs->count()) }}
                    </li>
                    <li>
                        <i class="bi bi-clock text-rc me-2"></i>
                        Added {{ $plant->created_at->diffForHumans() }}
                    </li>
                </ul>

                <div class="d-flex gap-2">
                    <a href="{{ route('plants.edit', $plant) }}" class="btn btn-rc btn-sm flex-grow-1">
                        <i class="bi bi-pencil me-1"></i>Edit Plant
                    </a>
                    <form method="POST" action="{{ route('plants.destroy', $plant) }}"
                          onsubmit="return confirm('Remove {{ addslashes($plant->name) }}? This cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ── RIGHT: Growth Tracking ───────────────────────────── --}}
    <div class="col-lg-8">

        {{-- Add growth log form --}}
        <div class="card mb-4">
            <div class="card-header-rc p-3">
                <h6 class="mb-0 text-white fw-bold">
                    <i class="bi bi-plus-circle me-2"></i>Log Growth Entry
                </h6>
            </div>
            <div class="card-body p-4">

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('growth-logs.store', $plant) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">

                        {{-- Date --}}
                        <div class="col-md-4">
                            <label for="logged_at" class="form-label fw-semibold">
                                <i class="bi bi-calendar me-1 text-rc"></i>Date <span class="text-danger">*</span>
                            </label>
                            <input type="date" id="logged_at" name="logged_at"
                                   class="form-control @error('logged_at') is-invalid @enderror"
                                   value="{{ old('logged_at', date('Y-m-d')) }}"
                                   max="{{ date('Y-m-d') }}" required>
                            @error('logged_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Photo --}}
                        <div class="col-md-8">
                            <label for="growth_photo" class="form-label fw-semibold">
                                <i class="bi bi-camera me-1 text-rc"></i>Photo <span class="text-muted small">(optional)</span>
                            </label>
                            <input type="file" id="growth_photo" name="photo"
                                   class="form-control @error('photo') is-invalid @enderror"
                                   accept="image/*">
                            @error('photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Note --}}
                        <div class="col-12">
                            <label for="note" class="form-label fw-semibold">
                                <i class="bi bi-journal-text me-1 text-rc"></i>Observation Note
                            </label>
                            <textarea id="note" name="note" rows="2"
                                      class="form-control @error('note') is-invalid @enderror"
                                      placeholder="New leaves, root growth, any issues?">{{ old('note') }}</textarea>
                            @error('note')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-rc">
                                <i class="bi bi-plus-circle me-1"></i>Add Entry
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Growth log timeline --}}
        <h5 class="fw-bold text-rc mb-3">
            <i class="bi bi-graph-up me-2"></i>Growth History
            <span class="badge bg-rc-light text-rc ms-1" style="font-size:.8rem;">{{ $growthLogs->count() }}</span>
        </h5>

        @if($growthLogs->isEmpty())
            <div class="card">
                <div class="card-body text-center py-4">
                    <div style="font-size:2.5rem;">📅</div>
                    <p class="text-muted mt-2 mb-0">No growth entries yet. Log the first one above!</p>
                </div>
            </div>
        @else
            <div class="position-relative ps-4" style="border-left: 3px solid #52b788;">
                @foreach($growthLogs as $log)
                <div class="mb-4 position-relative">
                    {{-- Timeline dot --}}
                    <div class="position-absolute bg-white rounded-circle border border-2 d-flex align-items-center justify-content-center"
                         style="width:30px; height:30px; left:-42px; top:6px; border-color:#52b788 !important; font-size:.85rem;">
                        {{ $log->photo_url ? '📸' : '📝' }}
                    </div>

                    <div class="card">
                        @if($log->photo_url)
                            <img src="{{ '/' . $log->photo_url }}"
                                 class="card-img-top" style="height:220px; object-fit:cover; border-radius:12px 12px 0 0;" alt="Growth log photo">
                        @endif
                        <div class="card-body py-3 px-3">
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <div>
                                    <span class="fw-semibold text-rc">
                                        <i class="bi bi-calendar2-check me-1"></i>
                                        {{ \Carbon\Carbon::parse($log->logged_at)->format('d M Y') }}
                                    </span>
                                    <span class="text-muted small ms-2">
                                        ({{ \Carbon\Carbon::parse($log->logged_at)->diffForHumans() }})
                                    </span>
                                </div>
                                {{-- Delete entry --}}
                                <form method="POST"
                                      action="{{ route('growth-logs.destroy', ['plant' => $plant->id, 'growthLog' => $log->id]) }}"
                                      onsubmit="return confirm('Delete this growth entry?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2">
                                        <i class="bi bi-trash" style="font-size:.8rem;"></i>
                                    </button>
                                </form>
                            </div>

                            @if($log->note)
                                <p class="mt-2 mb-0 small">{{ $log->note }}</p>
                            @else
                                <p class="mt-2 mb-0 small text-muted fst-italic">No note recorded.</p>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif

        {{-- ── Feature 5: Plant Care Management ───────────────── --}}
        <div class="mt-5">
            <h5 class="fw-bold text-rc mb-3">
                <i class="bi bi-calendar-check me-2"></i>Care Schedule
                <span class="badge bg-rc-light text-rc ms-1" style="font-size:.8rem;">{{ $plant->careSchedules->count() }}</span>
            </h5>

            {{-- Add care schedule form --}}
            <div class="card mb-4">
                <div class="card-header-rc p-3">
                    <h6 class="mb-0 text-white fw-bold">
                        <i class="bi bi-plus-circle me-2"></i>Add Care Task
                    </h6>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('care-schedules.store', $plant) }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="care_task" class="form-label fw-semibold">
                                    <i class="bi bi-check2-circle me-1 text-rc"></i>Task <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="care_task" name="task"
                                       class="form-control @error('task') is-invalid @enderror"
                                       value="{{ old('task') }}"
                                       placeholder="e.g. Watering, Fertilizing" required>
                                @error('task')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label for="frequency" class="form-label fw-semibold">
                                    <i class="bi bi-arrow-repeat me-1 text-rc"></i>Frequency <span class="text-danger">*</span>
                                </label>
                                <select id="frequency" name="frequency"
                                        class="form-select @error('frequency') is-invalid @enderror" required>
                                    <option value="">Select...</option>
                                    <option value="daily" {{ old('frequency') == 'daily' ? 'selected' : '' }}>Daily</option>
                                    <option value="every_2_days" {{ old('frequency') == 'every_2_days' ? 'selected' : '' }}>Every 2 Days</option>
                                    <option value="weekly" {{ old('frequency') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                    <option value="biweekly" {{ old('frequency') == 'biweekly' ? 'selected' : '' }}>Biweekly</option>
                                    <option value="monthly" {{ old('frequency') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                </select>
                                @error('frequency')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label for="next_due_at" class="form-label fw-semibold">
                                    <i class="bi bi-calendar-event me-1 text-rc"></i>Next Due
                                </label>
                                <input type="date" id="next_due_at" name="next_due_at"
                                       class="form-control @error('next_due_at') is-invalid @enderror"
                                       value="{{ old('next_due_at', date('Y-m-d')) }}">
                                @error('next_due_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12">
                                <label for="care_notes" class="form-label fw-semibold">
                                    <i class="bi bi-journal-text me-1 text-rc"></i>Notes <span class="text-muted small">(optional)</span>
                                </label>
                                <textarea id="care_notes" name="notes" rows="2"
                                          class="form-control @error('notes') is-invalid @enderror"
                                          placeholder="Any special instructions...">{{ old('notes') }}</textarea>
                                @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-rc">
                                    <i class="bi bi-plus-circle me-1"></i>Add Care Task
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Existing care schedules --}}
            @if($plant->careSchedules->isEmpty())
                <div class="card">
                    <div class="card-body text-center py-4">
                        <div style="font-size:2.5rem;">💧</div>
                        <p class="text-muted mt-2 mb-0">No care tasks scheduled yet. Add one above!</p>
                    </div>
                </div>
            @else
                <div class="row g-3">
                    @foreach($plant->careSchedules as $schedule)
                        @php
                            $isOverdue = $schedule->next_due_at && $schedule->next_due_at->isPast();
                            $isDueToday = $schedule->next_due_at && $schedule->next_due_at->isToday();
                        @endphp
                        <div class="col-md-6">
                            <div class="card h-100 {{ $isOverdue ? 'border-danger border-2' : ($isDueToday ? 'border-warning border-2' : '') }}">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="fw-bold mb-1">
                                                @if($isOverdue)
                                                    <i class="bi bi-exclamation-triangle text-danger me-1"></i>
                                                @elseif($isDueToday)
                                                    <i class="bi bi-bell text-warning me-1"></i>
                                                @else
                                                    <i class="bi bi-check2-circle text-rc me-1"></i>
                                                @endif
                                                {{ $schedule->task }}
                                            </h6>
                                            <span class="badge bg-rc-light text-rc">
                                                {{ str_replace('_', ' ', ucfirst($schedule->frequency)) }}
                                            </span>
                                        </div>
                                        <div class="d-flex gap-1">
                                            <form method="POST" action="{{ route('care-schedules.complete', ['plant' => $plant->id, 'careSchedule' => $schedule->id]) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-outline-success" title="Mark Complete">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('care-schedules.destroy', ['plant' => $plant->id, 'careSchedule' => $schedule->id]) }}"
                                                  onsubmit="return confirm('Delete this care task?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    @if($schedule->next_due_at)
                                        <p class="small mt-2 mb-1 {{ $isOverdue ? 'text-danger fw-bold' : 'text-muted' }}">
                                            <i class="bi bi-calendar-event me-1"></i>
                                            {{ $isOverdue ? 'Overdue since' : ($isDueToday ? 'Due today' : 'Next due') }}
                                            {{ !$isDueToday ? $schedule->next_due_at->format('d M Y') : '' }}
                                        </p>
                                    @endif

                                    @if($schedule->notes)
                                        <p class="small text-muted mt-1 mb-0">{{ $schedule->notes }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- ── Feature 16: Plant Propagation Tracker ──────────── --}}
        <div class="mt-5">
            <h5 class="fw-bold text-rc mb-3">
                <i class="bi bi-scissors me-2"></i>Propagation Tracker
                <span class="badge bg-rc-light text-rc ms-1" style="font-size:.8rem;">{{ $plant->propagations->count() }}</span>
            </h5>

            {{-- Add propagation form --}}
            <div class="card mb-4">
                <div class="card-header-rc p-3">
                    <h6 class="mb-0 text-white fw-bold">
                        <i class="bi bi-plus-circle me-2"></i>Start New Propagation
                    </h6>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('propagations.store', $plant) }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="prop_method" class="form-label fw-semibold">
                                    <i class="bi bi-tools me-1 text-rc"></i>Method <span class="text-danger">*</span>
                                </label>
                                <select id="prop_method" name="method"
                                        class="form-select @error('method') is-invalid @enderror" required>
                                    <option value="">Select method...</option>
                                    <option value="seed" {{ old('method') == 'seed' ? 'selected' : '' }}>🌰 Seed</option>
                                    <option value="cutting" {{ old('method') == 'cutting' ? 'selected' : '' }}>✂️ Cutting</option>
                                    <option value="division" {{ old('method') == 'division' ? 'selected' : '' }}>🪴 Division</option>
                                </select>
                                @error('method')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label for="start_date" class="form-label fw-semibold">
                                    <i class="bi bi-calendar me-1 text-rc"></i>Start Date <span class="text-danger">*</span>
                                </label>
                                <input type="date" id="start_date" name="start_date"
                                       class="form-control @error('start_date') is-invalid @enderror"
                                       value="{{ old('start_date', date('Y-m-d')) }}" required>
                                @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label for="prop_status" class="form-label fw-semibold">
                                    <i class="bi bi-flag me-1 text-rc"></i>Status
                                </label>
                                <select id="prop_status" name="status"
                                        class="form-select @error('status') is-invalid @enderror">
                                    <option value="in_progress" {{ old('status', 'in_progress') == 'in_progress' ? 'selected' : '' }}>🔄 In Progress</option>
                                    <option value="rooted" {{ old('status') == 'rooted' ? 'selected' : '' }}>🌱 Rooted</option>
                                    <option value="transplanted" {{ old('status') == 'transplanted' ? 'selected' : '' }}>🪴 Transplanted</option>
                                    <option value="failed" {{ old('status') == 'failed' ? 'selected' : '' }}>❌ Failed</option>
                                </select>
                                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label for="root_development" class="form-label fw-semibold">
                                    <i class="bi bi-tree me-1 text-rc"></i>Root Development <span class="text-muted small">(optional)</span>
                                </label>
                                <textarea id="root_development" name="root_development" rows="2"
                                          class="form-control @error('root_development') is-invalid @enderror"
                                          placeholder="Describe root growth progress...">{{ old('root_development') }}</textarea>
                                @error('root_development')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label for="prop_notes" class="form-label fw-semibold">
                                    <i class="bi bi-journal-text me-1 text-rc"></i>Notes <span class="text-muted small">(optional)</span>
                                </label>
                                <textarea id="prop_notes" name="notes" rows="2"
                                          class="form-control @error('notes') is-invalid @enderror"
                                          placeholder="Medium used, temperature, etc.">{{ old('notes') }}</textarea>
                                @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-rc">
                                    <i class="bi bi-plus-circle me-1"></i>Start Propagation
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Existing propagations --}}
            @if($plant->propagations->isEmpty())
                <div class="card">
                    <div class="card-body text-center py-4">
                        <div style="font-size:2.5rem;">🌱</div>
                        <p class="text-muted mt-2 mb-0">No propagation attempts yet. Start one above!</p>
                    </div>
                </div>
            @else
                <div class="row g-3">
                    @foreach($plant->propagations->sortByDesc('created_at') as $prop)
                        @php
                            $statusConfig = match($prop->status) {
                                'in_progress'  => ['icon' => 'arrow-repeat',    'color' => 'primary', 'label' => 'In Progress'],
                                'rooted'       => ['icon' => 'check-circle',    'color' => 'success', 'label' => 'Rooted'],
                                'transplanted' => ['icon' => 'award',           'color' => 'info',    'label' => 'Transplanted'],
                                'failed'       => ['icon' => 'x-circle',        'color' => 'danger',  'label' => 'Failed'],
                                default        => ['icon' => 'question-circle', 'color' => 'secondary', 'label' => ucfirst($prop->status)],
                            };
                            $methodEmoji = match($prop->method) {
                                'seed'     => '🌰',
                                'cutting'  => '✂️',
                                'division' => '🪴',
                                default    => '🌱',
                            };
                            $daysSinceStart = \Carbon\Carbon::parse($prop->start_date)->diffInDays(now());
                        @endphp
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h6 class="fw-bold mb-1">
                                                {{ $methodEmoji }} {{ ucfirst($prop->method) }} Propagation
                                            </h6>
                                            <span class="badge bg-{{ $statusConfig['color'] }}">
                                                <i class="bi bi-{{ $statusConfig['icon'] }} me-1"></i>{{ $statusConfig['label'] }}
                                            </span>
                                        </div>
                                        <div class="d-flex gap-1">
                                            <form method="POST" action="{{ route('propagations.destroy', ['plant' => $plant->id, 'propagation' => $prop->id]) }}"
                                                  onsubmit="return confirm('Delete this propagation record?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    <ul class="list-unstyled small text-muted mb-2">
                                        <li class="mb-1">
                                            <i class="bi bi-calendar text-rc me-1"></i>
                                            Started {{ \Carbon\Carbon::parse($prop->start_date)->format('d M Y') }}
                                            <span class="text-muted">({{ $daysSinceStart }} days ago)</span>
                                        </li>
                                        @if($prop->root_development)
                                            <li class="mb-1">
                                                <i class="bi bi-tree text-rc me-1"></i>
                                                <strong>Roots:</strong> {{ $prop->root_development }}
                                            </li>
                                        @endif
                                    </ul>

                                    @if($prop->notes)
                                        <p class="small text-muted mb-2">{{ $prop->notes }}</p>
                                    @endif

                                    {{-- Inline status update form --}}
                                    <form method="POST" action="{{ route('propagations.update', ['plant' => $plant->id, 'propagation' => $prop->id]) }}" class="mt-2">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="method" value="{{ $prop->method }}">
                                        <input type="hidden" name="start_date" value="{{ $prop->start_date->format('Y-m-d') }}">
                                        <div class="row g-2 align-items-end">
                                            <div class="col">
                                                <select name="status" class="form-select form-select-sm">
                                                    <option value="in_progress" {{ $prop->status == 'in_progress' ? 'selected' : '' }}>🔄 In Progress</option>
                                                    <option value="rooted" {{ $prop->status == 'rooted' ? 'selected' : '' }}>🌱 Rooted</option>
                                                    <option value="transplanted" {{ $prop->status == 'transplanted' ? 'selected' : '' }}>🪴 Transplanted</option>
                                                    <option value="failed" {{ $prop->status == 'failed' ? 'selected' : '' }}>❌ Failed</option>
                                                </select>
                                            </div>
                                            <div class="col-auto">
                                                <button type="submit" class="btn btn-sm btn-rc">Update</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>{{-- /col --}}
</div>{{-- /row --}}
@endsection
