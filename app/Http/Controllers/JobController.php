<?php

namespace App\Http\Controllers;
use App\Models\Job;
use App\Models\FailedJob;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class JobController extends Controller
{
    public function deleteAllJobs()
    {
        Job::truncate();
        return response()->json(['message' => 'All jobs deleted successfully']);
    }

    public function deleteAllFailedJobs()
    {
        FailedJob::truncate();
        return response()->json(['message' => 'All failed jobs deleted successfully']);
    }
}