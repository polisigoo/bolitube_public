<?php

namespace App\Http\Controllers;

use App\Library\GernerateUniqueID;

use App\Library\MyHelper;
use App\Models\Episodio;
use App\Models\Serie;
use App\Movie;
use App\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use JildertMiedema\LaravelPlupload\Facades\Plupload;
use Symfony\Component\HttpFoundation\Session\Session;

class PageController extends Controller
{

    public function serielist(){
        $series = Serie::paginate(20);

        return view('list')->with(compact('series'));
    }


    public function listCategoria($categoria){
        $carpetas = DB::table('videos')->select('carpeta_season')
            ->where('categoria', e($categoria))
            ->orderBy('id', 'desc')
            ->distinct()
            ->get();

        if ($carpetas->isEmpty()){
            $data['title'] = '404';
            $data['name'] = 'Page not found';

            return response()
                ->view('errors.404',$data,404);
        }

        return view('categorialist')->with(compact('videos', 'carpetas', 'categoria'));
    }


    public function welcome(){
        return view('welcome');
    }

    public function index(){

        /*$videos = DB::table('videos')->get(); //DB:table porque se puede mixear con las coleciones
        $videos = $videos->shuffle();*/

        $series = Serie::paginate(12);

        $generos = Serie::select('generos')->distinct()->get();

        $ultimostres = Serie::latest()->take(20)->get(); //latest tiene en cuenta el created_at

        return view('index')->with(compact('series', 'generos','ultimostres'));
    }

    public function uploads(){
        return view('upload');
    }

    public function tags($tagname){
        $tag = htmlentities(addslashes($tagname));

        $tag = '%' . $tag . '%';

        $videos = Video::select('titulo', 'unique_id', 'created_at', 'image_name', 'categoria', 'carpeta_season')
            ->where('tags', 'LIKE', $tag)->paginate(15);

        return view('tags')->with(compact('videos'));
    }

    public function miniatures(){

        $categorias = DB::table('videos')->select('categoria')
            ->orderBy('id', 'desc')
            ->distinct()
            ->get();

       /* $carpeta = DB::table('videos')->select('carpeta_season')
            ->orderBy('categoria', 'id', 'carpeta_season', 'desc')
            ->distinct()
            ->get();*/

        return view('miniature')
                ->with(compact('categorias'));
    }

    public function getopt(Request $request, $select){
        echo json_encode("AA");
    }

    public function guardarcambios(Request $request, $videoid){

        //Hay que poner todos los campos del form, se puede poner '' para no agregarles regla de valid.
        request()->validate([
            'titulo' => 'required',
            'tags' => 'required'
        ],[
            'titulo.required' => 'Es necesario especificar un titulo',
            'tags.required' => 'Es necesario completar el campo tags'
        ]);

        if ($request->has('rel')) {
            //$url = str_replace(url('/watch'). '/', '', e($request->input('url')));

            /*$video = Video::where('id', e($request->input('rel')))
                ->where('unique_id', '=', e($videoid))
                ->first();*/
            $parts = parse_url(url()->previous());
            $path_parts=explode('/', $parts['path']);


            if ($videoid->unique_id === $path_parts[count($path_parts)-1]) {
                $videoid->titulo = e($request->input('titulo'));

                /*$video->categoria = e($request->input('categoria'));
                $video->carpeta_season = e($request->input('carpeta'));*/

                $videoid->tags = e($request->input('tags'));

                $videoid->save();


                $request->session()->flash('success_msg', 'El video fue editado correctamente');

                return redirect(url()->previous());
            }else{
                $request->session()->flash('error_msg', 'Ocurrió un error mientras validábamos el video');

                return redirect(url()->previous());
            }
        }else{
            return redirect('/list');
        }

        //Storage::disk('public')->put('cambios.txt', $request->input('id'));
    }

