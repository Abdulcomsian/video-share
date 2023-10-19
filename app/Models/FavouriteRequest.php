<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{ PersonalJob , EditorRequest };

class FavouriteRequest extends Model
{
    use HasFactory;

    protected $table = 'favourite_request';

    protected $primaryKey = 'id';

    protected $fillable = ['job_id' , 'editor_request_id'];

    public function job()
    {
        return $this->belongsTo(PersonalJob::class , 'job_id' , 'id');
    }

    public function editorRequest()
    {
        return $this->belongsTo(EditorRequest::class , 'editor_request_id' , 'id');
    }

}
