<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Laravel\Passport\HasApiTokens;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
//    use HasApiTokens, Notifiable, CanResetPassword, EntrustUserTrait, SoftDeletes;
    use HasApiTokens, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'username', 'email', 'password'
    ];

    protected $hidden = [
        'password', 'remember_token', 'verified', 'verification_token', 'deleted_at',
        'former_id', 'former_pwd', 'email', 'created_at', 'updated_at'
    ];

    protected $dates = ['deleted_at'];

    /**
     * Get the user profile.
     */
    public function profile()
    {
        return $this->hasOne('App\Models\UserProfile');
    }

    /**
     * Get all of the user's resources.
     */
    public function resources()
    {
        return $this->hasMany('App\Models\Resource');
    }

    /**
     * Get all of the user's likes.
     */
    public function likes()
    {
        return $this->hasMany('App\Models\Like');
    }

    /**
     *  Get all the user collections
     */
    public function collections()
    {
        return $this->hasMany('App\Models\Collection');
    }

    /**
     *  Get all the user polls
     */
    public function polls()
    {
        return $this->belongsToMany('App\Models\Poll');
    }

    /**
     *  Get all the user votes
     */
    public function pollVote()
    {
        return $this->hasMany('App\Models\PollVote');
    }

    public function getJWTIdentifier()
    {
        // Eloquen model method
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
