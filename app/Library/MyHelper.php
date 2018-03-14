<?php
/**
 * Created by PhpStorm.
 * User: PcRace
 * Date: 10/03/2018
 * Time: 18:44
 */

namespace App\Library;


use App\Models\Episodio;
use App\Models\Serie;
use Illuminate\Support\Facades\Storage;

class MyHelper
{

    /**
     * @param string $seriename
     * @param string $temporada
     * @param string $episodionumber
     * @return string $keywords
     */
    public function generateKeywords($seriename, $temporada, $episodionumber){
        $myk = explode(",", "Ver {$seriename} Online, ver gratis {$seriename} online, ver serie {$seriename} online, ver {$seriename} temporada {$temporada} capitulo {$episodionumber} hd gratis, ver serie {$seriename} temporada {$temporada} episodio {$episodionumber} online gratis, ver estreno {$seriename} online, {$seriename} online ver, Ver serie {$seriename} Español Latino, ver {$seriename} Latino Online, Serie {$seriename} Español Online, serie {$seriename} Subtitulado, en Español, en Español Latino, en Latino, ver serie {$seriename} temporada {$temporada} capitulo {$episodionumber} online gratis, ver {$seriename} hd gratis.");
        return $myk;
    }


    /**
     * @param string $seriename
     * @param string $temporada
     * @param string $episodionumber
     * @return string $keywords
     */
    public function generateKeywordsMovie($moviename){
        $string = "Ver .a. Online, ver gratis .a. online, ver pelicula .a. online, ver pelicula .a. online gratis, ver estreno .a. online, .a. online ver, Ver pelicula .a. Español Latino, ver .a. Latino Online, pelicula .a. Español Online, pelicula .a. Subtitulado, en Español, en Español Latino, en Latino, ver pelicula .a. online gratis, ver .a. hd gratis, ver .a. hd Online.";
        $s = str_replace(".a.",$moviename,$string);
        return $s;
    }

    /**
     * @param $titulo_t
     * @param $temporada_n
     * @param $episodio_n
     * @param $resumen
     * @param $video_url
     * @param $keywords
     * @param $transmitido
     * @param $post_path
     * @param $serie_id
     * @param $id
     * @return bool
     */
    public function agregarEpisodio($titulo_t, $temporada_n, $episodio_n, $resumen, $video_url, $keywords, $transmitido, $post_path, $serie_id, $id){
        $ep = new Episodio();
        $ep->titulo = e($titulo_t);
        $ep->temporada = e($temporada_n);
        $ep->episodio = e($episodio_n);
        $ep->resumen = e($resumen);
        $ep->video_url = e($video_url);
        $ep->keywords = e($keywords);
        $ep->fecha_estreno = e($transmitido);
        $ep->image_path = e($post_path);
        $ep->serie_id = e($serie_id);
        $ep->id_db = e($id);

        return $ep->save();
    }


    public function creadorMasivoEpisodiosTemporadas($serieid, $local_serie_id){
        if (!empty($serie_info = Serie::find($local_serie_id))) {
            $json = file_get_contents("https://api.themoviedb.org/3/tv/{$serieid}?api_key=cc4b67c52acb514bdf4931f7cedfd12b&language=es");
            $serie = json_decode($json);

            if (!isset($serie->seasons)) return '{error: "Show not found"}';

            $epAdded = 0;
            foreach ($serie->seasons as $season) {
                if ($season->season_number !== 0) {
                    $i = 1;
                    while ($i <= $season->episode_count) {
                        $jsonepis = file_get_contents("https://api.themoviedb.org/3/tv/{$serieid}/season/{$season->season_number}/episode/{$i}?api_key=cc4b67c52acb514bdf4931f7cedfd12b&language=es");
                        $episodo = json_decode($jsonepis);

                        $keywords = $this->generateKeywords($serie_info->show_name, $season->season_number, $i);
                        $keywords = implode(",", $keywords);

                        if ($this->agregarEpisodio($episodo->name, $episodo->season_number, $episodo->episode_number, $episodo->overview, "Undefined", $keywords, $episodo->air_date,'https://image.tmdb.org/t/p/w500' . $episodo->still_path, $local_serie_id, $episodo->id)) {
                            $epAdded++;
                        }
                        $i++;
                    }
                }
            }
        }

        return $epAdded;
    }

    /**
     * @param int $serieid
     * @param int $local_serie_id
     * @param int $temporada
     * @return int
     */
    public function creadorEpisodiosPorTemporada($serieid, $local_serie_id, $temporada){
        if (!empty($serie_info = Serie::find($local_serie_id))) {
            $json = file_get_contents("https://api.themoviedb.org/3/tv/{$serieid}?api_key=cc4b67c52acb514bdf4931f7cedfd12b&language=es");
            $serie = json_decode($json);

            if (!isset($serie->seasons)) return '{error: "Show not found"}';

            $epAdded = 0;
            foreach ($serie->seasons as $season) {
                if ($season->season_number === $temporada){
                    $i = 1;
                    while ($i <= $season->episode_count) {
                        $jsonepis = file_get_contents("https://api.themoviedb.org/3/tv/{$serieid}/season/{$season->season_number}/episode/{$i}?api_key=cc4b67c52acb514bdf4931f7cedfd12b&language=es");
                        $episodo = json_decode($jsonepis);

                        $keywords = $this->generateKeywords($serie_info->show_name, $season->season_number, $i);
                        $keywords = implode(",", $keywords);

                        $episode = Episodio::select('titulo', 'video_url', 'keywords', 'resumen', 'temporada')
                            ->where('serie_id', $local_serie_id)
                            ->where('temporada', $season->season_number)
                            ->where('episodio', $i)
                            ->first();

                        if (empty($episodie)) {
                            if ($this->agregarEpisodio($episodo->name, $episodo->season_number, $episodo->episode_number, $episodo->overview, "Undefined", $keywords, $episodo->air_date, 'https://image.tmdb.org/t/p/w500' . $episodo->still_path, $local_serie_id, $episodo->id)) {
                                $epAdded++;
                            }
                        }
                        $i++;
                    }
                }
            }
        }

        return $epAdded;
    }

    public function urlDesdeDepuracion($codigoDepuracion){
        $obj = json_decode($codigoDepuracion);
        return $obj->euri;
    }
}