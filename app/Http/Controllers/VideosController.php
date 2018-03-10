<?php

namespace App\Http\Controllers;

use App\Library\GernerateUniqueID;
use App\Library\ImageQuality;
use App\Token;
use App\Video;

use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use JildertMiedema\LaravelPlupload\Facades\Plupload;
use Symfony\Component\HttpFoundation\Session\Session;

use Illuminate\Http\Request;

class VideosController extends Controller
{
    public function stream(Request $request, $videoid){

        /** GET INFO FROM DB AND START STREAM */
        if (!empty($videoid)) {
            if($request->session()->get('ssid') ===  $videoid->unique_id){

                $tokenrecibido = e($request->input('tn'));
                $recivedip = e($request->input('ip'));
                $token = Token::where('token', $tokenrecibido)->first();

                if ($token !== null) {
                    if ($recivedip === $token->ip) {
                        $date1 = new DateTime($token->created_at);
                        $date2 = new DateTime(date("Y-m-d H:i:s"));
                        $interval = $date1->diff($date2);
                        $diferencia = $interval->h;
                        //dd($diferencia);

                        if ($diferencia < 2) {
                            $videosDir = storage_path() . '/videos/';

                            if (file_exists($filePath = $videosDir . "/" . $videoid->categoria . '/' . $videoid->carpeta_season . '/' . $videoid->file_name)) {
                                $stream = new \App\Library\VideoStream($filePath);
                                return response()->stream(function () use ($stream) {
                                    $stream->start();
                                });
                            }
                        } else {
                            //Eliminamos el token
                            $token->delete();
                        }
                    }
                }
            }
        }
        // }

        return response("File doesn't exists or you do not have permission", 404);
    }

    public function watch(Video $videoid)
    {
        //session(['key' => '990']);

        $video = $videoid;

        $file_info= DB::table('videos')
            ->select('file_name', 'categoria', 'carpeta_season', 'titulo', 'tags', 'id', 'image_name')
            ->where('unique_id', '=', $video->id)
            ->get();

        $videosDir = storage_path() . '/videos/';

        $ant = $video->id - 1;
        $sig = $video->id + 1;

        /**  GET LIST ALL categories AND folders */
        $lista = DB::table('videos')->select('categoria', 'carpeta_season')
            ->orderBy('categoria', 'id', 'carpeta_season', 'desc')
            ->distinct()
            ->get();

        /** GET ALL RELATED VIDEOS, IN THE SAME category AND folder */
        $related = DB::table('videos')
            ->select('unique_id', 'titulo', 'created_at', 'image_name')
            ->where('categoria', '=', $video->categoria)
            ->where('carpeta_season', '=', $video->carpeta_season)->get();

        /** GET Previous AND Following VIDEOS */
        /*$anterior = DB::table('videos')
            ->select('unique_id', 'titulo')
            ->where('categoria', '=', $video->categoria)
            ->where('carpeta_season', '=', $video->carpeta_season)
            ->where('id','=', $ant)->get();*/
        $anterior = DB::table('videos')
            ->select('unique_id', 'titulo')
            ->orderBy('created_at', 'desc')
            ->where('categoria', $video->categoria)
            ->where('carpeta_season', '=', $video->carpeta_season)
            ->where('id','<=', $ant)->get()->first();

        /*$siguiente = DB::table('videos')
            ->select('unique_id', 'titulo')
            ->where('categoria', '=', $video->categoria)
            ->where('carpeta_season', '=', $video->carpeta_season)
            ->where('id','=', $sig)->get();*/
        $siguiente = DB::table('videos')
            ->select('unique_id', 'titulo')
            ->orderBy('created_at', 'asc')
            ->where('categoria', $video->categoria)
            ->where('carpeta_season', '=', $video->carpeta_season)
            ->where('id','>=', $sig)->get()->first();

        /** EXPLODE TAGS BY COMMA */
        $tags = explode(",", $video->tags);

        /** IF FILE EXIST GET name AND RETURN ALL THE INFO OF THE FILE*/
        if (file_exists($filePath = $videosDir . "/" . $video->categoria . '/' . $video->carpeta_season . '/' . $video->file_name)) {
            $video_src = "video/" . $video->unique_id;
            $mime = "video/mp4"; //Dejar asi porque con el mime_type da error hasta por un .mkv ...

            // $title = DB::select('select title from videos where uniqueid = ?', $video);
            $title = $video->titulo;

            $image_name = $video->image_name;
            $categoria = $video->categoria;
            $carpeta = $video->carpeta_season;
            $videoid = $video->unique_id;

            return view('watch')->with(compact('video_src', 'mime', 'title', 'lista', 'tags', 'related', 'anterior', 'siguiente', 'videoid', 'categoria','carpeta', 'image_name'));
        }else{
          //  return redirect('/list');
        }

    }

