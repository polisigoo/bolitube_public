@extends("layouts/plantillaPrincipal")

@section("Title")
Home @endsection

@section("links")
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset("css/main.css") }}" rel="stylesheet">
    <link href="{{ asset("css/white/watchWhite.css") }}" rel="stylesheet">
    <link href="{{ asset("css/fonts/style.css") }}" rel="stylesheet">
@endsection


@section("body")
<div id="dt_contenedor">
@include("layouts.navbarWhite")
<div id="contenedor">
<div id="single">
    {{-- oncontextmenu="return false;"--}}
    <div class="content">
        <div class="playerVideo">
            @if($movie->video_url !== "Undefined")
            <iframe src="{!! url('/player?id=') . $movie->id . '&m=t'!!}" width="100%" height="480px" id="g-video"></iframe>
            @else
                <div style="width: 100%; height: 480px">
                    <p style="color: red">No se encontro un link valido.</p>
                </div>
            @endif
        </div>

        <div id="info" class="sbox">
            <div class="modo-cine">
             <button class="btn btn-dark" id="mod-cine">Modo cine <i class="icon-enter" id="c-ico"></i></button>
            </div>
            <h1 class="inf_episodio">{{ $movie->titulo }} ({{substr($movie->fecha_estreno, 0,4)}})</h1>

            <div class="edit"><a href="{{ route('movie.watch',[
                                            'movieuri' => $movie->uri]) . '/edit' }}"><span class="icon-pencil"></span>Editar</a></div>

            <div class="overview">
                <h2>Sinopsis</h2>
                <h3 class="inf_descripcion">{{ $movie->resumen }}</h3>
            </div>

            <div class="tags">
                <h6>tags:</h6>
                    <h6 style="display: inline; color: #cccccc">{{ $movie->keywords }}</h6>
            </div>
        </div>

        <form id="upload" method="post" action="{{ url('storage/create') }}" accept-charset="UTF-8" enctype="multipart/form-data">
            <input id="input-file" name="file" type="file" value="" style="display:block;height:0;width:0;" />
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
        </form>
        {{--<div type="hidden" id="path" value="url('/')"></div>--}}

        <div class="sbox sinfo">
            <h2>{{ str_replace("_", " ", $movie->titulo) }}</h2>
            <div id="categ_content" style="padding-top:0">
                <div id="categ_carpeta">
                    <div class="carpeta_content">
                        <div class="se-a" style="display:block">
                            <ul class="videos">
                                    <?php $ab = 1; ?>
                                   {{--@foreach($related as $rel)--}}
                                    {{--<li class="mark-{{ $ab }}">--}}
                                        {{--<div class="imagen">--}}
                                            {{--<a href="#">--}}
                                            {{--@if(empty($rel->image_name))--}}
                                                {{--<img src="{{ asset('css/img/question.png') }}" alt="{{ $title }}" id="imagen">--}}
                                            {{--@else--}}
                                                {{--@if(!file_exists(storage_path() . '/app/public/images/' . $categoria .'/' . $carpeta . '/hq'. $rel->image_name))--}}
                                                    {{--<img src="{{ url('/storage/images/'.$categoria .'/'.$carpeta.'/'.$rel->image_name) }}" alt="{{ $title }}" id="imagen">--}}
                                                {{--@else--}}
                                                    {{--<img src="{{ url('/storage/images/'.$categoria .'/'.$carpeta.'/hq'.$rel->image_name) }}" alt="{{ $title }}" id="imagen">--}}
                                                {{--@endif--}}
                                            {{--@endif--}}
                                            {{--</a>--}}
                                        {{--</div>--}}
                                        {{--<div class="numerando">1x{{ $ab }}</div>--}}
                                        {{--<div class="episodiotitle">--}}
                                            {{--<a href="{{ route('video.watch', ['videoid' => $rel->unique_id]) }}">{{ $rel->titulo }}</a>--}}
                                            {{--<span class="date">{{ date("F d, Y", strtotime($rel->created_at)) }}</span>--}}
                                        {{--</div>--}}
                                    {{--</li>--}}
                                       {{--<?php $ab++;  ?>--}}
                                   {{--@endforeach--}}

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="sidebar" id="s-bar">
        <div class="categorias">
            <nav class="categ">
                <h2>Categorias</h2>
                <ul class="categ scrolling mCustomScrollbar _mCS_2 mCS-autoHide" style="position: relative; overflow: visible;">
                    <div id="mCSB_2" class="mCustomScrollBox mCS-minimal-dark mCSB_vertical mCSB_outside" tabindex="0" style="max-height: 282px; overflow: auto">
                        <div id="mCSB_2_container" class="mCSB_container" style="position: relative; left: 0px;" dir="ltr">
                            <?php $g = array(); $var = "Abc";?>
                            @foreach($generos as $genero)
                                @if(preg_match("/[,]/", $genero->generos) === 1)
                                    @if(!in_array($var, $g))
                                        <li class="categ"><a href="{{'Ruta a generos'}}">{{ $var = strstr($genero->generos, ",", true) }}</a></li>
                                    @endif
                                @else
                                    <li class="categ"><a href="{{'Ruta a generos'}}">{{ $var = $genero->generos }}</a></li>
                                @endif
                                <?php $g[] = $var; ?>
                            @endforeach
                        </div>
                    </div>
                </ul>
            </nav>
        </div>

        <h2 class="as_content_title">Más vistos del mes</h2>
        <div id="rSB" class="rScrollBox">
            <div id="rContainer" class="rContainer" style="position: relative;top: 0;left: 0;">
                <aside id="aside_content" class="asideContent">
                    <div class="as_content">
                        <article class="as_item_a" id="post-14">
                            <a href="/movies/la-ciudad-de-las-estrellas-la-la-land/">
                                <div class="image">
                                    <img src="http://blog.aulaformativa.com/wp-content/uploads/2016/01/razones-peso-aprender-python-este-2016-base-teorica.jpg" alt="Python" class="as_img_loaded">
                                    <div class="as_data">
                                        <h3>Titulo</h3>
                                        <span class="as_date">Categoria - <i>Carpeta</i></span>
                                    </div>
                                    <span class="as_quality">HD-R</span>
                                </div>
                            </a>
                        </article>
                        <article class="as_item_a" id="post-14">
                            <a href="/movies/la-ciudad-de-las-estrellas-la-la-land/">
                                <div class="image">
                                    <img src="http://is5.mzstatic.com/image/thumb/Purple128/v4/20/66/53/2066535d-3cb7-b487-3056-69a7152d3504/source/1200x630bb.jpg" alt="Python" class="as_img_loaded">
                                    <div class="as_data">
                                        <h3>Titulo</h3>
                                        <span class="as_date">Categoria - <i>Carpeta</i></span>
                                    </div>
                                    <span class="as_quality">HD-R</span>
                                </div>
                            </a>
                        </article>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</div>