    public function search(Request $request){
        $search = e($request->input('query_s'));

        $search = empty($search) ||
                  preg_match("/[0-9]/" , $search) ||
                  strlen($search) < 3 ? '123' : $search;

        $videos_tag = DB::table('videos')->where('tags', 'LIKE', "%". $search . "%")->take(8)->get();

        $videos_titulo = DB::table('videos')->where('titulo', 'LIKE', "%". $search)->take(8)->get();

        $videos_final = $videos_tag->merge($videos_titulo);

        $videos_final->unique();

        $categorias = DB::table('videos')
            ->select('categoria')
            ->where('categoria', 'LIKE', "%" . $search . "%")
            ->distinct()
            ->take(7)
            ->get();

        $carpetas = DB::table('videos')
            ->select('carpeta_season')
            ->where('carpeta_season', 'LIKE', "%" . $search . "%")
            ->distinct()
            ->take(7)
            ->get();


        $tags = DB::table('videos')
            ->select('tags')
            ->where('tags', 'LIKE', "%" . $search . "%")
            ->distinct()
            ->take(7)
            ->get();

        /** Get distinct tags */
        if (!$tags->isEmpty()) {
            $tagsvacio = false;
            foreach ($tags as $tag) {
                $new_arr[] = $tag->tags;
            }
            $res_arr = implode(',', $new_arr);
            $tags = explode(",", $res_arr);
            $tags = array_unique($tags);
        }else{
            $tagsvacio = true;
        }

        return view('search')->with(compact('videos_final', 'categorias', 'carpetas', 'tags', 'tagsvacio'));
    }

    public function refreshToken(Request $request){
        $oldToken = e($request->input('otkn'));
        $ip =  e($request->input('ip'));

        $tken = new GernerateUniqueID();
        $tken = $tken->verify_and_return($oldToken, $ip);
        return $tken;
    }

    public function createSerie(){
        return view('crearserie');
    }

    public function searchSerie(Request $request){
        $name = str_replace(" ", '%20',$request->input('title'));

        if ($p_ep = !empty($request->input('fecha'))){
            $json = file_get_contents("https://api.themoviedb.org/3/search/tv?api_key=cc4b67c52acb514bdf4931f7cedfd12b&language=es&query={$name}&page=1&first_air_date_year={$p_ep}");
            $obj = json_decode($json);
            $id = $obj->results[0]->id;
        }else{
            $json = file_get_contents("https://api.themoviedb.org/3/search/tv?api_key=cc4b67c52acb514bdf4931f7cedfd12b&language=es&query={$name}&page=1");
            $obj = json_decode($json);
            $id = $obj->results[0]->id;
            //$json = $obj->results[0];
            //$json = json_encode($json);
        }

        $json = file_get_contents("https://api.themoviedb.org/3/tv/{$id}?api_key=cc4b67c52acb514bdf4931f7cedfd12b&language=es");

        return $json;
    }

    public function saveSerie(Request $request){
        request()->validate([
            't_original' => 'required',
            'cant_temp' => 'required',
            'resumen' => '',
            'post_path' => 'required',
            'fondo_path' => 'required',
            'generos' => 'required',
            'p_transmision' => 'required',
            'id' => 'required',
            'total_e' => '',
            'creador' => '',
            'u_transmision' => '',
            'titulo_t' => ''
        ],[
            't_original.required' => 'Es necesario especificar un video_url',
            'cant_temp.required' => 'Es necesario completar el campo titulo_t',
            'resumen.required' => 'Es necesario especificar un post_path',
            'post_path.required' => 'Es necesario completar el campo serie_id',
            'fondo_path.required' => 'Es necesario completar el campo transmitido',
            'generos.required' => 'Es necesario especificar un temporada_n',
            'p_transmision.required' => 'Es necesario completar el campo episodio_n',
            'id.required' => 'Es necesario especificar un id_db'
        ]);

        if (!empty($request->input('t_original')) ||
            !empty($request->input('cant_temp'))||
            !empty($request->input('post_path'))||
            !empty($request->input('fondo_path'))||
            !empty($request->input('generos'))) {
            $helper = new MyHelper();

            if (empty(Serie::select('id_db')->where('id_db', e($request->input('id')))->first())) {
                $serie = new Serie();
                $serie->show_name = e($request->input('t_original'));
                $serie->temporadas = e($request->input('cant_temp'));
                $serie->poster_path = e($request->input('post_path'));
                $serie->fondo_path = e($request->input('fondo_path'));
                $serie->total_ep = e($request->input('total_e'));
                $serie->director_creadores = e($request->input('creador'));
                $serie->primera_transmision = e($request->input('p_transmision'));
                $serie->ultima_transmision = e($request->input('u_transmision'));
                $serie->generos = e($request->input('generos'));
                $serie->titulo_esp = e($request->input('titulo_t'));
                $serie->descripcion = e($request->input('resumen'));
                $serie->id_db = e($request->input('id'));
                $serie->uri = preg_replace('[^0-9a-zA-Z]', "", str_replace(" ", '-', strtolower(e($request->input('t_original')))));
                if ($serie->save()) {
                    if ($request->input('todo') === 'crear_todos') {
                        $id_l = Serie::select('id')->where('id_db', e($request->input('id')))->first();

                        $agregados = $helper->creadorMasivoEpisodiosTemporadas($request->input('id'), $id_l->id);

                        return "<h1 style='color: #31e73d; text-align: center'>Serie añadida correctamente con $agregados episodios</h1><a href='" . url('create/serie') . "'>Volver</a>";
                    }

                    return "<h1 style='color: #31e73d; text-align: center'>Serie añadida correctamente/h1><a href='" . url('create/serie') . "'>Volver</a>";
                }
                else{
                    return "<h1 style='color: red;  text-align: center'>Error! No se pudo agregar la serie</h1>";
                }
            }else{
                return "<h1 style='color: red;  text-align: center'>Error! No se pudo agregar la serie porque ya existe!</h1>";
            }
        }
    }

