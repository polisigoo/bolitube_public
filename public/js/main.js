var setup = {
    //"trackTimeOffset" : "100"
    //volume : "$_COOKIE['volume']"
};

var player = videojs('my-video', setup ,function(){
    var myPlayer = this;

    // - Adding buttons delay and onClick event
    var addDel = addNewButton({
        player: player,
        icon: "plus",
        id: "addDelayButton",
        title : "Agregar 0.5s de delay al subitulo"
    });
    addDel.onclick = function() {
        addSubDelay('my-video');
    };

    var remDel = addNewButton({
        player: player,
        icon: "minus",
        id: "removeDelayButton",
        title : "Quitar 0.5s de delay al subitulo"
    });
    remDel.onclick = function() {
        removeSubDelay('my-video');
    };
});

player.on('ready', function() {
    var subBtn = addCaptionButton({
        player: player,
        id: "addSubtitle",
        title: "Agregar subtitulo al video"
    });
    subBtn.onclick = function(){
        $('#input-file').click();
    };
});

$("#input-file").on("change",function(){
    var reg=/(.srt|.vtt)$/;
    if (!reg.test($("#input-file").val())) {
        alert('Invalid File Type');
        return false;
    }
    uploadFile();
});





