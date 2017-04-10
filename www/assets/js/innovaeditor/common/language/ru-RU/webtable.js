function loadTxt() {
    document.getElementById("tab0").innerHTML = "ВСТАВКА";
    document.getElementById("tab1").innerHTML = "ИЗМЕНЕНИЕ";
    document.getElementById("tab2").innerHTML = "АВТОФОРМАТ";
    document.getElementById("btnDelTable").value = "Удалить выбранную таблицу";
    document.getElementById("btnIRow1").value = "Вставить строку (над)";
    document.getElementById("btnIRow2").value = "вставить строку (под)";
    document.getElementById("btnICol1").value = "Вставить столбец (слева)";
    document.getElementById("btnICol2").value = "Вставить столбец (справа)";
    document.getElementById("btnDelRow").value = "Удалить строку";
    document.getElementById("btnDelCol").value = "Удалить столбец";
    document.getElementById("btnMerge").value = "Склеить ячейки";
    document.getElementById("lblFormat").innerHTML = "ФОРМАТ:";
    document.getElementById("lblTable").innerHTML = "Таблица";
    document.getElementById("lblEven").innerHTML = "Четные строки";
    document.getElementById("lblOdd").innerHTML = "Нечетные строки";
    document.getElementById("lblCurrRow").innerHTML = "Текущая строка";
    document.getElementById("lblCurrCol").innerHTML = "Текущий столбец";
    document.getElementById("lblBg").innerHTML = "Фон:";
    document.getElementById("lblText").innerHTML = "Текст:";    
    document.getElementById("lblBorder").innerHTML = "РАМККА:";
    document.getElementById("lblThickness").innerHTML = "Толщина:";
    document.getElementById("lblBorderColor").innerHTML = "Цвет:";
    document.getElementById("lblCellPadding").innerHTML = "ПОЛЯ ЯЧЕЙКИ:";
    document.getElementById("lblFullWidth").innerHTML = "Во всю ширина";
    document.getElementById("lblAutofit").innerHTML = "Автоподгонка";
    document.getElementById("lblFixedWidth").innerHTML = "Фиксированная ширина:";
    document.getElementById("lnkClean").innerHTML = "ОЧИСТИТЬ";
    document.getElementById("lblTextAlign").innerHTML = "ВЫРАВНИВАНИЕ ТЕКСТА:";
    document.getElementById("btnAlignLeft").value = "По левому краю";
    document.getElementById("btnAlignCenter").value = "По центру";
    document.getElementById("btnAlignRight").value = "По правому краю";
    document.getElementById("btnAlignTop").value = "По верхнему краю";
    document.getElementById("btnAlignMiddle").value = "По середине";
    document.getElementById("btnAlignBottom").value = "По нижнему краю";

    document.getElementById("lblColor").innerHTML = "ЦВЕТ:";
    document.getElementById("lblCellSize").innerHTML = "РАЗМЕР ЯЧЕЙКИ:";
    document.getElementById("lblCellWidth").innerHTML = "Ширина:";
    document.getElementById("lblCellHeight").innerHTML = "Высота:";       
}
function writeTitle() {
    document.write("<title>" + "Таблица" + "</title>")
}
function getTxt(s) {
    switch (s) {
        case "Clean Formatting": return "Очистить форматирование";
    }
}