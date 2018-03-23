<?php

namespace App\Http\Controllers;

use App\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{

    public function watchMovie($movieuri){
        $movie = $movieuri;

        if (rand(0, 100) < 50 && rand(0, 50) > 20) {
            $view = Movie::whereId($movie->id)->increment('views');
        }

        $generos = Movie::select('generos')->distinct()->get();

        $genders = collect();
        foreach($generos as $genero){
            foreach(explode(',', $genero->generos) as $ga){
                $genders->push($ga);
            }
        }
        $genders = $genders->unique();

        $mas_vistos = Movie::select('uri', 'titulo', 'fondo_path', 'fecha_estreno')
            ->orderBy('views', 'desc')
            ->take(6)
            ->get();

        return view('watchMovie')->with(compact('movie', 'generos', 'mas_vistos', 'genders'));
    }

    public function movielist(){
        $movies = Movie::paginate(20);

        return view('listMovies')->with(compact('movies'));
    }

    public function editMovie($movieuri){
        $movie = $movieuri;

        return view('editMovie')->with(compact('movie'));
    }

    public function editSaveMovie($movieuri){
        $movie = $movieuri;

        request()->validate([
            'rel' => 'required',
            'titulo' => 'required',
            'resumen' => 'required',
            'keywords' => 'required',
            'image_path' => 'required'
        ],[
            'keywords.required' => 'Es necesario especificar un keywords',
            'rel.required' => 'Es necesario especificar un id',
            'resumen.required' => 'Es necesario completar el campo resumen',
            'titulo.required' => 'Es necesario completar el campo titulo',
            'image_path.required' => 'Es necesario completar el campo image_path'
        ]);

        $pelic = Movie::find(request()->rel);

        if (empty($pelic)){
            abort(404);
        }

        if ($pelic->titulo != request()->titulo) {
            $pelic->titulo = e(request()->titulo);
        }

        if (!empty(request()->resumen) && $pelic->resumen != request()->resumen) {
            $pelic->resumen = e(request()->resumen);
        }

        if (!empty(request()->video_url) && $pelic->video_url != request()->video_url) {
            $videos = array();

            foreach (explode(",", request()->video_url) as $item) {
                $key = encrypt($item);

                array_push($videos, $key);
            }

            $pelic->video_url = implode(",", $videos);
        }

        if ($pelic->keywords != request()->keywords) {
            $pelic->keywords = e(request()->keywords);
        }

        if ($pelic->poster_path != request()->image_path) {
            $pelic->poster_path = e(request()->image_path);
        }

        if ($pelic->save()){
            return "Guardado";
        }else{
            return "Error. " . var_dump(request()->all());
        }
    }
}
