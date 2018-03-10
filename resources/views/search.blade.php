@extends("layouts/plantillaPrincipal")

@section("Title")
Home @endsection

@section("links")
    <link href="{{ asset("css/fonts/style.css") }}" rel="stylesheet">
    <link href="{{ asset("css/watch.css") }}" rel="stylesheet">
    <link href="{{ asset("css/searchSyle.css") }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
@endsection

@section("body")
<div id="dt_contenedor">
@include("layouts.navbarWhite")
<div id="contenedor">
<div id="single">
    <div class="videos" id="">
        @if($categorias->isEmpty() && $carpetas->isEmpty() && $tagsvacio && $videos_final->isEmpty())
            <h1 class="semititulo">No hemos encontrado nada con tu consulta. Inténtalo de nuevo!</h1>

            <div id="buscador">
                <form action="{{ route('search') }}" method="GET">
                    <div id="custom-search-input">
                        <div>
                            <input type="text" name="query_s" placeholder="Buscar" />
                            <span>
                                <button type="submit" value="">
                                    <i class="icon-search" style="color: #00bf00"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                </form>
            </div>
        @endif

        @if(!$videos_final->isEmpty())
        <div class="search-videos">
            <h1 class="semititulo">Videos</h1>
            @foreach($videos_final as $video)
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
        @endif

        @if(!$categorias->isEmpty() || !$carpetas->isEmpty() || !$tagsvacio)
        <div class="c-c-t">
        <div class="search-categories three">
            <h1 class="semititulo">Categorias</h1>
                <ul class="categoria">
                    @if(!$categorias->isEmpty())
                        @foreach($categorias as $categoria)
                        <li>

                        <div class="title">
                            <a href="{{ route('video.list.categoria', ['categoria' => $categoria->categoria]) }}">{{ $categoria->categoria }}</a>
                        </div>
                        </li>
                        @endforeach
                    @else
                        <p>No hemos podido encontrar nada aquí!</p>
                    @endif
                </ul>
        </div>
        <div class="search-carpetas three">
            <h1 class="semititulo">Carpetas</h1>
                <ul class="carpeta">
                    @if(!$carpetas->isEmpty())
                        @foreach($carpetas as $carpeta)
                        <?php $categ = DB::table('videos')->select('categoria')
                                        ->where('carpeta_season', $carpeta->carpeta_season)
                                        ->distinct()
                                        ->first();?>
                        <li>
                            <div class="title">
                <a href="{{ route('video.list.categoria', ['categoria' => $categ->categoria."#".$carpeta->carpeta_season]) }}">{{ $carpeta->carpeta_season }}</a>
                            </div>
                        </li>
                        @endforeach
                    @else
                        @if($categorias->isEmpty())
                            <p>Por aquí tampoco!</p>
                        @else
                            <p>No hemos podido encontrar nada aquí!</p>
                        @endif
                    @endif
                </ul>
        </div>
        <div class="search-tags three">
            <h1 class="semititulo">Tags</h1>
                <ul class="tag">
                    @if(!empty($tags))
                        @foreach($tags as $tag)
                            <li>
                                <div class="title">
                                    <a href="{{ route('tag', ['tagname' => $tag]) }}">{{ $tag }}</a>
                                </div>
                            </li>
                        @endforeach
                    @else
                        @if($categorias->isEmpty() || $carpetas->isEmpty())
                            <p>Vaya.. no hay nada para mostrar</p>
                        @else
                            <p>No hemos podido encontrar nada aquí!</p>
                        @endif
                        <p>No hemos podido encontrar nada aquí!</p>
                    @endif
                </ul>
        </div>
    </div>
    @endif
    </div>
</div>
</div>
</div>
@endsection
