@extends("layouts/plantillaPrincipal")

@section("Title")
Home @endsection

@section("links")
    <link href="{{ asset("css/fonts/style.css") }}" rel="stylesheet">
    <link href="{{ asset("css/listMovies.css") }}" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css?family=PT+Sans" rel="stylesheet">
@endsection

@section("body")
<div id="dt_contenedor">
@include("layouts.navbarWhite")

<div id="contenedor">
<div id="single">
    <div class="videos">

    <h2><a href="#" class="titulo">Peliculas</a></h2>
    <div class="clear" style="clear: both;"></div>
        <ul class='listas'>

        @foreach($movies as $movie)
            <?php $fecha = substr($movie->fecha_estreno, 0,4); ?>
            <ol class="folder">
                <div class="imagen">
                    <a href="{{ route('movie.watch', ['movieuri' => $movie->uri]) }}">
                        @if(empty($movie->poster_path))
                            @if(empty($movie->fondo_path))
                                <img src="{{ asset('css/img/question.png') }}" alt="{{ $movie->titulo }}" id="imagen" style="margin-top: -3px; width: 237px;height: 148px;border-radius: .25rem;margin-left: 32.5px !important;">
                            @else
                                <img src="{{ $movie->fondo_path }}" alt="{{ $movie->titulo }}" style="margin-top: -3px; width: 237px;height: 148px;border-radius: .25rem;margin-left: 14.5px;" id="imagen">
                            @endif
                        @else
                          <img src="{{ $movie->poster_path }}" alt="{{ $movie->titulo }}" style="margin-top: -3px; width: 237px;height: 148px;border-radius: .25rem;margin-left: 14.5px;" id="imagen">
                        @endif
                    </a>

                    <div class="cantidad">
                        <p>{{ $fecha }}</p>
                    </div>
                </div>
                <div class="info">
                    <div class="wrapper">
                        <div class="title">
                            <a href="{{ route('movie.watch', ['movieuri' => $movie->uri]) }}">{{ htmlspecialchars_decode($movie->titulo ." (".$fecha . ")") }}</a>
                            @if(!empty($movie->fecha_estreno))
                                <?php $fecha = \Carbon\Carbon::parse($movie->fecha_estreno)->format('d/m/Y');
                                $str = Carbon\Carbon::createFromFormat('d/m/Y', $fecha);?>
                                <span>{{ $str->toFormattedDateString() }}</span>
                            @endif
                        </div>
                    </div>
                    <p class="overview">{{ substr($movie->resumen,0,315) }}...</p>
                </div>
            </ol>
        @endforeach

        </ul>
    </div>
    {{ $movies->links() }}
</div>
</div>
</div>
@endsection
@section('afterjquery')
    <script>
        $('#a-list-movie').addClass('active');
    </script>
@endsection
