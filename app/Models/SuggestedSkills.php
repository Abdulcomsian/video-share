<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuggestedSkills extends Model
{
    use HasFactory;
    protected $table = "suggested_skills";
    protected $primaryKey = "id";
    protected $fillable = [
        "name",
    ];

}
