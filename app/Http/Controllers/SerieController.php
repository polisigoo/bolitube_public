<?php

namespace App\Http\Controllers;

use App\Models\Episodio;
use App\Models\Serie;
use Illuminate\Http\Request;

class SerieController extends Controller
{

    public function serie($serieuri, $temporada, $episodio){
        $episode = Episodio::select('titulo', 'video_url', 'keywords', 'resumen', 'temporada', 'episodio', 'id', 'image_path', 'id_db')
                    ->where('serie_id', $serieuri->id)
                    ->where('temporada', e($temporada))
                    ->where('episodio', e($episodio))
                    ->first();

        if (empty($episode))
            abort(404);

        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );

        //Check updates
        if (rand(0, 100) < 10 && rand(0, 50) > 10) {
            $json = file_get_contents("https://api.themoviedb.org/3/tv/{$serieuri->id_db}/season/{$temporada}/episode/{$episodio}?api_key=cc4b67c52acb514bdf4931f7cedfd12b&language=es", false, stream_context_create($arrContextOptions));
            $obj = json_decode($json);

            $a = false;

            if ($episode->titulo !== $obj->name){
                $episode->titulo = e($obj->name);
                $a = true;
            }

            if ($episode->resumen !== $obj->overview){
                $episode->resumen = e($obj->overview);
                $a = true;
            }

            if ($episode->image_path !== "https://image.tmdb.org/t/p/w500" . $obj->still_path){
                $episode->image_path = "https://image.tmdb.org/t/p/w500" . e($obj->still_path);
                $a = true;
            }

            if ($a){
                $episode->save();
            }
        }

        $episodios = Episodio::where('temporada', e($temporada))
            ->where('serie_id', $serieuri->id)
            ->orderBy('episodio', 'asc')
            ->get();



        $keywords = explode(",", $episode->keywords);

        $anterior = Episodio::select('episodio', 'titulo')
            ->where('serie_id', $serieuri->id)
            ->where('temporada', e($temporada))
            ->where('episodio', e($episodio) - 1)
            ->first();

        $siguiente = Episodio::select('episodio', 'titulo')
            ->where('serie_id', $serieuri->id)
            ->where('temporada', e($temporada))
            ->where('episodio', e($episodio) + 1)
            ->first();

        if(!isset($anterior)){
           $anterior = null;
        }

        if (!isset($siguiente)){
            $siguiente = null;
        }

        /** generos */
        $generos = Serie::select('generos')->get();
        $genders = collect();
        foreach($generos as $genero){
            foreach(explode(',', $genero->generos) as $ga){
                $genders->push($ga);
            }
        }
        $genders = $genders->unique();

        $serie = $serieuri;


        return view('watch')->with(compact(
                            'episode',
                            'keywords',
                            'anterior',
                            'siguiente',
                            'serie',
                            'episodios',
                            'genders'));
    }

    public function episodeslist($serieuri){
        $serie = $serieuri;

        return view('categorialist')->with(compact('serie'));
    }

    public function serieedit($serieuri){
        $serie = $serieuri;

        return view('editserie')->with(compact('serie'));
    }

    public function episodeEdit($serieuri, $temporada, $episodio){
        $serie = $serieuri;

        $episode = Episodio::select('titulo', 'video_url', 'keywords', 'resumen', 'temporada', 'image_path', 'id')
            ->where('serie_id', $serieuri->id)
            ->where('temporada', e($temporada))
            ->where('episodio', e($episodio))
            ->first();

        if (empty($episode))
            abort(404);

        $keywords = $episode->keywords;
        $keywords = explode(",", $keywords);

        for ($i = 0; $i < count($keywords); $i++){
            if ($i + 1 < count($keywords)) {
                $keywords[$i] .= ',';
            }
        }

        return view('editepisode')->with(compact('serie', 'episode', 'keywords'));
    }

    public function episodeSave(){
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

        $ep = Episodio::find(request()->rel);

        if (empty($ep)){
            abort(404);
        }

        if ($ep->titulo != request()->titulo) {
            $ep->titulo = e(request()->titulo);
        }

        if (!empty(request()->resumen) && $ep->resumen != request()->resumen) {
            $ep->resumen = e(request()->resumen);
        }

        if (!empty(request()->video_url) && $ep->video_url != request()->video_url) {
            $videos = array();

            foreach (explode(",", request()->video_url) as $item) {
                $key = encrypt($item);

                array_push($videos, $key);
            }

            $ep->video_url = implode(",", $videos);
        }

        if ($ep->keywords != request()->keywords) {
            $ep->keywords = e(request()->keywords);
        }

        if ($ep->image_path != request()->image_path) {
            $ep->image_path = e(request()->image_path);
        }

        if ($ep->save()){
            return "Guardado";
        }else{
            return "Error. " . var_dump(request()->all());
        }
    }
}
