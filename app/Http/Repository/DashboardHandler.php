<?php

namespace App\Http\Repository;
use App\Models\{ Folder , User , Files , PersonalJob };
use App\Http\AppConst;

class DashboardHandler{

    public function totalFolders()
    {
       return Folder::count();
    }

    public function totalVideoFile()
    {
        return Files::whereIn('extension' , ['webm','mp4'])->count();
    }

    public function totalImages()
    {   
        return Files::whereIn('extension' , ['png','jpg','jpeg','PNG','JPG','JPEG'])->count();
    }

    public function totalUser()
    {
        return User::count();
    }

    public function activeJobs()
    {
        return PersonalJob::whereIn('status' , ['awarded' , 'unawarded'])->count();
    }

    public function completedJob()
    {
        return PersonalJob::where('status' , 'completed')->count();
    }

    public function clients()
    {
        return User::where('type' , AppConst::CLIENT)->count();
    }

    public function editors(){
        return User::where('type' , AppConst::EDITOR)->count();
    }

}