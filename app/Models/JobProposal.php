<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobProposal extends Model
{
    use HasFactory;

    protected $table = "requests";
    protected $primaryKey = "id";
    protected $fillable = [ "description" , "bid_price"];

}
