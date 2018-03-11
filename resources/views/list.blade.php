@extends("layouts/plantillaPrincipal")

@section("Title")
Home @endsection

@section("links")
    <link href="{{ asset("css/fonts/style.css") }}" rel="stylesheet">
    <link href="{{ asset("css/listStyle.css") }}" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css?family=PT+Sans" rel="stylesheet">
@endsection

@section("body")
<div id="dt_contenedor">
@include("layouts.navbarWhite")

<div id="contenedor">
<div id="single">
    <div class="videos">

        <ul class='listas'>
            <h2><a href="#" class="titulo">Series</a></h2>

        @foreach($series as $serie)
            <ol class="folder">
                <div class="imagen">
                    <a href="{{ route('episodios.list', ['serieuri' => $serie->uri]) }}">
                        @if(empty($serie->fondo_path))
                            @if(empty($serie->poster_path))
                                <img src="{{ asset('css/img/question.png') }}" alt="{{ $serie->show_name }}" id="imagen" style="margin-top: -3px; width: 237px;height: 148px;border-radius: .25rem;margin-left: 32.5px !important;">
                            @else
                                <img src="{{ $serie->poster_path }}" alt="{{ $serie->show_name }}" style="margin-top: -3px; width: 237px;height: 148px;border-radius: .25rem;margin-left: 14.5px;" id="imagen">
                            @endif
                        @else
                          <img src="{{ $serie->fondo_path }}" alt="{{ $serie->show_name }}" style="margin-top: -3px; width: 237px;height: 148px;border-radius: .25rem;margin-left: 14.5px;" id="imagen">
                        @endif
                    </a>
                </div>
                <div class="cantidad">
                    <p>{{ $serie->temporadas }} <i class="icon-stack"></i></p>
                </div>
                <div class="title">
                    <a href="{{ route('episodios.list', ['serieuri' => $serie->uri]) }}" class="btn btn-info">{{ $serie->show_name }}</a>
                </div>
            </ol>
        @endforeach

        </ul>
    </div>
</div>
</div>
</div>
@endsection
@section('afterjquery')
    <script>
        $('#a-list').addClass('active');
    </script>
@endsection
