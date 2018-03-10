<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $table = 'videos';

    protected $fillable = ['titulo','categoria','carpeta_season','mime_type','file_name','unique_id', 'tags','image_name'];

    public function getRouteKeyName()
    {
        return 'unique_id';
    }
}
