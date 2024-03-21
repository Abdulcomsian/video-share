<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class SocialLink extends Model
{
    use HasFactory;

    protected $table = "social_links";
    protected $primaryKey = "id";
    protected $fillable = ['user_id' , 'platform' , 'url' ];

    public function user()
    {
        return $this->belongsTo(User::class , 'user_id' , 'id');
    }

}
