<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{ JobProposal};

class EditorRequest extends Model
{
    use HasFactory;

    protected $table = "job_editor_request";
    protected $primaryKey = "id";
    protected $fillable = ['editor_id' , 'job_id' , 'request_id'];

    public function proposal()
    {
        return $this->belongsTo(JobProposal::class , 'request_id' , 'id');
    }

}
