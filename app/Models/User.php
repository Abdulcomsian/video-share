<?php

namespace App\Models;

use App\Http\AppConst;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\{ PersonalJob , Comment , Skill ,  EditorProfile , Education , Address , Folder , EditorRequest};
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
// use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable , SoftDeletes;

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
        'token',
        'remember_token',
        'profile_image',
        'notification_status'
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

    protected static function booted()
    {
        static::addGlobalScope('videoDetail' , function(Builder $builder){
            $builder->with('portfolioVideo');
        });
    }

    public function comments()
    {
        return $this->hasMany(Comment::class , 'user_id' , 'id');
    }

    public function skills()
    {
         return $this->morphMany(Skill::class, 'skillable');
        // return $this->hasMany(Skill::class, 'skillable_url')->where('skillable_url', self::class);;
    }

    public function favourite()
    {
        return $this->hasMany(Favourite::class , 'client_id', 'id');
    }

    public function editorProfile()
    {
        return $this->hasOne(EditorProfile::class , 'editor_id' , 'id');
    }

    public function portfolio()
    {
        return $this->hasMany(EditorPortfolio::class , 'editor_id' , 'id');
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

    public function bid()
    {
        return $this->belongsToMany(PersonalJob::class ,'job_editor_request' ,'editor_id' , 'job_id');
    }

    public function biddings()
    {
        return $this->hasMany(EditorRequest::class , 'editor_id' , 'id');
    }

    public function cancelJob()
    {
        return $this->hasMany(EditorRequest::class , 'editor_id', 'id')->where('status' , AppConst::CANCEL_JOB);
    }

    public function doneJob()
    {
        return $this->hasMany(EditorRequest::class , 'editor_id', 'id')->where('status' , AppConst::DONE_JOB);
    }

    public function socialLink()
    {
        return $this->hasMany(SocialLink::class , 'user_id' , 'id');
    }

    public function portfolioVideo()
    {
        return $this->hasOne(PortfolioVideo::class , 'user_id' , 'id');
    }



}
