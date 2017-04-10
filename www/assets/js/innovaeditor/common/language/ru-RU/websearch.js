function loadTxt() {
    document.getElementById("lblSearch").innerHTML = "ПОИСК:";
    document.getElementById("lblReplace").innerHTML = "ЗАМЕНА:";
    document.getElementById("lblMatchCase").innerHTML = "Совпадение";
    document.getElementById("lblMatchWhole").innerHTML = "Совпадение целого слова";

    document.getElementById("btnSearch").value = "продолжить поиск"; ;
    document.getElementById("btnReplace").value = "заменить";
    document.getElementById("btnReplaceAll").value = "заменить все";
}
function getTxt(s) {
    switch (s) {
        case "Finished searching": return "Поиск по документу закончен.\nВозобновить поиск с начала документа?";
        default: return "";
    }
}
function writeTitle() {
    document.write("<title>Поиск & Замена</title>")
}