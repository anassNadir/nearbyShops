<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Shop extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'shops';

    
    protected $hidden = [
        'users_liked_ids',
    ];

    /**
    * relationShip => shop liked by many users
    */
    public function likedBy()
    {
        return $this->belongsToMany('App\User', null, 'preferred_shops_ids', 'users_liked_ids');
    }

     /**
     * relationShip => shop disliked many times
     */
    public function beenDisliked()
    {
        return $this->hasMany('App\DislikedShops', 'shop_id');
    }

}
