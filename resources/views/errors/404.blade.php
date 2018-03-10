@extends("layouts/plantillaPrincipal")

@section("Title")
Home @endsection

@section("links")
    <link href="{{ asset("css/fonts/style.css") }}" rel="stylesheet">
    <link href="{{ asset("css/error404.css") }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=PT+Sans" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Oxygen" rel="stylesheet">
@endsection

@section("body")
<div id="dt_contenedor">
@include("layouts.navbarWhite")

<div id="contenedor">
<div id="single">
    <div class="error">
        <h1 style="color: red">Error: 404</h1>
        <h1><i class="icon-location2 red"></i> La pagina solicitada no pudo ser encontrada...</h1>
        <p id="talvez">Talvez puedas encontrar algo en la  <a href="{{ route('index') }}">pagina principal..</a></p>
        <p></p>

        <div id="buscador">
            <p>Támbien puedes buscar algo por aquí</p>
            <form action="{{ route('search') }}" method="GET">
            <div id="custom-search-input">
                <div class="input-group">
                    <input type="text" class="form-control input-lg" name="query_s" placeholder="Buscar" />
                    <span class="input-group-btn">
                        <button type="submit" value="">
                            <i class="icon-search" style="color: #00bf00"></i>
                        </button>
                    </span>
                </div>
            </div>
            </form>
        </div>

    </div>
    </div>
</div>
</div>
</div>
@endsection
