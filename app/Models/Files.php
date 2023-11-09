<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{ Folder , Comment };

class Files extends Model
{
    use HasFactory;

    protected $table = "files";
    protected $primaryKey = "id";
    protected $fillable = ['folder_id' , 'path' , 'extension' , 'type' ,'thumbnail' ];

    public function folder()
    {
        return $this->belongsTo(Folder::class , 'folder_id' , 'id');
    }

    public function comments(){
        return $this->morphMany(Comment::class , 'commentable');
    }
}
