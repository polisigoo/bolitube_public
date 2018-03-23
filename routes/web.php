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

Route::get('/series/{serieuri}/{temporada}/{episodio}/', 'SerieController@serie')->name('serie.watch')->where(['temporada' => "[0-9]+", 'episodio' => "[0-9]+"]);


Route::middleware(['auth'])->group(function () {
    Route::get('/series/{serieuri}/{temporada}/{episodio}/edit', 'SerieController@episodeEdit')
        ->name('episode.edit')
        ->where(['temporada' => "[0-9]+", 'episodio' => "[0-9]+"]);


    Route::post('/series/{serieuri}/{temporada}/{episodio}/save', 'SerieController@episodeSave')
        ->name('episode.save')
        ->where(['temporada' => "[0-9]+", 'episodio' => "[0-9]+"]);

    //return view to edit
    Route::get('/movies/{movieuri}/edit', 'MovieController@editMovie')
        ->name('movie.edit');

    //save edited data
    Route::post('/movies/{movieuri}/save', 'MovieController@editSaveMovie')
        ->name('edit.movie.save');

    Route::get('/series/{serieuri}/edit', 'SerieController@serieedit')
        ->name('serie.edit');

    //Ruta usada para guardar cambios desde el formulario
    Route::post('/series/{serieuri}/edit/savechanges', 'PageController@serieEditSave')
        ->name('serie.edit.save');

    //Ruta usada para agregar temporadas
    Route::post('/series/{serieuri}/edit/save', 'PageController@serieAddSeason')->name('serie.addSeason');


    //Recepcion de los videos a subir
    Route::post('/upload', "VideosController@videoupload");


    //Formulario para subir videos
    Route::get('/uploads', 'PageController@uploads')->name('video.upload');

    /** CREATE */

    //Formulario para crear serie
    Route::get('/create/serie', 'PageController@createSerie')->name('create.serie');

    //Formulario para crear serie
    Route::post('/create/serie', 'PageController@saveSerie');

    //Formulario para crear serie
    Route::get('/create/movie', 'PageController@createMovie')->name('create.movie');

    //Formulario para crear serie
    Route::post('/create/movie', 'PageController@saveMovie');

    //Formulario para crear episodio
    Route::get('/create/episodio', 'PageController@createEpisodio')->name('create.episodio');

    //Formulario para crear episodio
    Route::post('/create/episodio', 'PageController@saveEpisodio');


});

//Lista de series
Route::get('/series/', 'PageController@serieList')->name('series.list');

//Lista de episodios
Route::get('/series/{serieuri}', 'SerieController@episodeslist')->name('episodios.list');

/** Movie */
Route::get('/movies/{movieuri}', 'MovieController@watchMovie')->name('movie.watch');

Route::get('/movies/', 'MovieController@movielist')->name('movie.list');


/** RELATED WITH VIDEOS */
//Obtenemos el id del video a ver y devolvemos la vista con el id del video, mime y titulo
// En la vista es cuando llamamos a la ruta /video/{filename}
Route::get('/watch/{videoid}', 'VideosController@watch')->name('video.watch');

//Obtiene el nombre del video y realiza el stream en eg: localhost/routes/public/video/arrested.mkv
Route::get('/video/{videoid}', 'VideosController@stream')->name('video.stream');

//Lista de videos
Route::get('/list', 'PageController@list')->name('video.list');

//Lista de videos
Route::get('/list/{categoria}', 'PageController@listCategoria')->name('video.list.categoria');



////Entrega el formulario para editar la info. del video
//Route::get('/edit/video/{videoid}',"VideosController@editvideo")
//    ->where('videoid', '[A-Za-z0-9]+')
//    ->name('edit.video');
//
////guarda la miniatura del video
//Route::post('/edit/video/{videoid}/save',"VideosController@savechanges")->where('videoid', '[A-Za-z0-9]+');
//
////guarda los datos modificados
//Route::post('/save/{videoid}', "PageController@guardarcambios")->where('videoid', '[A-Za-z0-9]+');

/** SUBTITULOS */

////Recepcion de los subtitulos
//Route::post('storage/create', "SubtitleController@upload");


/** Refresh token */
Route::post('/videos/retken', 'PageController@refreshToken')->name('videos.refreshtoken');


/** TAGS */
Route::get('/tag/{tagname}', "PageController@tags")->name('tag');

/** SEARCH */
Route::get('/searchs', 'PageController@search')->name('search');

//Search serie
Route::post('/search/serie', 'PageController@searchSerie')->name('search.serie');

//Search serie
Route::post('/search/movie', 'PageController@searchMovie')->name('search.movie');

//Search episodio
Route::post('/search/episodio', 'PageController@searchEpisodio')->name('search.episodio');

//Search
Route::post('/search', 'PageController@searchDelivery')->name('search.delivery');


/** Others */
Route::get('/test', function () {
    /*
    $arrContextOptions=array(
        "ssl"=>array(
            "verify_peer"=>false,
            "verify_peer_name"=>false,
        ),
    );

    $json = file_get_contents('https://api.themoviedb.org/3/movie/293660/similar?api_key=cc4b67c52acb514bdf4931f7cedfd12b&language=es&page=1', false, stream_context_create($arrContextOptions));
    $obj = json_decode($json);


    dd($obj);*/
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

////Formulario de prueba, borrar
//Route::get('/formulario', function (){
//    return view('new');
//});


//Player
Route::get('/player', 'PageController@player')->name('player');

//embed
Route::get('/embed/{server}/{key}', 'PageController@embed')->name('embed');


Auth::routes();

Route::get('/register', function (){
    return redirect()->route('index');
})->name('register');

Route::get('/home', 'HomeController@index')->name('home');
