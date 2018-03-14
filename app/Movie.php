<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{

    public function getRouteKeyName()
    {
        return 'uri';
    }
}
