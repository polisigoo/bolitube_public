<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MovieController extends Controller
{

    public function watchMovie($movieuri){
        $movie = $movieuri;

        dd($movie);

        return view('watchMovie');
    }
}
