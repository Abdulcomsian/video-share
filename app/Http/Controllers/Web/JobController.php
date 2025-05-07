<?php

namespace App\Http\Controllers\Web;

use App\DataTables\JobListDataTable;
use App\Http\Controllers\Controller;
use App\Models\PersonalJob;

class JobController extends Controller
{
    public function index(JobListDataTable $dataTable)
    {
        return $dataTable->render('admin.jobs.index');
    }

    public function show($id)
    {

        $job = PersonalJob::with('skills' , 'jobFolder.files' , 'review', 'user', 'awardedRequest.proposal','doneRequest.proposal','payment','requestList.proposal','requestList.editor')->where('id' , $id)->first();

        if($job->status === 'completed') {

            $jobBudget = $job->doneRequest->proposal->bid_price ?? 0;

        }
        elseif ($job->status === 'awarded') {

            $jobBudget = $job->awardedRequest->proposal->bid_price ?? 0;

        }
        elseif ($job->status === 'canceled') {

            $jobBudget = $job->awardedRequest->proposal->bid_price ?? 0;

        }
        else
        {
            $jobBudget = $row->budget ?? 0;
        }

        // dd($job->toArray());

        return view('admin.jobs.show', compact('job', 'jobBudget'));

    }

    public function jobProposals($jobId)
    {

        if (request()->ajax()) {

            $job = PersonalJob::where('id' , $jobId)->first();
            $requests = $job->requestList()->with('editor', 'proposal')->paginate(5);
            return view('admin.jobs.proposals_list', compact('requests'))->render();

        }

    }
}
