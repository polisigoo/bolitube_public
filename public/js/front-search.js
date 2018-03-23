var searching = false;

var spinner = document.createElement('div').innerHTML = '<div class="spinner icon-spinner2" aria-hidden="true"></div>';

var delay = (function(){
    var timer = 0;
    return function(callback, ms){
        clearTimeout (timer);
        timer = setTimeout(callback, ms);
    }
})();

function clear(){
    var myNode = document.getElementById("items");
    while (myNode.firstChild) {
        myNode.removeChild(myNode.firstChild);
    }
    $('.l-search').css('background-color','transparent');
}

$('input[name="query_s"]').on("input", function() {
    var se = $('input[name="query_s"]').val();

    if (se.length < 2 || searching){
        return;
    }

    clear();

    var spin = $('#spinn');

    spin.removeClass("hidden");

    spin.css("display", "inline-block");

    $('.l-search').css('background-color', '#fff');




    searching = true;

    var data = {
        'keyword' :se
    };

    $.ajax({
        type: 'POST',
        url: s_i.url,
        data: data,
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(data){
            if( data['error'] || data.length <= 0) {
                clear();
                return;
            }

            data.forEach(function(a) {
                var item = document.createElement('li');
                    item.className = "item";
                var link = document.createElement('a');
                var imagen = document.createElement('div');
                    imagen.className = "imagen";
                var img = document.createElement('img');
                var titulo = document.createElement('div');
                    titulo.className = "titulo";
                var estreno = document.createElement('span');
                    estreno.className = "estreno";
                var resum = document.createElement('div');
                    resum.className = "resum";
                var overv = document.createElement('p');
                    overv.className = "overv";

                link.href = a['uri'];

                img.src = a['poster_path'];
                img.alt = "Imagen de " + a['titulo'];
                img.title = a['titulo'];
                imagen.appendChild(img);

                estreno.innerText = a['fecha_estreno'];
                titulo.innerText = a['titulo'];

                overv.innerText  = a['resumen'].substr(0, 50) + "...";
                resum.appendChild(overv);

                link.appendChild(imagen);
                link.appendChild(titulo);
                link.appendChild(resum);
                item.appendChild(link);

                document.getElementsByClassName("items")[0].appendChild(item);
            });
        },
        complete: function () {
            searching = false;
            spin.addClass("hidden");
        }
    });
});

$('#contenedor, .navbar').on('click', function () {
    delay(function(){
        clear();
    }, 150);
});

// document.addEventListener('scroll', function (event) {
//     clear();
// }, true);