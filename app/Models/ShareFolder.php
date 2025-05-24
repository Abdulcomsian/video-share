<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{ User , PersonalJob , ShareFolderFiles};

class ShareFolder extends Model
{
    use HasFactory;

    protected $table = "share_folder";
    protected $primaryKey = "id";
    protected $fillable = ['editor_id' , 'job_id' , 'name' ];


    public function editor()
    {
        return $this->belongsTo(User::class , 'editor_id' , 'id');
    }

    public function job()
    {
        return $this->belongsTo(User::class , 'job_id' , 'id');
    }

    public function files()
    {
        return $this->hasMany(ShareFolderFiles::class , 'share_folder_id' , 'id');
    }


}
