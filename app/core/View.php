<?php

namespace app\core;

class View // это базовый класс который определяет какой конкретно класс будет подключен
{

    protected $route;
    protected $path;
    protected $layout = 'default';
    public function __construct($route)
    {
        $this->route = $route;
        $this->path = $route['controller'] . '/' . $route['action'];
    }

    public function render($data)
    {

        // debug($data);

        $layout = "app/views/layouts/{$this->layout}.php";
        $view = "app/views/{$this->path}.php";


        if (file_exists($view)) {
            // если файл сущ то подключаем
            ob_start();
            require_once $view;
            $content = ob_get_clean();
        } else {
            $content = "<img src='/public/images/errors/404.png width='100%'>"; // todo здесь ошибку присваиваем свойству так как иначе вывод ошибки ломает верстку, а $content выводится в layout согласно архитектуре между header & futer
        }
        if (file_exists($layout)) {

            require_once $layout;
        } else {
            echo "<img src='/public/images/errors/404.png' width='100%'>";
        }
    }
}
