<?php

namespace App\Models;

use App\Http\AppConst;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{User , Skill , ShareFolder , JobPayment , EditorRequest , FavouriteRequest , Folder , Review};

class PersonalJob extends Model
{
    use HasFactory;

    protected $table = "personal_jobs";
    protected $primaryKey = "id";
    protected $fillable = ['client_id' , 'title'  , 'description' , 'budget' , 'deadline', 'status', 'awarded_date' , 'folder_id'];

    public function user()
    {
        return $this->belongsTo(User::class , 'client_id' , 'id');
    }

    public function skills()
    {
        return $this->morphMany(Skill::class, 'skillable');
    }

    public function allEditor()
    {
        return $this->belongsToMany(User::class , 'job_editor_request', 'job_id' , 'editor_id');
    }

    public function folder()
    {
        return $this->hasOne(ShareFolder::class , 'job_id' , 'id');
    }

    public function requestList()
    {
        return $this->hasMany(EditorRequest::class, 'job_id' , 'id');
    }

    public function payment()
    {
        return $this->hasOne(JobPayment::class  , 'job_id' , 'id');
    }

    public function awardedRequest()
    {
        return $this->hasOne(EditorRequest::class , 'job_id' , 'id' )->where( 'status' , AppConst::AWARDED_JOB );
    }

    public function doneRequest()
    {
        return $this->hasOne(EditorRequest::class , 'job_id' , 'id' )->where( 'status' , AppConst::DONE_JOB );
    }

    public function unawardedRequest()
    {
        return $this->hasMany(EditorRequest::class, 'job_id' , 'id')->where('status' , AppConst::UN_AWARDED_JOB);
    }

    public function favouriteRequest()
    {
        return $this->hasMany(FavouriteRequest::class , 'job_id' , 'id');
    }

    public function jobFolder()
    {
        return $this->belongsTo(Folder::class , 'folder_id' , 'id');
    }

    public function review()
    {
        return $this->hasOne(Review::class , 'job_id' , 'id'); 
    }

}