    public function createEpisodio(){
        $series = Serie::select('id','show_name')->get();

        return view('agregarepisodio')->with(compact('series'));
    }

    public function searchEpisodio(Request $request){
        $serie = str_replace(" ", '%20',$request->serie);

        $temporada = str_replace(" ", '%20', str_replace("Temporada ", "",$request->temporada));
        $episodio = str_replace(" ", '%20', preg_replace("/Episodio [0-9]x+/","", $request->episodio));


        if (!empty($serie) && empty($temporada) && empty($episodio)){
            $temporadas = Serie::select('temporadas')->where('id', $serie)->first();
            return $temporadas->temporadas;
        }elseif (!empty($temporada) && !empty($serie) && empty($episodio)){
            $id = Serie::select('id_db')->where('id', $serie)->first();

            $json = file_get_contents("https://api.themoviedb.org/3/tv/{$id->id_db}?api_key=cc4b67c52acb514bdf4931f7cedfd12b&language=es");

            $json = json_decode($json);

            if(!isset($json->seasons[$request->temporada])){
                return $json->seasons[$request->temporada - 1]->episode_count;
            }elseif($json->seasons[$request->temporada]->name === ["Specials", "Extras"]){
                return $json->seasons[$request->temporada + 1]->episode_count;
            }

            return $json->seasons[$request->temporada]->episode_count;

        }elseif (!empty($episodio) && !empty($temporada) && !empty($serie)){
            $id = Serie::select('id_db', 'show_name')->where('id', $serie)->first();
            $json = file_get_contents("https://api.themoviedb.org/3/tv/{$id->id_db}/season/{$temporada}/episode/{$episodio}?api_key=cc4b67c52acb514bdf4931f7cedfd12b&language=es");

            $obj = json_decode($json);

            $myk = new \App\Library\MyHelper();
            $key = $myk->generateKeywords($id->show_name, $temporada, $episodio);
            $keywords = implode(",", $key);
            $obj->keywords = $keywords;

            $json = json_encode($obj);

            return $json;
        }
    }

    public function saveEpisodio(Request $request){
        request()->validate([
            'serie_id' => 'required',
            'titulo_t' => 'required',
            'resumen' => '',
            'transmitido' => 'required',
            'temporada_n' => 'required',
            'episodio_n' => 'required',
            'keywords' => 'required',
            'post_path' => 'required',
            'video_url' => 'required',
            'id' => ''
        ],[
            'video_url.required' => 'Es necesario especificar un video_url',
            'titulo_t.required' => 'Es necesario completar el campo titulo_t',
            'post_path.required' => 'Es necesario especificar un post_path',
            'serie_id.required' => 'Es necesario completar el campo serie_id',
            'transmitido.required' => 'Es necesario completar el campo transmitido',
            'temporada_n.required' => 'Es necesario especificar un temporada_n',
            'episodio_n.required' => 'Es necesario completar el campo episodio_n'
        ]);

        $helper = new MyHelper();

        $created = $helper->agregarEpisodio($request->titulo_t, $request->temporada_n, $request->episodio_n, $request->resumen, $request->video_url, $request->keywords, $request->transmitido, $request->post_path, $request->serie_id,  $request->id);

        if($created)
            return "<h1 style='color: #31e73d; text-align: center'>Episodio añadida correctamente</h1><a href='".url('create/serie')."'>Volver</a>";
        else
            return "<h1 style='color: red;  text-align: center'>Error! No se pudo agregar el episodio</h1>";
    }


    public function serieAddSeason($serieuri){
        $serie = $serieuri;
        $helper = new MyHelper();

        $creados = $helper->creadorEpisodiosPorTemporada($serie->id_db,$serie->id,intval(request()->tp));

        if ($creados === 0){
            return response()->json(['error' => 'No se pudo crear la temporada...'], 400);
        }
    }

