<?php

namespace app\controllers;

use app\core\Controller; //подключаем род контроллер
// придя из рутера к MainController видим что он наследуется от Controller и идем туда ...
class MainController extends Controller // наследуемся от род контроллера. MainController этот частный класс в котором будут именно методы кот для данной стр. тянут из БД какуюто информацию (т.е. подкл к БД, возьми инф и возврати сюда ) для каждой стр будут свои методы
{
    // здесь будут методы непосредственно для главной страницы (например: получить тел, пол инф о пользователе) из этих методов будем обращаться к моделям непосредственно
    public function indexAction() // где action это название метода при добовлении префикса action
    {
        //echo '<br>' . " Контроллер: " . __CLASS__ . "|Экшен: " . __METHOD__;
        $this->view->render('123'); // запускаем шаблон header & footer и содержимое нашего контента 123, теперь можем с ней работать и отображать ее в виде
    }
}
