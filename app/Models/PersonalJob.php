<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{user};

class PersonalJob extends Model
{
    use HasFactory;

    protected $table = "personal_jobs";
    protected $primaryKey = "id";
    protected $fillable = ['client_id' , 'title'  , 'description' , 'budget' , 'deadline', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class , 'client_id' , 'id');
    }


    public function allEditor()
    {
        return $this->belongsToMany(User::class , 'job_editor_request', 'job_id' , 'editor_id');
    }

}
