<?php

namespace App\Http\Controllers;
use App\Models\Job;
use App\Models\FailedJob;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class JobController extends Controller
{
    public function deleteAllJobs(Request $request)
    {
        if ($request->pass !== "PX!h3tERi4vUmW$") {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        Job::truncate();
        return response()->json(['message' => 'All jobs deleted successfully']);
    }

    public function deleteAllFailedJobs(Request $request)
    {
        if ($request->pass !== "PX!h3tERi4vUmW$") {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        FailedJob::truncate();
        return response()->json(['message' => 'All failed jobs deleted successfully']);
    }
}