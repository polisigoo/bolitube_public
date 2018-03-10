<?php

use App\Video;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Library\GernerateUniqueID;

class VideosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

       /* $videoid = "3sqxa6oa7144";

        $video_info = Video::select('categoria', 'carpeta_season', 'image_name', 'id')->where('unique_id', '=', e($videoid))->first();

        $video = Video::find($video_info->id);

        $video->image_name = $videoid;

        $video->save();*/

        /*$video = new \App\Video;

        $video->titulo = "Prueba";
        $video->categoria = "Prueba";
        $video->carpeta_season = "Prueba";
        $video->mime_type = "Prueba";
        $video->file_name = "Prueba";
        $video->unique_id = "Prueba";
        $video->tags = "Prueba";

        $video->save();*/

        /*
        $anterior = DB::table('videos')
            ->select('unique_id', 'titulo')
            ->where('categoria', '=', 'Series')
            ->where('carpeta_season', '=', 'Arrested_develop_S1')
            ->where('id','=', 10 - 1)->get();

        $exist = !$anterior->isEmpty();

        dd($anterior[0]->unique_id);*/



        //AND
        /*$related = DB::table('videos')->select('unique_id', 'titulo', 'created_at')->where('categoria', '=', 'Series')->where('carpeta_season', '=', 'Arrested_develop_S1')->get();
        dd($related);*/




        /* $videos = DB::table('videos')->select('titulo', 'unique_id')->where('tags', 'LIKE', '%php%')->get();

         dd($videos);*/

        /*$cant = DB::table('videos')->select('carpeta_season')->where('categoria', '=', 'Series')->count();

        dd($cant);*/

        /*$lista = DB::table('videos')->select('categoria', 'carpeta_season')->orderBy('categoria', 'id', 'carpeta_season', 'desc')->distinct()->get();

        dd($lista[1]->categoria . ' -> ' . $lista[1]->carpeta_season);


                                                    */
//        $file_info= DB::table('videos')->select('file_name', 'categoria', 'carpeta_season', 'titulo')->where('unique_id', '=', '68axn5ood70o')->get();
//
//        $existe = !$file_info->isEmpty() ? "tiene algo" : "esta vacio";
//
//        dd($file_info[0]->file_name . ' / '. $file_info[0]->categoria . ' / '. $file_info[0]->carpeta_season . ' / '. $file_info[0]->titulo);



        /*$videos = DB::table('videos')->select('titulo', 'unique_id')->distinct()->get();

        dd($videos[0]->unique_id);*/

        //Check if exist
        //$exist = DB::table('videos')->where('unique_id', '2mwachhmlaw4')->exists();


        //Add video
        /*$a = new GernerateUniqueID();
        $id = $a->generate_random_id_yt();

        DB::insert('insert into videos (titulo, categoria, carpeta_season, mime_type, file_name, unique_id, tags) values (:titulo, :categoria, :carpeta, :mime, :file_name, :unique_id, :tags)', [':titulo' => 'bb',':categoria' => 'aa', ':carpeta' => 'aa', ':mime' => 'video', ':file_name' => 'gge',':unique_id' => $id,':tags' => 'abc']);
        */
    }
}
