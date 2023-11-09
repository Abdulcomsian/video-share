<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
class Comment extends Model
{
    use HasFactory;

    protected $table = "comments";
    protected $primaryKey = "id";
    protected $fillable = ['user_id' , 'commentable_id' , 'commentable_type' , 'comment_text'];

    public function commentable(){
        return $this->morphTo();
    }

    public function user(){
        return $this->belongsTo(User::class , 'user_id' , 'id');
    }

}
