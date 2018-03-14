@extends("layouts/plantillaPrincipal")

@section("Title")
Home @endsection

@section("links")
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ asset("css/fonts/style.css") }}" rel="stylesheet">
    {{--<link href="{{ asset("css/watch.css") }}" rel="stylesheet">--}}
    {{--<link href="{{ asset("css/indexStyle.css") }}" rel="stylesheet">--}}

    {{--White--}}
    <link href="{{ asset("css/white/indexStyleWhite.css") }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset("css/slick.css") }}">
    <link rel="stylesheet" href="{{ asset("css/slick-theme.css") }}">

    <link href="https://fonts.googleapis.com/css?family=PT+Sans" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
@endsection

@section("body")
<div id="dt_contenedor">
@include("layouts.navbarWhite")
    <div class="live-search">

    </div>
    <div class="dcategorias">
        <ul id="categs">
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
        </ul>
    </div>
    <div class="s-slider">
        <h1 class="semititulo" style="margin-bottom: -5px;">Series recientes</h1>
        <div class="slick-sli">
            @foreach($ultimostres as $ultimo)
                <div>
                 @if(empty($ultimo->poster_path))
                      <img src="{{ asset('css/img/question.png') }}" alt="{{ $ultimo->show_name }}">
                @else
                <a href="{{ route('episodios.list', ['serieuri' => $ultimo->uri]) }}">
                  <img src="{{ $ultimo->poster_path }}" alt="{{ $ultimo->show_name }}" style="width: 200px;height: 193px;">
                </a>
                @endif
                </div>
            @endforeach
        </div>
    </div>
</div>

<div id="single">
    <div class="videos">
        <h1 class="semititulo" style="margin-bottom: -5px;">Todos los videos</h1>
       @foreach($series as $serie)
           <ul class="video">
               <li>
                   <div class="imagen">
                    <a href="{{ route('episodios.list', ['serieuri' => $serie->uri]) }}">
                    @if(empty($serie->fondo_path))
                    <img src="{{ asset('css/img/question.png') }}" alt="{{ $serie->show_name }}" id="imagen" style="margin-top: -3px; width: 225px;height: 125px;border-radius: .25rem;margin-left: 32.5px !important;">
                    @else
                        <img src="{{ $serie->fondo_path }}" alt="{{ $serie->show_name }}" style="margin-top: -3px; width: 290px;height: 151px;border-radius: .25rem;margin-left: 14.5px;" id="imagen">
                    @endif
                    </a>
                   </div>
                   <div class="title">
                       <a href="{{ route('episodios.list', ['serieuri' => $serie->uri]) }}">{{ $serie->show_name }}</a>
                       <span class="date">{{ date("F d, Y", strtotime($serie->primera_transmision)) }}</span>
                   </div>
               </li>
           </ul>
       @endforeach

        <div class="text-center">
        {{ $series->links() }}
        </div>
    </div>
</div>
@endsection

@section('afterjquery')
        <script type="text/javascript" src="{{ asset("js/slick.min.js") }}"></script>
        <script>
            $(document).ready(function(){
                $('.slick-sli').slick({
                    arrows : true,
                    slidesToShow: 2,
                    slidesToScroll: 1,
                    autoplay: true,
                    dots : true,
                    autoplaySpeed: 2000,
                    variableWidth: true,
                });
            });
        </script>
    <script>
        $('#a-inicio').addClass('active');
    </script>
@endsection