</div>
</div>
@endsection

@section('afterbootstrap')
    <script>
        $( document ).ready(function() {
            var c = false;
            $('#TituloDePagina').text('{{ $movie->titulo }}');
            $('#mod-cine').click(function () {
                if(!c){
                    $('.playerVideo').attr('style', 'width: calc(100% + 340px) !important;background-color: #191919;height: 560px;');

                    $('#s-bar').css({
                        'bottom' : '0',
                        'height' : '526px'
                    });

                    $('#c-ico').removeClass().addClass('icon-exit');

                    var data = {
                        "c-mode" : 'on'
                    };

                    $.ajax({
                        data:  {
                            'c-mode' : 'on'
                        },
                        url: '{{ url('/multfunc') }}',
                        type:  'post',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    c = true;
                }else{
                    $('.playerVideo').attr('style', 'width: 100% !important;height: 480px;');
                    $('#s-bar').css({
                        'bottom' : '0',
                        'height' : '100%'
                    });

                    $('#c-ico').removeClass().addClass('icon-enter');

                    $.ajax({
                        data: {
                            "c-mode" : 'off'
                        },
                        url: '{{ url('/multfunc') }}',
                        type:  'post',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    c = false;
                }
            });

            @if(session()->get('c-mode') === "on")
            $('#mod-cine').click();
            @endif

            $('#g-video').bind('contextmenu',function() { return false; });

            /** Reset width video size */
            $('.vjs-fullscreen-control').click(function () {
                c = true;
                $('#mod-cine').trigger('click');
            });
        });
    </script>
@endsection