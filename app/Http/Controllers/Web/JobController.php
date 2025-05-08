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

        $job = PersonalJob::with('skills' , 'jobFolder.files' , 'review', 'user','payment','requestList.proposal','requestList.editor','acceptedRequest.proposal','acceptedRequest.editor')->where('id' , $id)->first();

        // dd($job->toArray(),$job->requestList->toArray());

        return view('admin.jobs.show', compact('job'));

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
