<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setPasswordAttribute($password){
        $this->attributes['password'] = Hash::make($password);
    }

    public function image() {
        return $this->hasOne('App\Image');
    }
    //$user->image->pluck('image')->first();

    public function info() {
        return $this->hasOne('App\Info');
    }
    //$user->info->pluck('name')->first();
    //$user->info->pluck('job')->first();
    //$user->info->pluck('phone')->first();
    //$user->info->pluck('address')->first();

    public function media() {
        return $this->hasOne('App\Media');
    }
    //$user->media->pluck('vkontakte')->first();
    //$user->media->pluck('telegram')->first();
    //$user->media->pluck('instagram')->first();

    public function status() {
        return $this->hasOne('App\Status');
    }
    //$user->status->pluck('status')->first();
}
