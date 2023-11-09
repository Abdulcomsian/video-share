<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ShareFolder;

class ShareFolderFiles extends Model
{
    use HasFactory;

    protected $table = "share_folder_files";
    protected $primaryKey = "id";
    protected $fillable = ['share_folder_id' , 'path' , 'extension' , 'type' , 'files'];



    public function folder()
    {
        return $this->belongsTo(ShareFolder::class , 'share_folder_id' , 'id');
    }

    public function comments(){
        return $this->morphMany(Comment::class , 'commentable');
    }


}
