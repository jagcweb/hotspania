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
            return redirect()->route('jobs.current')->with('error', 'Unauthorized');
        }
        
        Job::truncate();
        
        return redirect()->route('jobs.current')->with('success', 'All jobs deleted successfully');
    }

    public function deleteAllFailedJobs(Request $request)
    {
        if ($request->pass !== "PX!h3tERi4vUmW$") {
            return redirect()->route('jobs.current')->with('error', 'Unauthorized');
        }
        
        FailedJob::truncate();
        
        return redirect()->route('jobs.current')->with('success', 'All failed jobs deleted successfully');
    }
}
