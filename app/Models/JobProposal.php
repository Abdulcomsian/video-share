<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{JobPayment};

class JobProposal extends Model
{
    use HasFactory;

    protected $table = "requests";
    protected $primaryKey = "id";
    protected $fillable = [ "description" , "bid_price" , 'status'];

    public function payment()
    {
        return $this->hasOne(JobPayment::class , 'request_id' , 'id');
    }

}
