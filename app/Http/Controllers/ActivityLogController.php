<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('viewAny', ActivityLog::class);

        $logs = ActivityLog::with('user')
            ->orderByDesc('created_at')
            ->paginate(25);

        return view('activity-logs.index', compact('logs'));
    }
}
