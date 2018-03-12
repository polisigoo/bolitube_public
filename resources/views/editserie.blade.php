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
    <style>
        .loader {
            border: 7px solid #f3f3f3;
            border-radius: 50%;
            border-top: 7px solid #3498db;
            width: 45px;
            height: 45px;
            -webkit-animation: spin 2s linear infinite; /* Safari */
            animation: spin 2s linear infinite;
            margin-top: 15px;
        }

        /* Safari */
        @-webkit-keyframes spin {
            0% { -webkit-transform: rotate(0deg); }
            100% { -webkit-transform: rotate(360deg); }
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
@endsection

@section("body")
<div id="dt_contenedor">
@include("layouts.navbarWhite")

        <div id="contenedor" style="position:relative;">
            <div class="info">
                <form action="{{ "GuardarCambios"}}" method="post">
                <div class="separador">
                    <a href="{{ route('episodios.list', ['serieuri' => request()->serieuri]) }}" target="_blank" id="link">
                            <span class="icon-new-tab"></span></a>
                    <dd>

                    <input type="text" readonly value="{{ route('video.watch', ['videoid' => request()->videoid]) }}" name="url" class="w3-input w3-border i-sml">
                    </dd>
                </div>

                <input type="hidden" value="{{ $serie->id }}" name="rel" id="rel" readonly>

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

                    <label for="titulo">Nombre de la serie</label>
                    <input type="text" value="{{ $serie->show_name }}" name="titulo" id="titulo" class="w3-input w3-border w3-round-large" maxlength="90">
                    {{csrf_field()}}
                </div>

                <div class="separador">
                    <label for="tags">Descripcion</label>
                    <input type="text" value="{{ html_entity_decode($serie->descripcion, ENT_QUOTES) }}" name="tags" id="tags" class="w3-input w3-border w3-round-large">
                    <small>Separa los tags por una coma, e.g: temporada 1, Dr. House,2010,etc</small>
                </div>
                    <button type="submit" value="Submit" class="btn btn-primary btn-sm edit-button right">Guardar</button>
                </form>
            </div>

            <div class="separador miniatura">
                <label for="tags" style="width: 100%;">Miniatura</label>

                {{-- TODO: PONER LA IMAGEN A LA DERECHA, SI NO ANDA EL VIDEO DEL TO.DO DE ABAJO --}}
                <div class="img" style="background-color: #fff5f7; padding: 9px; width: 140px;margin: 5px 0; margin-bottom: 15px;">
                    @if(empty($serie->poster_path))
                        <img src="{{ asset('css/img/missed_image.png') }}" alt="{{ $serie->show_name }}" style="width: 120px; " id="imagen">
                    @else
                        <img src="{{ $serie->poster_path }}" alt="{{ $serie->show_name }}" style="width: 120px; " id="imagen">
                    @endif
                </div>

                <div id="btns">
                    <a id="pickfiles" class="button w3-teal" href="javascript:;" style="padding: 5px; margin: 5px 0;">Elegir otra miniatura</a>
                    <small style="display: block;">Tamaño máximo: 2mb</small>

                    <div id="correcto"><span class="icon-checkmark"></span><p> Subida correctamente</p></div>

                    <div id="error"><span class="icon-cross"></span><p> Hubo un error mientras guardábamos la imagen: </p></div>
                    {{--<a id="uploadfiles" href="javascript:;" style=""><span class="icon-plus"></span>Guardar</a>--}}
                </div>
            </div>

            <div id="agregar_temp">
                @for($i = 1; $i <= $serie->temporadas; $i++)
                    @if(empty(\App\Models\Episodio::select('id')->where('serie_id', $serie->id)->where('temporada', $i)->first()))
                    <div>
                    <a id="temporada{{ $i }}" class="button w3-teal addbtn" href="javascript:;" style="padding: 5px; margin: 5px 0;">Agregar temporada {{$i}}</a>
                    <input type="hidden" name="tem" class="temp" value="{{$i}}">
                    </div>
                    @endif
                @endfor

            <div id="load" style="position: relative;display: none">
                <div class="loader"></div>
                <p style="position: absolute;top: 85%;left: 8%;">Añadiendo temporada...</p>
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
    <script>
        $( document ).ready(function() {
            var enviado = false;
            $('#agregar_temp').on('click','.addbtn', function () {
                // get current ID($users_row['id']) for
                // particular row


                var vsec_uid = $(this).closest('div').find('.temp').val();
                    if(!enviado) {
                        enviado = true;

                        $('#load').attr('style', 'display: block');

                        $.ajax({
                            data: {
                                "id": "{!! $serie->id  !!}",
                                "uri": "{!! request()->serieuri->uri !!}",
                                "tp": vsec_uid,
                                "id2": "{!! $serie->id_db !!}"
                            },
                            url: '{{ route('serie.edit.save', ["serieuri" => request()->serieuri->uri]) }}',
                            type: 'post',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {
                                $('#load').attr('style', 'display: none');
                                $(`#temporada${vsec_uid}`).remove();

                                alert('Temporada creada.');
                                enviado = false;
                            },
                            error: function (error) {
                                alert(error.responseJSON.error);
                                enviado = false;
                                $('#load').attr('style', 'display: none');
                            }
                        });
                    }else{
                        alert('Espera a que termine el request anterior..');
                    }
                });
        });
    </script>
    <script type="text/javascript">

        // Custom example logic
        var uploader = new plupload.Uploader({
            runtimes : 'html5,flash,silverlight,html4',
            browse_button : 'pickfiles', // you can pass an id...
            container: document.getElementById('contenedor'), // ... or DOM Element itself
            {{--url : '{{ action('VideosController@savechanges', ['videoid' => $videoid]) }}',--}}
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
                        ui : '{{ ""  }}'
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