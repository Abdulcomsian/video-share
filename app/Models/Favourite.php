<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{User};
class Favourite extends Model
{
    use HasFactory;

    protected $table = "favourites";
    protected $primaryKey = "id";
    protected $fillable = ['editor_id' , 'client_id' ];

    public function client()
    {
        return $this->belongsTo(User::class , 'client_id' , 'id');
    }

    public function editor()
    {
        return $this->belongsTo(User::class , 'editor_id' , 'id');
    }
}
