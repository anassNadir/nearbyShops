<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Jenssegers\Mongodb\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $connection = 'mongodb';
    protected $collection = 'users';
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
        'password', 'preferred_shops_ids',
    ];

    /**
     * relationShip => user has many preferred shops
     */
    public function preferredShops()
    {
        return $this->belongsToMany('App\Shop', null, 'users_liked_ids', 'preferred_shops_ids');
    }

    /**
     * relationShip => user has many disliked shops
     */
    public function dislikedShops()
    {
        return $this->hasMany('App\DislikedShops', 'user_id');
    }
}
