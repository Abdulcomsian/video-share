<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    protected $table = "skills";
    protected $primaryKey = "id";
    protected $fillable = ['title' , 'skillable_id' , 'skillable_url' ];

    public function skillable()
    {
        return $this->morphTo();
    }
    
}
