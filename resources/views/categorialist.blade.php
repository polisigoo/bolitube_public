@extends("layouts/plantillaPrincipal")

@section("Title")
Home @endsection

@section("links")
    <link href="{{ asset("css/fonts/style.css") }}" rel="stylesheet">
    <link href="{{ asset("css/white/categorialistWhite.css") }}" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css?family=PT+Sans" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
@endsection

@section("body")
<div id="dt_contenedor">
@include("layouts.navbarWhite")
<div id="contenedor">
<div id="single">
    <div class="videos" id="">
           <div class="categoria" id="{{ $serie->show_name }}">
               <div class="info">
                   <h1 class="categ_title">{{ $serie->show_name }}</h1>
                   <div class="imagen">
                       <a href="#">
                           @if(empty($serie->poster_path))
                               <img src="{{ asset('css/img/question.png') }}" alt="{{ $episodio->titulo }}" id="imagen" style="margin-top: -3px; width: 225px;height: 125px;border-radius: .25rem;">
                           @else
                               <img src="{{ $serie->poster_path }}" alt="{{ $serie->show_name }}" style="margin-top: -3px;width: 211px;height: 211px;border-radius: .25rem;" id="imagen">
                           @endif
                       </a>
                   </div>
               </div>

   <div id="accordion">
    @for($i=1; $i <= $serie->temporadas; $i++)
        <?php $episodios = App\Models\Episodio::where('temporada', $i)
                       ->where('serie_id', $serie->id)
                       ->orderBy('temporada', 'asc')
                       ->orderBy('episodio', 'asc')
                       ->get(); ?>
       @if(!$episodios->isEmpty())
        <div class="season card">
            <button class="btn btn-link" data-toggle="collapse" data-target="#season{{$i}}" aria-expanded="true" aria-controls="collapseOne">
                Temporada {{ $i }}
            </button>
            @if($i === 1)
            <div class="episodes collapse show" id="season{{$i}}" aria-labelledby="headingOne" data-parent="#accordion">
            @else
            <div class="episodes collapse" id="season{{$i}}" aria-labelledby="headingOne" data-parent="#accordion">
            @endif
                <div class="card-body">
            @foreach($episodios as $episodio)
                <ul class="video">
                   <li>
                       <div class="imagen">
                           <a href="{{route('serie.watch',
                                        ['serieuri' => $serie->uri,
                                        'temporada' => $episodio->temporada,
                                        'episodio' => $episodio->episodio]) }}">
                               @if($episodio->image_path === 'https://image.tmdb.org/t/p/w500')
                                   <img src="{{ asset('css/img/missed_image.png') }}" alt="{{ $episodio->titulo }}" id="imagen" style="margin-top: -3px; width: 225px;height: 125px;border-radius: .25rem;">
                               @else
                               <img src="{{ $episodio->image_path }}" alt="{{ $episodio->titulo }}" style="margin-top: -3px; width: 225px;height: 125px;border-radius: .25rem;" id="imagen">
                               @endif
                           </a>
                       </div>
                       <div class="title">
                           <a href="{{route('serie.watch',
                                        ['serieuri' => $serie->uri,
                                        'temporada' => $episodio->temporada,
                                        'episodio' => $episodio->episodio]) }}">{{ $episodio->episodio }} - {{ $episodio->titulo }}</a>
                       </div>
                       <div class="date">{{ date("F d, Y", strtotime($episodio->fecha_estreno)) }}</div>
                       <div class="overview"><p class="resum">{{ $episodio->resumen }}</p></div>
                   </li>
                </ul>
            @endforeach
                </div>
            </div>
        </div>
        @endif
    @endfor
     </div>
    </div>
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
