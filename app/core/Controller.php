<?php

namespace app\core;

session_start();
abstract class Controller // это БАЗОВЫЙ КЛАСС он нужен для прописывания основной логики на подключение моделей видов и прочего
// в этом абстрактном классе формируется вся логика на подключение последующих видов и моделей (отсюда идет все подключение) 
{
    protected $route;
    protected $view;
    protected $model;
    public function __construct($route) // т.к. подкл виды и модели нам нуженм маршрут ($route) кот прокидывается из рутера (т.е. controller & action) т.к. названия файлов вида и моделей называются по названию контроллера
    {
        $this->route = $route;
        // debug($route);
        $this->view = new View($route); // создаем базовый класс вида (в папке core ) и в него пробрасываем массив $route содержащий controller & action, благодаря которому поним что в папке видов должна быть папка main и в этой папке должен находиться файл index.php

        $model_name = '\app\models\\' . ucfirst($route['controller']); // конфигурируем путь для подключаем модель которая соответствует названию контроллера(например MainController соответствуе модель Main, CatalogueController соответствует Catalogue)
        $this->model = new $model_name;

        // todo ВЫХОД ИЗ ПРОФИЛЯ
        if (isset($_GET['do']) and $_GET['do'] = 'exit') {
            session_unset('do');
            header('location: ' . $_SERVER['REDIRECT_URL']);
            // die();
        }




        // todo АТВТОРИЗАЦИЯ проверка данных пришедших из defoult.php пароль и имя
        if (isset($_POST['email']) and isset($_POST['password'])) {
            ////$password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT); // хешируем пароль введенный пользователем для того чтобы в полед сравнить его с паролем в БД.
            $res = $this->model->auth($_POST['email'], $_POST['password']);

            if ($res) {

                $_SESSION['auth'] = ['id' => $res['id'], 'email' => $res['email']];
                header('location: ' . $_SERVER['REDIRECT_URL']);
            } else {

                echo "
                <div class='card-panel red lighten-2 auth-error' style='position: absolute; left: 50%; top: 30%; transform: translate(-50%, -50%)'>Вы ввели не верные данные</div>";
            }
        }
    }


    // todo вызываем isAjax() в catalogueController
    public function isAjax()
    {

        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }
}
