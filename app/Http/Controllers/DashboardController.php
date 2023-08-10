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

    public function getAdminDashboard()
    {
        $folderCount = $this->dashboard->totalFolders();
        $videoCount = $this->dashboard->totalVideoFile();
        $imageCount = $this->dashboard->totalImages();
        $userCount = $this->dashboard->totalUser();
        $activeJobCount = $this->dashboard->activeJobs();
        $completedJobCount = $this->dashboard->completedJob();
        $clientCount = $this->dashboard->clients();
        $editorCount = $this->dashboard->editors();

        return view('admin.home')->with(["folderCount" => $folderCount , "videoCount" => $videoCount,
                                         "imageCount" => $imageCount , "userCount" => $userCount,
                                         "activeJobCount" => $activeJobCount , "completedJobCount" => $completedJobCount,
                                         "clientCount" => $clientCount , "editorCount" => $editorCount
                                        ]);
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
