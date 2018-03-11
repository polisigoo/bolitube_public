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
           <h1 class="categ_title">{{ $carpeta->carpeta_season }}</h1>
            <?php  $videos = App\Video::select('titulo', 'unique_id', 'created_at', 'image_name', 'categoria', 'carpeta_season')
                ->where('carpeta_season', e($carpeta->carpeta_season))
                ->where('categoria', $categoria)
                ->get();
            ?>
            @foreach($videos as $video)
           <ul class="video">
               <li>
                   <div class="imagen">
                       <a href="{{ route('video.watch', ['videoid' => $video->unique_id]) }}">
                           @if(empty($video->image_name))
                               <img src="{{ asset('css/img/question.png') }}" alt="{{ $video->titulo }}" id="imagen" style="margin-top: -3px; width: 225px;height: 125px;border-radius: .25rem;">
                           @else
                           <img src="{{ url('/storage/images/'.$video->categoria .'/'.$video->carpeta_season.'/hq'.$video->image_name) }}" alt="{{ $video->titulo }}" style="margin-top: -3px; width: 225px;height: 125px;border-radius: .25rem;" id="imagen">
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
