<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\{ PersonalJob , Comment , Skill ,  EditorProfile , Education , Address , Folder };
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type',
        'full_name',
        'email',
        'phone_number',
        'email_verified_at',
        'password',
        'verification_code',
        'remember_token',
        'profile_image'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function comments()
    {
        return $this->hasMany(Comment::class , 'user_id' , 'id');
    }

    public function skills()
    {
        return $this->hasMany(Skill::class, 'skillable');
    }

    public function favourite()
    {
        return $this->hasMany(Favourite::class , 'client_id', 'id');
    }

    public function editorProfile()
    {
        return $this->hasOne(EditorProfile::class , 'user_id' , 'id');
    }

    public function portfolio()
    {
        return $this->hasMany(EditorPortfolio::class , 'user_id' , 'id');
    }

    public function education()
    {
        return $this->hasMany(Education::class , 'user_id' , 'id');
    }

    public function address()
    {
        return $this->hasOne(Address::class , 'user_id' , 'id');
    }

    public function folders()
    {
        return $this->hasMany(Folder::class , 'client_id' , 'id');
    }

    public function proposal()
    {
        return $this->belongsToMany(PersonalJob::class ,'job_editor_request' ,'editor_id' , 'job_id');
    }


}
