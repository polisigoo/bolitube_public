@extends("layouts/plantillaPrincipal")

@section("Title")
Home @endsection

@section("links")
    {{-- Seo --}}
    <meta name="description" content="Aquí puedes ver {{ $movie->titulo }} en alta calidad. Disfrutalo totalmente gratis"/>
    <meta name="keywords" content="Aquí puedes ver {{ $movie->titulo }} en alta calidad. Disfrutalo totalmente gratis"/>

    <meta property="og:type" content="article" />
    <meta property="og:locale" content="es_ES" />
    <meta property="og:title" content="Ver {{ $movie->titulo . " " . substr($movie->fecha_estreno, 0,4) }} Latino Sub Online HD" />
    <meta property="og:url" content="{{ $movie->fondo_path }}" />
    <meta property="og:image" content="{{ $movie->fondo_path }}" />
    <meta property="og:image:width" content="500" />
    <meta property="og:image:height" content="281" />
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:description" content="Aquí puedes ver {{ $movie->titulo }} en alta calidad. Disfrutalo totalmente gratis" />
    <meta name="twitter:title" content="Ver {{ $movie->titulo . " " . substr($movie->fecha_estreno, 0,4) }} Latino Sub Online HD" />
    <meta name="twitter:image" content="{{ $movie->fondo_path }}" />


    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset("css/main.css") }}" rel="stylesheet">
    <link href="{{ asset("css/white/watchWhite.css") }}" rel="stylesheet">
    <link href="{{ asset("css/fonts/style.css") }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset("css/slick.css") }}">
    <link rel="stylesheet" href="{{ asset("css/slick-theme.css") }}">
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
            <h1 class="inf_episodio">{{ htmlspecialchars_decode($movie->titulo) }} ({{substr($movie->fecha_estreno, 0,4)}})</h1>

            <div class="edit"><a href="{{ route('movie.watch',[
                                            'movieuri' => $movie->uri]) . '/edit' }}"><span class="icon-pencil"></span>Editar</a></div>

            <div class="overview">
                <h2>Sinopsis</h2>
                <h3 class="inf_descripcion">{{ $movie->resumen }}</h3>
            </div>

            <div class="share">
                <div class="twitter">
                    <a data-id="52443" href="javascript: void(0);" onclick="window.open ('https://twitter.com/intent/tweet?text={{ $movie->titulo . substr($movie->fecha_estreno, 0,4) }}&amp;url={{ request()->url() }}', 'Twitter', 'toolbar=0, status=0, width=650, height=450');" data-rurl="{{ request()->url() }}" class="twitter dt_social">
                        <i class="icon-twitter"></i> <b>Twitter</b>
                    </a>
                </div>

                <div class="facebook">
                    <a data-id="52443" href="javascript: void(0);" onclick="window.open ('https://facebook.com/sharer.php?u={!! request()->url()  !!}', 'Facebook', 'toolbar=0, status=0, width=650, height=450');" class="facebook dt_social">
                        <i class="icon-facebook"></i> <b>Facebook</b>
                    </a>
                </div>
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

        <div class="related">
            <div class="s-slider">
                <h1 class="semititulo" style="">Películas relacionadas</h1>
                <div class="slick-sli">
                    <?php $arrContextOptions=array(
                        "ssl"=>array(
                            "verify_peer"=>false,
                            "verify_peer_name"=>false,
                        ),
                    );

                    $json = file_get_contents("https://api.themoviedb.org/3/movie/{$movie->id_db}/similar?api_key=cc4b67c52acb514bdf4931f7cedfd12b&language=es&page=1", false, stream_context_create($arrContextOptions));
                    $obj = json_decode($json); ?>
                    @foreach($obj->results as $ultimo)
                        <?php $fecha = substr($ultimo->release_date, 0,4); ?>
                        <div>
                            @if(empty($ultimo->poster_path))
                                <img src="{{ asset('css/img/question.png') }}" alt="{{ $ultimo->original_title }}">
                            @else
                                <a href="{{ /*route('episodios.list', ['serieuri' => $ultimo->uri])*/ preg_replace('[^0-9a-zA-Z]', "", str_replace(" ", '-', strtolower(str_replace(":" , "", $ultimo->original_title)))) . "-" .$fecha }}">
                                    <img src="{{ "https://image.tmdb.org/t/p/w400".$ultimo->poster_path }}" alt="{{ $ultimo->original_title }}" style="width: 200px;height: 193px;">
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="sbox sinfo">

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
                        @foreach($mas_vistos as $mov)
                        <article class="as_item_a" id="post-14">
                            <a href="{{ route('movie.watch',['movieuri' => $mov->uri]) }}">
                                <div class="image">
                                    <img src="{{ $mov->fondo_path }}" alt="{{ htmlspecialchars_decode($mov->titulo) }}" class="as_img_loaded">
                                    <div class="as_data">
                                        <h3>{{ htmlspecialchars_decode($mov->titulo) }}</h3>
                                        <span class="as_date">{{ $mov->fecha_estreno }}</span>
                                    </div>
                                    {{--<span class="as_quality">HD-R</span>--}}
                                </div>
                            </a>
                        </article>
                        @endforeach
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


    <script type="text/javascript" src="{{ asset("js/slick.min.js") }}"></script>
    <script>
            $('.slick-sli').slick({
                infinite: true,
                slidesToShow: 3,
                slidesToScroll: 3,
                variableWidth: true,
            });
    </script>
    <script>
        $( document ).ready(function() {
            var c = false;
            $('#TituloDePagina').text('{{ $movie->titulo }}');
            $('#mod-cine').click(function () {
                if(!c){
                    $('#g-video').attr('style', 'width: calc(100% + 340px) !important;background-color: #191919;height: 560px;');

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
                    $('#g-video').attr('style', 'width: 100% !important;height: 480px;');
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