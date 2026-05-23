@extends('layouts.app')

@section('title', 'Activity Reports')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Activity Reports</h1>
    <p class="text-gray-500 text-sm mt-0.5">Query activity history across any custom date range</p>
</div>

{{-- Filter card --}}
<div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mb-6">
    {{-- Quick presets --}}
    <div class="flex flex-wrap gap-2 mb-4">
        @php
            $presets = [
                'Today'       => [now()->toDateString(), now()->toDateString()],
                'Last 7 days' => [now()->subDays(7)->toDateString(), now()->toDateString()],
                'Last 30 days'=> [now()->subDays(30)->toDateString(), now()->toDateString()],
            ];
        @endphp
        @foreach ($presets as $label => [$ps, $pe])
            <a href="{{ route('reports', ['start' => $ps, 'end' => $pe]) }}"
               class="text-sm px-3 py-1.5 rounded-lg font-medium transition-colors
                      {{ ($start === $ps && $end === $pe) ? 'bg-blue-700 text-white' : 'bg-gray-100 hover:bg-blue-100 hover:text-blue-700 text-gray-700' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <form method="GET" action="{{ route('reports') }}" class="flex flex-col sm:flex-row gap-3 items-end">
        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-1">From</label>
            <input type="date" name="start" value="{{ $start }}"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-1">To</label>
            <input type="date" name="end" value="{{ $end }}"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <button type="submit"
                class="bg-blue-700 hover:bg-blue-800 text-white font-semibold px-5 py-2 rounded-lg text-sm transition-colors whitespace-nowrap">
            Search
        </button>
    </form>
</div>

{{-- Results --}}
@if ($start && $end)
    @php
        $done    = $activities->filter(fn ($a) => $a->currentStatus() === 'done')->count();
        $pending = $activities->count() - $done;
    @endphp

    <div class="flex items-center justify-between mb-4">
        <p class="text-gray-700 font-medium">
            {{ $activities->count() }} {{ Str::plural('activity', $activities->count()) }} found
            @if ($activities->count())
                <span class="text-gray-400 font-normal ml-1">({{ $done }} done, {{ $pending }} pending)</span>
            @endif
        </p>
    </div>

    @if ($activities->isEmpty())
        <div class="text-center py-16 text-gray-400">
            <p class="font-medium">No activities found in this date range.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach ($activities as $activity)
                @php $status = $activity->currentStatus(); @endphp
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
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
                                <span class="text-xs text-gray-400">{{ $activity->created_at->format('d M Y') }}</span>
                            </div>
                            <h3 class="font-semibold text-gray-900">{{ $activity->title }}</h3>
                            @if ($activity->description)
                                <p class="text-gray-500 text-sm mt-1">{{ $activity->description }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="mt-3 text-xs text-gray-400 flex flex-wrap gap-4">
                        <span>Created by <span class="font-medium text-gray-600">{{ $activity->creator->name }}</span></span>
                        <span>{{ $activity->created_at->format('d M Y, H:i') }}</span>
                    </div>

                    @if ($activity->updates->count())
                        <div class="mt-3 border-t border-gray-100 pt-3">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Update History</p>
                            <div class="space-y-2">
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
            @endforeach
        </div>
    @endif
@endif

@endsection
