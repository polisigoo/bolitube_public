<?php
/**
 * Created by PhpStorm.
 * User: PcRace
 * Date: 10/03/2018
 * Time: 18:44
 */

namespace App\Library;


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
}