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
            <label>Serie</label>
                <select name="series" id="series" class="custom-select custom-select-lg mb-3">
                    <option selected>Select</option>
                    @foreach($series as $serie)
                        <option value="{{ $serie->id }}">{{ $serie->show_name }}</option>
                    @endforeach
                </select><br>

            <label>Temporada</label>
            <select name="temporadas" id="temporadas" class="custom-select custom-select-m">
                <option selected>Select</option>
            </select><br>

            <label>Episodio</label>
            <select name="episodios" id="episodios" class="custom-select custom-select-m">
                <option selected>Select</option>
            </select>
        </div>

        <form action="{{ url('/create/episodio') }}" method="POST">
                {{ csrf_field()}}

                <div class="50"style="width: 50%; display: inline-block; float: left">
                    <label>Temporada</label>
                    <input type="text" class="field__input w3-input w3-animate-input form-control disabled" name="temporada_n" id="temporada_n"><br>
                </div>

                <div class="50"style="width: 50%; display: inline-block; float: left">
                    <label>Episodio numero</label>
                    <input type="text" class="field__input w3-input w3-animate-input form-control disabled" name="episodio_n" id="episodio_n"><br>
                </div>

                <div class="50" style="width: 50%; display: none; float: left">
                    <label>Titulo original</label>
                    <input type="text" class="field__input w3-input w3-animate-input form-control disabled" name="t_original" id="t_original" readonly><br>
                </div>

                <br>
                <div class="50"style="width: 50%; display: inline-block; float: left">
                    <label>Titulo</label>
                    <input type="text" class="field__input w3-input w3-animate-input form-control disabled" name="titulo_t" id="titulo_t"><br>
                </div>

                <label>Resumen</label>
                <input type="text" class="field__input w3-input w3-animate-input form-control disabled" name="resumen" id="resumen"><br>

                <label>Fecha de transmision</label>
                <input type="text" class="field__input w3-input w3-animate-input form-control disabled" name="transmitido" id="transmitido"><br>

                <label>Keywords (Separados por una coma)</label>
                <input type="text" class="field__input w3-input w3-animate-input form-control disabled" name="keywords" id="keywords"><br>

                <div class="50" style="width: 50%; display: inline-block; float: left">
                    <label>Poster path</label>
                    <input type="url" class="field__input w3-input w3-animate-input form-control disabled" name="post_path" id="post_path" readonly><br>
                </div>

                <div class="50" style="width: 50%; display: inline-block; float: left">
                    <label>Url video</label>
                    <input type="url" class="field__input w3-input w3-animate-input form-control disabled" name="video_url" id="video_url"><br>
                </div>

                <div class="50" style="width: 50%; display: inline-block; float: left">
                    <label>Id del episodio</label>
                    <input type="number" class="field__input w3-input w3-animate-input form-control disabled" name="id" id="id" readonly><br>
                </div>

                <input type="hidden" class="field__input w3-input w3-animate-input form-control disabled" name="serie_id" id="serie_id" readonly><br>

            <div id="btns">
                <button type="submit" id="pickfiles" class="button w3-teal" style="display: none"><span class="icon-cloud-upload"></span>Guardar serie</button>
                <a id="uploadfiles" class="button w3-teal" href="javascript:;"><span class="icon-cloud-upload"></span>Buscar!</a>
            </div>
        </form>

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
                $('#series').on('change', function() {

                    $('#temporadas')
                        .empty()
                        .append('<option selected>Select..</option>')
                    ;

                    $('#serie_id').val($(this).val());

                    $.ajax({
                        data: {
                            "serie" : $(this).val()
                        },
                        url: '{{ url('/search/episodio') }}',
                        type: 'post',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            var myselect = document.getElementById("temporadas");

                            for (var i = 1; i <= response; i++) {
                                var option = document.createElement("option");
                                option.value = i;
                                option.text = "Temporada " + i;
                                myselect.appendChild(option);
                            }

                            //console.log(response);
                            //$('#pickfiles').attr('style', 'display: block');
                        },
                        error: function (error) {
                            console.log(error);
                        }
                    });
                });

                $('#temporadas').on('change', function() {
                    var t = $(this).val();

                    $('#episodios')
                        .empty()
                        .append('<option selected>Select..</option>')
                    ;

                    $.ajax({
                        data: {
                            "serie" : $('#series').val(),
                            "temporada" : t
                        },
                        url: '{{ url('/search/episodio') }}',
                        type: 'post',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            var myselect = document.getElementById("episodios");

                            for (var i = 1; i <= response; i++) {
                                var option = document.createElement("option");
                                option.value = i;
                                option.text = "Episodio "+ t + "x" + i;
                                myselect.appendChild(option);
                            }
                        },
                        error: function (error) {
                            console.log(error);
                        }
                    });
                });

                $('#episodios').on('change', function() {
                    var t = $('#temporadas').val();

                    $('#episodio_n').val($(this).val());
                    $('#temporada_n').val(t);
                    $.ajax({
                        data: {
                            "serie" : $('#series').val(),
                            "temporada" : t,
                            "episodio" : $(this).val()
                        },
                        url: '{{ url('/search/episodio') }}',
                        type: 'post',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            ob = JSON.parse(response);
                            var g = [];
                            var c = [];

                            // $('#t_original').val(ob.original_name);
                            $('#resumen').val(ob.overview);

                            $('#transmitido').val(ob.air_date);

                            $('#keywords').val(ob.keywords);

                            $('#titulo_t').val(ob.name);
                            $('#post_path').val('https://image.tmdb.org/t/p/w500' + ob.still_path);

                            $('#post_p').attr('src', 'https://image.tmdb.org/t/p/w500' + ob.still_path);

                            $('#id').val(ob.id);

                             console.log(ob);
                            $('#pickfiles').attr('style', 'display: block');
                        },
                        error: function (error) {
                            console.log(error);
                        }
                    });
                });

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

                                $('#creador').val(c.toString());

                                $('#post_path').val('https://image.tmdb.org/t/p/w500' + ob.poster_path);
                                $('#fondo_path').val('https://image.tmdb.org/t/p/w500' + ob.backdrop_path);

                                $('#post_p').attr('src', 'https://image.tmdb.org/t/p/w500' + ob.poster_path);
                                $('#fondo_p').attr('src', 'https://image.tmdb.org/t/p/w500' + ob.backdrop_path);

                                // console.log(ob);
                                $('#pickfiles').attr('style', 'display: block');
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