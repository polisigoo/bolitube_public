<html>
    <head>
        <meta charset="UTF-8" />
        <title>Player</title>
        <meta name="robots" content="noindex">
        <META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
        <META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">

        <link href="{{ asset('css/playercss.css') }}" rel="stylesheet" type="text/css" />

        <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
        <script type="text/javascript">
            $(window).on('load', function() {
                setButtons();
            });

            $(window).resize(function() {
                setButtons();
            });

            function setButtons() {
                $(".flutua").css("position", "absolute");

                var dif = $(".panel_boot").outerHeight();
                var hElem = $(".flutua").height();
                var wElem = $(".flutua").width();
                var hJan = $(window).height();
                var wJan = $(window).width();
                x = parseInt((wJan - wElem) / 2);
                y = parseInt((hJan - hElem) - dif);
                $(".flutua").css("left", x);
                $(".flutua").css("top", y);
            }
            var LoadingTime = 0;
            $(document).ready(function() {
                $("body").on('click', '#player .butPlayFilm', function() {
                    setButtons();
                    var $input = $(this);
                    console.log($input);
                    var SvID = $input.attr("svid");
                    $('#player').css('display', 'none');
                    addiframe(SvID);
                    $('#Svplayer').css('display', 'block');
                });

                function addiframe(URL) {
                    var ifHTML = '<div class="CloseButton" id="CloseSv" style="display: block;"></div><iframe src="' + URL + '" width="100%" height="100%" scrolling="no" frameborder="0" allowfullscreen="" webkitallowfullscreen="" mozallowfullscreen=""></iframe>';
                    $('#SvplayerID').html(ifHTML);
                }

                $(".Svplayer").on('click', '#CloseSv', function() {
                    $('#Svplayer').css('display', 'none');
                    $('#player').css('display', 'block');
                    $('#Svplayer').html('<div id="SvplayerID" style=" width: 100%; height: 100%;"><div id="flashplayer" class="" style="position: absolute; width: 100%; height: 100%;"></div></div>');
                    setButtons();
                });
                setInterval(setButtons(), 100);
            });

            function changserver() {
                $('#Svplayer').css('display', 'none');
                $('#player').css('display', 'block');
                $('#Svplayer').html('<div id="SvplayerID" style=" width: 100%; height: 100%;"><div id="flashplayer" class="" style="position: absolute; width: 100%; height: 100%;"></div></div>');
                $('#NotFoundVideo').css('display', 'none');
                setButtons();
            }
        </script>
    </head>
    <body>
    <script src="{{ asset('js/code.js') }}"></script>
    <div id="Svplayer" class="Svplayer" style="position: absolute; width: 100%; height: 100%;bottom: -1.5px;background-color: #000;border-width: 1px;  border-style: solid;  border-color: #000; display: none;">
        <div id="SvplayerID" style=" width: 100%; height: 100%;">
            <div id="flashplayer" class="" style="position: absolute; width: 100%; height: 100%;"></div>
        </div>
    </div>
    <div id="player" align="center" class="panel">
        <div id="bgPlayer"></div>
        <div id="bgBlackPlayer"></div>
        <div class="centerPlayer">
            <div class="msgSelPlayer">Selecciona un Servidor para Reproducir el video.</br>
                </br><img style="margin-top: 11px;width: 69px;" src="{{ asset('css/img/fecha.png') }}" width="" height=""></div>
            <div class="butPlayFilm" svid="">
                <div class="iconPlay">
                    <img src="{{ asset('css/img/play.png') }}">
                </div>
                <div class="textPlay">
                    <div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bottomPlayer">
            <div class="optionsLeg">
                <ul class="menuPlayer">
                    <li class="bar"></li>
                    @foreach($opciones as $opcion)
                        <?php try{ $opurl = decrypt($opcion); } catch (Illuminate\Contracts\Encryption\DecryptException $e) { }; ?>
                        @if(empty($opurl))
                            <?php dd("No hay servidores disponibles") ?>
                        @endif
                        @if(!empty(request()->m))
                            <?php $mo = '/?m=t' ?>
                            @else
                            <?php $mo = '' ?>
                        @endif
                        @if(preg_match("/\b(\w*google\w*)\b/", $opurl))
                        <li class="option" style="background-color: hsl(11, 43%, 55%);" title="Ver en Google" data-player="Google" data-playerid="{!! url('/embed/google/'. $opcion . $mo)  !!}">
                            <div class="iconBig">
                                <div><img src="{{ asset('css/img/gdrive.png') }}" /></div>
                            </div>
                        </li>
                        @elseif (preg_match("/\b(\w*openload\w*)\b/", $opurl))
                        <li class="option" style="background-color: hsl(263, 43%, 55%);" title="Ver en Openload" data-player="Openload" data-playerid={!! url('/embed/openload/'. $opcion . $mo) !!}>
                            <div class="iconBig">
                                <div><img src="{{ asset('css/img/openload.png') }}" /></div>
                            </div>
                        </li>
                        @elseif (preg_match("/\b(\w*streamago\w*)\b/", $opurl))
                        <li class="option" style="background-color: hsl(217, 43%, 55%);" title="Ver en Streamango" data-player="Streamango" data-playerid="{!! url('/embed/streamago/'. $opcion . $mo)  !!}">
                            <div class="iconBig">
                                <div><img src="{{ asset('css/img/streamango.png') }}" /></div>
                            </div>
                        </li>
                        @elseif (preg_match("/\b(\w*rapidvideo\w*)\b/", $opurl))
                        <li class="option" style="background-color: hsl(217, 43%, 55%);" title="Ver en Rapidvideo" data-player="Rapidvideo" data-playerid="{!! url('/embed/rapidvideo/'. $opcion . $mo)  !!}">
                            <div class="iconBig">
                                <div><img src="{{ asset('css/img/rapidvideo.png') }}" /></div>
                            </div>
                        </li>
                        @elseif (preg_match("/\b(\w*vidlox\w*)\b/", $opurl))
                            <li class="option" style="background-color: hsl(217, 43%, 55%);" title="Ver en Vidlox" data-player="vidlox" data-playerid="{!! url('/embed/rapidvideo/'. $opcion . $mo)  !!}">
                                <div class="iconBig">
                                    <div><img src="{{ asset('css/img/vidlox.png') }}" /></div>
                                </div>
                            </li>
                        @elseif (preg_match("/\b(\w*vidoza\w*)\b/", $opurl))
                            <li class="option" style="background-color: hsl(217, 43%, 55%);" title="Ver en Vidoza" data-player="vidoza" data-playerid="{!! url('/embed/rapidvideo/'. $opcion . $mo)  !!}">
                                <div class="iconBig">
                                    <div><img src="{{ asset('css/img/vidoza.png') }}" /></div>
                                </div>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    </body>
</html>
