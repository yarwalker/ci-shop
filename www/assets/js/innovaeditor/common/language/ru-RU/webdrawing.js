function loadTxt() {
    document.getElementById("tab0").innerHTML = "РИСУНОК";
    document.getElementById("tab1").innerHTML = "УСТАНОВКИ";
    document.getElementById("tab3").innerHTML = "СОХРАНЕНО";

    document.getElementById("lblWidthHeight").innerHTML = "РАЗМЕР КАНВЫ:";
    
    var optAlign = document.getElementsByName("optAlign");
    optAlign[0].text = ""
    optAlign[1].text = "По левому"
    optAlign[2].text = "По правому"

    document.getElementById("lblTitle").innerHTML = "ЗАГОЛОВОК:";
    document.getElementById("lblAlign").innerHTML = "ВЫРАВНИВАНИЕ:";
    document.getElementById("lblSpacing").innerHTML = "ВЕРТИКАЛЬНЫЙ ПРОМЕЖУТОК:";
    document.getElementById("lblSpacingH").innerHTML = "ГОРИЗОНТАЛЬНЫЙ ПРОМЕЖУТОК:";

    document.getElementById("btnCancel").value = "закрыть";
}
function writeTitle() {
    document.write("<title>" + "Рисунок" + "</title>")
}
function getTxt(s) {
    switch (s) {
        case "insert": return "вставить";
        case "change": return "ok";
        case "DELETE": return "УДАЛИТЬ";
    }
}