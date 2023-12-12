<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index(Request $request)
    {
        return inertia('Job/Index');
    }

    public function list(Request $request): JsonResponse
    {
        $conditions = json_decode($request->input('condition'), true);

        return response()->json(
            Job::orderByDesc('id')
                ->when(!empty($conditions['lastCheckTime']), function ($query) use ($conditions) {
                    if ($conditions['lastCheckTime'] === 'checked') {
                        $query->whereNotNull('last_check_time');
                    } else if ($conditions['lastCheckTime'] === 'unchecked') {
                        $query->whereNull('last_check_time');
                    }
                })
                ->paginate($request->input('perPage', 10))
        );
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

        return response()->noContent();
    }

    public function patch(Request $request, $id)
    {
        $job = Job::find($id);

        $job->update([
            'job_name' => $request->input('jobName'),
            'url' => $request->input('url') ?? '',
            'last_check_time' => $request->input('lastCheckTime'),
            'rating' => $request->input('rating'),
            'delivery_time' => $request->input('deliveryTime'),
            'comment' => $request->input('comment'),
            'illegal' => $request->input('illegal'),
            'min_monthly_salary' => $request->input('minMonthlySalary'),
            'max_monthly_salary' => $request->input('maxMonthlySalary'),
            'min_annual_salary' => $request->input('minAnnualSalary'),
            'max_annual_salary' => $request->input('maxAnnualSalary'),
            'starred' => $request->input('starred'),
        ]);

        return response()->json(['status' => 'success']);
    }
}
