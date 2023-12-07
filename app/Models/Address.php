<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Country , City};
class Address extends Model
{
    use HasFactory;
    
    protected $table = "address";
    protected $primaryKey = "id";
    protected $fillable = [
        "country_id",
        "city_id",
        "address",
        "language",
        "user_id"
    ];

    public function country(){
        return $this->belongsTo(Country::class , 'country_id' , 'id');
    }

    public function city(){
        return $this->belongsTo(Country::class , 'city_id' , 'id');
    }
}
