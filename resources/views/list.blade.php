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

        <?php $cat = "dvsdv"; $cont = 0; ?>
        @foreach($lista as $list)

               {{-- Si la categoria no es igual a la categoria anterior --}}
               @if($cat != $list->categoria)
                </ul>

                <ul class='listas'>
                        <h2><a href="{{ route('video.list.categoria', ['categoria' => $list->categoria ]) }}" class="titulo">{{ $cat = $list->categoria }}</a></h2>
                @endif

                    <ol class="folder">
                        <div class="imagen">
                            <a href="{{ route('video.list.categoria', ['categoria' => $list->categoria .'#'.$list->carpeta_season]) }}">
                                <?php $img = App\Video::select('image_name')
                                    ->where('categoria', $list->categoria)
                                    ->where('carpeta_season',$list->carpeta_season )
                                    ->first();

                                    $cant_folders = App\Video::where('categoria', $list->categoria)
                                        ->where('carpeta_season',$list->carpeta_season )
                                        ->count();?>
                                @if(empty($img->image_name))
                                    <img src="{{ asset('css/img/question.png') }}" alt="{{ $list->carpeta_season }}" id="imagen" style="margin-top: -3px; width: 237px;height: 148px;border-radius: .25rem;margin-left: 32.5px !important;">
                                @else
                                  <img src="{{ url('/storage/images/'.$list->categoria .'/'.$list->carpeta_season.'/hq'.$img->image_name) }}" alt="{{ $list->carpeta_season }}" style="margin-top: -3px; width: 237px;height: 148px;border-radius: .25rem;margin-left: 14.5px;" id="imagen">
                                @endif
                            </a>
                        </div>
                        <div class="cantidad">
                            <p>{{ $cant_folders }} <i class="icon-stack"></i></p>
                        </div>
                        <div class="title">
                            <a href="{{ route('video.list.categoria', ['categoria' => $list->categoria .'#'.$list->carpeta_season]) }}" class="btn btn-info">{{ str_replace("_", " ", $list->carpeta_season) }}</a>
                        </div>

                    <!-- Button trigger modal -->


                    </ol>
        @endforeach
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