    public function serieEditSave($serieuri){
        request()->validate([
            'rel' => 'required',
            'titulo' => 'required',
            'resumen' => 'required',
            'generos' => ''
        ],[
            'rel.required' => 'Es necesario especificar un rel',
            'titulo.required' => 'Es necesario completar el campo titulo',
            'resumen.required' => 'Es necesario especificar un resumen',
            'generos.required' => 'Es necesario completar el campo generos'
        ]);

        if (request()->rel != $serieuri->id)
            abort(404);

        $serie = Serie::find($serieuri->id);

        if (!empty(request()->titulo) && $serie->show_name != request()->titulo) {
            $serie->show_name = e(request()->titulo);
        }

        if (!empty(request()->resumen) && $serie->descripcion != request()->resumen) {
            $serie->descripcion = e(request()->resumen);
        }

        if ($serie->generos != request()->generos) {
            $serie->generos = e(request()->generos);
        }

        if ($serie->save())
            return "Cambios guardados";
        else
            return "Error al guardar los cambios";
    }

    public function createMovie(){

        return view("crearpelicula");
    }

    public function searchMovie(Request $request){
        $name = str_replace(" ", '%20',$request->input('title'));

        if ($estreno = !empty($request->input('fecha'))){
            $json = file_get_contents("https://api.themoviedb.org/3/search/movie?api_key=cc4b67c52acb514bdf4931f7cedfd12b&language=es&query={$name}&page=1&include_adult=true&year={$estreno}");

            $obj = json_decode($json);
            $id = $obj->results[0]->id;
        }else{
            $json = file_get_contents("https://api.themoviedb.org/3/search/movie?api_key=cc4b67c52acb514bdf4931f7cedfd12b&language=es&query={$name}&page=1&include_adult=true");
            $obj = json_decode($json);
            $id = $obj->results[0]->id;
            //$json = $obj->results[0];
            //$json = json_encode($json);
        }

        $json = file_get_contents("https://api.themoviedb.org/3/movie/$id?api_key=cc4b67c52acb514bdf4931f7cedfd12b&language=es");

        return $json;
    }

    public function saveMovie(Request $request){
        request()->validate([
            't_original' => 'required',
            'titulo_t' => 'required',
            'resumen' => 'required',
            'generos' => 'required',
            'video_url' => 'required',
            'id' => 'required',
            'estreno' => '',
            'duracion' => ''
        ],[
            't_original.required' => 'Es necesario especificar un t_original',
            'titulo_t.required' => 'Es necesario completar el campo titulo_t',
            'resumen.required' => 'Es necesario especificar un resumen',
            'generos.required' => 'Es necesario completar el campo generos',
            'id.required' => 'Es necesario completar el campo id',
            'video_url.required' => 'Es necesario completar el campo video_url'
        ]);

        $helper = new MyHelper();

        $myk = new MyHelper();
        $key = $myk->generateKeywordsMovie(e($request->input('titulo_t')));

        $estreno = e($request->input('estreno'));
        if (isset($estreno)){
            $fecha = substr($estreno, 0,4);
            $uri = preg_replace('[^0-9a-zA-Z]', "", str_replace(" ", '-', strtolower(e($request->input('t_original')))));
            $uri .= "-" . $fecha;
        }else{
            $uri = preg_replace('[^0-9a-zA-Z]', "", str_replace(" ", '-', strtolower(e($request->input('t_original')))));
        }


        if (empty(Movie::select('id_db')->where('id_db', e($request->input('id')))->first())) {
            $pelicula = new Movie();
            $pelicula->titulo = e($request->input('titulo_t'));
            $pelicula->titulo_original = e($request->input('t_original'));
            $pelicula->video_url = e($request->input('video_url'));
            $pelicula->fecha_estreno = $estreno;
            $pelicula->duracion = e($request->input('duracion'));
            $pelicula->keywords = $key;
            $pelicula->generos = e($request->input('generos'));
            $pelicula->poster_path = e($request->input('post_path'));
            $pelicula->fondo_path = e($request->input('fondo_path'));
            $pelicula->id_db = e($request->input('id'));
            $pelicula->resumen = e($request->input('resumen'));
            $pelicula->uri = $uri;
            if ($pelicula->save()) {
                return "<h1 style='color: #31e73d; text-align: center'>Serie añadida correctamente/h1><a href='" . url('create/movie') . "'>Volver</a>";
            }
            else{
                return "<h1 style='color: red;  text-align: center'>Error! No se pudo agregar la serie</h1>";
            }
        }else{
            return "<h1 style='color: red;  text-align: center'>Error! No se pudo agregar la serie porque ya existe!</h1>";
        }
    }
}
