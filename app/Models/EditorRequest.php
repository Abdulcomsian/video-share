<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{ JobProposal , PersonalJob , FavouriteRequest , User };

class EditorRequest extends Model
{
    use HasFactory;

    protected $table = "job_editor_request";
    protected $primaryKey = "id";
    protected $fillable = ['editor_id' , 'job_id' , 'request_id', 'status'];

    public function proposal()
    {
        return $this->belongsTo(JobProposal::class , 'request_id' , 'id');
    }

    public function job()
    {
        return $this->belongsTo(PersonalJob::class , 'job_id' , 'id');
    }

    public function favourite()
    {
        return $this->hasOne(FavouriteRequest::class , 'editor_request_id' , 'id');
    }

    public function editor()
    {
        return $this->belongsTo(User::class , 'editor_id' , 'id');
    }

}
