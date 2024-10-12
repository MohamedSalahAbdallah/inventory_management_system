<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use App\Models\User;

class ActivityLogController extends Controller
{
    /**
     * Get all activity logs.
     */
    public function index()
    {
        // Get all activity logs
        $logs = Activity::with('causer')->get();
        return response()->json($logs);
    }

    /**
     * Get activity logs for a specific user.
     *
     * @param  int  $userId
     */
    public function getUserLogs($userId)
    {
        // Find user
        $user = User::findOrFail($userId);

        // Get activity logs for the specific user
        $logs = Activity::where('causer_id', $user->id)->with('causer', 'subject')->get()->all();

        

        return response()->json($logs);
    }
}
