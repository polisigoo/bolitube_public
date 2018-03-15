@extends("layouts/plantillaPrincipal")

@section("Title")
Home @endsection

@section("links")
    <link href="{{ asset("css/fonts/style.css") }}" rel="stylesheet">
    <link href="{{ asset("css/listSerie.css") }}" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css?family=PT+Sans" rel="stylesheet">
@endsection

@section("body")
<div id="dt_contenedor">
@include("layouts.navbarWhite")

<div id="contenedor">
<div id="single">
    <div class="videos">

        <h2><a href="#" class="titulo">Series</a></h2>
<div class="clear" style="clear: both;"></div>
        <ul class='listas'>

        @foreach($series as $serie)
            <ol class="folder">
                <div class="imagen">
                    <a href="{{ route('episodios.list', ['serieuri' => $serie->uri]) }}">
                        @if(empty($serie->poster_path))
                            @if(empty($serie->poster_path))
                                <img src="{{ asset('css/img/question.png') }}" alt="{{ $serie->show_name }}" id="imagen" style="margin-top: -3px; width: 237px;height: 148px;border-radius: .25rem;margin-left: 32.5px !important;">
                            @else
                                <img src="{{ $serie->fondo_path }}" alt="{{ $serie->show_name }}" style="margin-top: -3px; width: 237px;height: 148px;border-radius: .25rem;margin-left: 14.5px;" id="imagen">
                            @endif
                        @else
                          <img src="{{ $serie->poster_path }}" alt="{{ $serie->show_name }}" style="margin-top: -3px; width: 237px;height: 148px;border-radius: .25rem;margin-left: 14.5px;" id="imagen">
                        @endif
                    </a>

                    <div class="cantidad">
                        <p>{{ $serie->temporadas }} <i class="icon-stack"></i></p>
                    </div>
                </div>
                <div class="info">
                    <div class="wrapper">
                    <div class="title">
                        <a href="{{ route('episodios.list', ['serieuri' => $serie->uri]) }}">{{ $serie->show_name }}</a>
                        @if(!empty($serie->primera_transmision))
                        <?php $fecha = \Carbon\Carbon::parse($serie->primera_transmision)->format('d/m/Y');
                              $str = Carbon\Carbon::createFromFormat('d/m/Y', $fecha);?>
                        <span>{{ $str->toFormattedDateString() }}</span>
                        @endif
                    </div>
                    </div>
                    <p class="overview">{{ htmlspecialchars_decode(substr($serie->descripcion,0,315), ENT_QUOTES) }}...</p>
                </div>
            </ol>
        @endforeach

        </ul>
    </div>
    {{ $series->links() }}
</div>
</div>
</div>
@endsection
@section('afterjquery')
    <script>
        $('#a-list').addClass('active');
    </script>
@endsection
