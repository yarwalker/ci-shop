/*** Translation ***/
LanguageDirectory="ru-RU";

function getTxt(s)
  {
  switch(s)
    {
    case "Save":return "Сохранить";
    case "Preview":return "Просмотр";
    case "Full Screen":return "Во весь экран";
    case "Search":return "Поиск";
    case "Check Spelling":return "Проверка орфографии";
    case "Text Formatting":return "Форматирование текста";
    case "List Formatting":return "Форматирование списка";
    case "Paragraph Formatting":return "Форматирование абзаца";
    case "Styles":return "Стили";
    case "Custom CSS":return "Настройка CSS";
    case "Styles & Formatting":return "Стили & Форматирование";
    case "Style Selection":return "Выбор стиля";
    case "Paragraph":return "Абзац";
    case "Font Name":return "Шрифт";
    case "Font Size":return "Размер шрифта";
    case "Cut":return "Вырезать";
    case "Copy":return "Скопировать";
    case "Paste":return "Вставить";
    case "Undo":return "Отменить";
    case "Redo":return "Повторить";
    case "Bold":return "Жирный";
    case "Italic":return "Курсив";
    case "Underline":return "Подчеркивание";
    case "Strikethrough":return "Зачеркивание";
    case "Superscript":return "Верхний регистр";
    case "Subscript":return "Нижний регистр";
    case "Justify Left":return "Выровнять по левому краю";
    case "Justify Center":return "Выровнять по центру";
    case "Justify Right":return "Выровнять по правому краю";
    case "Justify Full":return "Выровнять по ширине";
    case "Numbering":return "Нумерованный список";
    case "Bullets":return "Ненумерованный список";
    case "Indent":return "Отступ";
    case "Outdent":return "Выступ";
    case "Left To Right":return "Слева направо";
    case "Right To Left":return "справа налево";
    case "Foreground Color":return "Цвет шрифта";
    case "Background Color":return "Цвет фона";
    case "Hyperlink":return "Ссылка";
    case "Bookmark":return "Закладка";
    case "Special Characters":return "Спецсимволы";
    case "Image":return "Картинка";
    case "Flash":return "Флэш";
    case "Media":return "Медиа";
    case "Content Block":return "Блок контента";
    case "Internal Link":return "Внутренняя ссылка";
    case "Internal Image":return "Внутренняя картинка";
    case "Object":return "Объект";
    case "Insert Table":return "Вставить таблицу";
    case "Table Size":return "Размер таблицы";
    case "Edit Table":return "Редактировать таблицу";
    case "Edit Cell":return "Редактиировать ячейку";
    case "Table":return "Таблица";
    case "AutoTable":return "Автоформат таблицы";
    case "Border & Shading":return "Рамка & Тени";
    case "Show/Hide Guidelines":return "Показать/Скрыть направляющие";
    case "Absolute":return "Абсолютное";
    case "Paste from Word":return "Вставить из Word";
    case "Line":return "Линия";
    case "Form Editor":return "Из редактора";
    case "Form":return "Из";
    case "Text Field":return "Текстовое поле";
    case "List":return "Список";
    case "Checkbox":return "Флажок";
    case "Radio Button":return "Радио-кнопка";
    case "Hidden Field":return "Скрытое поле";
    case "File Field":return "поле выбора файла";
    case "Button":return "Кнопка";
    case "Clean":return "Очистить";//not used
    case "View/Edit Source":return "Просмотр/Редактирование исходника";
    case "Tag Selector":return "Выбор тэга";
    case "Clear All":return "Очистить все";
    case "Tags":return "Тэги";

    case "Heading 1":return "Заголовок 1";
    case "Heading 2":return "Заголовок 2";
    case "Heading 3":return "Заголовок 3";
    case "Heading 4":return "Заголовок 4";
    case "Heading 5":return "Заголовок 5";
    case "Heading 6":return "Заголовок 6";
    case "Preformatted":return "Отформатированный";
    case "Normal (P)":return "Обычный (P)";
    case "Normal (DIV)":return "Обычный (DIV)";

    case "Size 1":return "Размер 1";
    case "Size 2":return "Размер 2";
    case "Size 3":return "Размер 3";
    case "Size 4":return "Размер 4";
    case "Size 5":return "Размер 5";
    case "Size 6":return "Размер 6";
    case "Size 7":return "Размер 7";

    case "Are you sure you wish to delete all contents?":
      return "Вы уверенеы что хотите удалить все содержимое?";
    case "Remove Tag": return "Удалить тэг";

    case "Custom Colors":return "Выбор цвета";
    case "More Colors...":return "Больше цветов...";
    case "Box Formatting":return "Форматирование блока";
    case "Advanced Table Insert":return "Вставка настраиваемой таблицы";
    case "Edit Table/Cell":return "Редактирование Таблицы/Ячейки";
    case "Print":return "Печать";
    case "Paste Text":return "Вставить текст";
    case "CSS Builder":return "Мастер CSS";
    case "Remove Formatting":return "Убрать форматирование";
    case "Table Dimension Text": return "Таблица";
    case "Table Advance Link": return "Дополнительно";

    case "Fonts": return "Шрифты";    
    case "Text": return "Текст";
    case "Link": return "Ссылка";
    case "YoutubeVideo": return "Youtube видео";
    case "Search & Replace": return "Найти & Заменить";
    case "HTML Editor": return "HTML редактор";
    case "Emoticons": return "Смайлы";
    case "PasteWarning": return "пожалуйста вставьте используя комбинацию клавиш (CTRL-V)."; /*Your browser security settings don't permit this operation.*/
    case "Quote": return "Цитата";
    default:return "";
    }
  }