function loadTxt() {
    document.getElementById("tab0").innerHTML = "ПОСТЕР";
    document.getElementById("tab1").innerHTML = "MPEG4 ВИДЕО";
    document.getElementById("tab2").innerHTML = "Ogg ВИДЕО";
    document.getElementById("tab3").innerHTML = "WebM ВИДЕО";
    document.getElementById("lbImage").innerHTML = "Постер/просмотр картинки (.png or .jpg):";
    document.getElementById("lblMP4").innerHTML = "MPEG4 видео (.mp4):";
    document.getElementById("lblOgg").innerHTML = "Ogg видео (.ogv):";
    document.getElementById("lblWebM").innerHTML = "WebM видео (.webm):";
    document.getElementById("lblDimension").innerHTML = "Введите размер видео (ширина x высота):";
    document.getElementById("divNote1").innerHTML = "Для информации по HTML5 video посмотрите: <a href='http://www.w3schools.com/html5/html5_video.asp' target='_blank'>www.w3schools.com/html5/html5_video.asp</a>." +
        "Имеются 3 поддерживаемых видеоформата: MP4, WebM (для MSIE 9+), и Ogg (для FireFox). Браузер будет использовать первый распознанный формат." +
        "Также, вам понадобится предпросмотр или постер.";
    document.getElementById("divNote2").innerHTML = "Чтобы переконвертировать видео в HTML5 видеоформат (MP4, WebM & Ogg) вы можете использовать: <a href='http://www.mirovideoconverter.com/' target='_blank'>www.mirovideoconverter.com</a>";

    document.getElementById("btnCancel").value = "закрыть";
    document.getElementById("btnInsert").value = "вставить";
}
function writeTitle() {
    document.write("<title>" + "HTML5 Видео" + "</title>")
}