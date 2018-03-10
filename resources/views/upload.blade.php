@extends("layouts/plantillaPrincipal")

@section("Title")
Upload @endsection

@section("links")
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script type="text/javascript" src="{{ asset('js/plupload.full.min.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/jquery-ui.min.css') }}">
    <link href="{{ asset("css/fonts/style.css") }}" rel="stylesheet">
    {{--<link rel="stylesheet" href="{{ asset('css/uploadStyle.css') }}">--}}
    {{--<link rel="stylesheet" href="{{ asset('css/uploadercss.css') }}">--}}
    <link rel="stylesheet" href="{{ asset('css/white/uploadStyleWhite.css') }}">
    <link rel="stylesheet" href="{{ asset('css/white/uploaderWhite.css') }}">

    <link href="https://fonts.googleapis.com/css?family=PT+Sans" rel="stylesheet">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
@endsection

@section("body")
<div id="dt_contenedor">
@include("layouts.navbarWhite")

<div id="contenedor">
    <div class="upload-form" id="container">
        <h1 class="replace-text">Upload Form</h1>
        <p>Selecciona una <i>categoria</i> y una <i>carpeta</i> para los videos, tambien debes añadir algunos tags para poder ubicarlo correctamente</p>

        <div class="serie">
        <label>Categoria / Serie</label>
        <input type="text" class="field__input w3-input w3-animate-input form-control" name="categoria" id="categoria" placeholder="e.g Silicon Valley"><br>
        </div>

        <div class="carpeta">
        <label>Carpeta / Temporada</label>
        <input type="text" class="field__input w3-input w3-animate-input form-control" name="carpeta" id="carpeta" placeholder="e.g Silicon Valley S1"><br>
        </div>

        <label>Tags</label>
        <div id="tags">
            <input type="text" class="field__input w3-input w3-animate-input form-control" name="tags" id="etiquetas" placeholder="e.g pelicula, 2017, español, Series"><br>
        </div>
        <p style="margin-top: -5px" id="nota">Nota: Si apretas enter el tag se agregará</p>

        <div id="btns">
            <a id="pickfiles" class="button w3-teal" href="javascript:;"><span class="icon-plus"></span>Select</a>
            <a id="uploadfiles" class="button w3-teal" href="javascript:;"><span class="icon-cloud-upload"></span>Upload</a>
        </div>

        <br>
        <br>

        <!-- File List -->
        <div id="filelist" class="cb">Your browser doesn't have Flash, Silverlight or HTML5 support.</div>
        <br />

        <!-- Progress Bar -->
        <div id="progressbar"></div>
        <div id="myProgress" class="progress">
            <div id="myBar" class="progress-bar progress-bar-striped progress-bar-animated"></div>
        </div>
    </div>

    <br />
    <pre id="console"></pre>

<div id="single">
    
</div>
</div>
</div>
@endsection
@section('afterjquery')
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script>
        $(function(){ // DOM ready

            var mytags = [];
            // ::: TAGS BOX

            $("#tags input").on({
                focusout : function() {
                    var txt = this.value.replace(/[^a-z0-9\+\-\.\/ /#]/ig,'').trim(); // allowed characters
                    //Remplazamos los espacios extras '          a            b' = 'a b'
                    txt = txt.replace(/ +(?= )/g,'');

                    //si la variable txt no esta vacia creamos un span con el valor ingresado
                    if(txt) $("<span/>", {text:txt.toLowerCase(), insertBefore:this});

                    // y lo agregamos al array, y vaciamos el input
                    mytags.push(txt.toLowerCase());
                    this.value = "";
                },
                keyup : function(ev) {
                    // if: comma|enter (delimit more keyCodes with | pipe)
                    if(/(188|13)/.test(ev.which)) $(this).focusout();
                }
            });

            function removeA(arr) {
                var what, a = arguments, L = a.length, ax;
                while (L > 1 && arr.length) {
                    what = a[--L];
                    while ((ax= arr.indexOf(what)) !== -1) {
                        arr.splice(ax, 1);
                    }
                }
                return arr;
            }

            $('#tags').on('click', 'span', function() {
                if(confirm("Remove "+ $(this).text() +"?")){
                    //Quitamos el elento del DOM
                    $(this).remove();

                    mytags.forEach(function (entry, index, mytags) { //Se borran pero en realidad no. no se que pasa
                        if(mytags[index] === $(this).text()) {
                            delete mytags[index];
                            console.log(mytags[index]);
                        }
                    });

                    //Obtenemos el valor del <p> y lo eliminamos, si existe
                    if (document.contains(document.getElementsByClassName("etique")[0])) {
                        var value = document.getElementsByClassName('etique')[0].innerHTML;
                        var str = value.replace($(this).text() + ',', '');

                        //Le damos el valor sin el elemento eliminado
                        document.getElementsByClassName('etique')[0].innerHTML = str;

                        //Tambien le cambiamos el valor al input, sin el elmento borrado
                        $('input[name=tags]').val($.trim(str));

                    }

                    //removeA(mytags, $(this).text() + ','); dont w
                }
            });

            $("#uploadfiles").on('click',function () {

                //remplazamos todos las , repetidas por '', creo que funciona xd
                mytags.forEach(function (entry) {
                    entry = entry.replace("/,+(?=,)/g",'');
                    console.log(entry);
                });

                //Le damos el valor del array
                $("#etiquetas").val($.trim(mytags.join(',')));

                // Si el elemento no existe se crea
                if (!document.contains(document.getElementsByClassName("etique")[0])) {
                    $("<p/>", {text: mytags.join(','), insertAfter: '#nota', class: 'etique'});
                }

                //Ocultamos el input
                document.getElementById("etiquetas").hidden = true;

                //removemos nota con el tip, asi no molesta
                document.getElementById("nota").remove();
            });

        });
    </script>
    <script type="text/javascript">
        var uploader = new plupload.Uploader({
            runtimes : 'html5,flash,silverlight,html4',
            browse_button : 'pickfiles', // you can pass an id...
            container: document.getElementById('container'), // ... or DOM Element itself
            url : '{{ url('/upload') }}',
            flash_swf_url : '../js/Moxie.swf',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            silverlight_xap_url : '../js/Moxie.xap',

            filters : {
                max_file_size : '10000mb',
                chunk_size: '900 kb',
                max_retries: 3,
                mime_types: [
                    {title : "Video files", extensions : "mp4,mkv,avi,ts"}
                ]
            },

            // PreInit events, bound before the internal events
            preinit : {

                UploadFile: function(up, file) {
                    up.setOption('multipart_params', {
                        categoria : document.getElementById('categoria').value,
                        carpeta : document.getElementById('carpeta').value,
                        tipo : file.type,
                        tags : document.getElementById('etiquetas').value
                    });
                }
            },

            init: {
                PostInit: function() {
                    document.getElementById('filelist').innerHTML = '';

                    document.getElementById('uploadfiles').onclick = function() {
                        uploader.start();
                        return false;
                    };
                },

                FilesAdded: function(up, files) {
                    plupload.each(files, function(file) {
                        document.getElementById('filelist').innerHTML += '<div class="addedFile" id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></div>';
                    });
                },

                UploadProgress: function(up, file) {
                    document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
                    $('#myProgress').css('display', 'block');
                    $('#myBar').css('width', file.percent + '%');
                },

                Error: function(up, err) {
                    document.getElementById('console').appendChild(document.createTextNode("\nError #" + err.code + ": " + err.message));
                }
            }
        });


        uploader.init();
    </script>
    <script>
        $('#a-upload').addClass('active');
    </script>
@endsection