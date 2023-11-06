<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PersonalJob;

class Review extends Model
{
    use HasFactory;

    protected $table = 'job_review';

    protected $primaryKey = 'id';

    protected $fillable = ['job_id' , 'rating' , 'comment'];

    public function job()
    {
        return $this->belongsTo(PersonalJob::class , 'job_id' , 'id');
    }


}
