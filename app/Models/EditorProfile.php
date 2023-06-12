<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EditorProfile extends Model
{
    use HasFactory;
	
    protected $table = "editor_profile";
    protected $primaryKey = "id";
    protected $fillable = [
        "editor_id",
        "title",
        "bio",
        "service_offering",
        "amount_per_hour"
    ];

}
