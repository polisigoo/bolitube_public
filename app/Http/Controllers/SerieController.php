<?php

namespace App\Http\Controllers;

use App\Models\Episodio;
use Illuminate\Http\Request;

class SerieController extends Controller
{

    public function serie($serieuri, $temporada, $episodio){
        $episode = Episodio::select('titulo', 'video_url', 'keywords', 'resumen', 'temporada')
                    ->where('serie_id', $serieuri->id)
                    ->where('temporada', e($temporada))
                    ->where('episodio', e($episodio))
                    ->first();

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

        $serie = $serieuri;


        return view('watch')->with(compact(
                            'episode',
                            'keywords',
                            'anterior',
                            'siguiente',
                            'serie'));
    }

    public function serieList($serieuri){

    }
}
