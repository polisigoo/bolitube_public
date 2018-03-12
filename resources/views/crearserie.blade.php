@extends("layouts/plantillaPrincipal")

@section("Title")
Upload @endsection

@section("links")
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script type="text/javascript" src="{{ asset('js/plupload.full.min.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/jquery-ui.min.css') }}">
    <link href="{{ asset("css/fonts/style.css") }}" rel="stylesheet">
    {{--<link rel="stylesheet" href="{{ asset('css/uploadStyle.css') }}">--}}
    {{--<link rel="stylesheet" href="{{ asset('css/uploadercss.css') }}">--}}
    <link rel="stylesheet" href="{{ asset('css/white/uploadStyleWhite.css') }}">
    <link rel="stylesheet" href="{{ asset('css/white/uploaderWhite.css') }}">

    <link href="https://fonts.googleapis.com/css?family=PT+Sans" rel="stylesheet">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
@endsection

@section("body")
<div id="dt_contenedor">
@include("layouts.navbarWhite")

<div id="contenedor">
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
    <div class="upload-form" id="container">
        <h1 class="replace-text">Upload Form</h1>
        <div class="serie">
        <label>Titulo de la serie (En ingles)</label>
        <input type="text" class="field__input w3-input w3-animate-input form-control" name="titulo" id="titulo" placeholder="e.g Silicon Valley"><br>
        </div>

        <div class="50"style="width: 50%; display: inline-block; float: left">
        <label>Fecha del primer episodio</label>
        <input type="number" class="field__input w3-input w3-animate-input form-control" name="p_ep" id="p_ep" placeholder="2010" min="1900" max="2050"><br>
        </div>

        <form action="{{ url('/create/serie') }}" method="POST">
        <div id="gral_inf" style="display: none">
            {{ csrf_field()}}
        <div class="50" style="width: 50%; display: inline-block; float: left">
        <label>Titulo original</label>
        <input type="text" class="field__input w3-input w3-animate-input form-control disabled" name="t_original" id="t_original" readonly><br>
        </div>

        <div class="50"style="width: 50%; display: inline-block; float: left">
        <label>Titulo</label>
        <input type="text" class="field__input w3-input w3-animate-input form-control disabled" name="titulo_t" id="titulo_t"><br>
        </div>

        <label>Resumen</label>
        <input type="text" class="field__input w3-input w3-animate-input form-control disabled" name="resumen" id="resumen" readonly><br>

        <label>Primera transmision</label>
        <input type="text" class="field__input w3-input w3-animate-input form-control disabled" name="p_transmision" id="p_transmision" readonly><br>

        <label>Ultima transmision</label>
        <input type="text" class="field__input w3-input w3-animate-input form-control disabled" name="u_transmision" id="u_transmision" readonly><br>

        <label>Generos (Separados por una coma)</label>
        <input type="text" class="field__input w3-input w3-animate-input form-control disabled" name="generos" id="generos"><br>

        <div class="50"style="width: 50%; display: inline-block; float: left">
        <label>Episodios totales</label>
        <input type="number" class="field__input w3-input w3-animate-input form-control disabled" name="total_e" id="total_e" readonly><br>
        </div>

        <div class="50" style="width: 50%; display: inline-block; float: left">
        <label>Cantidad temporadas</label>
        <input type="number" class="field__input w3-input w3-animate-input form-control disabled" name="cant_temp" id="cant_temp" readonly><br>
        </div>

        <div class="50" style="width: 50%; display: inline-block; float: left">
            <label>Creadores</label>
            <input type="text" class="field__input w3-input w3-animate-input form-control disabled" name="creador" id="creador" readonly><br>
        </div>

        <div class="50" style="width: 50%; display: inline-block; float: left">
            <label>Poster path</label>
            <input type="url" class="field__input w3-input w3-animate-input form-control disabled" name="post_path" id="post_path" readonly><br>
        </div>

        <div class="50" style="width: 50%; display: inline-block; float: left">
            <label>Imagen fondo</label>
            <input type="url" class="field__input w3-input w3-animate-input form-control disabled" name="fondo_path" id="fondo_path" readonly><br>
        </div>

        <div class="50" style="width: 50%; display: inline-block; float: left">
            <label>Id de la serie</label>
            <input type="number" class="field__input w3-input w3-animate-input form-control disabled" name="id" id="id" readonly><br>
        </div>

        <input type="checkbox" id="todos" name="todos" value="crear_todos"> <label for="cbox2">Agregar todos los episodios y temporadas?</label>

            <div id="btns">
                <button type="submit" id="pickfiles" class="button w3-teal" style="display: none"><span class="icon-cloud-upload"></span>Guardar serie</button>
            </div>
        </div>
        </form>

        <a id="uploadfiles" class="button w3-teal" href="javascript:;"><span class="icon-cloud-upload"></span>Buscar!</a>

        <div>
            <img src="" alt="" id="fondo_p" width="470px;" height="370px">
            <img src="" alt="" id="post_p" width="270px;" height="370px">
        </div>



<div id="single">
    
</div>
</div>
</div>
@endsection
@section('afterjquery')
        <script>
            $( document ).ready(function() {
                $('#uploadfiles').click(function () {
                    if ($('#titulo').val() !== "") {

                        $.ajax({
                            data: {
                                "title" : $('#titulo').val(),
                                "fecha" : $('#p_ep').val()
                            },
                            url: '{{ url('/search/serie') }}',
                            type: 'post',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                ob = JSON.parse(response);
                                var g = [];
                                var c = [];

                                ob.genres.forEach(function(valor, indice, array) {
                                    g.push(valor.name);
                                });

                                ob.created_by.forEach(function(valor, indice, array) {
                                    c.push(valor.name);
                                });


                                $('#t_original').val(ob.original_name);
                                $('#resumen').val(ob.overview);

                                $('#p_transmision').val(ob.first_air_date);
                                $('#u_transmision').val(ob.last_air_date);

                                $('#generos').val(g.toString());

                                $('#total_e').val(ob.number_of_episodes);
                                $('#cant_temp').val(ob.number_of_seasons);

                                $('#titulo_t').val(ob.name);

                                $('#id').val(ob.id);

                                $('#creador').val(c.toString());

                                $('#post_path').val('https://image.tmdb.org/t/p/w500' + ob.poster_path);
                                $('#fondo_path').val('https://image.tmdb.org/t/p/w500' + ob.backdrop_path);

                                $('#post_p').attr('src', 'https://image.tmdb.org/t/p/w500' + ob.poster_path);
                                $('#fondo_p').attr('src', 'https://image.tmdb.org/t/p/w500' + ob.backdrop_path);

                                // console.log(ob);
                                $('#pickfiles').attr('style', 'display: block');

                                $('#gral_inf').attr('style', 'display: block');
                            },
                            error: function (error) {
                                console.log(error);
                            }
                        });
                    }
                });
            });
    </script>
    <script>
        $('#a-upload').addClass('active');
    </script>
@endsection