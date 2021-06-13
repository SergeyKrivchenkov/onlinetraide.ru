<?php

namespace app\core; // где namespace определяем простратство имен, app\core путь от корня по которому находиться данный фаил

class Router
{
    private $routes = []; //это бубут маршруты
    private $params = [];
    public function __construct()
    {
        // echo __CLASS__; // это магическая контстанта зарезервированная функция выдает полный путь до файла с классом
        $routes_arr = require_once 'app/config/routes.php'; //подключаем файл с маршрутами
        // debug($routes_arr);
        foreach ($routes_arr as $route => $params) {
            //debug($route); // это key для метода foreach
            //debug($params); // это значение этого ключа
            $this->add($route, $params);
        }
    }
    private function add($route, $params)
    {
        $route =  '#^' . $route . '$#'; //# это регулярное выражение, ^ это 1 вхождение, $ это если совпало при 1 вхождениеи все остальное подставляемое обрезать
        $this->routes[$route] = $params;
        //перезаписываем $routes_arr
    }

    private function match() // func на сопостовление что ввел пользователь или нажал на ссылку
    {
        $url = trim($_SERVER['REQUEST_URI'], '/');
        // $_SERVER это то что вводит пользователь REQUEST_URI отображает дополнительно и GET параметры, trim обрезаем '/'
        $url = $this->removeQueryString($url); //очистка url от get запроса
        foreach ($this->routes as $route => $params) {
            if (preg_match($route, $url, $matches)) {
                //$route это то с чем сравниваем, $url это то что сравниваем,  в matches запишется то что совпадет после сравнения методом preg_match
                // $this->params
                $this->params = $params;
                return true;
            }
        }
        return false;
    }

    private function removeQueryString($url) // это как бы фильтр перед тем как проводить последующие операции
    {
        //'catalogue?page=3'
        $params = explode('?', $url); // разбираем строку (1 параметр делимитер = ? т.е разделитель, 2 это с чем работаем с какой строкой)
        return $params[0];
        //debug($params); // результат [0] => catalogue [1] => page=4 т.о разделили строку на 2 стоставляющее.
    }

    public function run() // func на запуск рутера который будет выполнять выше описанные функции
    {
        // пр-ка если совп путь то форм имя контроллера
        //подключаем непосредственно сам контроллер по названию ucfirst($this->params['controller']) кот нас отведет к видам
        if ($this->match()) {
            $controller_name = '\app\controllers\\' . ucfirst($this->params['controller']) . 'Controller'; // ведет к файлам таким как например MainController.php расположенным в папке controllers
            // проверка на существование пути
            if (class_exists($controller_name)) {
                // echo 'Exist';
                //если такой файл (как MainController.php) существует тогда подключаем файл с классом MainController через new, а путь сформирован здесь '\app\controllers\\'... и в итоге new $controller_name($this->params);
                $controller = new $controller_name($this->params); // создаем экземплер класса и передаем параметры
                $action_name = $this->params['action'] . 'Action';
                if (method_exists($controller, $action_name)) {
                    //echo 'Yes';
                    $controller->$action_name(); // строки будут печататься из MainController, т.к вызвали метод из нашего класса
                    /*
                    $mainController = new $controller_name; // когда создаем экз класса то .php не нужно
                    $mainController->indexAction();
                    */
                } else {
                    // echo 'Method ' . $action_name . ' does not exists';
                    echo "<img src='/public/images/errors/404.png' width='100%'>";
                }
            } else {
                // ВЫДАЕТ ОШИБКУ ЕСЛИ КОНТРОЛЛЕР НЕ ВЕРЕН
                // echo 'Controller ' . $controller_name . ' does not exists';
                echo "<img src='/public/images/errors/404.png' width='100%'>";
            }
        } else {
            // echo 'Rout ' . $_SERVER['REQUEST_URI'] . ' does not exists';
            echo "<img src='/public/images/errors/404.png' width='100%'>";
        }
    }
}
