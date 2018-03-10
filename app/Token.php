<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    protected $table = 'tokens';

    protected $fillable = ['token','ip'];

    public function getRouteKeyName()
    {
        return 'token';
    }
}
