<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPayment extends Model
{
    use HasFactory;

    protected $table = "job_payment";
    protected $primaryKey = "id";
    protected $fillable = ['job_id' , 'request_id'  , 'client_transfer_status' , 'editor_transfer_status' , 'client_payment_date', 'editor_payment_date'];

    

}
