@extends("layouts/plantillaPrincipal")

@section("Title")
Home @endsection

@section("links")
    <link href="{{ asset("css/fonts/style.css") }}" rel="stylesheet">
    {{--<link href="{{ asset("css/watch.css") }}" rel="stylesheet">--}}
    {{--<link href="{{ asset("css/indexStyle.css") }}" rel="stylesheet">--}}

    {{--White--}}
    <link href="{{ asset("css/white/indexStyleWhite.css") }}" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css?family=PT+Sans" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
@endsection

@section("body")
<div id="dt_contenedor">
@include("layouts.navbarWhite")
    <div class="dcategorias">
        <ul id="categs">
            @foreach($categorias as $categoria)
                <li class="categ"><a href="{{ route('video.list.categoria', ['categoria' => $categoria->categoria]) }}">{{ $categoria->categoria }}</a></li>
            @endforeach
        </ul>
    </div>
<div id="contenedor">
    {{-- Carousel --}}
    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
        <h1 class="semititulo">Más recientes</h1>
        <ol class="carousel-indicators">
            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
        </ol>
        <div class="carousel-inner">
            <?php $cont = 0; ?>
            @foreach($ultimostres as $ultimo)

                @if($cont == 0)
                <div class="carousel-item active">
                @else
                <div class="carousel-item">
                @endif
                    <a href="{{ route('video.watch', ['videoid' => $ultimo->unique_id]) }}">
                        @if(empty($ultimo->image_name))
                            <img src="{{ asset('css/img/question.png') }}" class="d-block w-100" alt="{{ $ultimo->titulo }}" style="width: 100%;height: 400px;">
                        @else
                            <img class="d-block w-100" src="{{ url("/storage/images/{$ultimo->categoria}/{$ultimo->carpeta_season}/max_res{$ultimo->image_name}") }}" alt="{{ $ultimo->titulo }}" style="width: 100%;height: 400px;"></a>
                        @endif
                    <div class="carousel-caption">
                        <h3>{{ $ultimo->titulo }}</h3>
                        <p>{{ date("F d, Y", strtotime($ultimo->created_at)) }}</p>
                    </div>
                </div>
            <?php $cont++ ?>
            @endforeach
        </div>
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>

<div id="single">
    <div class="videos">
        <h1 class="semititulo" style="margin-bottom: -5px;">Todos los videos</h1>
       @foreach($videos as $video)
           <ul class="video">
               <li>
                   <div class="imagen">
                    <a href="{{ route('video.watch', ['videoid' => $video->unique_id]) }}">
                    @if(empty($video->image_name))
                    <img src="{{ asset('css/img/question.png') }}" alt="{{ $video->titulo }}" id="imagen" style="margin-top: -3px; width: 225px;height: 125px;border-radius: .25rem;margin-left: 32.5px !important;">
                    @else
                        <img src="{{ url('/storage/images/'.$video->categoria .'/'.$video->carpeta_season.'/hq'.$video->image_name) }}" alt="{{ $video->titulo }}" style="margin-top: -3px; width: 290px;height: 151px;border-radius: .25rem;margin-left: 14.5px;" id="imagen">
                    @endif
                    </a>
                   </div>
                   <div class="title">
                       <a href="{{ route('video.watch', ['videoid' => $video->unique_id]) }}">{{ $video->titulo }}</a>
                       <span class="date">{{ date("F d, Y", strtotime($video->created_at)) }}</span>
                   </div>
               </li>
           </ul>
       @endforeach

        <div class="text-center">
        {{ $videos->links() }}
        </div>
    </div>
</div>
</div>
</div>
@endsection

@section('afterjquery')
    <script>
        $('#a-inicio').addClass('active');
    </script>
@endsection
