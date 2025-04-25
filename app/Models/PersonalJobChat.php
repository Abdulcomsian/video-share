<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalJobChat extends Model
{
    use HasFactory;

    protected $fillable = [
        'personal_job_id',
        'sender_id',
        'message',
        'is_read'
    ];

    protected $hidden = [
        'is_read'
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id')->select('id', 'full_name', 'email', 'type', 'profile_image');
    }

}
