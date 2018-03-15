<?php

namespace App\Http\Controllers;

use App\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{

    public function watchMovie($movieuri){
        $movie = $movieuri;

        $generos = Movie::select('generos')->distinct()->get();

        return view('watchMovie')->with(compact('movie', 'generos'));
    }

    public function movielist(){
        $movies = Movie::paginate(20);

        return view('listMovies')->with(compact('movies'));
    }
}
