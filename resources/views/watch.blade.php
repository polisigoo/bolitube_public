@extends("layouts/plantillaPrincipal")

@section("Title")
Home @endsection

@section("links")
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset("css/main.css") }}" rel="stylesheet">
    <link href="{{ asset("css/white/watchWhite.css") }}" rel="stylesheet">
    <link href="{{ asset("css/fonts/style.css") }}" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css?family=PT+Sans" rel="stylesheet">
@endsection


@section("body")
<div id="dt_contenedor">
@include("layouts.navbarWhite")
<div id="contenedor">
<div id="single">
    {{-- oncontextmenu="return false;"--}}
    <div class="content">
        <div class="playerVideo">
            @if($episode->video_url !== "Undefined")
            <iframe src="{{ url('/player?id=') . $episode->id }}" width="100%" height="480px" id="g-video"></iframe>
            @else
                <div style="width: 100%; height: 480px">
                    <p style="color: red">No se encontro un link valido.</p>
                </div>
            @endif
        </div>

        <div class="pag_episodes">
            @if($anterior !== null)
            <div class="ep_item">
                <a href="{{ route('serie.watch',
                            ['serieuri' => $serie->uri,
                            'temporada' => $episode->temporada,
                            'episodio' => $anterior->episodio]) }}" title="{{ $serie->show_name .' - Episode ' . $anterior->episodio }}">
                    <i class="icon-arrow-left2"></i> <span>video anterior</span>
                </a>
            </div>
            @else
            <div class="ep_item">
                <a class="nt">
                    <i class="icon-arrow-left2"></i> <span>video anterior</span>
                </a>
            </div>
            @endif

            <div class="ep_item">
                <a href="{{ url("series/{$serie->uri}/")}}">
                    <i class="icon-menu2"></i>
                    <span>lista de episodios</span>
                </a>
            </div>

            @if($siguiente !== null)
                <div class="ep_item">
                    <a href="{{ route('serie.watch',
                                ['serieuri' => $serie->uri,
                                'temporada' => $episode->temporada,
                                'episodio' => $siguiente->episodio]) }}" title="{{ $serie->show_name .' - Episode ' . $siguiente->episodio }}">
                        <span>siguiente video</span>
                        <i class="icon-arrow-right2"></i>
                    </a>
                </div>
            @else
                <div class="ep_item">
                    <a class="nt">
                        <span>siguiente video</span>
                        <i class="icon-arrow-right2"></i>
                    </a>
                </div>
            @endif

        </div>

        <div id="info" class="sbox">
            <div class="modo-cine">
             <button class="btn btn-dark" id="mod-cine">Modo cine <i class="icon-enter" id="c-ico"></i></button>
            </div>
            <h1 class="inf_episodio">{!! $episode->titulo !!}</h1>

            <div class="edit"><a href="{{ route('episode.edit',[
                                            'seriuri' => $serie->uri,
                                            'temporada' => $episode->temporada,
                                            'episodio' => $episode->episodio]) }}"><span class="icon-pencil"></span>Editar</a></div>

            <h3 class="inf_descripcion">{!! $episode->resumen !!}</h3>

            <div class="share">
                <ul>
                    <li>
                        <h3>Comparte la película!</h3>
                    </li>
                    <li>
                        <div class="twitter">
                            <a data-id="52443" href="javascript: void(0);" onclick="window.open ('https://twitter.com/intent/tweet?text={{ $episode->titulo . substr($episode->fecha_estreno, 0,4) }}&amp;url={{ request()->url() }}', 'Twitter', 'toolbar=0, status=0, width=650, height=450');" data-rurl="{{ request()->url() }}" class="twitter dt_social">
                                <i class="icon-twitter"></i> <b>Twitter</b>
                            </a>
                        </div>
                    </li>

                    <li>
                        <div class="facebook">
                            <a data-id="52443" href="javascript: void(0);" onclick="window.open ('https://facebook.com/sharer.php?u={!! request()->url()  !!}', 'Facebook', 'toolbar=0, status=0, width=650, height=450');" class="facebook dt_social">
                                <i class="icon-facebook"></i> <b>Facebook</b>
                            </a>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="tags">
                <h6>tags:</h6>
                @foreach($keywords as $tag)
                    <h6 style="display: inline; color: #cccccc">{!! $tag !!}, </h6>
                @endforeach
            </div>
        </div>

        <div class="sbox sinfo">
            <h2>{{ str_replace("_", " ", $serie->show_name) }}</h2>
            <div id="categ_content" style="padding-top:0">
                <div id="categ_carpeta">
                    <div class="carpeta_content">
                        <div class="se-a" style="display:block">
                            <ul class="videos">
                                    <?php $ab = 1; ?>
                                   @foreach($episodios as $episodio)
                                    <li class="mark-{{ $ab }}">
                                        <div class="imagen">
                                            <a href="#">
                                            @if($episodio->image_path === "https://image.tmdb.org/t/p/w500")
                                                <img src="{{ asset('css/img/missed_image.png') }}" alt="{{ $episodio->titulo }}" id="imagen">
                                            @else
                                                    <img src="{{ $episodio->image_path }}" alt="{{ $episodio->titulo }}" id="imagen">
                                            @endif
                                            </a>
                                        </div>
                                        <div class="numerando">{{ request()->temporada }}x{{ $ab }}</div>
                                        <div class="episodiotitle">
                                            <a href="{{ route('serie.watch', ["serieuri"  => request()->serieuri,
                                                                            "temporada" => request()->temporada,
                                                                            "esposidio" => $ab])  }}">{{ $episodio->titulo }}</a>
                                            <span class="date">{{ date("F d, Y", strtotime($episodio->fecha_estreno)) }}</span>
                                        </div>
                                    </li>
                                       <?php $ab++;  ?>
                                   @endforeach

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
                <i class="cat_carpetas">Carpetas</i>
                <ul class="categ scrolling mCustomScrollbar _mCS_2 mCS-autoHide" style="position: relative; overflow: visible;">
                    <div id="mCSB_2" class="mCustomScrollBox mCS-minimal-dark mCSB_vertical mCSB_outside scroll-black" tabindex="0" style="max-height: 282px; overflow: auto">
                        <div id="mCSB_2_container" class="mCSB_container" style="position: relative; left: 0px;" dir="ltr">
                            @foreach($genders as $genero)
                                <li class="categ"><a href="{{'Ruta a generos'}}">{{ $genero }}</a></li>
                            @endforeach
                        </div>
                    </div>
                </ul>
            </nav>
        </div>

        <h2 class="as_content_title">Más vistos del mes</h2>
        <div id="rSB" class="rScrollBox">
            <div id="rContainer" class="rContainer" style="position: relative;top: 0;left: 0;">
                <aside id="aside_content" class="asideContent scroll-black">
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
            $('#TituloDePagina').text('{!! $episode->titulo !!}' + ' - ' +'{!! $serie->show_name !!}');
            $('#mod-cine').click(function () {
                if(!c){
                    $('.playerVideo').attr('style', 'width: calc(100% + 340px) !important;background-color: #191919;height: 560px;');

                    $('#s-bar').css({
                        'bottom' : 'calc(35% + 53px)',
                        'height' : 'auto'
                        //'height' : 'calc(39% + 326px)'
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
                        'bottom' : 'auto',
                        'height' : 'auto'
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


            $('.mark-{{request()->episodio}}').css('opacity','0.7');

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