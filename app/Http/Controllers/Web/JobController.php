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

        $job = PersonalJob::with('skills' , 'jobFolder.files' , 'review', 'user')->where('id' , $id)->first();

        return view('admin.jobs.show', compact('job'));

    }
}
