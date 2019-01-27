## Pdf генерация для Joomla!

Для работы требуется библиотека Mpdf для Joomla!:
https://github.com/Delo-Design/jmpdf

## Описание
Представляет собой плагин для com_ajax.

## Создание страниц pdf
Для этого создайте папку "plg_system_jpdfgenerator" в папке "html" вашей теме:
<путь до joomla>/templates/<ваша тема>/html/plg_system_jpdfgenerator

Дальше вам нужно создать в этой папке - папку шаблона для PDF.
Например, создайте "mycards" каталог:

<путь до joomla>/templates/<ваша тема>/html/plg_system_jpdfgenerator/mycards

В mycards надо создать файлы "data.php" и "template.php"
- data.php - должен возвращать массив, это переменные для шаблона "template.php" ([Пример файла](https://github.com/Delo-Design/jpdfgenerator/blob/master/tmpl/default/data.php))
- template.php - это сам шаблон pdf, здесь вы можете писать html с css стилями ([Пример файла](https://github.com/Delo-Design/jpdfgenerator/blob/master/tmpl/default/template.php))

## Адрес для обращений
/index.php?option=com_ajax&plugin=jpdfgenerator&group=system&format=raw

Вы можете также добавить два дополнительных параметра:
- template - это какой шаблон использовать, по умолчанию "default"
- action - это действие (download, stream), где download - запустит скачку pdf, а stream - вывод на экран. По умолчанию используется "stream"

Пример адреса с этими параметрами
/index.php?option=com_ajax&plugin=jpdfgenerator&group=system&format=raw&template=mycards&action=download

Так же вы можете любые свои GET параметры прописывать, их можно использовать в файле data.php для манипуляций, например, список ID материалов.

Например:

/index.php?option=com_ajax&plugin=jpdfgenerator&group=system&format=raw&template=mycards&action=download&ids=1,3,4,5,7,8,105