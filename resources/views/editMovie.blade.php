@extends("layouts/plantillaPrincipal")

@section("Title")
Upload @endsection

@section("links")
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script type="text/javascript" src="{{ asset('js/plupload.full.min.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/jquery-ui.min.css') }}">

    <link rel="stylesheet" href="{{ asset('css/editvideo.css') }}">
    <link href="{{ asset("css/fonts/style.css") }}" rel="stylesheet">

    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
@endsection

@section("body")
<div id="dt_contenedor">
@include("layouts.navbarWhite")

        <div id="contenedor" style="position:relative;">
            <div class="info">
                <form action="{{ route('edit.movie.save',[
                                'movieuri' => $movie->uri]) }}" method="post" id="changesform">
                <div class="separador">
                    <a href="{{ route('movie.watch', ['movieuri' => $movie->uri]) }}" target="_blank" id="link">
                            <span class="icon-new-tab"></span></a>
                    <dd>

                    <input type="text" readonly value="{{ route('movie.watch', ['movieuri' => $movie->uri]) }}" name="url" class="w3-input w3-border i-sml">
                    </dd>
                </div>

                {{--<div class="separador">--}}
                    {{--<label for="titutlo">Categoria / Serie</label>--}}
                    {{--<input type="text" value="{{ $categoria }}" name="categoria" id="categoria" class="w3-input w3-border w3-round-large">--}}
                {{--</div>--}}

                {{--<div class="separador">--}}
                    {{--<label for="titutlo">Carpeta / Season</label>--}}
                    {{--<input type="text" value="{{ $carpeta }}" name="carpeta" id="carpeta" class="w3-input w3-border w3-round-large">--}}
                    <input type="hidden" value="{{ $movie->id }}" name="rel" id="rel" readonly>
                {{--</div>--}}

                <div class="separador">

                    {{-- Laravel pasa la variable $error a todas las vistas de manera automatica, exista o no un error --}}
                    @if($errors->any())
                        <div class="alert alert-danger" role="alert">
                            <h3 style="font-weight: bold">Corrije los siguientes errores:</h3>
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li style="margin: 15px 0 15px 45px;">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(Session::has('success_msg'))
                        <div class="alert alert-success">{{ session('success_msg') }}.
                            <a href="{{ route('video.list') }}" class="alert-link">Volver a la lista</a>.
                        </div>
                    @endif
                    @if(Session::has('error_msg'))
                        <div class="alert alert-danger">{{ session('error_msg') }}.</div>
                    @endif

                    <label for="titulo">Titulo del episodio</label>
                    <input type="text" value="{{ $movie->titulo }}" name="titulo" id="titulo" class="w3-input w3-border w3-round-large" maxlength="90">
                    {{csrf_field()}}
                </div>

                <div class="separador">
                    <label for="video_url">Url del video</label>
                    @if(empty($movie->video_url))
                    <input type="text" name="video_url" class="w3-input w3-border w3-round-large" id="video_url" placeholder="drive....">
                    @else
                        <?php $a = array(); ?>
                        @foreach(explode(",",$movie->video_url) as $item)
                            <?php try{ array_push($a, decrypt($item)); } catch (Illuminate\Contracts\Encryption\DecryptException $e) { }; ?>
                        @endforeach
                    <input type="text" name="video_url" class="w3-input w3-border w3-round-large" id="video_url" value="{{ implode(",", $a) }}">

                    @endif
                    <small>Separa url por comas</small>
                </div>

                <div class="separador">
                    <label for="taResumen">Sinopsis del episodio</label>
                    <textarea name="resumen" id="taResumen" form="changesform" class="form-control" rows="3" style="width: 55%">{{ $movie->resumen }}</textarea>
                </div>

                <div class="separador">
                    <label for="tags">Tags</label>
                        <input type="text" value="{{ $movie->keywords }}" name="keywords" id="keywords" class="w3-input w3-border w3-round-large">

                    <small>Separa los tags por una coma, e.g: temporada 1, Dr. House,2010,etc</small>
                </div>
                    <button type="submit" value="Submit" class="btn btn-primary btn-sm edit-button right">Guardar</button>
                </form>
            </div>
            <div class="separador miniatura">
                <label for="tags" style="width: 100%;">Miniatura</label>
                <div class="img" style="background-color: #fff5f7; padding: 9px; width: 140px;margin: 5px 0; margin-bottom: 15px;">
                    @if($movie->poster_path === "https://image.tmdb.org/t/p/w500")
                        <img src="{{ asset('css/img/missed_image.png') }}" alt="{{ $movie->titulo }}" style="width: 120px; " id="imagen">
                    @else
                        <img src="{{ $movie->poster_path }}" alt="{{ $movie->poster_path }}" style="width: 120px; " id="imagen">
                    @endif
                </div>

                <input type="url" name="image_path" class="w3-input w3-border w3-round-m" id="image_path" form="changesform" value="{{ $movie->poster_path }}" style="width: 55%">

                <div id="btns">
                    <a id="pickfiles" class="button w3-teal" href="javascript:;" style="padding: 5px; margin: 5px 0;">Elegir otra miniatura</a>
                    <small style="display: block;">Tamaño máximo: 2mb</small>

                    <div id="correcto"><span class="icon-checkmark"></span><p> Subida correctamente</p></div>

                    <div id="error"><span class="icon-cross"></span><p> Hubo un error mientras guardábamos la imagen: </p></div>
                    {{--<a id="uploadfiles" href="javascript:;" style=""><span class="icon-plus"></span>Guardar</a>--}}
                </div>
            </div>

            <div class="preview">

            </div>


            <!-- File List -->
            <div id="filelist" class="cb">Your browser doesn't have Flash, Silverlight or HTML5 support.</div>
            <br />


            <br />
            <pre id="console"></pre>
        </div>

        <div class="rep-video">
            {{-- Reproductor del video en la derecha, solo para pc --}}
            <video src=""></video>
        </div>

