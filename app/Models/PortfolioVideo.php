<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{User};

class PortfolioVideo extends Model
{
    use HasFactory;
    protected $table = "portfolio_video";
    protected $primaryKey = "id";
    protected $fillable = ['user_id' , 'video_url' ];

    public function user(){
        return $this->belongsTo(User::class , 'user_id' , 'id');
    }
}
