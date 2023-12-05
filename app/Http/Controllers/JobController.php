<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $jobs = Job::paginate($perPage);

        return inertia('Job/Index', [
            'total' => $jobs->total(),
            'jobs' => $jobs->items(),
            'perPage' => $perPage,
        ]);
    }
}
