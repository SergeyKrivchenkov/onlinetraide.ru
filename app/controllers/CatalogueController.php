<?php

namespace app\controllers;

use app\core\Controller;
// все что написанно в Controller доступно на стр. CatalogueController 

class CatalogueController extends Controller // находимся на стр. каталог
{

    public function indexAction() // это то что будет отображаться на главной стр. каталога
    {
        $categories = $this->model->getCategories();
        // $this->model->getCountProducts();
        $this->view->render($categories); // из каждого конкретного контроллера будет вызываться метод который отрисует вид и шаблон
        // debug($categories);
        //debug($this->view); // проверяем доступен ли нам вид
        // echo '<br>' . " Контроллер: " . __CLASS__ . "|Экшен: " . __METHOD__;
        // getCategories();
    }

    public function computersAction() //запускает действия которые берут информацию
    {
        //echo "Hello"; // будет виден при запуске ajax
        $this->save_ses_cat_id(); // это сессия и редерект
        $arr = $this->getProducts(); // это не тот метод что вызывается из модели, этот метод описан здесь ниже
        $this->view->render($arr); // это отправляется в вид (в базовый View.php)
    }

    public function clothesAction()
    {
        $this->save_ses_cat_id();
        $arr = $this->getProducts();
        $this->view->render($arr);
    }




    private function save_ses_cat_id() //! во избежание DRY создаем метод записи сессии и редеректа, т.к. такаяже логика как на компьютерах распростроняется и на одежду.
    {
        // session_destroy();        // debug($_SERVER);
        //echo 'computersAction';

        //? если зашли в 1 раз на страницу и сессии нет а кат-ид есть
        if (isset($_GET['cat_id']) and !isset($_SESSION['cat_id'])) {
            // сессию желательно вкл либо в index файле либо в базовом контроллере
            // если в сессию не разу не записан параметр 'cat_id', а так же если есть _GET параметр в адресной строке, то чтобы потом можно было _GET параметр очистить из адресной строки при этом чтобы показ. товары необходимо запомнить этот _GET параметр (соотв это cat_id) и то что будет длиться на протяжении всей работы с сайтом это как вариант _SESSION, поэтому в сессию записываем $_GET['cat_id'];
            $_SESSION['cat_id'] = $_GET['cat_id']; // если сессия не сущ то задаем ее из _GET параметра
            header('location: ' . $_SERVER['REDIRECT_URL']); //перезагружаем стр. саму на себя. при этом пишем не прямой путь на стр, а исп константы из $_SERVER, таким образом очищаем GET запрос который был
            // echo $_SESSION['cat_id']; // выведет 1 на экране для компьютеров и 2 для одежды

            //! ЕСЛИ ЗАШЛИ НЕ В 1 РАЗ И СЕССИЯ ЕСТЬ ТО НУЖНО ПРОВЕРИТЬ ЧТО, ТО ЧТО В СЕССИИ СОВП ИЛИ НЕТ С GET;
            //А) совподение сессии с GET
        } else if (isset($_GET['cat_id']) and isset($_SESSION['cat_id']) and ($_SESSION['cat_id'] == $_GET['cat_id'])) {
            // $_SESSION['cat_id'] = $_GET['cat_id']; // если сессия не сущ то задаем ее из _GET параметра

            header('location: ' . $_SERVER['REDIRECT_URL']);
            //задесь иначе это если сессия уже есть то необходимо убрать _GET параметр

            //todo б) если  GET и SESSION различные то (работа с одеждой т.к. id теперь не 1 а 2 и поэтому нужно перезаписывать сессию )
        } else if (isset($_GET['cat_id']) and isset($_SESSION['cat_id']) and ($_SESSION['cat_id'] != $_GET['cat_id'])) {
            $_SESSION['cat_id'] = $_GET['cat_id']; // перезаписываем сессию где 1 меняем на 2 которая соответствует категории одежда
            header('location: ' . $_SERVER['REDIRECT_URL']);
        }

        // в каталогКонтроллер будет доступна модель каталог, в майнКонтроллер будет доступна модель майн и т.д
    }

