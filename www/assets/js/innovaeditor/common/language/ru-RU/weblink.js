function loadTxt() {
    document.getElementById("tab0").innerHTML = "МОИ ФАЙЛЫ";
    document.getElementById("tab1").innerHTML = "СТИЛИ";
    document.getElementById("lblUrl").innerHTML = "ССЫЛКА:";
    document.getElementById("lblName").innerHTML = "ИМЯ:";
    document.getElementById("lblTitle").innerHTML = "ЗАГОЛОВОК:";
    document.getElementById("lblTarget1").innerHTML = "Открыть на странице";
    document.getElementById("lblTarget2").innerHTML = "Открыть в новом окне";
    document.getElementById("lblTarget3").innerHTML = "Открыть в Lightbox";
    document.getElementById("lnkNormalLink").innerHTML = "Обычная ссылка &raquo;";    
    document.getElementById("btnCancel").value = "закрыть";
}
function writeTitle() {
    document.write("<title>" + "Ссылка" + "</title>")
}
function getTxt(s) {
    switch (s) {
        case "insert": return "вставить";
        case "change": return "ok";
    }
}