<?php

namespace app\controllers;

use app\core\Controller;

class MainController extends Controller
{

    public function indexAction()
    {
        //echo '<br>' . " Контроллер: " . __CLASS__ . "|Экшен: " . __METHOD__;
        $this->view->render('123'); // запускаем шаблон header & footer и содержимое нашего контента 123, теперь можем с ней работать и отображать ее в виде
    }
}