    public function videoUpload(){
//        if (empty($_REQUEST["categoria"])){
//            return abort(425, 'El campo CATEGORIA es requerido.');
//        }

        //Redireccionamos y no se guarda. Los mensajes estan al pedo igual porque esto se hace a través de ajax
        request()->validate([
            'categoria' => 'required',
            'carpeta' => 'required'
        ], [
            'categoria.required' => 'El campo categoria es obligatorio',
            'carpeta.required' => 'El campo carpeta es obligatorio'
        ]);


        return Plupload::receive('file', function ($file)
        {
            date_default_timezone_set("America/Buenos_Aires");
            $hora = date('Y-m-d H:i:s');

            $file_name = $file->getClientOriginalName();

            $ext = '.' . $file->getClientOriginalExtension();

            $uniqid = new GernerateUniqueID();
            $id = $uniqid->generate_random_id_yt();
            $entropy = 0;

            /** GENERATE A UNIQUE ID 100% */
            while (DB::table('videos')->where('unique_id', $id)->exists()){
                $id = $uniqid->generate_random_id_yt($entropy);

                if ($entropy <= 3){
                    $entropy++;
                }else{
                    $entropy = 0;
                }
            }

            /** GET AND REFORMAT TEXT */
            $categoria = isset($_REQUEST["categoria"]) ? $_REQUEST["categoria"] : "undefined";

            $carpeta = isset($_REQUEST["carpeta"]) ? str_replace(" ","_",$_REQUEST["carpeta"]) : "undefined";

            $titulo = str_replace(['.-','.mp4','.avi','.mkv', 'www','[',']','HDTV','XviD','.com','DivxTotaL'], '', $file_name);
            $titulo = str_replace(['.'], ' ', $titulo);
            $titulo = trim(strip_tags($titulo));

            $tags = isset($_REQUEST["tags"]) ? $_REQUEST["tags"] : "undefineds";

            $tipo = isset($_REQUEST["tipo"]) ? $_REQUEST["tipo"] : "video/mp4";

            $file->move(storage_path() . '/videos/' . $categoria.'/' . $carpeta . '/', $id . $ext);

            /** INSERT VIDEO INTO DB */
            $video = new Video;

            $video->titulo = e($titulo);
            $video->categoria = e($categoria);
            $video->carpeta_season = e($carpeta);
            $video->mime_type = e($tipo);
            $video->file_name = e($id . $ext);
            $video->unique_id = e($id);
            $video->tags = e($tags);

            $video->save();


            return 'ready';
        });
    }

    public function editvideo(Video $video){
        $titulo = $video->titulo;
        $categoria = $video->categoria;
        $carpeta = $video->carpeta_season;
        $tags = $video->tags;
        $id = $video->id;
        $image_name = $video->image_name;
        $videoid = $video->unique_id;

        return view('editvideo')->with(compact('titulo', 'categoria', 'carpeta', 'tags', 'id', 'image_name', 'videoid'));
    }


    public function savechanges(Video $video){
        return Plupload::receive('file', function ($file) use (&$video) {

            $parts = parse_url(url()->previous());
            $path_parts=explode('/', $parts['path']);

            if ($video->unique_id === $path_parts[count($path_parts)-1]) {

                $ext = '.' . $file->getClientOriginalExtension();
                $path = storage_path() . '/app/public/images/' . $video->categoria .'/' . $video->carpeta_season . '/';

                $newvideo = Video::find($video->id);

                $newvideo->image_name = $video->unique_id . $ext;

                $newvideo->save();

                //$image = imagecreatefromjpeg($file->getClientOriginalName());
                //imagejpeg($file->getClientOriginalName(), $path . $video->unique_id. 'hq' . $ext, 50);

                /*$image = Image::make($file);

                // Save the orignal image
                $image->save($path);*/


                $file->move($path, $video->unique_id . $ext);

                $imgQ = new ImageQuality();
                $d = $imgQ->compress($path . $video->unique_id . $ext, $path .'hq'.$video->unique_id .$ext, 50);

                $d = $imgQ->compress($path . $video->unique_id . $ext, $path .'max_res'.$video->unique_id .$ext, 95);

                unlink($path . $video->unique_id .$ext);

                return 'ready';
            }
        });
    }
}
