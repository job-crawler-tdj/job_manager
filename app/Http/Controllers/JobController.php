<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Carbon\Carbon;
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
            'currentPage' => $jobs->currentPage(),
        ]);
    }

    public function put(Request $request)
    {
        $jobs = collect(json_decode($request->input('importJson'), true));
        $companies = $jobs->pluck('companyName')->unique()->toArray();
        $companiesExist = Job::whereIn('company', $companies)->pluck('company')->toArray();
        Job::whereIn('company', $companies)->update(['last_seen' => Carbon::now()->format('Y-m-d H:i:s')]);
        $jobs = $jobs->filter(function ($job) use ($companiesExist) {
            return !in_array($job['companyName'], $companiesExist);
        });

        $jobs = $jobs->unique('companyName');

        Job::insert(
            $jobs->map(function ($job) {
                return [
                    'user_id' => auth()->user()->id,
                    'source' => $job['source'],
                    'company' => $job['companyName'],
                    'job_name' => $job['jobName'],
                    'url' => $job['url'],
                    'min_monthly_salary' => $job['minMonthlySalary'],
                    'max_monthly_salary' => $job['maxMonthlySalary'],
                    'min_annual_salary' => $job['minAnnualSalary'],
                    'max_annual_salary' => $job['maxAnnualSalary'],
                    'last_seen' => Carbon::now()->format('Y-m-d H:i:s'),
                ];
            })->toArray()
        );
    }
}
