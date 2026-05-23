<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $activities = collect();
        $start = $request->input('start');
        $end   = $request->input('end');

        if ($start && $end) {
            $request->validate([
                'start' => 'required|date',
                'end'   => 'required|date|after_or_equal:start',
            ]);

            $activities = Activity::with(['creator', 'updates.updater'])
                ->whereDate('created_at', '>=', $start)
                ->whereDate('created_at', '<=', $end)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('reports', compact('activities', 'start', 'end'));
    }
}
