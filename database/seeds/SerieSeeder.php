<?php

use App\Models\Serie;
use Illuminate\Database\Seeder;

class SerieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $serie = new Serie();
        $serie->show_name = "The big bang theory";
        $serie->temporadas = 25;
        $serie->poster_path = "img./ooBGRQBdbGzBxAVfExiO8r7kloA.jpg";
        $serie->fondo_path = "img./nGsNruW3W27V6r4gkyc3iiEGsKR.jpg";
        $serie->total_ep = 500;
        $serie->director_creadores = "Chuck Lorre, Bill Prady";
        $serie->primera_transmision = "2007-09-24";
        $serie->ultima_transmision = "2018-03-29";
        $serie->generos = "Comedia";
        $serie->titulo_esp = "La teoria del big bang";
        $serie->descripcion = "The Big Bang Theory se centra en la vida de cinco personajes que residen en Pasadena, California: los compañeros de piso Leonard Hofstadter y Sheldon Cooper; su vecina Penny, una guapa camarera que aspira a triunfar como actriz; y los amigos y compañeros de trabajo de Leonard y Sheldon (igual de friquis y socialmente inadaptados que ellos), el ingeniero mecánico Howard Wolowitz y el astrofísico Raj Koothrappali. El grado de \"friquismo\" y el intelecto de los cuatro chicos choca de forma cómica con las habilidades sociales y el sentido común de Penny.";
        $serie->save();
    }
}
