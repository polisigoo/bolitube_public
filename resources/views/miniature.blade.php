@extends("layouts.plantillaPrincipal")

@section("Title")
Home @endsection

@section("links")
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link href="{{ asset("css/main.css") }}" rel="stylesheet">
    <link href="{{ asset("css/watch.css") }}" rel="stylesheet">
@endsection


@section("body")
<div id="dt_contenedor">
@include("layouts.navbar")
<div id="contenedor">
<div id="single">
    <div class="content">
        <div class="options">
            <select name="categorias" id="categoria">
                <option value="">Selecciona una categoria</option>
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->categoria }}">{{ $categoria->categoria }}</option>
                @endforeach
            </select>
        </div>

        <div id="show">
            <!-- ITEMS TO BE DISPLAYED HERE -->
        </div>
    </div>
</div>
</div>
</div>
@endsection

@section('afterbootstrap')
    <script>
        function getopt()
        {

        }
    </script>
    <script>
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('select#categoria').on('change', function (){

                var selected = $(this).val(); /* GET THE VALUE OF THE SELECTED DATA */
                var dataString = "selected:"+selected; /* STORE THAT TO A DATA STRING */

                $.post('{{ url('/getopt') . '/' }}'+$(this).val(), function(response){
                    if(response.success)
                    {
                        alert("AAAA");
                    }
                }, 'json');

                {{--$.ajax({ /* THEN THE AJAX CALL */--}}
                    {{--type: "POST",--}}
                    {{--url: '{{ url('/getopt') . '/' }}',--}}
                    {{--data: {selected:selected},--}}
                    {{--headers: {--}}
                        {{--'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')--}}
                    {{--},--}}
                    {{--success: function(result){ /* GET THE TO BE RETURNED DATA */--}}
                        {{--$("#show").html(result); /* THE RETURNED DATA WILL BE SHOWN IN THIS DIV */--}}
                    {{--}--}}
                {{--});--}}
            });

            /*$('select#carpetas').on('change', function (){
                alert("bb");
            });*/
        });
    </script>
@endsection