<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class DislikedShops extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'disliked_shops';
    
    protected $fillable = [
        'shop_id'
    ];

}
