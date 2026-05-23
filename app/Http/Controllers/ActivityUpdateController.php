<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityUpdateController extends Controller
{
    public function store(Request $request, Activity $activity)
    {
        $data = $request->validate([
            'status' => 'required|in:done,pending',
            'remark' => 'nullable|string|max:1000',
        ]);

        ActivityUpdate::create([
            'activity_id' => $activity->id,
            'updated_by'  => Auth::id(),
            'status'      => $data['status'],
            'remark'      => $data['remark'] ?? null,
            'updated_at'  => now(),
        ]);

        return back()->with('success', 'Activity status updated.');
    }
}
