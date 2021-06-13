<?php
// debug($_SERVER);
$file = __DIR__ . '/products_inc.php'; // указаываем путь к файлу через контстанту директории __DIR__. Это самый оптимальный варинт не зависящий от размещения самого файла
//debug(__DIR__);// показывае директорию в кот находимся, поэтому целесообразно писать не обсолючный путь руками а указать константу __DIR__;
if (file_exists($file)) {
    include $file;
} else {
    // $path = 'public/images/errors/404.png';
    echo "<img src='/public/images/errors/404.png' width='100%'>";
}
