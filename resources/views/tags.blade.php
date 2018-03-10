@extends("layouts/plantillaPrincipal")

@section("Title")
Tags @endsection

@section("links")
    <link href="{{ asset("css/fonts/style.css") }}" rel="stylesheet">
    <link href="{{ asset("css/watch.css") }}" rel="stylesheet">
    <link href="{{ asset("css/tagsStyle.css") }}" rel="stylesheet">
@endsection

@section("body")
<div id="dt_contenedor">
@include("layouts.navbarWhite")

<div id="contenedor">
<div id="single">
    <div class="videos">
       @foreach($videos as $video)
           <ul class="video">
               <li>
                   <div class="imagen">
                       <a href="#">
                           @if(empty($video->image_name))
                               <img src="{{ asset('css/img/question.png') }}" alt="{{ $video->titulo }}" id="imagen">
                           @else
                               @if(!file_exists(storage_path() . '/app/public/images/' . $video->categoria .'/' . $video->carpeta_season . '/' .'hq'. $video->image_name))
                                   <img src="{{ url('/storage/images/'.$video->categoria .'/'.$video->carpeta_season.'/'.$video->image_name) }}" alt="{{ $video->titulo }}" style="margin-top: -3px;max-width: 90%;" id="imagen">
                               @else
                                   <img src="{{ url('/storage/images/'.$video->categoria .'/'.$video->carpeta_season.'/hq'.$video->image_name) }}" alt="{{ $video->titulo }}" style="margin-top: -3px; width: 290px;height: 151px;border-radius: .25rem;margin-left: 14.5px;" id="imagen">
                               @endif
                           @endif
                       </a>
                   </div>
                   <div class="title">
                       <a href="{{ route('video.watch', ['videoid' => $video->unique_id]) }}">{{ $video->titulo }}</a>
                       <span class="date">{{ $video->created_at }}</span>
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
