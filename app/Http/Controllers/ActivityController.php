<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date', now()->toDateString());

        $activities = Activity::with(['creator', 'updates.updater'])
            ->whereDate('created_at', $date)
            ->orderBy('created_at', 'asc')
            ->get();

        $done    = $activities->filter(fn ($a) => $a->currentStatus() === 'done')->count();
        $pending = $activities->count() - $done;

        return view('dashboard', compact('activities', 'date', 'done', 'pending'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'          => 'required|string|max:250',
            'title_custom'   => 'nullable|string|max:250',
            'description'    => 'nullable|string',
            'category'       => 'nullable|string|max:100',
            'category_custom'=> 'nullable|string|max:100',
        ]);

        $title    = $data['title'] === '__other__'   ? ($data['title_custom'] ?? '')    : $data['title'];
        $category = ($data['category'] ?? '') === '__other__' ? ($data['category_custom'] ?? null) : ($data['category'] ?? null);

        if (empty(trim($title))) {
            return back()->withErrors(['title' => 'Please specify the activity title.']);
        }

        Activity::create([
            'title'       => $title,
            'description' => $data['description'] ?? null,
            'category'    => $category,
            'created_by'  => Auth::id(),
        ]);

        return back()->with('success', 'Activity added successfully.');
    }

    public function destroy(Activity $activity)
    {
        if (Auth::id() !== $activity->created_by && Auth::user()->role !== 'admin') {
            abort(403, 'You are not authorised to delete this activity.');
        }

        $activity->delete();

        return back()->with('success', 'Activity deleted.');
    }
}