</div>
@endsection
@section('afterjquery')
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script type="text/javascript">
        // Custom example logic

        var uploader = new plupload.Uploader({
            runtimes : 'html5,flash,silverlight,html4',
            browse_button : 'pickfiles', // you can pass an id...
            container: document.getElementById('contenedor'), // ... or DOM Element itself
            url : '{{ 'savechanges' }}',
            flash_swf_url : '../js/Moxie.swf',
            multi_selection : false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            silverlight_xap_url : '../js/Moxie.xap',

            /*resize : {width : 620, height : 250, quality : 200, crop: true},*/

            filters : {
                max_file_size : '7mb',
                chunk_size: '900 kb',
                max_retries: 3,
                mime_types: [
                    {title : "Image files", extensions : "jpg,gif,png,bmp,apng,svg"}
                ]
            },

            // PreInit events, bound before the internal events
            preinit : {

                UploadFile: function(up, file) {
                    up.setOption('multipart_params', {
                        rel : document.getElementById('rel').value,
                        ui : '{{ 'uniqueid' }}'
                    });
                }
            },

            init: {
                PostInit: function() {
                    document.getElementById('filelist').innerHTML = '';

                    document.getElementById('uploadfiles').onclick = function() {
                        uploader.start();
                        return false;
                    };
                },

                FilesAdded: function(up, files) {
                    plupload.each(files, function(file) {

                        if (up.files.length > 1) {
                            up.removeFile(file);
                        }


                       // document.getElementById('filelist').innerHTML += '<div class="addedFile" id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></div>';

                        up.start();
                    });
                },

                FilesRemoved: function(up, files) {
                    if (up.files.length < 1) {
                        $('#pickfiles').fadeIn('slow');
                    }


                    //TODO: VER SI REALMENTE SE BORRO O ES QUE SOLO SE BORRA VISUALEMENTE

                    //document.getElementById(filles.id).remove();
                },

                UploadProgress: function(up, file) {
                   /* document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
                    $('#myBar').css('width', file.percent + '%');*/
                },

                Error: function(up, err) {
                    //document.getElementById('console').appendChild(document.createTextNode("\nError #" + err.code + ": " + err.message));

                    $('#correcto').css('display', 'none');

                    $('#error').append($(this).text() + err.message + " " + err.code);

                    $('#error').css('display', 'block');
                },

                UploadComplete: function(up, files) {
                    $('#correcto').css('display', 'block');
                }
            }
        });


        uploader.init();
    </script>
@endsection