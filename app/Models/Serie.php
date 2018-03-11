<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Serie extends Model
{

    public function getRouteKeyName()
    {
        return 'uri';
    }
}
