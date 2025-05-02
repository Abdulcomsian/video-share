<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Repository\DashboardHandler;

class DashboardController extends Controller
{
    protected $dashboard;

    public function __construct(DashboardHandler $dashboard)
    {
        $this->dashboard = $dashboard;
    }

    public function index()
    {

        $activeJobCount = $this->dashboard->activeJobs();
        $completedJobCount = $this->dashboard->completedJob();
        $clientCount = $this->dashboard->clients();
        $editorCount = $this->dashboard->editors();

        return view('admin.home', compact("activeJobCount", "completedJobCount", "clientCount", "editorCount"));
    }


    public function getClientPage()
    {
        return view('admin.client');
    }

    public function getEditorPage()
    {
        return view('admin.editor');
    }

    public function getFolderPage()
    {
        return view('admin.folder');
    }

    public function getFilePage()
    {
        return view('admin.file');
    }

}
