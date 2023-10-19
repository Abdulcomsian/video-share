<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{User , Skill , ShareFolder , JobPayment , EditorRequest , FavouriteRequest};

class PersonalJob extends Model
{
    use HasFactory;

    protected $table = "personal_jobs";
    protected $primaryKey = "id";
    protected $fillable = ['client_id' , 'title'  , 'description' , 'budget' , 'deadline', 'status', 'awarded_date'];

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
        return $this->hasOne(EditorRequest::class , 'job_id' , 'id' )->where( 'status' , 1 );
    }

    public function favouriteRequest()
    {
        return $this->hasMany(FavouriteRequest::class , 'job_id' , 'id');
    }

}
