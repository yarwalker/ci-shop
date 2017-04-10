function loadTxt() {
    document.getElementById("tab0").innerHTML = "FLICKR";
    document.getElementById("tab1").innerHTML = "МОИ ФАЙЛЫ";
    document.getElementById("tab2").innerHTML = "СТИЛИ";
    document.getElementById("tab3").innerHTML = "ЭФФЕКТЫ";
    document.getElementById("lblTag").innerHTML = "ТЭГ:";
    document.getElementById("lblFlickrUserName").innerHTML = "Имя Пользователя Flickr:";
    document.getElementById("lnkLoadMore").innerHTML = "Загрузить больше";
    document.getElementById("lblImgSrc").innerHTML = "Источник картинки:";
    document.getElementById("lblWidthHeight").innerHTML = "ШИРИНА x ВЫСОТА:";
    
    var optAlign = document.getElementsByName("optAlign");
    optAlign[0].text = ""
    optAlign[1].text = "По левому"
    optAlign[2].text = "по правому"

    document.getElementById("lblTitle").innerHTML = "ЗАГОЛОВОК:";
    document.getElementById("lblAlign").innerHTML = "ВЫРАВНИВАНИЕ:";
    document.getElementById("lblMargin").innerHTML = "ОТСТУП: (ВЕРХНИЙ / ПРАВЫЙ / НИЖНИЙ / ЛЕВЫЙ)";
    document.getElementById("lblSize1").innerHTML = "МАЛЕНЬКИЙ КВАДРАТ";
    document.getElementById("lblSize2").innerHTML = "МИНИАТЮРА";
    document.getElementById("lblSize3").innerHTML = "МАЛЕНЬКИЙ";
    document.getElementById("lblSize5").innerHTML = "СРЕДНИЙ";
    document.getElementById("lblSize6").innerHTML = "БОЛЬШОЙ";

    document.getElementById("lblOpenLarger").innerHTML = "ОТКРЫТЬ УВЕЛИЧЕННОЕ ИЗОБРАЖЕНИЕ В LIGHTBOX, ИЛИ";
    document.getElementById("lblLinkToUrl").innerHTML = "ССЫЛКА:";
    document.getElementById("lblNewWindow").innerHTML = "ОТКРЫТЬ В НОВОМ ОКНЕ.";
    document.getElementById("btnCancel").value = "закрыть";
    document.getElementById("btnSearch").value = " Поиск ";

    document.getElementById("btnRestore").value = "Исходная картинка";
    document.getElementById("btnSaveAsNew").value = "Сохранить как новое изображение"; 
}
function writeTitle() {
    document.write("<title>" + "Картинка" + "</title>")
}
function getTxt(s) {
    switch (s) {
        case "insert": return "вставить";
        case "change": return "ok";
        case "notsupported": return "Внешнее изображение не поддерживается.";
    }
}