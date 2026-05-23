@extends('layouts.app')

@section('title', 'Daily Activity View')

@section('content')

{{-- Page header --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Daily Activity View</h1>
        <p class="text-gray-500 text-sm mt-0.5">Track and manage team activities for {{ \Carbon\Carbon::parse($date)->format('l, d M Y') }}</p>
    </div>
    <div class="flex items-center gap-3">
        <form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-2">
            <input type="date" name="date" value="{{ $date }}"
                   onchange="this.form.submit()"
                   class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </form>
        <button onclick="document.getElementById('add-form').classList.toggle('hidden')"
                class="bg-blue-700 hover:bg-blue-800 text-white font-semibold px-4 py-2 rounded-lg text-sm transition-colors whitespace-nowrap">
            + Add Activity
        </button>
    </div>
</div>

{{-- Stats --}}
<div class="grid grid-cols-3 gap-4 mb-6">
    @foreach([['Total', $activities->count(), 'blue'], ['Done', $done, 'green'], ['Pending', $pending, 'yellow']] as [$label, $value, $color])
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 text-center">
        <p class="text-3xl font-bold text-{{ $color }}-600">{{ $value }}</p>
        <p class="text-gray-500 text-sm mt-1">{{ $label }}</p>
    </div>
    @endforeach
</div>

{{-- Add Activity Form --}}
<div id="add-form" class="hidden bg-blue-50 border border-blue-200 rounded-xl p-6 mb-6">
    <h3 class="font-semibold text-gray-800 mb-4">New Activity</h3>
    <form method="POST" action="{{ route('activities.store') }}" class="space-y-3">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Activity Title *</label>
            <select name="title" id="title-select" data-other-toggle="title-custom" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Select an activity</option>
                @foreach([
                    'Daily SMS count in comparison to SMS count from logs',
                    'Check delivery reports vs sent messages',
                    'Monitor failed SMS transactions',
                    'Review network uptime and availability',
                    'Database backup verification',
                    'Application error log review',
                    'Incident ticket resolution follow-up',
                    'System performance monitoring',
                    'API response time check',
                    'Check pending customer complaints',
                    'Review escalated support tickets',
                    'Daily server health check',
                    '__other__',
                ] as $opt)
                    @if($opt === '__other__')
                        <option value="__other__">Other (specify)</option>
                    @else
                        <option value="{{ $opt }}" {{ old('title') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                    @endif
                @endforeach
            </select>
            <input type="text" name="title_custom" id="title-custom"
                   placeholder="Describe the activity..."
                   class="hidden mt-2 w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                <select name="category" id="cat-select" data-other-toggle="cat-custom"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Select category</option>
                    @foreach(['SMS','Network','Database','Application','Infrastructure','Security','Customer Support','Reporting','__other__'] as $cat)
                        @if($cat === '__other__')
                            <option value="__other__">Other (specify)</option>
                        @else
                            <option value="{{ $cat }}">{{ $cat }}</option>
                        @endif
                    @endforeach
                </select>
                <input type="text" name="category_custom" id="cat-custom"
                       placeholder="Specify category..."
                       class="hidden mt-2 w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <input type="text" name="description"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="Optional details">
            </div>
        </div>

        <div class="flex gap-3 pt-1">
            <button type="submit"
                    class="bg-blue-700 hover:bg-blue-800 text-white font-semibold px-5 py-2 rounded-lg text-sm transition-colors">
                Add Activity
            </button>
            <button type="button"
                    onclick="document.getElementById('add-form').classList.add('hidden')"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold px-5 py-2 rounded-lg text-sm transition-colors">
                Cancel
            </button>
        </div>
    </form>
</div>

{{-- Activities List --}}
@if ($activities->isEmpty())
    <div class="text-center py-16 text-gray-400">
        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2
                     M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        <p class="font-medium text-gray-500">No activities logged for this date.</p>
        <p class="text-sm mt-1">Click "+ Add Activity" to create one.</p>
    </div>
@else
    <div class="space-y-4">
        @foreach ($activities as $activity)
            @php $status = $activity->currentStatus(); @endphp
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                {{-- Top row --}}
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center flex-wrap gap-2 mb-1.5">
                            <span class="{{ $status === 'done' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }} inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold">
                                {{ ucfirst($status) }}
                            </span>
                            @if ($activity->category)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700">
                                    {{ $activity->category }}
                                </span>
                            @endif
                        </div>
                        <h3 class="font-semibold text-gray-900 leading-snug">{{ $activity->title }}</h3>
                        @if ($activity->description)
                            <p class="text-gray-500 text-sm mt-1">{{ $activity->description }}</p>
                        @endif
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        {{-- Update button --}}
                        <button onclick="toggleModal('modal-{{ $activity->id }}')"
                                class="bg-blue-700 hover:bg-blue-800 text-white text-sm font-semibold px-3 py-1.5 rounded-lg transition-colors">
                            Update
                        </button>
                        {{-- Delete (creator or admin) --}}
                        @if (auth()->id() === $activity->created_by || auth()->user()->role === 'admin')
                            <form method="POST" action="{{ route('activities.destroy', $activity) }}"
                                  onsubmit="return confirm('Delete this activity?')">
                                @csrf @method('DELETE')
                                <button class="bg-red-500 hover:bg-red-600 text-white text-sm font-semibold px-3 py-1.5 rounded-lg transition-colors">
                                    Delete
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                {{-- Meta --}}
                <div class="mt-3 text-xs text-gray-400 flex flex-wrap gap-4">
                    <span>Created by <span class="font-medium text-gray-600">{{ $activity->creator->name }}</span></span>
                    <span>{{ $activity->created_at->format('d M Y, H:i') }}</span>
                    @if ($activity->updates->count())
                        @php $last = $activity->updates->last(); @endphp
                        <span>Last update: <span class="font-medium text-gray-600">{{ $last->updater->name }}</span> at {{ $last->updated_at->format('H:i') }}</span>
                    @endif
                </div>

                {{-- Update history --}}
                @if ($activity->updates->count())
                    <div x-data="{ open: false }" class="mt-3 border-t border-gray-100 pt-3">
                        <button @click="open = !open"
                                class="text-sm text-blue-600 hover:text-blue-700 font-medium flex items-center gap-1">
                            <span x-text="open ? 'Hide history' : 'Show history ({{ $activity->updates->count() }})'">
                                Show history ({{ $activity->updates->count() }})
                            </span>
                            <span :class="open ? 'rotate-180' : ''" class="transition-transform inline-block">▾</span>
                        </button>

                        <div x-show="open" x-cloak class="mt-3 space-y-2">
                            @foreach ($activity->updates->reverse() as $upd)
                                <div class="flex items-start gap-3 text-sm bg-gray-50 rounded-lg p-3">
                                    <span class="{{ $upd->status === 'done' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }} inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold shrink-0 mt-0.5">
                                        {{ ucfirst($upd->status) }}
                                    </span>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-gray-700">{{ $upd->remark ?: 'No remark added' }}</p>
                                        <p class="text-gray-400 text-xs mt-1">
                                            <span class="font-medium text-gray-600">{{ $upd->updater->name }}</span>
                                            &bull; {{ $upd->updater->department ?? ucfirst($upd->updater->role) }}
                                            &bull; {{ $upd->updated_at->format('d M Y, H:i') }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Update Modal --}}
            <div id="modal-{{ $activity->id }}"
                 class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
                <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 overflow-hidden">
                    <div class="bg-blue-700 px-6 py-4">
                        <h2 class="text-white text-lg font-semibold">Update Activity</h2>
                        <p class="text-blue-200 text-sm mt-0.5 truncate">{{ $activity->title }}</p>
                    </div>
                    <form method="POST" action="{{ route('updates.store', $activity) }}" class="p-6 space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                            <div class="flex gap-3">
                                @foreach(['done' => 'Done', 'pending' => 'Pending'] as $val => $label)
                                    <label class="flex-1 cursor-pointer">
                                        <input type="radio" name="status" value="{{ $val }}" class="sr-only peer"
                                               {{ $status === $val ? 'checked' : '' }}>
                                        <div class="w-full py-2.5 rounded-lg text-sm font-semibold text-center border-2 transition-all
                                                    peer-checked:{{ $val === 'done' ? 'bg-green-500 border-green-500 text-white' : 'bg-yellow-400 border-yellow-400 text-gray-900' }}
                                                    border-gray-200 text-gray-600 hover:border-gray-300">
                                            {{ $label }}
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Remark / Notes</label>
                            <textarea name="remark" rows="3"
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                                      placeholder="Describe what was done or why it is pending..."></textarea>
                        </div>
                        <div class="flex gap-3 pt-1">
                            <button type="button" onclick="toggleModal('modal-{{ $activity->id }}')"
                                    class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 rounded-lg text-sm transition-colors">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="flex-1 bg-blue-700 hover:bg-blue-800 text-white font-semibold py-2 rounded-lg text-sm transition-colors">
                                Save Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@endif

<script>
function toggleModal(id) {
    document.getElementById(id).classList.toggle('hidden');
}

// Other dropdown logic
document.addEventListener('DOMContentLoaded', function () {
    [['title-select', 'title-custom'], ['cat-select', 'cat-custom']].forEach(function([selId, inpId]) {
        var sel = document.getElementById(selId);
        var inp = document.getElementById(inpId);
        if (!sel || !inp) return;
        function toggle() {
            if (sel.value === '__other__') {
                inp.classList.remove('hidden'); inp.required = true; inp.focus();
            } else {
                inp.classList.add('hidden'); inp.required = false; inp.value = '';
            }
        }
        sel.addEventListener('change', toggle);
        toggle();
    });
});
</script>
@endsection
