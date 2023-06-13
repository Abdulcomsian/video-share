<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{PersonalJob , Files, User};

class Folder extends Model
{
    use HasFactory;

    protected $table = "folders";
    protected $primaryKey = "id";
    protected $fillable = ['client_id' , 'name' ];


    public function project()
    {
        return $this->hasMany( PersonalJob::class , 'folder_id' , 'id' );
    }

    public function files()
    {
        return $this->hasMany(Files::class , 'folder_id' , 'id');
    }

    public function client()
    {
        return $this->belongsTo( User::class , 'client_id' , 'id' );
    }

}
