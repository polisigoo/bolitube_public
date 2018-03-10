<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use JildertMiedema\LaravelPlupload\Facades\Plupload;

//Route::get('/', 'PageController@welcome');

Route::get('/', 'PageController@index')->name('index');

Route::get('/watch', function (){
    return redirect('/list');
});

/** RELATED WITH VIDEOS */
//Obtenemos el id del video a ver y devolvemos la vista con el id del video, mime y titulo
// En la vista es cuando llamamos a la ruta /video/{filename}
Route::get('/watch/{videoid}', 'VideosController@watch')->name('video.watch');

//Obtiene el nombre del video y realiza el stream en eg: localhost/routes/public/video/arrested.mkv
Route::get('/video/{videoid}', 'VideosController@stream')->name('video.stream');

//Recepcion de los videos a subir
Route::post('/upload', "VideosController@videoupload");

//Lista de videos
Route::get('/list', 'PageController@list')->name('video.list');

//Lista de videos
Route::get('/list/{categoria}', 'PageController@listCategoria')->name('video.list.categoria');

//Formulario para subir videos
Route::get('/uploads', 'PageController@uploads')->name('video.upload');



//Formulario para crear serie
Route::get('/create/serie', 'PageController@createSerie')->name('create.serie');

//Formulario para crear serie
Route::post('/create/serie', 'PageController@saveSerie');



//Formulario para crear episodio
Route::get('/create/episodio', 'PageController@createEpisodio')->name('create.episodio');

//Formulario para crear episodio
Route::post('/create/episodio', 'PageController@saveEpisodio');



//Entrega el formulario para editar la info. del video
Route::get('/edit/video/{videoid}',"VideosController@editvideo")
    ->where('videoid', '[A-Za-z0-9]+')
    ->name('edit.video');

//guarda la miniatura del video
Route::post('/edit/video/{videoid}/save',"VideosController@savechanges")->where('videoid', '[A-Za-z0-9]+');

//guarda los datos modificados
Route::post('/save/{videoid}', "PageController@guardarcambios")->where('videoid', '[A-Za-z0-9]+');

/** SUBTITULOS */

//Recepcion de los subtitulos
Route::post('storage/create', "SubtitleController@upload");


/** Refresh token */
Route::post('/videos/retken', 'PageController@refreshToken')->name('videos.refreshtoken');


/** TAGS */
Route::get('/tag/{tagname}', "PageController@tags")->name('tag');

/** SEARCH */
Route::get('/search', 'PageController@search')->name('search');

//Search serie
Route::post('/search/serie', 'PageController@searchSerie')->name('search.serie');


//Search episodio
Route::post('/search/episodio', 'PageController@searchEpisodio')->name('search.episodio');

/** Others */
Route::get('/test', function () {
    $myk = new \App\Library\MyHelper();
    $key = $myk->generateKeywords('Shameless', '2', '5');

    $keywords = implode(",", $key);

    $json = file_get_contents('https://api.themoviedb.org/3/tv/1418/season/11/episode/2?api_key=cc4b67c52acb514bdf4931f7cedfd12b&language=es');
    $obj = json_decode($json);

    $obj->keywords = $keywords;

    dd($obj);
});

Route::post('/multfunc', function (){
    if (!empty(request()->input('c-mode'))){
        $c = request()->input('c-mode');
        session(['c-mode' => $c]);

        return 'saved';
    }else{
        return 'error';
    }
});

Route::post('/getopt/{select}', "PageController@getopt");

//Formulario de prueba, borrar
Route::get('/formulario', function (){
    return view('new');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
