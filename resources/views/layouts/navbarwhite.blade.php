<div class="s" style="position: relative;">
<link rel="stylesheet" href="{{ asset('css/navbarwhite.css') }}">
<nav class="navbar navbar-expand-lg navbar-light bg-light py-1">
    <a class="navbar-brand" href="{{ url('/') }}">
        <img src="{{ asset("css/img/favicon-bo.png") }}" class="d-inline-block align-top brand-logo">
        <p>Bolitube</p>
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/') }}" id="a-inicio"> Inicio <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('series.list')}}" id="a-list">Series</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('movie.list')}}" id="a-list-movie">Movies</a>
            </li>
            @if(Illuminate\Support\Facades\Auth::check())
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Crear
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ route('create.serie') }}">Serie</a>
                    <a class="dropdown-item" href="{{ route('create.episodio') }}">Episodio</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('create.movie') }}">Pelicula</a>
                </div>
            </li>
            @endif
        </ul>
        <form class="form-inline my-2 my-lg-0" action="{{ route('search') }}">
            <input class="form-control mr-sm-2" type="search" name="query_s" placeholder="Search" aria-label="Search" autocomplete="off">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit"><i class="icon-search" style="color: #00bf00"></i></button>
        </form>
    </div>
</nav>

<div class="l-search">
    <div class="spinner icon-spinner2 hidden" aria-hidden="true" id="spinn"></div>
    <ul class="items scroll-black" id="items">
    </ul>
</div>
</div>