<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Gather relevant data for the admin dashboard (e.g., user count, pending tasks, recent activity)
        $data = [
            // 'userCount' => User::count(),
            // 'pendingTasks' => Task::where('status', 0)->count(),
            // 'recentActivity' => Activity::latest()->limit(5)->get(),
        ];

        return view('admin.dashboard', $data);
    }
}