    private function getProducts()
    {
        //todo ПРОВЕРКА ДЛЯ ПАГИНАЦИИ
        // если есть страница на которую мы щелкнули


        if (isset($_GET['page'])) {
            $cur_page = $_GET['page']; // то в текущею стр cur_page запишется та стр на которой мы находится и соответствует цыфре на шкале для пагинации
        } else {
            $cur_page = 1; // иначе если никуда не щелкнули то текущая отображаемая стр это 1
            // т.е $cur_page заполниться либо тем на что нажали либо 1 по умолчанию
        }

        $param_value = $_SESSION['cat_id']; // берем теперь не из адресной строки get параметр по которому будем искать продукты соответствующие требуемому id а из сессии так как от get параметра мы избавились через rederect.
        $param_name = 'catalogue_id'; // указываем называние поля catalogue_id(как в БД) и присваиваем свойству $param_name в котором перечислены уникальные значения идентификаторы. По этому параметру 'catalogue_id' будет искаться значение
        $count_on_page = 3; // кол-во элементов кот хотим видеть на стр. Здесь мы можем контролировать кол-во выводимых продуктов


        $arr = $this->model->getProducts($param_name, $param_value, $cur_page, $count_on_page); // запускается из модели метод getProducts() универсален для выбора товара передаем сюда описанные выше свойства и будет создан в модели, сюда запишется результат отбора товара по id
        //debug($products); покажет массив компьютеров или одежды
        $arr['count_on_page'] = $count_on_page; // добовляем в массив (либо через push) под ключом 'count_on_page' значение указанное в $count_on_page. Теперь в вид придет не 2 элемента а 3 элемента
        $arr['cur_page'] = $cur_page; // добовляем текущею страницу
        return $arr;
    }

    //todo оформляем заказ (lesson 68 45:14)

    public function checkoutAction()
    {
        $products_arr = $_POST['products'];
        $res = $this->model->checkout($_SESSION['auth']['id'], $products_arr); // где  json_decode из JS в php т.к. php не понимает массив объектов в котором мы получили данные.
        echo json_encode($res); // перевод информации для восприятия из php в JS
    }







    // todo метод удаления из корзины (lesson 67)
    public function delete_from_cartAction() // не забываем добавить к методу Action, после заполнения метода направляемся в папку model к Catalogue
    {
        if ($this->isAjax()) {
            if (isset($_POST['product_id'])) {
                $res =  $this->model->delete_from_cart($_SESSION['auth']['id'], $_POST['product_id']); // передаем сессию чтобы привезаться к пользователю и передаем айди т.е. то на что осуществляем воздействие
                echo json_encode($res); //возвращаем результат который представляет из себя массив
            } else {
                echo 'false';
            }
        } else {
            echo "<img src='/public/images/errors/404.png' width='100%'>";
        }
    }

    // todo метод добовления в корзину

    public function add_to_cartAction()
    {
        if ($this->isAjax()) {

            ////echo ($_POST['product_id'] . $_POST['count'] . ($_POST['price']));

            if (isset($_POST['product_id']) and isset($_POST['count']) and isset($_POST['price'])) {
                $res =  $this->model->addItemIntoCart($_SESSION['auth']['id'], $_POST['product_id'], $_POST['count'], $_POST['price']); //запускаем модель чтобы добавить в карзину новый товар юзеру. addItemIntoCart описываем в модели Catalogue
                echo json_encode($res);
                // Применяем json_encode что бы была возможность понимания синтаксиса записи одного языка в другом языке напримет (возможность передачи сущностей и возможности распарсить для дольнейшей работы) js & php (php ассоциативный массив и синтаксис через => а в js этот же ассоцитивный моссив называется объектом и у него другой синтаксис)
                // echo $res;
            } else {
                echo 'false';
            }
            // echo ($_POST['product_id'] . $_POST['count'] . $_POST['price']);
            // echo "AJAX yes";
            //echo $res; // ajax будет работать с php только через методы кот выводят разультат на экран. return здесь не работает. Этот echo подхватиться в ajax кот описан в products_inc. $_SESSION['auth']['id'] это id зарегистрированного пользователя он принимается в модели Catalogue.php 
        } else {
            echo "<img src='/public/images/errors/404.png' width='100%'>";
        }
    }
    public function get_client_cartAction() // без префикса Action запрос не сработает
    {
        if ($this->isAjax()) {
            // echo 'client cart';
            $client_id = $_POST['client_id']; // приняли id
            $res = $this->model->get_client_cart($client_id); // вызываем метод передаем в него id клиента
            echo json_encode($res); // массив передать не можем поэтому через json_encode результат преобразуем в строку после чего она будет передана в JS
        } else {
            echo "<img src='/public/images/errors/404.png' width='100%'>";
        }
    }
}
